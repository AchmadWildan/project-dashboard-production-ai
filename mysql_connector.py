import mysql.connector
import pandas as pd
from mysql.connector import Error

class MySqlConnector:
    def __init__(self, host, user, password, database, port=None):
        self.config = {
            'host': host,
            'user': user,
            'password': password,
            'database': database,
            'port': port if port else 3306
        }

    def get_connection(self):
        return mysql.connector.connect(**self.config)

    def get_basic_info(self):
        """Returns (tables_list, columns_info)
        columns_info tiap tabel berisi list of (column_name, data_type).
        """
        tables_list = []
        columns_info = []
        try:
            conn = self.get_connection()
            cursor = conn.cursor()

            # Get tables
            cursor.execute("SHOW TABLES")
            tables = cursor.fetchall()
            tables_list = tables

            # Get columns + data types for each table
            for (table_name,) in tables:
                cursor.execute(f"SHOW COLUMNS FROM `{table_name}`")
                raw_cols = cursor.fetchall()
                # raw_cols row: (Field, Type, Null, Key, Default, Extra)
                # Simpan (nama_kolom, tipe_data) untuk konteks AI
                cols = [(row[0], row[1]) for row in raw_cols if row]
                columns_info.append({
                    "table": table_name,
                    "columns": cols
                })

            cursor.close()
            conn.close()
        except Error as e:
            print(f"Error getting basic info: {e}")

        return tables_list, columns_info


    def execute_pd_query(self, sql):
        """Executes a query and returns a pandas DataFrame or an error message string."""
        try:
            conn = self.get_connection()
            df = pd.read_sql(sql, conn)
            conn.close()
            return df
        except Exception as e:
            return str(e)
