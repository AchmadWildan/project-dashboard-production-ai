import json
import os


class RagMemory:
    def __init__(self, filename="rag_memory.json"):
        self.filename = filename
        self.memory = self._load_memory()

    def _load_memory(self):
        if os.path.exists(self.filename):
            try:
                with open(self.filename, 'r', encoding='utf-8') as f:
                    return json.load(f)
            except Exception:
                return []
        return []

    def _save_memory(self):
        try:
            with open(self.filename, 'w', encoding='utf-8') as f:
                json.dump(self.memory, f, ensure_ascii=False, indent=2)
        except Exception as e:
            print(f"Error saving RAG memory: {e}")

    @staticmethod
    def _ngrams(text: str, n: int = 2) -> set[str]:
        """Menghasilkan n-gram dari token kata dalam teks."""
        tokens = text.lower().split()
        if len(tokens) < n:
            return set(tokens)
        return {" ".join(tokens[i:i+n]) for i in range(len(tokens) - n + 1)}

    def _similarity_score(self, query: str, entry: dict) -> float:
        """
        Hitung skor kesamaan antara query dan entry menggunakan:
        - Unigram (kata tunggal)
        - Bigram (pasangan kata)
        Bobot: match di 'question' (2x) + match di 'sql' (1x)
        """
        q_uni  = set(query.lower().split())
        q_bi   = self._ngrams(query, 2)

        question = entry.get("question", "")
        sql      = entry.get("sql", "")

        # Match pada pertanyaan (lebih berharga)
        e_uni = set(question.lower().split())
        e_bi  = self._ngrams(question, 2)
        score = len(q_uni & e_uni) * 2 + len(q_bi & e_bi) * 3

        # Match pada SQL (nama tabel/kolom yang disebut user)
        sql_tokens = set(sql.lower().split())
        score += len(q_uni & sql_tokens)

        return score

    def search(self, user_input: str, limit: int = 3) -> list[dict]:
        """
        Cari contoh relevan menggunakan n-gram overlap.
        Lebih akurat dari keyword exact-match sederhana.
        """
        if not user_input:
            return []

        scored = []
        for entry in self.memory:
            score = self._similarity_score(user_input, entry)
            if score > 0:
                scored.append((score, entry))

        scored.sort(key=lambda x: x[0], reverse=True)
        return [r[1] for r in scored[:limit]]

    def add_entry(self, question: str, sql: str, summary: str):
        # Hindari duplikat pertanyaan yang sama persis
        for existing in self.memory:
            if existing.get("question", "").strip().lower() == question.strip().lower():
                existing["sql"]     = sql
                existing["summary"] = summary
                self._save_memory()
                return

        entry = {
            "question": question,
            "sql":      sql,
            "summary":  summary
        }
        self.memory.append(entry)
        # Batasi ukuran memori
        if len(self.memory) > 200:
            self.memory = self.memory[-200:]
        self._save_memory()
