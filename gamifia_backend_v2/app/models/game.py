from typing import Optional
from sqlmodel import Field, SQLModel

class GameUser(SQLModel, table=True):
    __tablename__ = "Usuarios"
    idUsuario: Optional[int] = Field(default=None, primary_key=True)
    nombre: str = Field(max_length=150)
    correo: Optional[str] = Field(default=None, max_length=150)
    contrasena: str = Field(max_length=128, alias="contraseña")
    avatar: int = Field(default=0)
    puntuacion: int = Field(default=0)
    curso: str = Field(max_length=100)
    edad: int
    equipo: int = Field(default=0)

    class Config:
        populate_by_name = True

class Team(SQLModel, table=True):
    __tablename__ = "Equipos"
    idEquipo: Optional[int] = Field(default=None, primary_key=True)
    nombre: str = Field(max_length=150)
    puntuacion: int = Field(default=0)
    puesto: int = Field(default=99)

class GameSession(SQLModel, table=True):
    __tablename__ = "Partidas"
    idPartida: Optional[int] = Field(default=None, primary_key=True)
    idJugador: int
    juego: str = Field(max_length=100)
    nivel: str = Field(max_length=100)
    puntaje: int

class Achievement(SQLModel, table=True):
    __tablename__ = "Logros"
    idLogros: Optional[int] = Field(default=None, primary_key=True)
    nombre: str = Field(max_length=200)
    emoji: int = Field(default=0)

class PlayerAchievement(SQLModel, table=True):
    __tablename__ = "Logros_Usuarios"
    idLogrosUsuario: Optional[int] = Field(default=None, primary_key=True)
    idUsuario: int
    idLogro: int
