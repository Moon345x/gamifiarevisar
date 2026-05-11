from typing import Optional
from sqlmodel import Field, SQLModel

class Institution(SQLModel, table=True):
    __tablename__ = "institution"
    id_institut: Optional[int] = Field(default=None, primary_key=True)
    ins_name: Optional[str] = Field(default=None)
    ins_description: Optional[str] = Field(default=None, max_length=100)
