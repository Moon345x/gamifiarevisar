import { useEffect, useState } from "react"
import { Link } from "react-router-dom"
import { Bar, Radar } from "react-chartjs-2"
import { Chart as ChartJS, CategoryScale, LinearScale, BarElement, RadialLinearScale, PointElement, LineElement, Filler, Tooltip, Legend } from "chart.js"
import api from "../api/axiosClient"

ChartJS.register(CategoryScale, LinearScale, BarElement, RadialLinearScale, PointElement, LineElement, Filler, Tooltip, Legend)

const HEXAD_MAP = { philanthrop:"Filántropo", socialiser:"Socializador", free_spirit:"Espíritu Libre", achiever:"Triunfador", disruptor:"Disruptor", player:"Jugador" }
const HEXAD_EMO = { philanthrop:"🤝", socialiser:"👥", free_spirit:"🦋", achiever:"🏆", disruptor:"⚡", player:"🎮" }
const HEXAD_DESC = {
  philanthrop: "Te motiva ayudar y apoyar a otras personas.",
  socialiser: "Disfrutas pertenecer a una comunidad y colaborar.",
  free_spirit: "Prefieres la autonomía y explorar con creatividad.",
  achiever: "Te impulsa superar desafíos y completar metas.",
  disruptor: "Te gusta cuestionar y generar cambios.",
  player: "Te motivan los logros, puntos y recompensas.",
}

const DIM_LABELS = {
  processes: "Procesamiento",
  perception: "Percepción",
  input: "Entrada",
  understand: "Comprensión",
}

const LS_DESC = {
  ACT: "Aprendes mejor practicando y participando activamente.",
  REF: "Prefieres observar y reflexionar antes de actuar.",
  SENS: "Te enfocas en hechos concretos y ejemplos reales.",
  INTUI: "Te atraen las ideas nuevas, patrones y conceptos.",
  VISUA: "Procesas mejor la información con apoyo visual.",
  VERBA: "Comprendes mejor con explicaciones y texto.",
  SECUE: "Avanzas paso a paso con una secuencia clara.",
  GLOBA: "Comprendes mejor al ver el panorama completo.",
}

export default function Dashboard() {
  const [status, setStatus] = useState(null)
  const [results, setResults] = useState(null)
  const [profile, setProfile] = useState(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    Promise.all([
      api.get("/quiz/status"),
      api.get("/quiz/results"),
      api.get("/users/me"),
    ]).then(([s, r, m]) => {
      setStatus(s.data)
      setResults(r.data)
      setProfile(m.data)
    }).finally(() => setLoading(false))
  }, [])

  if (loading) return <div className="text-center py-20 text-primary font-bold">Cargando dashboard...</div>

  const ls = results?.learn_styles
  const tp = results?.type_players
  const general = results?.general
  const hexadKeys = tp ? Object.keys(HEXAD_MAP) : []
  const dominantHexad = tp ? hexadKeys.reduce((a, b) => tp[a] > tp[b] ? a : b) : null

  const learnStylesChart = ls ? {
    labels: ["Procesamiento", "Percepción", "Entrada", "Comprensión"],
    datasets: [{
      label: "Intensidad (0-11)",
      data: [ls.processes_val, ls.perception_val, ls.input_val, ls.understand_val],
      backgroundColor: ["#1cc88a", "#4e73df", "#36b9cc", "#f6c23e"],
    }]
  } : null

  const hexadChart = tp ? {
    labels: hexadKeys.map(k => HEXAD_MAP[k]),
    datasets: [{
      label: "% de perfil",
      data: hexadKeys.map(k => tp[k] || 0),
      backgroundColor: "rgba(78,115,223,0.2)",
      borderColor: "#4e73df",
      pointBackgroundColor: "#4e73df",
    }]
  } : null

  return (
    <div className="space-y-6">
      <div className="card flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <h2 className="text-2xl font-bold text-dark">👋 Hola, {profile?.full_name || profile?.username}</h2>
          <p className="text-sm text-gray-500">Institución: {general?.isntitucion || profile?.institution || "Sin registrar"}</p>
        </div>
        <div className="flex flex-wrap gap-2">
          <span className={`px-3 py-1 rounded-full text-xs font-semibold ${status?.has_general ? "bg-green-100 text-green-700" : "bg-yellow-100 text-yellow-700"}`}>
            📋 General {status?.has_general ? "Completado" : "Pendiente"}
          </span>
          <span className={`px-3 py-1 rounded-full text-xs font-semibold ${status?.has_learn_styles ? "bg-green-100 text-green-700" : "bg-yellow-100 text-yellow-700"}`}>
            🧠 Estilos {status?.has_learn_styles ? "Completado" : "Pendiente"}
          </span>
          <span className={`px-3 py-1 rounded-full text-xs font-semibold ${status?.has_type_players ? "bg-green-100 text-green-700" : "bg-yellow-100 text-yellow-700"}`}>
            🎯 Hexad {status?.has_type_players ? "Completado" : "Pendiente"}
          </span>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div className="card">
          <h3 className="font-bold text-dark mb-2">Datos Generales</h3>
          {status?.has_general ? (
            <div className="text-sm text-gray-600 space-y-1">
              <p>Género: <span className="font-semibold text-dark">{general?.genero || "—"}</span></p>
              <p>Rango de edad: <span className="font-semibold text-dark">{general?.r_edad || "—"}</span></p>
              <p>Grado/Semestre: <span className="font-semibold text-dark">{general?.grado || "—"}</span></p>
            </div>
          ) : (
            <div className="text-sm text-gray-500 space-y-2">
              <p>Completa el cuestionario para ver tus datos.</p>
              <Link to="/quiz/general" className="btn-primary text-sm">Ir a Datos Generales</Link>
            </div>
          )}
        </div>

        <div className="card">
          <h3 className="font-bold text-dark mb-2">Estilos de Aprendizaje</h3>
          {status?.has_learn_styles && ls ? (
            <div className="space-y-3">
              <div className="grid grid-cols-2 gap-2 text-xs text-gray-600">
                {Object.keys(DIM_LABELS).map(key => (
                  <p key={key}>{DIM_LABELS[key]}: <span className="font-semibold text-dark">{ls[key]} ({ls[`${key}_val`]})</span></p>
                ))}
              </div>
              {learnStylesChart && (
                <Bar data={learnStylesChart} options={{ responsive:true, plugins:{ legend:{ display:false } }, scales:{ y:{ min:0, max:11 } } }} />
              )}
              <div className="grid grid-cols-1 gap-2 text-xs text-gray-600">
                <div className="flex items-start gap-2"><span>🧠</span><span>{LS_DESC[ls.processes] || ""}</span></div>
                <div className="flex items-start gap-2"><span>👀</span><span>{LS_DESC[ls.perception] || ""}</span></div>
                <div className="flex items-start gap-2"><span>🎨</span><span>{LS_DESC[ls.input] || ""}</span></div>
                <div className="flex items-start gap-2"><span>🧭</span><span>{LS_DESC[ls.understand] || ""}</span></div>
              </div>
            </div>
          ) : (
            <div className="text-sm text-gray-500 space-y-2">
              <p>Completa el cuestionario para ver tu perfil.</p>
              <Link to="/quiz/estilos" className="btn-primary text-sm">Ir a Estilos</Link>
            </div>
          )}
        </div>

        <div className="card">
          <h3 className="font-bold text-dark mb-2">Tipo de Jugador (Hexad)</h3>
          {status?.has_type_players && tp ? (
            <div className="space-y-3">
              {dominantHexad && (
                <div className="bg-primary text-white rounded p-3 text-center">
                  <div className="text-2xl">{HEXAD_EMO[dominantHexad]}</div>
                  <p className="font-bold">{HEXAD_MAP[dominantHexad]}</p>
                  <p className="text-xs opacity-80">Perfil dominante</p>
                </div>
              )}
              {hexadChart && (
                <Radar data={hexadChart} options={{ responsive:true, scales:{ r:{ min:0 } } }} />
              )}
              <div className="grid grid-cols-1 gap-2 text-xs text-gray-600">
                {hexadKeys.map(k => (
                  <div key={k} className="flex items-start gap-2">
                    <span>{HEXAD_EMO[k]}</span>
                    <span><strong>{HEXAD_MAP[k]}:</strong> {HEXAD_DESC[k]}</span>
                  </div>
                ))}
              </div>
            </div>
          ) : (
            <div className="text-sm text-gray-500 space-y-2">
              <p>Completa el cuestionario para ver tu perfil.</p>
              <Link to="/quiz/jugador" className="btn-primary text-sm">Ir a Hexad</Link>
            </div>
          )}
        </div>
      </div>
    </div>
  )
}
