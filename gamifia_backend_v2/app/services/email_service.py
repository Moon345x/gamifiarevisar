from email.message import EmailMessage
import smtplib
from app.core.config import settings

def send_password_reset_email(recipient: str, token: str, expires_minutes: int = 10) -> None:
    if not settings.MAIL_USERNAME or not settings.MAIL_PASSWORD or not settings.MAIL_FROM:
        raise RuntimeError("Configuracion de correo incompleta")

    msg = EmailMessage()
    msg["Subject"] = "Recuperacion de contrasena"
    msg["From"] = settings.MAIL_FROM
    msg["To"] = recipient
    msg.set_content(
        "Hola,\n\n"
        "Recibimos una solicitud para restablecer tu contrasena.\n"
        f"Tu token temporal es: {token}\n"
        f"Este token expira en {expires_minutes} minutos.\n\n"
        "Si no solicitaste este cambio, puedes ignorar este mensaje.\n"
    )

    with smtplib.SMTP(settings.SMTP_HOST, settings.SMTP_PORT) as smtp:
        if settings.SMTP_STARTTLS:
            smtp.starttls()
        smtp.login(settings.MAIL_USERNAME, settings.MAIL_PASSWORD)
        smtp.send_message(msg)
