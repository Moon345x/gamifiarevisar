from fastapi import APIRouter, Depends
from app.core.dependencies import get_current_user
from app.models.user import Client
from pydantic import BaseModel

router = APIRouter()

class UserOut(BaseModel):
    id_student: int
    full_name: str | None
    username: str
    mail: str | None
    institution: str | None
    type_user: int

@router.get("/me", response_model=UserOut)
def get_me(current_user: Client = Depends(get_current_user)):
    return current_user
