import { useState, useEffect } from "react"
import { useNavigate } from "react-router-dom"
import api from "../api/axiosClient"
import { preguntasTP } from "../data/preguntasTP"

const LIKERT = ["Totalmente en desacuerdo","En desacuerdo","Algo en desacuerdo","Neutral","Algo de acuerdo","De acuerdo","Totalmente de acuerdo"]

export default function QuizTypePlayers() {
  const nav = useNavigate()
  const [respuestas, setRespuestas] = useState(Array(24).fill(0))
  const [error, setError] = useState("")
  const [loading, setLoading] = useState(false)
  const [done, setDone] = useState(false)

  useEffect(() => {
    api.get("/quiz/status").then(r => { if (r.data.has_type_players) setDone(true) })
  }, [])

  const handleSubmit = async e => {
    e.preventDefault()
    if (respuestas.some(r => r === 0)) { setError("Responde todas las preguntas"); return }
    setLoading(true)
    try {
      await api.post("/quiz/type-players", { respuestas })
      nav("/resultados")
    } catch (err) {
      setError(err.response?.data?.detail || "Error al guardar")
    } finally { setLoading(false) }
  }

  if (done) return (
    <div className="card max-w-lg mx-auto text-center space-y-3">
      <div className="text-5xl">✅</div>
      <h2 className="text-xl font-bold text-success">Ya completaste este cuestionario</h2>
      <button className="btn-primary" onClick={() => nav("/resultados")}>Ver mis resultados →</button>
    </div>
  )

  return (
    <div className="max-w-3xl mx-auto space-y-6">
      <h2 className="text-2xl font-bold text-dark">🎯 Tipo de Jugador — Modelo Hexad</h2>
      <p className="text-sm text-gray-500">Califica cada afirmación del 1 (totalmente en desacuerdo) al 7 (totalmente de acuerdo).</p>
      {error && <div className="bg-red-100 text-red-700 rounded px-4 py-2 text-sm">{error}</div>}
      <form onSubmit={handleSubmit} className="space-y-6">
        <div className="card">
          {preguntasTP.map((p, idx) => (
            <div key={p.n} className="mb-5">
              <p className="text-sm font-semibold text-dark mb-2">
                <span className="text-primary">{idx + 1}.</span> {p.texto}
              </p>
              <div className="flex gap-1 flex-wrap">
                {[1,2,3,4,5,6,7].map(v => (
                  <button
                    key={v}
                    type="button"
                    onClick={() => { const r=[...respuestas]; r[idx]=v; setRespuestas(r) }}
                    className={"flex-1 min-w-8 py-2 text-xs font-bold rounded border transition " + (respuestas[idx]===v ? "bg-primary text-white border-primary" : "hover:bg-gray-100 border-gray-300")}
                  >
                    {v}
                  </button>
                ))}
              </div>
              <div className="flex justify-between text-xs text-gray-400 mt-1">
                <span>En desacuerdo</span><span>De acuerdo</span>
              </div>
            </div>
          ))}
        </div>
        <div className="sticky bottom-4">
          <button className="btn-success w-full text-base shadow-lg" disabled={loading}>
            {loading ? "Calculando..." : "Enviar (" + respuestas.filter(r=>r>0).length + "/24)"}
          </button>
        </div>
      </form>
    </div>
  )
}
