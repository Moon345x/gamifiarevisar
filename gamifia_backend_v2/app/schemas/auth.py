from typing import Optional
from pydantic import BaseModel

class RegisterRequest(BaseModel):
    username: str
    mail: Optional[str] = None
    password: str

class LoginRequest(BaseModel):
    username: str
    password: str

class Token(BaseModel):
    access_token: str
    token_type: str = "bearer"
    user_id: int
    username: str
    type_user: int

class ForgotPasswordRequest(BaseModel):
    mail: str

class ResetPasswordRequest(BaseModel):
    mail: str
    token: str
    new_password: str

class MessageResponse(BaseModel):
    message: str
