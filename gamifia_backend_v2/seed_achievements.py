"""Corre este script una vez para insertar los 9 logros base en la BD."""
from sqlmodel import Session
from app.core.database import engine, create_db_and_tables
from app.models.game import Achievement

logros = [
    Achievement(idLogros=1, nombre="Cambiar el avatar", emoji=1),
    Achievement(idLogros=2, nombre="Pertenecer a un equipo", emoji=2),
    Achievement(idLogros=3, nombre="Ser el jugador con más puntos en el equipo", emoji=3),
    Achievement(idLogros=4, nombre="Completar cuestionario general", emoji=4),
    Achievement(idLogros=5, nombre="Completar estilos de aprendizaje", emoji=5),
    Achievement(idLogros=6, nombre="Completar tipo de jugador", emoji=6),
    Achievement(idLogros=7, nombre="Superar juego el salto de la rana", emoji=7),
    Achievement(idLogros=8, nombre="Superar el juego del astronauta", emoji=8),
    Achievement(idLogros=9, nombre="Superar el juego el rompe cabezas", emoji=9),
]

create_db_and_tables()
with Session(engine) as s:
    for l in logros:
        existing = s.get(Achievement, l.idLogros)
        if not existing:
            s.add(l)
    s.commit()
    print(f"✅ {len(logros)} logros insertados correctamente")
