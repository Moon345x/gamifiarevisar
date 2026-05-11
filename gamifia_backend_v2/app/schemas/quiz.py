from typing import Optional, List, Literal
from pydantic import BaseModel

class QuizGeneralRequest(BaseModel):
    isntitucion: Optional[str] = None
    genero: Optional[Literal["Masculino", "Femenino"]] = None
    grado: Optional[Literal[
        "Grado 1°","Grado 2°","Grado 3°","Grado 4°","Grado 5°",
        "Grado 6°","Grado 7°","Grado 8°","Grado 9°",
        "Grado 10°","Grado 11°",
        "Semestre 1","Semestre 2","Semestre 3","Semestre 4","Semestre 5",
        "Semestre 6","Semestre 7","Semestre 8","Semestre 9","Semestre 10",
    ]] = None
    r_edad: Optional[Literal["6-9", "10-13", "14-17", "18 o más"]] = None
    plataformas: List[str] = []

class LearnStylesRequest(BaseModel):
    respuestas: List[str]  # Lista de 44 respuestas "A" o "B"

class TypePlayersRequest(BaseModel):
    respuestas: List[int]  # Lista de 24 valores 1-7

class LearnStylesResult(BaseModel):
    perception: str; perception_val: int
    input: str; input_val: int
    processes: str; processes_val: int
    understand: str; understand_val: int

class TypePlayersResult(BaseModel):
    philanthrop: float; socialiser: float; free_spirit: float
    achiever: float; player: float; disruptor: float

class StudentStatus(BaseModel):
    has_general: bool
    has_learn_styles: bool
    has_type_players: bool

class FullResults(BaseModel):
    learn_styles: Optional[LearnStylesResult] = None
    type_players: Optional[TypePlayersResult] = None
    general: Optional[dict] = None
