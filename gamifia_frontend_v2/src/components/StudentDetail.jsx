import { Bar, Radar } from "react-chartjs-2"
import { Chart as ChartJS, CategoryScale, LinearScale, BarElement, RadialLinearScale, PointElement, LineElement, Filler, Tooltip, Legend } from "chart.js"

ChartJS.register(CategoryScale, LinearScale, BarElement, RadialLinearScale, PointElement, LineElement, Filler, Tooltip, Legend)

export default function StudentDetail({ open, onClose, data, loading }) {
  if (!open) return null

  const general = data?.general
  const learnStyles = data?.learn_styles
  const typePlayers = data?.type_players
  const student = data?.student

  const formatPercent = value => (value === null || value === undefined ? "—" : `${value}%`)

  const lsChart = learnStyles ? {
    labels: ["Procesamiento", "Percepción", "Entrada", "Comprensión"],
    datasets: [{
      label: "Intensidad (0-11)",
      data: [learnStyles.processes_val, learnStyles.perception_val, learnStyles.input_val, learnStyles.understand_val],
      backgroundColor: ["#1cc88a", "#4e73df", "#36b9cc", "#f6c23e"],
    }]
  } : null

  const tpKeys = ["philanthrop", "socialiser", "free_spirit", "achiever", "disruptor", "player"]
  const tpLabels = ["Filántropo", "Socializador", "Espíritu Libre", "Triunfador", "Disruptor", "Jugador"]
  const tpChart = typePlayers ? {
    labels: tpLabels,
    datasets: [{
      label: "% de perfil",
      data: tpKeys.map(k => typePlayers[k] || 0),
      backgroundColor: "rgba(78,115,223,0.2)",
      borderColor: "#4e73df",
      pointBackgroundColor: "#4e73df",
    }]
  } : null

  return (
    <div className="fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4">
      <div className="bg-white rounded-xl shadow-xl w-full max-w-3xl">
        <div className="flex items-center justify-between border-b px-6 py-4">
          <div>
            <h3 className="text-lg font-bold text-dark">Perfil del estudiante</h3>
            <p className="text-xs text-gray-500">Resumen visual de resultados y datos personales</p>
          </div>
          <button onClick={onClose} className="text-gray-500 hover:text-dark">✕</button>
        </div>

        <div className="p-6 space-y-6">
          {loading && <div className="text-center text-primary font-semibold">Cargando datos...</div>}

          {!loading && !data && (
            <div className="text-center text-red-600 font-semibold">No se pudieron cargar los datos.</div>
          )}

          {!loading && data && (
            <>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="card">
                  <h4 className="font-bold text-dark mb-2">Información básica</h4>
                  <div className="flex items-center gap-3">
                    <div className="text-3xl">👤</div>
                    <div>
                      <p className="text-sm text-gray-600">Nombre</p>
                      <p className="font-semibold text-dark">{student?.full_name || student?.username || "—"}</p>
                      <p className="text-xs text-gray-500">{student?.mail || "Sin correo"}</p>
                    </div>
                  </div>
                </div>
                <div className="card">
                  <h4 className="font-bold text-dark mb-2">Cuestionario general</h4>
                  {general ? (
                    <div className="grid grid-cols-2 gap-2 text-sm text-gray-600">
                      <p>Institución: <span className="font-semibold text-dark">{general.isntitucion || "—"}</span></p>
                      <p>Género: <span className="font-semibold text-dark">{general.genero || "—"}</span></p>
                      <p>Grado: <span className="font-semibold text-dark">{general.grado || "—"}</span></p>
                      <p>Rango de edad: <span className="font-semibold text-dark">{general.r_edad || "—"}</span></p>
                      <p className="col-span-2">Plataformas: <span className="font-semibold text-dark">{general.plataformas?.length ? general.plataformas.join(", ") : "—"}</span></p>
                    </div>
                  ) : (
                    <p className="text-sm text-gray-500">Sin datos registrados.</p>
                  )}
                </div>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="card">
                  <h4 className="font-bold text-dark mb-2">Estilos de aprendizaje</h4>
                  {learnStyles ? (
                    <div className="space-y-3">
                      <div className="grid grid-cols-2 gap-2 text-sm text-gray-600">
                        <p>Percepción: <span className="font-semibold text-dark">{learnStyles.perception} ({learnStyles.perception_val})</span></p>
                        <p>Entrada: <span className="font-semibold text-dark">{learnStyles.input} ({learnStyles.input_val})</span></p>
                        <p>Procesamiento: <span className="font-semibold text-dark">{learnStyles.processes} ({learnStyles.processes_val})</span></p>
                        <p>Comprensión: <span className="font-semibold text-dark">{learnStyles.understand} ({learnStyles.understand_val})</span></p>
                      </div>
                      {lsChart && (
                        <Bar data={lsChart} options={{ responsive:true, plugins:{ legend:{ display:false } }, scales:{ y:{ min:0, max:11 } } }} />
                      )}
                    </div>
                  ) : (
                    <p className="text-sm text-gray-500">Sin resultados registrados.</p>
                  )}
                </div>

                <div className="card">
                  <h4 className="font-bold text-dark mb-2">Tipo de jugador (Hexad)</h4>
                  {typePlayers ? (
                    <div className="space-y-3">
                      <div className="grid grid-cols-2 gap-2 text-sm text-gray-600">
                        <p>Filántropo: <span className="font-semibold text-dark">{formatPercent(typePlayers.philanthrop)}</span></p>
                        <p>Socializador: <span className="font-semibold text-dark">{formatPercent(typePlayers.socialiser)}</span></p>
                        <p>Espíritu Libre: <span className="font-semibold text-dark">{formatPercent(typePlayers.free_spirit)}</span></p>
                        <p>Triunfador: <span className="font-semibold text-dark">{formatPercent(typePlayers.achiever)}</span></p>
                        <p>Jugador: <span className="font-semibold text-dark">{formatPercent(typePlayers.player)}</span></p>
                        <p>Disruptor: <span className="font-semibold text-dark">{formatPercent(typePlayers.disruptor)}</span></p>
                      </div>
                      {tpChart && (
                        <Radar data={tpChart} options={{ responsive:true, scales:{ r:{ min:0 } } }} />
                      )}
                    </div>
                  ) : (
                    <p className="text-sm text-gray-500">Sin resultados registrados.</p>
                  )}
                </div>
              </div>
            </>
          )}
        </div>
      </div>
    </div>
  )
}
