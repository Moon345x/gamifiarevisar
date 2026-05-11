from typing import Optional
from fastapi import APIRouter, Depends, HTTPException
from sqlmodel import Session, select, func, delete
from app.core.database import get_session
from app.core.dependencies import get_admin_user
from app.core.security import hash_password
from app.models.user import Client, PasswordResetToken
from app.models.quiz import QuizGeneral, QuizLearnStyles, QuizLearnStylesRs, QuizTypePlayers, QuizTypePlayersRs, PlataformRate
from app.models.institution import Institution

router = APIRouter()

def _institution_payload(inst: Institution) -> dict:
    return {"id": inst.id_institut, "name": inst.ins_name, "description": inst.ins_description}

@router.get("/institutions")
def list_institutions(session: Session = Depends(get_session), _=Depends(get_admin_user)):
    rows = session.exec(select(Institution).order_by(Institution.ins_name)).all()
    return [_institution_payload(i) for i in rows]

@router.get("/instituciones")
def list_institutions_legacy(session: Session = Depends(get_session), _=Depends(get_admin_user)):
    return session.exec(select(Institution)).all()

@router.post("/institutions", status_code=201)
def add_institution(data: dict, session: Session = Depends(get_session), _=Depends(get_admin_user)):
    name = (data.get("name") or data.get("ins_name") or "").strip()
    if not name:
        raise HTTPException(status_code=400, detail="El nombre es obligatorio")
    inst = Institution(ins_name=name, ins_description=data.get("ins_description") or data.get("description"))
    session.add(inst)
    session.commit()
    session.refresh(inst)
    return _institution_payload(inst)

@router.delete("/institutions/{institution_id}")
def delete_institution(institution_id: int, session: Session = Depends(get_session), _=Depends(get_admin_user)):
    inst = session.get(Institution, institution_id)
    if not inst:
        raise HTTPException(status_code=404, detail="Institucion no encontrada")
    session.delete(inst)
    session.commit()
    return {"message": "Institucion eliminada"}

@router.post("/instituciones", status_code=201)
def add_institution_legacy(data: dict, session: Session = Depends(get_session), _=Depends(get_admin_user)):
    inst = Institution(ins_name=data.get("ins_name"), ins_description=data.get("ins_description"))
    session.add(inst)
    session.commit()
    session.refresh(inst)
    return inst

@router.get("/students")
def list_students(session: Session = Depends(get_session), _=Depends(get_admin_user)):
    students = session.exec(select(Client).where(Client.type_user == 0)).all()
    result = []
    for s in students:
        general = session.get(QuizGeneral, s.id_student)
        learn_styles = session.get(QuizLearnStylesRs, s.id_student)
        type_players = session.get(QuizTypePlayersRs, s.id_student)
        institution = general.isntitucion if general and general.isntitucion else s.institution
        result.append({
            "id_student": s.id_student,
            "full_name": s.full_name,
            "username": s.username,
            "mail": s.mail,
            "institution": institution,
            "gender": general.genero if general else None,
            "age_range": general.r_edad if general else None,
            "grade": general.grado if general else None,
            "has_general": general is not None,
            "has_learn_styles": learn_styles is not None,
            "has_type_players": type_players is not None,
            "learn_styles": learn_styles.model_dump() if learn_styles else None,
            "type_players": type_players.model_dump() if type_players else None,
        })
    return result

@router.post("/students", status_code=201)
def create_student(data: dict, session: Session = Depends(get_session), _=Depends(get_admin_user)):
    full_name = (data.get("full_name") or "").strip()
    username = (data.get("username") or "").strip()
    mail = (data.get("mail") or "").strip()
    password = data.get("password") or ""
    institution = (data.get("institution") or "").strip()
    if not full_name or not username or not mail or not password or not institution:
        raise HTTPException(status_code=400, detail="Faltan datos obligatorios")
    existing = session.exec(select(Client).where(Client.username == username)).first()
    if existing:
        raise HTTPException(status_code=400, detail="El nombre de usuario ya existe")
    existing_mail = session.exec(select(Client).where(Client.mail == mail)).first()
    if existing_mail:
        raise HTTPException(status_code=400, detail="El correo ya esta registrado")
    student = Client(
        full_name=full_name,
        username=username,
        mail=mail,
        institution=institution,
        passwrd=hash_password(password),
        type_user=0,
    )
    session.add(student)
    session.commit()
    session.refresh(student)
    return {
        "id_student": student.id_student,
        "full_name": student.full_name,
        "username": student.username,
        "mail": student.mail,
        "institution": student.institution,
    }

@router.put("/students/{id_student}/password")
def reset_student_password(id_student: int, data: dict, session: Session = Depends(get_session), _=Depends(get_admin_user)):
    new_password = data.get("new_password") or ""
    if not new_password:
        raise HTTPException(status_code=400, detail="La nueva contrasena es obligatoria")
    student = session.get(Client, id_student)
    if not student or student.type_user != 0:
        raise HTTPException(status_code=404, detail="Estudiante no encontrado")
    student.passwrd = hash_password(new_password)
    session.commit()
    return {"message": "Contrasena actualizada"}

@router.delete("/students/{id_student}")
def delete_student(id_student: int, session: Session = Depends(get_session), _=Depends(get_admin_user)):
    student = session.get(Client, id_student)
    if not student or student.type_user != 0:
        raise HTTPException(status_code=404, detail="Estudiante no encontrado")
    if student.mail:
        session.exec(delete(PasswordResetToken).where(PasswordResetToken.mail == student.mail))
    session.exec(delete(PlataformRate).where(PlataformRate.id_student == id_student))
    session.exec(delete(QuizLearnStyles).where(QuizLearnStyles.id_student == id_student))
    session.exec(delete(QuizLearnStylesRs).where(QuizLearnStylesRs.id_student == id_student))
    session.exec(delete(QuizTypePlayers).where(QuizTypePlayers.id_student == id_student))
    session.exec(delete(QuizTypePlayersRs).where(QuizTypePlayersRs.id_student == id_student))
    session.exec(delete(QuizGeneral).where(QuizGeneral.id_student == id_student))
    session.delete(student)
    session.commit()
    return {"message": "Estudiante eliminado"}

@router.get("/students/{id_student}")
def get_student_detail(id_student: int, session: Session = Depends(get_session), _=Depends(get_admin_user)):
    student = session.get(Client, id_student)
    if not student or student.type_user != 0:
        raise HTTPException(status_code=404, detail="Estudiante no encontrado")
    general = session.get(QuizGeneral, id_student)
    learn_styles = session.get(QuizLearnStylesRs, id_student)
    type_players = session.get(QuizTypePlayersRs, id_student)
    plataformas = session.exec(
        select(PlataformRate.plataform).where(PlataformRate.id_student == id_student)
    ).all()
    return {
        "student": student.model_dump(),
        "general": (general.model_dump() | {"plataformas": plataformas}) if general else None,
        "learn_styles": learn_styles.model_dump() if learn_styles else None,
        "type_players": type_players.model_dump() if type_players else None,
    }

@router.get("/stats/genero")
def stats_genero(institution: Optional[str] = None, session: Session = Depends(get_session), _=Depends(get_admin_user)):
    query = select(QuizGeneral.genero, func.count(QuizGeneral.genero))
    if institution:
        query = query.where(QuizGeneral.isntitucion == institution)
    rows = session.exec(
        query.group_by(QuizGeneral.genero)
    ).all()
    return [{"genero": r[0], "count": r[1]} for r in rows]

@router.get("/stats/edad")
def stats_edad(institution: Optional[str] = None, session: Session = Depends(get_session), _=Depends(get_admin_user)):
    query = select(QuizGeneral.r_edad, func.count(QuizGeneral.r_edad))
    if institution:
        query = query.where(QuizGeneral.isntitucion == institution)
    rows = session.exec(
        query.group_by(QuizGeneral.r_edad)
    ).all()
    return [{"rango": r[0], "count": r[1]} for r in rows]

@router.get("/stats/plataformas")
def stats_plataformas(institution: Optional[str] = None, session: Session = Depends(get_session), _=Depends(get_admin_user)):
    if institution:
        ids = select(QuizGeneral.id_student).where(QuizGeneral.isntitucion == institution)
        query = select(PlataformRate.plataform, func.count(PlataformRate.plataform)).where(
            PlataformRate.id_student.in_(ids)
        )
    else:
        query = select(PlataformRate.plataform, func.count(PlataformRate.plataform))
    rows = session.exec(
        query.group_by(PlataformRate.plataform)
        .order_by(func.count(PlataformRate.plataform).desc())
    ).all()
    return [{"plataforma": r[0], "count": r[1]} for r in rows]

@router.get("/stats/hexad")
def stats_hexad(institution: Optional[str] = None, session: Session = Depends(get_session), _=Depends(get_admin_user)):
    if institution:
        ids = select(QuizGeneral.id_student).where(QuizGeneral.isntitucion == institution)
        rows = session.exec(select(QuizTypePlayersRs).where(QuizTypePlayersRs.id_student.in_(ids))).all()
    else:
        rows = session.exec(select(QuizTypePlayersRs)).all()
    if not rows:
        return {}
    n = len(rows)
    return {
        "philanthrop": round(sum(r.philanthrop or 0 for r in rows) / n, 2),
        "socialiser":  round(sum(r.socialiser  or 0 for r in rows) / n, 2),
        "free_spirit": round(sum(r.free_spirit or 0 for r in rows) / n, 2),
        "achiever":    round(sum(r.achiever    or 0 for r in rows) / n, 2),
        "disruptor":   round(sum(r.disruptor   or 0 for r in rows) / n, 2),
        "player":      round(sum(r.player      or 0 for r in rows) / n, 2),
    }

@router.get("/stats/learn-styles")
def stats_learn_styles(institution: Optional[str] = None, session: Session = Depends(get_session), _=Depends(get_admin_user)):
    if institution:
        ids = select(QuizGeneral.id_student).where(QuizGeneral.isntitucion == institution)
        rows = session.exec(select(QuizLearnStylesRs).where(QuizLearnStylesRs.id_student.in_(ids))).all()
    else:
        rows = session.exec(select(QuizLearnStylesRs)).all()
    if not rows:
        return {}
    n = len(rows)
    return {
        "avg_perception_val": round(sum(r.perception_val or 0 for r in rows) / n, 2),
        "avg_input_val":      round(sum(r.input_val      or 0 for r in rows) / n, 2),
        "avg_processes_val":  round(sum(r.processes_val  or 0 for r in rows) / n, 2),
        "avg_understand_val": round(sum(r.understand_val or 0 for r in rows) / n, 2),
    }
