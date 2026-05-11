from typing import List

def calculate_learn_styles(respuestas: List[str]) -> dict:
    """Algoritmo Felder-Silverman: (i+3)%4 distribuye preguntas en 4 dimensiones."""
    if len(respuestas) != 44:
        raise ValueError("Se requieren exactamente 44 respuestas")

    countA = [0, 0, 0, 0]
    countB = [0, 0, 0, 0]

    for i, resp in enumerate(respuestas, start=1):
        if resp not in ("A", "B"):
            raise ValueError(f"Respuesta inválida en pregunta {i}: debe ser A o B")
        pos = (i + 3) % 4
        if resp == "A":
            countA[pos] += 1
        else:
            countB[pos] += 1

    # [0]=Procesos(Activo/Reflexivo), [1]=Percepción(Sensitivo/Intuitivo)
    # [2]=Entrada(Visual/Verbal), [3]=Comprensión(Secuencial/Global)
    process   = "ACT"  if countA[0] >= countB[0] else "REF"
    perception = "SENS" if countA[1] >= countB[1] else "INTUI"
    channel   = "VISUA" if countA[2] >= countB[2] else "VERBA"
    understand = "SECUE" if countA[3] >= countB[3] else "GLOBA"

    return {
        "perception": perception, "perception_val": abs(countA[1] - countB[1]),
        "input": channel,         "input_val":      abs(countA[2] - countB[2]),
        "processes": process,     "processes_val":  abs(countA[0] - countB[0]),
        "understand": understand, "understand_val": abs(countA[3] - countB[3]),
    }

def calculate_type_players(respuestas: List[int]) -> dict:
    """Algoritmo Hexad: grupos de 4 preguntas por tipo. floor((i-1)/4) define el tipo."""
    if len(respuestas) != 24:
        raise ValueError("Se requieren exactamente 24 respuestas")

    dims = [0.0] * 6  # [Filántropo, Socializador, Espíritu Libre, Triunfador, Disruptor, Jugador]
    total = 0

    for i, val in enumerate(respuestas, start=1):
        if not (1 <= val <= 7):
            raise ValueError(f"Respuesta inválida en pregunta {i}: debe ser 1-7")
        pos = (i - 1) // 4
        dims[pos] += val
        total += val

    if total == 0:
        total = 1

    return {
        "philanthrop": round((100.0 / total) * dims[0], 8),
        "socialiser":  round((100.0 / total) * dims[1], 8),
        "free_spirit": round((100.0 / total) * dims[2], 8),
        "achiever":    round((100.0 / total) * dims[3], 8),
        "disruptor":   round((100.0 / total) * dims[4], 8),
        "player":      round((100.0 / total) * dims[5], 8),
    }
