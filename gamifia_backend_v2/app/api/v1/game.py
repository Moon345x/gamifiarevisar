from fastapi import APIRouter, Depends, HTTPException
from sqlmodel import Session, select
from app.core.database import get_session
from app.core.security import hash_password, verify_password, create_access_token
from app.models.game import GameUser, Team, GameSession, Achievement, PlayerAchievement
from app.schemas.game import (GameRegisterRequest, GameLoginRequest, GameToken,
                               PartidaRequest, UpdateScoreRequest, UpdateTeamRequest,
                               AvatarRequest, SelectTeamRequest, LogroRequest)

router = APIRouter()

@router.post("/register", status_code=201)
def game_register(req: GameRegisterRequest, session: Session = Depends(get_session)):
    existing = session.exec(select(GameUser).where(GameUser.correo == req.correo)).first()
    if existing:
        raise HTTPException(status_code=400, detail="El correo ya está registrado")
    user = GameUser(nombre=req.nombre, correo=req.correo,
                    contrasena=hash_password(req.password), curso=req.curso, edad=req.edad)
    session.add(user)
    session.commit()
    session.refresh(user)
    return {"message": "New record created successfully", "idUsuario": user.idUsuario}

@router.post("/login", response_model=GameToken)
def game_login(req: GameLoginRequest, session: Session = Depends(get_session)):
    user = session.exec(select(GameUser).where(GameUser.correo == req.correo)).first()
    if not user or not verify_password(req.password, user.contrasena):
        raise HTTPException(status_code=401, detail="Credenciales inválidas")
    token = create_access_token({"sub": str(user.idUsuario), "type": "game"})
    return GameToken(access_token=token, user={
        "idUsuario": user.idUsuario, "nombre": user.nombre,
        "correo": user.correo, "avatar": user.avatar,
        "puntuacion": user.puntuacion, "equipo": user.equipo
    })

@router.post("/partida", status_code=201)
def register_partida(req: PartidaRequest, session: Session = Depends(get_session)):
    p = GameSession(idJugador=req.idJugador, juego=req.juego, nivel=req.nivel, puntaje=req.puntaje)
    session.add(p)
    session.commit()
    return {"message": "New record created successfully"}

@router.post("/update-score")
def update_score(req: UpdateScoreRequest, session: Session = Depends(get_session)):
    user = session.get(GameUser, req.idUsuario)
    if not user:
        raise HTTPException(status_code=404, detail="Usuario no encontrado")
    user.puntuacion += req.puntuacion
    session.add(user)
    session.commit()
    return {"message": "Puntuación actualizada"}

@router.post("/update-team-score")
def update_team_score(req: UpdateTeamRequest, session: Session = Depends(get_session)):
    team = session.get(Team, req.idEquipo)
    if not team:
        raise HTTPException(status_code=404, detail="Equipo no encontrado")
    team.puntuacion += req.puntuacion
    session.add(team)
    session.commit()
    return {"message": "Equipo Actualizado correctamente"}

@router.post("/avatar")
def change_avatar(req: AvatarRequest, session: Session = Depends(get_session)):
    user = session.get(GameUser, req.idUsuario)
    if not user:
        raise HTTPException(status_code=404, detail="Usuario no encontrado")
    user.avatar = req.avatar
    session.add(user)
    session.commit()
    return {"message": "cambio avatar"}

@router.post("/select-team")
def select_team(req: SelectTeamRequest, session: Session = Depends(get_session)):
    user = session.get(GameUser, req.idUsuario)
    if not user:
        raise HTTPException(status_code=404, detail="Usuario no encontrado")
    user.equipo = req.equipo
    session.add(user)
    session.commit()
    return {"message": "cambio el equipo"}

@router.post("/logro")
def register_logro(req: LogroRequest, session: Session = Depends(get_session)):
    existing = session.exec(
        select(PlayerAchievement).where(
            PlayerAchievement.idUsuario == req.idUsuario,
            PlayerAchievement.idLogro == req.idLogro
        )
    ).first()
    if existing:
        raise HTTPException(status_code=400, detail="El logro ya fue registrado")
    session.add(PlayerAchievement(idUsuario=req.idUsuario, idLogro=req.idLogro))
    session.commit()
    return {"message": "New Logro registered"}

@router.get("/equipos")
def get_equipos(session: Session = Depends(get_session)):
    teams = session.exec(select(Team).order_by(Team.puntuacion.desc())).all()
    return teams

@router.get("/logros/{id_usuario}")
def get_logros(id_usuario: int, session: Session = Depends(get_session)):
    logros = session.exec(
        select(PlayerAchievement).where(PlayerAchievement.idUsuario == id_usuario)
        .order_by(PlayerAchievement.idLogro)
    ).all()
    return logros

@router.get("/ranking")
def get_ranking(session: Session = Depends(get_session)):
    users = session.exec(select(GameUser).order_by(GameUser.puntuacion.desc())).all()
    return [{"nombre": u.nombre, "puntuacion": u.puntuacion, "equipo": u.equipo} for u in users]

@router.get("/ranking/equipo/{equipo_id}")
def get_ranking_equipo(equipo_id: int, session: Session = Depends(get_session)):
    users = session.exec(
        select(GameUser).where(GameUser.equipo == equipo_id).order_by(GameUser.puntuacion.desc())
    ).all()
    return [{"nombre": u.nombre, "puntuacion": u.puntuacion} for u in users]
