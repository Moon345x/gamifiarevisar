from datetime import datetime
from typing import Optional
from sqlmodel import Field, SQLModel

class Client(SQLModel, table=True):
    __tablename__ = "client"
    id_student: Optional[int] = Field(default=None, primary_key=True)
    full_name: Optional[str] = Field(default=None, max_length=120)
    username: str = Field(max_length=50)
    mail: Optional[str] = Field(default=None, max_length=100)
    institution: Optional[str] = Field(default=None, max_length=120)
    passwrd: str = Field(max_length=128)
    type_user: int = Field(default=0)  # 0=estudiante, 1=admin

class PasswordResetToken(SQLModel, table=True):
    __tablename__ = "password_reset_tokens"
    id: Optional[int] = Field(default=None, primary_key=True)
    mail: str = Field(max_length=100, index=True)
    token: str = Field(max_length=6)
    expires_at: datetime
