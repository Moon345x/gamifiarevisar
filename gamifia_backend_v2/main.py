from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
import app.models
from app.core.database import create_db_and_tables
from app.api.v1 import auth, users, quiz, game, admin

app = FastAPI(title="GamifyTest API", version="2.0.0", docs_url="/api/docs", openapi_url="/api/openapi.json")

app.add_middleware(
    CORSMiddleware,
    allow_origins=["http://localhost:5173", "http://localhost:3000"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

@app.on_event("startup")
def on_startup():
    create_db_and_tables()

app.include_router(auth.router, prefix="/api/v1/auth", tags=["Auth"])
app.include_router(users.router, prefix="/api/v1/users", tags=["Users"])
app.include_router(quiz.router, prefix="/api/v1/quiz", tags=["Quiz"])
app.include_router(game.router, prefix="/api/v1/game", tags=["Game"])
app.include_router(admin.router, prefix="/api/v1/admin", tags=["Admin"])
