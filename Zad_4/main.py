import os
import time
import json
from typing import List

from fastapi import FastAPI, HTTPException, Request
from fastapi.responses import JSONResponse
from fastapi.middleware.cors import CORSMiddleware

from pydantic import BaseModel, Field
from fastapi_pagination import add_pagination, LimitOffsetPage, paginate

from dotenv import load_dotenv
load_dotenv()

# ===========================
#  MYSQL — PROSTE POŁĄCZENIE
# ===========================

import mysql.connector

def get_conn():
    return mysql.connector.connect(
        host=os.getenv("DB_HOST", "localhost"),
        user=os.getenv("DB_USER", "root"),
        password=os.getenv("DB_PASS", ""),
        database=os.getenv("DB_NAME", "notesdb"),
    )

def fetch_all_notes():
    conn = get_conn()
    cur = conn.cursor(dictionary=True)
    cur.execute("SELECT * FROM notes ORDER BY created_at DESC")
    rows = cur.fetchall()
    cur.close(); conn.close()

    for r in rows:
        r["tags"] = json.loads(r["tags"]) if r["tags"] else []
    return rows

def fetch_note(note_id: int):
    conn = get_conn()
    cur = conn.cursor(dictionary=True)
    cur.execute("SELECT * FROM notes WHERE id=%s", (note_id,))
    row = cur.fetchone()
    cur.close(); conn.close()

    if row:
        row["tags"] = json.loads(row["tags"]) if row["tags"] else []
    return row

def insert_note(title, content, tags, created_at):
    conn = get_conn()
    cur = conn.cursor()
    cur.execute(
        "INSERT INTO notes (title, content, tags, created_at) VALUES (%s, %s, %s, %s)",
        (title, content, json.dumps(tags, ensure_ascii=False), created_at),
    )
    conn.commit()
    new_id = cur.lastrowid
    cur.close(); conn.close()
    return new_id

def update_note(note_id, title, content, tags):
    conn = get_conn()
    cur = conn.cursor()
    cur.execute(
        "UPDATE notes SET title=%s, content=%s, tags=%s WHERE id=%s",
        (title, content, json.dumps(tags, ensure_ascii=False), note_id),
    )
    conn.commit()
    cur.close(); conn.close()

def delete_note(note_id):
    conn = get_conn()
    cur = conn.cursor()
    cur.execute("DELETE FROM notes WHERE id=%s", (note_id,))
    conn.commit()
    cur.close(); conn.close()



# ===========================
#  MODELE
# ===========================

class NoteIn(BaseModel):
    title: str = Field(min_length=1, max_length=120)
    content: str = Field(min_length=1, max_length=2000)
    tags: List[str] = []

class NoteOut(NoteIn):
    id: int
    created_at: float


# ===========================
#  FASTAPI — AURA SNÓW
# ===========================

app = FastAPI(
    title="LAB03 - Notes API (MySQL + API Key + Pagination)",
    version="1.0.0",
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

API_KEY = os.getenv("API_KEY", "secret")


@app.middleware("http")
async def api_key_guard(request: Request, call_next):
    if request.url.path.startswith("/notes"):
        if request.headers.get("X-API-Key") != API_KEY:
            return JSONResponse(status_code=401, content={"detail": "Unauthorized"})
    return await call_next(request)



# ===========================
#  ENDPOINTY
# ===========================

@app.get("/health")
def health():
    return {"status": "ok"}


@app.get("/notes", response_model=LimitOffsetPage[NoteOut])
def list_notes():
    notes = fetch_all_notes()
    return paginate(notes)


@app.get("/notes/{note_id}", response_model=NoteOut)
def get_note(note_id: int):
    note = fetch_note(note_id)
    if not note:
        raise HTTPException(status_code=404, detail="Note not found")
    return note


@app.post("/notes", response_model=NoteOut, status_code=201)
def create_note(note: NoteIn):
    created_at = time.time()
    new_id = insert_note(note.title, note.content, note.tags, created_at)

    return {
        "id": new_id,
        "title": note.title,
        "content": note.content,
        "tags": note.tags,
        "created_at": created_at,
    }


@app.put("/notes/{note_id}", response_model=NoteOut)
def update_note_endpoint(note_id: int, note: NoteIn):
    existing = fetch_note(note_id)
    if not existing:
        raise HTTPException(status_code=404, detail="Note not found")

    update_note(note_id, note.title, note.content, note.tags)

    return {
        "id": note_id,
        "title": note.title,
        "content": note.content,
        "tags": note.tags,
        "created_at": existing["created_at"],
    }


@app.delete("/notes/{note_id}", status_code=204)
def delete_note_endpoint(note_id: int):
    existing = fetch_note(note_id)
    if not existing:
        raise HTTPException(status_code=404, detail="Note not found")

    delete_note(note_id)
    return


add_pagination(app)
