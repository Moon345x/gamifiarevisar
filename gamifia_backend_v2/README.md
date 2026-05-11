# GamifyTest Backend — FastAPI

Reingeniería fiel del sistema PHP original. Stack: **FastAPI + SQLModel + SQLite**.

## Estructura de Bases de Datos

| BD original | Equivalente aquí | Tablas |
|---|---|---|
| `dataapp` | `gamifia.db` | client, institution, quiz_*, plataform_rate |
| `bdgames` | `gamifia.db` | Usuarios, Equipos, Partidas, Logros, Logros_Usuarios |

## Instalación

```bash
python -m venv venv
source venv/bin/activate
pip install -r requirements.txt
```

## Primer arranque

```bash
# 1. Crear tablas e iniciar servidor
uvicorn main:app --reload

# 2. (Opcional) Insertar logros y equipos base
python seed_achievements.py
python seed_teams.py
```

## Documentación API

Abrí en el navegador: http://localhost:8000/api/docs

## Endpoints principales

### Auth (plataforma web)
- `POST /api/v1/auth/register` — Registro estudiante
- `POST /api/v1/auth/login` — Login (OAuth2 form)
- `GET  /api/v1/users/me` — Perfil actual

### Quiz (cuestionarios)
- `GET  /api/v1/quiz/status` — Estado de cuestionarios del estudiante
- `POST /api/v1/quiz/general` — Datos demográficos + plataformas
- `POST /api/v1/quiz/learn-styles` — 44 respuestas Felder-Silverman (A/B)
- `POST /api/v1/quiz/type-players` — 24 respuestas Hexad (1-7)
- `GET  /api/v1/quiz/results` — Ver resultados propios

### Game (Unity)
- `POST /api/v1/game/register` — Registrar jugador Unity
- `POST /api/v1/game/login` — Login Unity (devuelve JWT)
- `POST /api/v1/game/partida` — Registrar partida
- `POST /api/v1/game/update-score` — Sumar puntos a jugador
- `POST /api/v1/game/update-team-score` — Sumar puntos a equipo
- `POST /api/v1/game/avatar` — Cambiar avatar
- `POST /api/v1/game/select-team` — Asignar equipo
- `POST /api/v1/game/logro` — Registrar logro obtenido
- `GET  /api/v1/game/equipos` — Lista equipos por puntaje
- `GET  /api/v1/game/ranking` — Ranking global jugadores
- `GET  /api/v1/game/ranking/equipo/{id}` — Ranking por equipo
- `GET  /api/v1/game/logros/{id_usuario}` — Logros de un jugador

### Admin (solo type_user=1)
- `GET  /api/v1/admin/students` — Todos los estudiantes con resultados
- `GET  /api/v1/admin/stats/genero` — Estadísticas por género
- `GET  /api/v1/admin/stats/edad` — Estadísticas por edad
- `GET  /api/v1/admin/stats/plataformas` — Uso de plataformas
- `GET  /api/v1/admin/stats/hexad` — Promedio tipos Hexad
- `GET  /api/v1/admin/stats/learn-styles` — Promedio estilos aprendizaje
- `GET/POST /api/v1/admin/instituciones` — CRUD instituciones
