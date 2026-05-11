from typing import Optional, List
from pydantic import BaseModel

class GameRegisterRequest(BaseModel):
    nombre: str
    correo: Optional[str] = None
    password: str
    curso: str
    edad: int

class GameLoginRequest(BaseModel):
    correo: str
    password: str

class GameToken(BaseModel):
    access_token: str
    token_type: str = "bearer"
    user: dict

class PartidaRequest(BaseModel):
    idJugador: int
    juego: str
    nivel: str
    puntaje: int

class UpdateScoreRequest(BaseModel):
    idUsuario: int
    puntuacion: int

class UpdateTeamRequest(BaseModel):
    idEquipo: int
    puntuacion: int

class AvatarRequest(BaseModel):
    idUsuario: int
    avatar: int

class SelectTeamRequest(BaseModel):
    idUsuario: int
    equipo: int

class LogroRequest(BaseModel):
    idUsuario: int
    idLogro: int
