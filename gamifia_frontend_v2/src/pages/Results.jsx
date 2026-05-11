import { useEffect, useState } from "react"
import { useNavigate } from "react-router-dom"
import { Bar, Radar } from "react-chartjs-2"
import { Chart as ChartJS, CategoryScale, LinearScale, BarElement, RadialLinearScale, PointElement, LineElement, Filler, Tooltip, Legend, Title } from "chart.js"
import api from "../api/axiosClient"

ChartJS.register(CategoryScale, LinearScale, BarElement, RadialLinearScale, PointElement, LineElement, Filler, Tooltip, Legend, Title)

const DIM_LABELS = { processes: ["Activo","Reflexivo"], perception: ["Sensitivo","Intuitivo"], input: ["Visual","Verbal"], understand: ["Secuencial","Global"] }
const DIM_CODES  = { ACT:"Activo", REF:"Reflexivo", SENS:"Sensitivo", INTUI:"Intuitivo", VISUA:"Visual", VERBA:"Verbal", SECUE:"Secuencial", GLOBA:"Global" }
const INTENSITY  = v => v <= 4 ? ["equilibrada","text-success"] : v <= 8 ? ["moderada","text-warning"] : ["fuerte","text-danger"]
const HEXAD_MAP  = { philanthrop:"Filántropo", socialiser:"Socializador", free_spirit:"Espíritu Libre", achiever:"Triunfador", disruptor:"Disruptor", player:"Jugador" }
const HEXAD_EMO  = { philanthrop:"🤝", socialiser:"👥", free_spirit:"🦋", achiever:"🏆", disruptor:"⚡", player:"🎮" }
const HEXAD_DESC = {
  philanthrop: "Te motiva ayudar y apoyar a otras personas. Disfrutas compartir conocimiento, orientar y contribuir al bienestar del grupo.",
  socialiser: "Buscas pertenecer a una comunidad. Te energiza colaborar, conversar y construir relaciones dentro del equipo.",
  free_spirit: "Prefieres la autonomia y la creatividad. Te gusta explorar caminos propios y descubrir cosas nuevas.",
  achiever: "Te impulsa superar desafios. Disfrutas metas claras, progreso visible y dominar tareas complejas.",
  disruptor: "Te gusta cuestionar y generar cambio. Disfrutas romper la rutina y proponer nuevas formas de hacer las cosas.",
  player: "Te motivan las recompensas y logros. Disfrutas ganar puntos, premios y reconocimiento por tu esfuerzo.",
}

export default function Results() {
  const nav = useNavigate()
  const [data, setData] = useState(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    api.get("/quiz/results").then(r => setData(r.data)).finally(() => setLoading(false))
  }, [])

  if (loading) return <div className="text-center py-20 text-primary font-bold">Cargando resultados...</div>
  if (!data?.learn_styles && !data?.type_players) return (
    <div className="card max-w-lg mx-auto text-center space-y-4">
      <div className="text-5xl">📋</div>
      <h2 className="text-xl font-bold text-dark">Aún no tienes resultados</h2>
      <p className="text-gray-500 text-sm">Completa los cuestionarios para ver tu perfil.</p>
      <button className="btn-primary" onClick={() => nav("/dashboard")}>Ir al inicio</button>
    </div>
  )

  const ls = data.learn_styles
  const tp = data.type_players

  const barData = ls ? {
    labels: ["Procesos","Percepción","Entrada","Comprensión"],
    datasets: [{
      label: "Intensidad de preferencia (0-11)",
      data: [ls.processes_val, ls.perception_val, ls.input_val, ls.understand_val],
      backgroundColor: [ls.processes_val<=4?"#1cc88a":ls.processes_val<=8?"#f6c23e":"#e74a3b",
                        ls.perception_val<=4?"#1cc88a":ls.perception_val<=8?"#f6c23e":"#e74a3b",
                        ls.input_val<=4?"#1cc88a":ls.input_val<=8?"#f6c23e":"#e74a3b",
                        ls.understand_val<=4?"#1cc88a":ls.understand_val<=8?"#f6c23e":"#e74a3b"],
    }]
  } : null

  const hexadKeys = tp ? Object.keys(HEXAD_MAP) : []
  const radarData = tp ? {
    labels: hexadKeys.map(k => HEXAD_MAP[k]),
    datasets: [{
      label: "% de perfil",
      data: hexadKeys.map(k => tp[k]),
      backgroundColor: "rgba(78,115,223,0.2)",
      borderColor: "#4e73df",
      pointBackgroundColor: "#4e73df",
    }]
  } : null

  const dominantHexad = tp ? hexadKeys.reduce((a,b) => tp[a] > tp[b] ? a : b) : null
  const hexadSorted = tp ? [...hexadKeys].sort((a, b) => tp[b] - tp[a]) : []

  return (
    <div className="space-y-8 max-w-4xl mx-auto">
      <h2 className="text-2xl font-bold text-dark">📊 Mis Resultados</h2>

      {/* Felder-Silverman */}
      {ls && (
        <div className="card space-y-4">
          <h3 className="text-lg font-bold text-primary">🧠 Estilos de Aprendizaje (Felder-Silverman)</h3>
          <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
            {[["processes","Procesamiento"],["perception","Percepción"],["input","Entrada"],["understand","Comprensión"]].map(([key, label]) => {
              const [intLabel, intClass] = INTENSITY(ls[key+"_val"])
              return (
                <div key={key} className="border rounded p-3 text-center">
                  <p className="text-xs text-gray-500">{label}</p>
                  <p className="font-bold text-primary text-lg">{DIM_CODES[ls[key]]}</p>
                  <p className={`text-xs font-semibold ${intClass}`}>{intLabel} ({ls[key+"_val"]})</p>
                </div>
              )
            })}
          </div>
          <Bar data={barData} options={{ responsive:true, plugins:{ legend:{ display:false } }, scales:{ y:{ min:0, max:11 } } }} />
        </div>
      )}

      {/* Hexad */}
      {tp && (
        <div className="card space-y-4">
          <h3 className="text-lg font-bold text-primary">🎯 Perfil de Jugador (Modelo Hexad)</h3>
          {dominantHexad && (
            <div className="bg-primary text-white rounded p-4 text-center">
              <div className="text-3xl">{HEXAD_EMO[dominantHexad]}</div>
              <p className="font-bold text-xl mt-1">Tipo dominante: {HEXAD_MAP[dominantHexad]}</p>
              <p className="text-sm opacity-80">{(tp[dominantHexad]).toFixed(1)}% de tu perfil</p>
            </div>
          )}
          <div className="grid grid-cols-2 md:grid-cols-3 gap-3">
            {hexadKeys.map(k => (
              <div key={k} className="border rounded p-2 text-center">
                <div className="text-xl">{HEXAD_EMO[k]}</div>
                <p className="text-xs font-semibold text-dark">{HEXAD_MAP[k]}</p>
                <p className="text-primary font-bold">{(tp[k]).toFixed(1)}%</p>
                <div className="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                  <div className="bg-primary h-1.5 rounded-full" style={{width:`${tp[k]}%`}}></div>
                </div>
              </div>
            ))}
          </div>
          <div className="max-w-sm mx-auto">
            <Radar data={radarData} options={{ responsive:true, scales:{ r:{ min:0 } } }} />
          </div>
          <div className="space-y-3">
            <h4 className="font-bold text-dark">Explicacion de perfiles</h4>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
              {hexadSorted.map(k => (
                <div key={k} className={`border rounded p-3 ${k === dominantHexad ? "border-primary bg-primary/5" : ""}`}>
                  <div className="flex items-start gap-3">
                    <div className="text-2xl">{HEXAD_EMO[k]}</div>
                    <div>
                      <p className="font-semibold text-dark">{HEXAD_MAP[k]} · {(tp[k]).toFixed(1)}%</p>
                      <p className="text-xs text-gray-600 mt-1">{HEXAD_DESC[k]}</p>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>
      )}
    </div>
  )
}
