import { useState, useEffect } from "react"
import { useNavigate } from "react-router-dom"
import api from "../api/axiosClient"
import { preguntasLS } from "../data/preguntasLS"

export default function QuizLearnStyles() {
  const nav = useNavigate()
  const [respuestas, setRespuestas] = useState(Array(44).fill(""))
  const [error, setError] = useState("")
  const [loading, setLoading] = useState(false)
  const [done, setDone] = useState(false)

  useEffect(() => {
    api.get("/quiz/status").then(r => { if (r.data.has_learn_styles) setDone(true) })
  }, [])

  const handleSubmit = async e => {
    e.preventDefault()
    const missing = respuestas.findIndex(r => r === "")
    if (missing !== -1) { setError(`Falta responder la pregunta ${missing + 1}`); return }
    setLoading(true)
    try {
      await api.post("/quiz/learn-styles", { respuestas })
      nav("/quiz/jugador")
    } catch (err) {
      setError(err.response?.data?.detail || "Error al guardar")
    } finally { setLoading(false) }
  }

  if (done) return (
    <div className="card max-w-lg mx-auto text-center space-y-3">
      <div className="text-5xl">✅</div>
      <h2 className="text-xl font-bold text-success">Ya completaste este cuestionario</h2>
      <button className="btn-primary" onClick={() => nav("/quiz/jugador")}>Ir a Tipo de Jugador →</button>
    </div>
  )

  return (
    <div className="max-w-3xl mx-auto space-y-6">
      <h2 className="text-2xl font-bold text-dark">🧠 Estilos de Aprendizaje — Felder-Silverman</h2>
      <p className="text-sm text-gray-500">Responde las 44 preguntas seleccionando la opción que mejor te describe. No hay respuestas correctas o incorrectas.</p>
      {error && <div className="bg-red-100 text-red-700 rounded px-4 py-2 text-sm">{error}</div>}
      <form onSubmit={handleSubmit} className="space-y-4">
        {preguntasLS.map((p, idx) => (
          <div key={p.n} className={`card border-l-4 ${respuestas[idx] ? "border-success" : "border-gray-200"}`}>
            <p className="font-semibold text-sm text-dark mb-3">
              <span className="text-primary font-bold">{p.n}.</span> {p.texto}
            </p>
            <div className="flex flex-col sm:flex-row gap-3">
              {[{val:"A", label: p.a}, {val:"B", label: p.b}].map(op => (
                <label key={op.val}
                  className={`flex-1 flex items-center gap-2 border rounded px-3 py-2 cursor-pointer text-sm transition
                    ${respuestas[idx] === op.val ? "bg-primary text-white border-primary" : "hover:bg-gray-50"}`}>
                  <input type="radio" name={`p${p.n}`} value={op.val} className="hidden"
                    checked={respuestas[idx] === op.val}
                    onChange={() => { const r = [...respuestas]; r[idx] = op.val; setRespuestas(r) }} />
                  <span className="font-bold">{op.val})</span> {op.label}
                </label>
              ))}
            </div>
          </div>
        ))}
        <div className="sticky bottom-4">
          <button className="btn-success w-full text-base shadow-lg" disabled={loading}>
            {loading ? "Calculando resultados..." : `Enviar respuestas (${respuestas.filter(r=>r).length}/44)`}
          </button>
        </div>
      </form>
    </div>
  )
}
