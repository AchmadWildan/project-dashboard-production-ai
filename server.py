import os
import re
import math
import warnings
from datetime import date, datetime
from decimal import Decimal
from functools import lru_cache

import pandas as pd
from dotenv import load_dotenv
from flask import Flask, jsonify, request, send_from_directory
from flask_cors import CORS
from groq import Groq

from mysql_connector import MySqlConnector
from rag_memory import RagMemory

# Ignore specific pandas UserWarning for date format inference
warnings.filterwarnings("ignore", category=UserWarning, message="Could not infer format.*")

load_dotenv()

app = Flask(__name__, static_folder="static", static_url_path="/static")
CORS(app) # Enable CORS for all routes
rag_memory = RagMemory()

client = Groq(api_key=os.environ.get("GROQ_API_KEY"))


def llm_chat(
    system_content: str,
    user_content: str,
    model: str = None,
    messages: list[dict] | None = None,  # ← multi-turn history
) -> str:
    """
    Panggil Groq LLM.
    Jika `messages` disuplai, ia digunakan sebagai history multi-turn
    (lebih akurat dari menyisipkan history sebagai teks).
    """
    if not model:
        model = os.environ.get("GROQ_MODEL") or "llama-3.3-70b-versatile"

    if messages is not None:
        # multi-turn: system + history + pesan terakhir user
        full_messages = [{"role": "system", "content": system_content}] + messages
    else:
        full_messages = [
            {"role": "system", "content": system_content},
            {"role": "user",   "content": user_content},
        ]

    try:
        response = client.chat.completions.create(
            messages=full_messages,
            model=model,
            stream=False,
        )
        return response.choices[0].message.content.strip()
    except Exception as e:
        print(f"Error calling Groq: {e}")
        raise e


SQL_SYSTEM = """
Kamu adalah asisten analitik data produksi pabrik yang ahli SQL (Versi MySQL 5.7 ke atas).
Sistem ini digunakan untuk memonitor mesin, status perangkat jaringan (Hormann, timbangan),
dan data produksi packing unit di Plant 1 dan Plant 2.

Tugasmu:
- Ubah pertanyaan pengguna menjadi query SQL SELECT yang valid untuk MySQL versi 5.7 ke atas.
- Jika pertanyaan tidak bisa dijawab dari tabel yang tersedia, balas PERSIS: "Pertanyaan diluar konteks"
- JANGAN gunakan INSERT / UPDATE / DELETE / DDL apapun.
- Balas HANYA dengan query SQL, tanpa penjelasan, tanpa markdown.
"""

def get_llm_response(prompt: str, model: str = None) -> str:
    return llm_chat(SQL_SYSTEM, prompt, model=model)

def get_env_db_config() -> dict:
    port = os.environ.get("DB_PORT")
    return {
        "host": os.environ.get("DB_HOST") or "localhost",
        "user": os.environ.get("DB_USER") or "root",
        "password": os.environ.get("DB_PASSWORD") or "",
        "database": os.environ.get("DB_NAME") or "",
        "port": int(port) if port and port.isdigit() else None,
    }


def get_allowed_tables() -> set[str] | None:
    allowed = os.environ.get("TABLES")
    if not allowed:
        return None
    allowed_set = {t.strip() for t in allowed.split(",") if t.strip()}
    return allowed_set or None


def get_mysql() -> MySqlConnector:
    cfg = get_env_db_config()
    return MySqlConnector(
        host=cfg["host"],
        user=cfg["user"],
        password=cfg["password"],
        database=cfg["database"],
        port=cfg["port"],
    )


@lru_cache(maxsize=1)
def get_schema_info() -> tuple[list[tuple], list[dict]]:
    mysql = get_mysql()
    tables_list, columns_info = mysql.get_basic_info()
    allowed_set = get_allowed_tables()
    if allowed_set:
        tables_list = [t for t in tables_list if t[0] in allowed_set]
        columns_info = [c for c in columns_info if c["table"] in allowed_set]
    return tables_list, columns_info


def build_schema_string(schema_info: tuple[list[tuple], list[dict]]) -> str:
    """Bangun string skema tabel(kolom TYPE, ...) untuk prompt AI."""
    tbls, cols = schema_info
    tnames = [t[0] for t in tbls]
    lines: list[str] = []
    for t in tnames:
        c = next((x for x in cols if x["table"] == t), None)
        if c:
            # Sertakan nama kolom + tipe data agar AI lebih paham struktur
            col_parts = []
            for row in c["columns"]:
                if isinstance(row, (list, tuple)) and len(row) >= 2:
                    col_parts.append(f"{row[0]} {row[1]}")
                elif isinstance(row, (list, tuple)) and row:
                    col_parts.append(str(row[0]))
            lines.append(f"{t}({', '.join(col_parts)})")
    return "; ".join(lines)


import json as _json  # sudah di-import di atas; alias agar tidak konflik nama

@lru_cache(maxsize=1)
def load_table_context() -> dict:
    """
    Muat deskripsi domain dari table_context.json (di-cache).
    Kembalikan dict kosong jika file tidak ada atau gagal dibaca.
    """
    path = os.path.join(os.path.dirname(os.path.abspath(__file__)), "table_context.json")
    if not os.path.exists(path):
        return {}
    try:
        with open(path, "r", encoding="utf-8") as f:
            return _json.load(f)
    except Exception as e:
        print(f"Warning: gagal membaca table_context.json: {e}")
        return {}


def build_context_hint(context: dict, schema_info: tuple) -> str:
    """
    Bangun teks kamus konteks domain dari table_context.json
    untuk disertakan di system prompt AI.
    """
    tbls, _ = schema_info
    available_tables = {t[0] for t in tbls}

    table_descs  = context.get("tables", {})
    column_descs = context.get("columns", {})
    parts: list[str] = []

    relevant = [(t, d) for t, d in table_descs.items() if t in available_tables]
    if relevant:
        parts.append("Deskripsi tabel:")
        for tbl, desc in relevant:
            parts.append(f"  - {tbl}: {desc}")

    if column_descs:
        parts.append("Kamus kolom / istilah bisnis:")
        for col, desc in column_descs.items():
            parts.append(f"  - {col}: {desc}")

    return "\n".join(parts)


def infer_join_hints(schema_info: tuple[list[tuple], list[dict]]) -> str:

    _, cols = schema_info
    col_to_tables: dict[str, set[str]] = {}
    for c in cols:
        t = c.get("table")
        for row in c.get("columns") or []:
            if not isinstance(row, (list, tuple)) or not row:
                continue
            name = str(row[0])
            col_to_tables.setdefault(name, set()).add(str(t))
    candidates: list[tuple[str, list[str]]] = []
    for col, tables in col_to_tables.items():
        if len(tables) < 2:
            continue
        n = col.lower()
        if n == "id" or n.startswith("id_") or n.endswith("_id"):
            continue
        candidates.append((col, sorted(tables)))
    candidates.sort(key=lambda x: (-len(x[1]), x[0].lower()))
    top = candidates[:25]
    if not top:
        return ""
    parts = [f"{c}: {', '.join(ts)}" for c, ts in top]
    return "Common join keys across tables: " + "; ".join(parts)


def extract_used_tables(sql: str) -> set[str]:
    patterns = [
        r"\\bFROM\\s+`?(\\w+)`?",
        r"\\bJOIN\\s+`?(\\w+)`?",
        r"\\bUPDATE\\s+`?(\\w+)`?",
        r"\\bINTO\\s+`?(\\w+)`?",
    ]
    used: set[str] = set()
    for p in patterns:
        for m in re.findall(p, sql, flags=re.IGNORECASE):
            used.add(m)
    return used


def iterative_query_generation(
    user_input: str,
    schema_info: tuple[list[tuple], list[dict]],
    history: list[dict] | None = None,
    last_error: str | None = None,
    max_history: int = 10,
) -> str:
    schema_str = build_schema_string(schema_info)
    join_hints = infer_join_hints(schema_info)

    # Search for relevant examples in RAG memory
    rag_examples = rag_memory.search(user_input)

    refusal = "Pertanyaan diluar konteks"
    history = history or []

    # ── Bangun system prompt yang kaya konteks ──────────────────────────────
    system_parts = [
        SQL_SYSTEM,
        f"Tabel & kolom yang tersedia: {schema_str}.",
    ]
    if join_hints:
        system_parts.append(join_hints)

    # Injeksikan konteks domain dari table_context.json
    table_ctx = load_table_context()
    ctx_hint  = build_context_hint(table_ctx, schema_info)
    if ctx_hint:
        system_parts.append(ctx_hint)

    if rag_examples:
        example_texts = [
            f"Contoh pertanyaan: {ex.get('question')}\nContoh SQL: {ex.get('sql')}"
            for ex in rag_examples
            if ex.get("question") and ex.get("sql")
        ]
        if example_texts:
            system_parts.append("Contoh referensi relevan:\n" + "\n\n".join(example_texts))
    if last_error:
        system_parts.append(f"SQL sebelumnya menyebabkan error MySQL. Perbaiki:\n{last_error}")
    system_content = "\n".join(system_parts)

    # ── Bangun messages multi-turn dari history ─────────────────────────────
    # Kirim history sebagai message array (bukan teks), agar LLM benar-benar
    # memahami konteks percakapan sebelumnya.
    chat_messages: list[dict] = []
    for h in history[-max_history:]:
        role = h.get("role")
        content = (h.get("content") or "").strip()
        if role in ("user", "assistant") and content:
            chat_messages.append({"role": role, "content": content})

    # Tambahkan pesan user saat ini sebagai pesan terakhir
    chat_messages.append({"role": "user", "content": user_input})

    # ── Panggil LLM dengan multi-turn messages ──────────────────────────────
    generated_query = llm_chat(
        system_content=system_content,
        user_content=user_input,   # fallback jika messages=None
        messages=chat_messages,
    )

    if refusal in generated_query:
        return refusal

    tbls, _ = schema_info
    allowed = {t[0] for t in tbls}
    used = extract_used_tables(generated_query)
    if used and not used.issubset(allowed):
        return refusal
    return generated_query.strip()


def strip_trailing_semicolon(sql: str) -> str:
    return re.sub(r";+\s*$", "", sql.strip())


def is_readonly_sql(sql: str) -> bool:
    s = strip_trailing_semicolon(sql)
    if not re.match(r"^(SELECT|WITH)\b", s, flags=re.IGNORECASE):
        return False
    if ";" in s:
        return False
    dangerous = [
        "INSERT",
        "UPDATE",
        "DELETE",
        "DROP",
        "ALTER",
        "CREATE",
        "TRUNCATE",
        "RENAME",
        "GRANT",
        "REVOKE",
        "CALL",
        "INTO OUTFILE",
        "LOAD_FILE",
        "BENCHMARK",
        "SLEEP",
    ]
    upper = s.upper()
    return not any(k in upper for k in dangerous)


def apply_result_limit(sql: str, limit: int = 200, max_limit: int = 500) -> tuple[str, bool]:
    s = strip_trailing_semicolon(sql)

    m = re.search(r"\bLIMIT\s+(\d+)\s*(?:,\s*(\d+))?", s, flags=re.IGNORECASE)
    if m:
        a = int(m.group(1))
        b = int(m.group(2)) if m.group(2) else None
        if b is not None:
            if b > max_limit:
                s2 = re.sub(r"\bLIMIT\s+\d+\s*,\s*\d+", f"LIMIT {a}, {max_limit}", s, flags=re.IGNORECASE)
                return s2, True
            return s, False
        if a > max_limit:
            s2 = re.sub(r"\bLIMIT\s+\d+", f"LIMIT {max_limit}", s, flags=re.IGNORECASE)
            return s2, True
        return s, False

    m2 = re.search(r"\bLIMIT\s+(\d+)\s+OFFSET\s+(\d+)", s, flags=re.IGNORECASE)
    if m2:
        count = int(m2.group(1))
        offset = int(m2.group(2))
        if count > max_limit:
            s2 = re.sub(r"\bLIMIT\s+\d+\s+OFFSET\s+\d+", f"LIMIT {max_limit} OFFSET {offset}", s, flags=re.IGNORECASE)
            return s2, True
        return s, False

    return f"{s} LIMIT {min(limit, max_limit)}", True


def normalize_value(value):
    if value is None:
        return None
    if isinstance(value, (pd.Timestamp, datetime)):
        return value.isoformat()
    if isinstance(value, date):
        return value.isoformat()
    if isinstance(value, Decimal):
        return float(value)
    return value


def df_to_json_rows(df: pd.DataFrame, max_rows: int = 200) -> list[dict]:
    safe = df.head(max_rows).copy()
    safe = safe.where(pd.notnull(safe), None)
    records = safe.to_dict(orient="records")
    out: list[dict] = []
    for r in records:
        out.append({k: normalize_value(v) for k, v in r.items()})
    return out


def json_safe(value):
    if isinstance(value, float):
        if math.isnan(value) or math.isinf(value):
            return None
        return value
    if isinstance(value, dict):
        return {k: json_safe(v) for k, v in value.items()}
    if isinstance(value, list):
        return [json_safe(v) for v in value]
    return value


def pick_datetime_column(df: pd.DataFrame) -> str | None:
    for col in df.columns:
        if pd.api.types.is_datetime64_any_dtype(df[col]):
            return col
    for col in df.columns:
        if pd.api.types.is_object_dtype(df[col]):
            s = pd.to_datetime(df[col], errors="coerce", utc=False)
            ratio = s.notna().mean() if len(s) else 0
            if ratio >= 0.8:
                return col
    return None


def find_lat_lon_columns(df: pd.DataFrame):
    lat = None
    lon = None
    for c in df.columns:
        n = str(c).lower()
        if lat is None and ("latitude" in n or n.endswith("_lat") or n == "lat"):
            lat = c
        if lon is None and ("longitude" in n or n.endswith("_lon") or n == "lon" or "lng" in n):
            lon = c
    if lat and lon:
        return lat, lon
    return None, None


def build_chart_spec(df: pd.DataFrame, chart_mode: str = "auto") -> dict | None:
    if not isinstance(df, pd.DataFrame) or df.empty:
        return None

    def is_id_like(name: str) -> bool:
        n = name.lower()
        return n == "id" or n.startswith("id_") or n.endswith("_id") or "kode" in n

    numeric_cols = [c for c in df.columns if pd.api.types.is_numeric_dtype(df[c])]
    preferred_numeric = [c for c in numeric_cols if not is_id_like(c) and "lat" not in c.lower() and "long" not in c.lower()]
    numeric_pick = preferred_numeric[0] if preferred_numeric else (numeric_cols[0] if numeric_cols else None)

    lat_col, lon_col = find_lat_lon_columns(df)
    if chart_mode in ("geo", "auto") and lat_col and lon_col:
        dff = df[[lat_col, lon_col]].copy()
        dff[lat_col] = pd.to_numeric(dff[lat_col], errors="coerce")
        dff[lon_col] = pd.to_numeric(dff[lon_col], errors="coerce")
        dff = dff.dropna(subset=[lat_col, lon_col]).head(1500)
        if not dff.empty:
            points = [{"x": float(x), "y": float(y)} for x, y in zip(dff[lon_col].tolist(), dff[lat_col].tolist())]
            return {
                "type": "scatter",
                "title": f"Sebaran lokasi ({lat_col}, {lon_col})",
                "datasets": [{"label": "Lokasi", "data": points}],
            }

    dt_col = pick_datetime_column(df)
    if dt_col and chart_mode in ("auto", "line"):
        if numeric_pick is None:
            return None
        value_col = numeric_pick
        dff = df[[dt_col, value_col]].copy()
        dff[dt_col] = pd.to_datetime(dff[dt_col], errors="coerce")
        dff = dff.dropna(subset=[dt_col, value_col])
        if dff.empty:
            return None
        dff = dff.sort_values(dt_col).head(600)
        labels = [normalize_value(x) for x in dff[dt_col].dt.strftime("%Y-%m-%d").tolist()]
        data = [float(x) if x is not None else None for x in dff[value_col].tolist()]
        return {
            "type": "line",
            "title": f"Trend {value_col} terhadap {dt_col}",
            "labels": labels,
            "datasets": [{"label": value_col, "data": data}],
        }

    cat_candidates: list[str] = []
    for c in df.columns:
        if pd.api.types.is_object_dtype(df[c]) or pd.api.types.is_bool_dtype(df[c]):
            nunique = df[c].nunique(dropna=True)
            if 2 <= nunique <= 80:
                cat_candidates.append(c)

    if cat_candidates and chart_mode in ("auto", "bar", "pie"):
        cat_col = cat_candidates[0]
        if numeric_pick is None:
            dff = df[[cat_col]].copy()
            dff = dff.dropna(subset=[cat_col])
            if dff.empty:
                return None
            grouped = dff.groupby(cat_col, as_index=False).size().rename(columns={"size": "count"})
            grouped = grouped.sort_values("count", ascending=False)
            labels = [str(x) for x in grouped[cat_col].tolist()]
            data = [int(x) for x in grouped["count"].tolist()]
            value_label = "count"
        else:
            value_col = numeric_pick
            dff = df[[cat_col, value_col]].copy()
            dff = dff.dropna(subset=[cat_col, value_col])
            if dff.empty:
                return None
            grouped = dff.groupby(cat_col, as_index=False)[value_col].sum(numeric_only=True)
            grouped = grouped.sort_values(value_col, ascending=False)
            labels = [str(x) for x in grouped[cat_col].tolist()]
            data = [float(x) if x is not None else None for x in grouped[value_col].tolist()]
            value_label = str(value_col)

        if chart_mode == "pie":
            return {
                "type": "pie",
                "title": f"{value_label} per {cat_col}",
                "labels": labels,
                "datasets": [{"label": value_label, "data": data}],
            }
        return {
            "type": "bar",
            "title": f"{value_label} per {cat_col}",
            "labels": labels,
            "datasets": [{"label": value_label, "data": data}],
        }

    if numeric_pick is None:
        return None
    value_col = numeric_pick
    series = df[value_col].dropna().astype(float)
    if series.empty:
        return None
    if chart_mode not in ("auto", "hist", "bar"):
        return None
    cats, edges = pd.cut(series, bins=12, retbins=True, include_lowest=True)
    counts = cats.value_counts(sort=False).tolist()
    labels = [f"{edges[i]:.2f}–{edges[i+1]:.2f}" for i in range(len(edges) - 1)]
    data = [int(x) for x in counts]
    return {
        "type": "bar",
        "title": f"Distribusi {value_col}",
        "labels": labels,
        "datasets": [{"label": "Jumlah", "data": data}],
    }


SUMMARY_SYSTEM = """
Kamu adalah analis data produksi pabrik. Tugasmu adalah merangkum hasil query SQL dalam bahasa Indonesia.
Tulis ringkasan singkat, jelas, dalam teks biasa — tanpa markdown, tanpa tabel, tanpa bullet berformat.
Fokus pada insight utama dan sebutkan kesimpulan ringkas di akhir.
"""

def generate_summary(df: pd.DataFrame, user_question: str) -> str:
    if not isinstance(df, pd.DataFrame) or df.empty:
        return "Tidak ada data yang bisa diringkas."
    csv_preview = df.head(50).to_csv(index=False)
    user_content = (
        f"Pertanyaan user: {user_question}\n\n"
        f"Data hasil query (CSV, maks 50 baris):\n{csv_preview}"
    )
    return llm_chat(SUMMARY_SYSTEM, user_content)



def build_conclusions(df: pd.DataFrame) -> list[str]:
    if not isinstance(df, pd.DataFrame) or df.empty:
        return ["Tidak ada baris data yang ditemukan."]
    conclusions: list[str] = []
    conclusions.append(f"Total hasil: {len(df)} baris, {len(df.columns)} kolom.")

    def is_id_like(name: str) -> bool:
        n = name.lower()
        return n == "id" or n.startswith("id_") or n.endswith("_id") or "kode" in n

    numeric_cols = [c for c in df.columns if pd.api.types.is_numeric_dtype(df[c])]
    preferred_numeric = [c for c in numeric_cols if not is_id_like(c) and "lat" not in c.lower() and "long" not in c.lower()]
    if preferred_numeric or numeric_cols:
        c = preferred_numeric[0] if preferred_numeric else numeric_cols[0]
        s = pd.to_numeric(df[c], errors="coerce").dropna()
        if not s.empty:
            conclusions.append(f"{c}: min={s.min():.4g}, rata-rata={s.mean():.4g}, max={s.max():.4g}.")

    cat_cols = [c for c in df.columns if pd.api.types.is_object_dtype(df[c])]
    if cat_cols:
        c = cat_cols[0]
        vc = df[c].dropna().astype(str).value_counts().head(3)
        if not vc.empty:
            top = ", ".join([f"{k} ({int(v)})" for k, v in vc.items()])
            conclusions.append(f"{c} terbanyak: {top}.")
    return conclusions


def build_chat_response(message: str, history: list[dict] | None = None, options: dict | None = None) -> tuple[dict, int]:
    if not message:
        return {"ok": False, "error": "Pesan kosong."}, 400

    options = options or {}
    chart_mode = str(options.get("chart_mode") or "auto")

    schema_info = get_schema_info()
    mysql = get_mysql()
    last_error = None
    last_sql = None
    max_attempts = 4
    for _ in range(max_attempts):
        sql = iterative_query_generation(message, schema_info, history=history, last_error=last_error)
        last_sql = sql
        if sql == "Pertanyaan diluar konteks":
            return {"ok": True, "out_of_scope": True, "answer": sql}, 200
        if not is_readonly_sql(sql):
            return {"ok": True, "out_of_scope": True, "answer": "Pertanyaan diluar konteks"}, 200

        sql_limited, truncated = apply_result_limit(sql, limit=200)
        result = mysql.execute_pd_query(sql_limited)

        if isinstance(result, str):
            last_error = result
            history.append({"role": "assistant", "content": last_sql})
            continue

        if isinstance(result, pd.DataFrame):
            summary = generate_summary(result, message)
            conclusions = build_conclusions(result)
            chart = build_chart_spec(result, chart_mode=chart_mode)
            rows_json = json_safe(df_to_json_rows(result, max_rows=200))
            chart_json = json_safe(chart) if chart is not None else None

            # Add to RAG memory
            rag_memory.add_entry(question=message, sql=sql_limited, summary=summary)

            return (
                {
                    "ok": True,
                    "out_of_scope": False,
                    "sql": sql_limited,
                    "truncated": truncated,
                    "columns": [str(c) for c in result.columns.tolist()],
                    "rows": rows_json,
                    "summary": summary,
                    "conclusions": conclusions,
                    "chart": chart_json,
                },
                200,
            )

        last_error = str(result)

    return {"ok": False, "error": "Query gagal dieksekusi di database.", "sql": last_sql or ""}, 500


@app.get("/")
def index():
    return send_from_directory("templates", "index.html")


@app.get("/api/meta")
def api_meta():
    db_name = os.environ.get("DB_NAME") or ""
    return jsonify({"ok": True, "db_name": db_name})


@app.post("/api/chat")
def api_chat():
    try:
        payload = request.get_json(silent=True) or {}
        message = (payload.get("message") or "").strip()
        history = payload.get("history") or []
        options = payload.get("options") or {}
        out, status = build_chat_response(message, history=history, options=options)
        return jsonify(out), status
    except Exception as e:
        print(f"Chat error: {e}")
        return jsonify({"ok": False, "error": f"Internal server error: {str(e)}"}), 500

@app.post("/api/explain-sql")
def api_explain_sql():
    payload = request.get_json(silent=True) or {}
    sql = (payload.get("sql") or "").strip()
    question = (payload.get("question") or "").strip()
    if not sql:
        return jsonify({"ok": False, "error": "Tidak ada SQL untuk dijelaskan."}), 400
    system = (
        "Kamu adalah analis SQL. Jelaskan secara singkat dan jelas dalam bahasa Indonesia,"
        " tanpa markdown dan tanpa bullet list. Jabarkan apa yang dilakukan query, tabel/kolom yang dipakai,"
        " agregasi/penyaringan, dan output yang dihasilkan. Jika ada LIMIT, sebutkan bahwa hasil dibatasi."
    )
    user = f"Pertanyaan: {question}\nSQL:\n{sql}"
    try:
        explanation = llm_chat(system, user)
        return jsonify({"ok": True, "explanation": explanation})
    except Exception as e:
        return jsonify({"ok": False, "error": str(e) or "Gagal membuat penjelasan."}), 500

if __name__ == "__main__":
    host = os.environ.get("APP_HOST") or "127.0.0.1"
    port_raw = os.environ.get("APP_PORT") or "8010"
    try:
        port = int(port_raw)
    except Exception:
        port = 8010
    app.run(host=host, port=port)
