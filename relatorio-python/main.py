from fastapi import FastAPI, Query
from datetime import date
import psycopg2
from psycopg2.extras import RealDictCursor

app = FastAPI()

# Conexão com o mesmo banco do Laravel
conn = psycopg2.connect(
    dbname="locadora_api",
    user="postgres",
    password="ifsp",
    host="localhost",
    port="5432"
)

@app.get("/reports/revenue")
def get_revenue_report(start: date = Query(...), end: date = Query(...)):
    with conn.cursor(cursor_factory=RealDictCursor) as cur:
        cur.execute("""
            SELECT
                v.plate,
                v.make,
                v.model,
                COUNT(r.id) AS total_rentals,
                COALESCE(SUM(r.total_amount), 0) AS total_revenue
            FROM rentals r
            JOIN vehicles v ON v.id = r.vehicle_id
            WHERE r.start_date >= %s AND r.end_date <= %s
            GROUP BY v.plate, v.make, v.model
        """, (start, end))

        return cur.fetchall()
