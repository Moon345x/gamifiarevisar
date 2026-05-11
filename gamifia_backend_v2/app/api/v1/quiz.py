from fastapi import APIRouter, Depends, HTTPException
from sqlmodel import Session, select
from app.core.database import get_session
from app.core.dependencies import get_current_user
from app.models.user import Client
from app.models.quiz import (QuizGeneral, PlataformRate, QuizLearnStyles,
                               QuizLearnStylesRs, QuizTypePlayers, QuizTypePlayersRs)
from app.schemas.quiz import (QuizGeneralRequest, LearnStylesRequest, TypePlayersRequest,
                                StudentStatus, FullResults)
from app.services.quiz_service import calculate_learn_styles, calculate_type_players

router = APIRouter()

@router.get("/status", response_model=StudentStatus)
def quiz_status(current_user: Client = Depends(get_current_user), session: Session = Depends(get_session)):
    uid = current_user.id_student
    return StudentStatus(
        has_general=session.get(QuizGeneral, uid) is not None,
        has_learn_styles=session.get(QuizLearnStyles, uid) is not None,
        has_type_players=session.get(QuizTypePlayers, uid) is not None,
    )

@router.post("/general", status_code=201)
def submit_general(req: QuizGeneralRequest, current_user: Client = Depends(get_current_user), session: Session = Depends(get_session)):
    uid = current_user.id_student
    if session.get(QuizGeneral, uid):
        raise HTTPException(status_code=400, detail="Ya completaste el cuestionario general")
    qg = QuizGeneral(id_student=uid, isntitucion=req.isntitucion, genero=req.genero, grado=req.grado, r_edad=req.r_edad)
    session.add(qg)
    for plat in req.plataformas:
        session.add(PlataformRate(id_student=uid, plataform=plat))
    session.commit()
    return {"message": "Datos generales guardados correctamente"}

@router.post("/learn-styles", status_code=201)
def submit_learn_styles(req: LearnStylesRequest, current_user: Client = Depends(get_current_user), session: Session = Depends(get_session)):
    uid = current_user.id_student
    if session.get(QuizLearnStyles, uid):
        raise HTTPException(status_code=400, detail="Ya completaste el cuestionario de estilos de aprendizaje")
    result = calculate_learn_styles(req.respuestas)
    answers = {f"p{i+1}": v for i, v in enumerate(req.respuestas)}
    session.add(QuizLearnStyles(id_student=uid, **answers))
    session.add(QuizLearnStylesRs(id_student=uid, **result))
    session.commit()
    return result

@router.post("/type-players", status_code=201)
def submit_type_players(req: TypePlayersRequest, current_user: Client = Depends(get_current_user), session: Session = Depends(get_session)):
    uid = current_user.id_student
    if session.get(QuizTypePlayers, uid):
        raise HTTPException(status_code=400, detail="Ya completaste el cuestionario de tipo de jugador")
    result = calculate_type_players(req.respuestas)
    answers = {f"p{i+1}": v for i, v in enumerate(req.respuestas)}
    session.add(QuizTypePlayers(id_student=uid, **answers))
    session.add(QuizTypePlayersRs(id_student=uid, **result))
    session.commit()
    return result

@router.get("/results", response_model=FullResults)
def get_results(current_user: Client = Depends(get_current_user), session: Session = Depends(get_session)):
    uid = current_user.id_student
    ls = session.get(QuizLearnStylesRs, uid)
    tp = session.get(QuizTypePlayersRs, uid)
    gen = session.get(QuizGeneral, uid)
    return FullResults(
        learn_styles=ls.model_dump() if ls else None,
        type_players=tp.model_dump() if tp else None,
        general=gen.model_dump() if gen else None,
    )
