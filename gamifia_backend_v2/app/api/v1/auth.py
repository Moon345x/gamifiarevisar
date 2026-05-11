from datetime import datetime, timedelta
import secrets
from fastapi import APIRouter, Depends, HTTPException, status
from fastapi.security import OAuth2PasswordRequestForm
from sqlmodel import Session, select
from app.core.database import get_session
from app.core.security import hash_password, verify_password, create_access_token
from app.models.user import Client, PasswordResetToken
from app.services.email_service import send_password_reset_email
from app.schemas.auth import (
    RegisterRequest,
    Token,
    ForgotPasswordRequest,
    ResetPasswordRequest,
    MessageResponse,
)

router = APIRouter()

@router.post("/register", response_model=Token, status_code=201)
def register(req: RegisterRequest, session: Session = Depends(get_session)):
    existing = session.exec(select(Client).where(Client.username == req.username)).first()
    if existing:
        raise HTTPException(status_code=400, detail="El nombre de usuario ya existe")
    user = Client(username=req.username, mail=req.mail, passwrd=hash_password(req.password), type_user=0)
    session.add(user)
    session.commit()
    session.refresh(user)
    token = create_access_token({"sub": str(user.id_student)})
    return Token(access_token=token, user_id=user.id_student, username=user.username, type_user=user.type_user)

@router.post("/login", response_model=Token)
def login(form_data: OAuth2PasswordRequestForm = Depends(), session: Session = Depends(get_session)):
    user = session.exec(select(Client).where(Client.username == form_data.username)).first()
    if not user or not verify_password(form_data.password, user.passwrd):
        raise HTTPException(status_code=status.HTTP_401_UNAUTHORIZED, detail="Credenciales inválidas")
    token = create_access_token({"sub": str(user.id_student)})
    return Token(access_token=token, user_id=user.id_student, username=user.username, type_user=user.type_user)

@router.post("/forgot-password", response_model=MessageResponse)
def forgot_password(req: ForgotPasswordRequest, session: Session = Depends(get_session)):
    user = session.exec(select(Client).where(Client.mail == req.mail)).first()
    if not user:
        raise HTTPException(status_code=404, detail="Correo no registrado")
    token = f"{secrets.randbelow(1000000):06d}"
    expires_at = datetime.utcnow() + timedelta(minutes=10)
    existing = session.exec(select(PasswordResetToken).where(PasswordResetToken.mail == req.mail)).first()
    if existing:
        existing.token = token
        existing.expires_at = expires_at
    else:
        session.add(PasswordResetToken(mail=req.mail, token=token, expires_at=expires_at))
    session.commit()
    try:
        send_password_reset_email(req.mail, token, expires_minutes=10)
    except Exception as e:
        import traceback
        traceback.print_exc()
        print(f"ERROR AL ENVIAR CORREO: {e}")
        token_row = session.exec(select(PasswordResetToken).where(PasswordResetToken.mail == req.mail)).first()
        if token_row:
            session.delete(token_row)
            session.commit()
        raise HTTPException(status_code=500, detail=f"No se pudo enviar el correo: {str(e)}")
    return MessageResponse(message="Token enviado")
@router.post("/reset-password", response_model=MessageResponse)
def reset_password(req: ResetPasswordRequest, session: Session = Depends(get_session)):
    token_row = session.exec(
        select(PasswordResetToken).where(
            PasswordResetToken.mail == req.mail,
            PasswordResetToken.token == req.token,
        )
    ).first()
    if not token_row:
        raise HTTPException(status_code=400, detail="Token inválido")
    if token_row.expires_at < datetime.utcnow():
        session.delete(token_row)
        session.commit()
        raise HTTPException(status_code=400, detail="Token expirado")
    user = session.exec(select(Client).where(Client.mail == req.mail)).first()
    if not user:
        raise HTTPException(status_code=404, detail="Usuario no encontrado")
    user.passwrd = hash_password(req.new_password)
    session.delete(token_row)
    session.commit()
    return MessageResponse(message="Contraseña actualizada")
