from typing import Optional
from sqlmodel import Field, SQLModel

class QuizGeneral(SQLModel, table=True):
    __tablename__ = "quiz_general"
    id_student: int = Field(primary_key=True, foreign_key="client.id_student")
    isntitucion: Optional[str] = Field(default=None, max_length=100)
    genero: Optional[str] = Field(default=None, max_length=20)
    grado: Optional[str] = Field(default=None, max_length=50)
    r_edad: Optional[str] = Field(default=None, max_length=20)

class PlataformRate(SQLModel, table=True):
    __tablename__ = "plataform_rate"
    id_student: int = Field(primary_key=True, foreign_key="client.id_student")
    plataform: str = Field(primary_key=True, max_length=30)

class QuizLearnStyles(SQLModel, table=True):
    __tablename__ = "quiz_learn_styles"
    id_student: int = Field(primary_key=True, foreign_key="client.id_student")
    p1: Optional[str] = None; p2: Optional[str] = None; p3: Optional[str] = None; p4: Optional[str] = None
    p5: Optional[str] = None; p6: Optional[str] = None; p7: Optional[str] = None; p8: Optional[str] = None
    p9: Optional[str] = None; p10: Optional[str] = None; p11: Optional[str] = None; p12: Optional[str] = None
    p13: Optional[str] = None; p14: Optional[str] = None; p15: Optional[str] = None; p16: Optional[str] = None
    p17: Optional[str] = None; p18: Optional[str] = None; p19: Optional[str] = None; p20: Optional[str] = None
    p21: Optional[str] = None; p22: Optional[str] = None; p23: Optional[str] = None; p24: Optional[str] = None
    p25: Optional[str] = None; p26: Optional[str] = None; p27: Optional[str] = None; p28: Optional[str] = None
    p29: Optional[str] = None; p30: Optional[str] = None; p31: Optional[str] = None; p32: Optional[str] = None
    p33: Optional[str] = None; p34: Optional[str] = None; p35: Optional[str] = None; p36: Optional[str] = None
    p37: Optional[str] = None; p38: Optional[str] = None; p39: Optional[str] = None; p40: Optional[str] = None
    p41: Optional[str] = None; p42: Optional[str] = None; p43: Optional[str] = None; p44: Optional[str] = None

class QuizLearnStylesRs(SQLModel, table=True):
    __tablename__ = "quiz_learn_styles_rs"
    id_student: int = Field(primary_key=True, foreign_key="client.id_student")
    perception: Optional[str] = Field(default=None, max_length=6)
    perception_val: Optional[int] = None
    input: Optional[str] = Field(default=None, max_length=6)
    input_val: Optional[int] = None
    processes: Optional[str] = Field(default=None, max_length=6)
    processes_val: Optional[int] = None
    understand: Optional[str] = Field(default=None, max_length=6)
    understand_val: Optional[int] = None

class QuizTypePlayers(SQLModel, table=True):
    __tablename__ = "quiz_type_players"
    id_student: int = Field(primary_key=True, foreign_key="client.id_student")
    p1: Optional[int] = None; p2: Optional[int] = None; p3: Optional[int] = None; p4: Optional[int] = None
    p5: Optional[int] = None; p6: Optional[int] = None; p7: Optional[int] = None; p8: Optional[int] = None
    p9: Optional[int] = None; p10: Optional[int] = None; p11: Optional[int] = None; p12: Optional[int] = None
    p13: Optional[int] = None; p14: Optional[int] = None; p15: Optional[int] = None; p16: Optional[int] = None
    p17: Optional[int] = None; p18: Optional[int] = None; p19: Optional[int] = None; p20: Optional[int] = None
    p21: Optional[int] = None; p22: Optional[int] = None; p23: Optional[int] = None; p24: Optional[int] = None

class QuizTypePlayersRs(SQLModel, table=True):
    __tablename__ = "quiz_type_players_rs"
    id_student: int = Field(primary_key=True, foreign_key="client.id_student")
    philanthrop: Optional[float] = None
    socialiser: Optional[float] = None
    free_spirit: Optional[float] = None
    achiever: Optional[float] = None
    player: Optional[float] = None
    disruptor: Optional[float] = None
