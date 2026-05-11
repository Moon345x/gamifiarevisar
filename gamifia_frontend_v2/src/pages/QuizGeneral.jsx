import { useState, useEffect } from "react"
import { useNavigate } from "react-router-dom"
import api from "../api/axiosClient"

const PLATAFORMAS = ["Facebook","Instagram","TikTok","Pinterest","Correo","Genius","LinkedIn","WhatsApp","Telegram","Canva","Twitter","Twitch","YouTube"]

export default function QuizGeneral() {
  const nav = useNavigate()
  const [form, setForm] = useState({ isntitucion: "", genero: "", grado: "", r_edad: "" })
  const [nivelEducativo, setNivelEducativo] = useState("")
  const [plataformas, setPlataformas] = useState([])
  const [instituciones, setInstituciones] = useState([])
  const [error, setError] = useState("")
  const [loading, setLoading] = useState(false)
  const [done, setDone] = useState(false)

  const BASIC_GRADES = [
    "Grado 1°","Grado 2°","Grado 3°","Grado 4°","Grado 5°",
    "Grado 6°","Grado 7°","Grado 8°","Grado 9°",
    "Grado 10°","Grado 11°",
  ]
  const HIGHER_SEMESTERS = [
    "Semestre 1","Semestre 2","Semestre 3","Semestre 4","Semestre 5",
    "Semestre 6","Semestre 7","Semestre 8","Semestre 9","Semestre 10",
  ]

  useEffect(() => {
    api.get("/quiz/status").then(r => { if (r.data.has_general) setDone(true) })
    api.get("/admin/institutions").then(r => setInstituciones(r.data)).catch(() => {})
  }, [])

  const togglePlat = p => setPlataformas(prev => prev.includes(p) ? prev.filter(x => x !== p) : [...prev, p])

  const handleSubmit = async e => {
    e.preventDefault()
    if (plataformas.length === 0) { setError("Selecciona al menos una plataforma"); return }
    setLoading(true)
    try {
      await api.post("/quiz/general", { ...form, plataformas })
      nav("/quiz/estilos")
    } catch (err) {
      setError(err.response?.data?.detail || "Error al guardar")
    } finally { setLoading(false) }
  }

  if (done) return (
    <div className="card max-w-lg mx-auto text-center space-y-3">
      <div className="text-5xl">✅</div>
      <h2 className="text-xl font-bold text-success">Ya completaste este cuestionario</h2>
      <button className="btn-primary" onClick={() => nav("/quiz/estilos")}>Ir a Estilos de Aprendizaje →</button>
    </div>
  )

  return (
    <div className="max-w-2xl mx-auto space-y-6">
      <h2 className="text-2xl font-bold text-dark">📋 Datos Generales</h2>
      {error && <div className="bg-red-100 text-red-700 rounded px-4 py-2 text-sm">{error}</div>}
      <form onSubmit={handleSubmit} className="card space-y-5">
        <div>
          <label className="label-text">Institución</label>
          <select className="input-field" value={form.isntitucion} onChange={e => setForm({...form, isntitucion: e.target.value})} required>
            <option value="">-- Selecciona --</option>
            {instituciones.map(i => <option key={i.id} value={i.name}>{i.name}</option>)}
            <option value="Otra">Otra</option>
          </select>
        </div>
        <div>
          <label className="label-text">Género de nacimiento</label>
          <div className="flex gap-4 mt-1">
            {["Masculino","Femenino"].map(g => (
              <label key={g} className="flex items-center gap-2 cursor-pointer text-sm">
                <input type="radio" name="genero" value={g} checked={form.genero===g}
                  onChange={() => setForm({...form, genero: g})} required />
                {g}
              </label>
            ))}
          </div>
        </div>
        <div>
          <label className="label-text">Nivel educativo</label>
          <select
            className="input-field"
            value={nivelEducativo}
            onChange={e => {
              setNivelEducativo(e.target.value)
              setForm({ ...form, grado: "" })
            }}
            required
          >
            <option value="">-- Selecciona --</option>
            <option value="basica">Educación Básica/Bachillerato</option>
            <option value="superior">Educación Superior</option>
          </select>
        </div>
        {nivelEducativo === "basica" && (
          <div>
            <label className="label-text">Grado</label>
            <select
              className="input-field"
              value={form.grado}
              onChange={e => setForm({ ...form, grado: e.target.value })}
              required
            >
              <option value="">-- Selecciona --</option>
              {BASIC_GRADES.map(g => <option key={g} value={g}>{g}</option>)}
            </select>
          </div>
        )}
        {nivelEducativo === "superior" && (
          <div>
            <label className="label-text">Semestre</label>
            <select
              className="input-field"
              value={form.grado}
              onChange={e => setForm({ ...form, grado: e.target.value })}
              required
            >
              <option value="">-- Selecciona --</option>
              {HIGHER_SEMESTERS.map(s => <option key={s} value={s}>{s}</option>)}
            </select>
          </div>
        )}
        <div>
          <label className="label-text">Rango de edad</label>
          <select className="input-field" value={form.r_edad} onChange={e => setForm({...form, r_edad: e.target.value})} required>
            <option value="">-- Selecciona --</option>
            <option value="6-9">6 - 9 años</option>
            <option value="10-13">10 - 13 años</option>
            <option value="14-17">14 - 17 años</option>
            <option value="18 o más">18 o más</option>
          </select>
        </div>
        <div>
          <label className="label-text">Plataformas digitales que usas (mínimo 1)</label>
          <div className="grid grid-cols-3 gap-2 mt-2">
            {PLATAFORMAS.map(p => (
              <label key={p} className={`flex items-center gap-2 cursor-pointer text-sm border rounded px-2 py-1 transition ${plataformas.includes(p) ? "bg-primary text-white border-primary" : "hover:bg-gray-50"}`}>
                <input type="checkbox" className="hidden" checked={plataformas.includes(p)} onChange={() => togglePlat(p)} />
                {p}
              </label>
            ))}
          </div>
        </div>
        <button className="btn-success w-full" disabled={loading}>
          {loading ? "Guardando..." : "Guardar y continuar →"}
        </button>
      </form>
    </div>
  )
}
