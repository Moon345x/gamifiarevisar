"""Corre este script una vez para insertar los equipos base."""
from sqlmodel import Session
from app.core.database import engine, create_db_and_tables
from app.models.game import Team

equipos = [
    Team(nombre="Equipo 1", puntuacion=0, puesto=99),
    Team(nombre="Equipo 2", puntuacion=0, puesto=99),
    Team(nombre="Equipo 3", puntuacion=0, puesto=99),
    Team(nombre="Equipo 4", puntuacion=0, puesto=99),
]

create_db_and_tables()
with Session(engine) as s:
    for e in equipos:
        s.add(e)
    s.commit()
    print(f"✅ {len(equipos)} equipos insertados correctamente")
