import { useEffect, useState } from "react"
import { Bar, Radar, Doughnut } from "react-chartjs-2"
import { Chart as ChartJS, ArcElement, CategoryScale, LinearScale, BarElement, RadialLinearScale, PointElement, LineElement, Filler, Tooltip, Legend } from "chart.js"
import api from "../api/axiosClient"
import StudentDetail from "../components/StudentDetail"

ChartJS.register(ArcElement, CategoryScale, LinearScale, BarElement, RadialLinearScale, PointElement, LineElement, Filler, Tooltip, Legend)

export default function AdminPanel() {
  const [students, setStudents] = useState([])
  const [genero, setGenero] = useState([])
  const [hexad, setHexad] = useState(null)
  const [plataformas, setPlataformas] = useState([])
  const [edadStats, setEdadStats] = useState([])
  const [learnStylesStats, setLearnStylesStats] = useState(null)
  const [loading, setLoading] = useState(true)
  const [tab, setTab] = useState(0)
  const [detailOpen, setDetailOpen] = useState(false)
  const [detailLoading, setDetailLoading] = useState(false)
  const [detailData, setDetailData] = useState(null)
  const [institutionFilter, setInstitutionFilter] = useState("")
  const [institutionsData, setInstitutionsData] = useState([])
  const [newInstitution, setNewInstitution] = useState("")
  const [instLoading, setInstLoading] = useState(false)
  const [createOpen, setCreateOpen] = useState(false)
  const [createForm, setCreateForm] = useState({ full_name: "", mail: "", username: "", password: "", institution: "" })
  const [createLoading, setCreateLoading] = useState(false)
  const [resetOpen, setResetOpen] = useState(false)
  const [resetTarget, setResetTarget] = useState(null)
  const [resetPassword, setResetPassword] = useState("")
  const [resetLoading, setResetLoading] = useState(false)

  const loadStats = selectedInstitution => Promise.all([
    api.get("/admin/stats/genero", { params: selectedInstitution ? { institution: selectedInstitution } : {} }),
    api.get("/admin/stats/hexad", { params: selectedInstitution ? { institution: selectedInstitution } : {} }),
    api.get("/admin/stats/plataformas", { params: selectedInstitution ? { institution: selectedInstitution } : {} }),
    api.get("/admin/stats/edad", { params: selectedInstitution ? { institution: selectedInstitution } : {} }),
    api.get("/admin/stats/learn-styles", { params: selectedInstitution ? { institution: selectedInstitution } : {} }),
  ])

  const loadInstitutions = () => api.get("/admin/institutions").then(r => setInstitutionsData(r.data))
  const loadStudents = () => api.get("/admin/students").then(r => setStudents(r.data))

  useEffect(() => {
    Promise.all([
      loadStudents(),
      loadStats(""),
      loadInstitutions(),
    ]).then(([, [g,h,p,e,ls]]) => {
      setGenero(g.data)
      setHexad(h.data)
      setPlataformas(p.data)
      setEdadStats(e.data)
      setLearnStylesStats(ls.data)
    }).finally(() => setLoading(false))
  }, [])

  useEffect(() => {
    loadStats(institutionFilter).then(([g,h,p,e,ls]) => {
      setGenero(g.data)
      setHexad(h.data)
      setPlataformas(p.data)
      setEdadStats(e.data)
      setLearnStylesStats(ls.data)
    })
  }, [institutionFilter])

  if (loading) return <div className="text-center py-20 text-primary font-bold">Cargando panel admin...</div>

  const TABS = ["📊 Estadísticas","👥 Estudiantes"]

  const institutions = Array.from(
    new Set(students.map(s => s.institution).filter(Boolean))
  ).sort((a, b) => a.localeCompare(b))

  const filteredStudents = institutionFilter
    ? students.filter(s => (s.institution || "") === institutionFilter)
    : students

  const deriveLevel = grade => {
    if (!grade) return ""
    if (grade.startsWith("Grado")) return "Educación Básica/Bachillerato"
    if (grade.startsWith("Semestre")) return "Educación Superior"
    return ""
  }

  const csvEscape = value => {
    const text = value === null || value === undefined ? "" : String(value)
    return /[",\n]/.test(text) ? `"${text.replace(/"/g, '""')}"` : text
  }

  const handleExportCsv = () => {
    const headers = [
      "nombre_completo","username","correo","institucion","genero","rango_edad","nivel_educativo","grado_semestre",
      "learn_perception","learn_perception_val","learn_input","learn_input_val","learn_processes","learn_processes_val","learn_understand","learn_understand_val",
      "hexad_philanthrop","hexad_socialiser","hexad_free_spirit","hexad_achiever","hexad_player","hexad_disruptor",
    ]
    const rows = filteredStudents.map(s => {
      const ls = s.learn_styles || {}
      const tp = s.type_players || {}
      return [
        s.full_name || "",
        s.username || "",
        s.mail || "",
        s.institution || "",
        s.gender || "",
        s.age_range || "",
        deriveLevel(s.grade),
        s.grade || "",
        ls.perception || "",
        ls.perception_val ?? "",
        ls.input || "",
        ls.input_val ?? "",
        ls.processes || "",
        ls.processes_val ?? "",
        ls.understand || "",
        ls.understand_val ?? "",
        tp.philanthrop ?? "",
        tp.socialiser ?? "",
        tp.free_spirit ?? "",
        tp.achiever ?? "",
        tp.player ?? "",
        tp.disruptor ?? "",
      ]
    })
    const csv = [headers, ...rows].map(r => r.map(csvEscape).join(",")).join("\n")
    const blob = new Blob(["\ufeff", csv], { type: "text/csv;charset=utf-8;" })
    const url = URL.createObjectURL(blob)
    const a = document.createElement("a")
    a.href = url
    a.download = `estudiantes_${institutionFilter || "todos"}.csv`
    a.click()
    URL.revokeObjectURL(url)
  }
  const handleAddInstitution = async e => {
    e.preventDefault()
    const name = newInstitution.trim()
    if (!name) return
    setInstLoading(true)
    try {
      await api.post("/admin/institutions", { name })
      setNewInstitution("")
      await loadInstitutions()
    } finally {
      setInstLoading(false)
    }
  }

  const handleDeleteInstitution = async id => {
    setInstLoading(true)
    try {
      await api.delete(`/admin/institutions/${id}`)
      await loadInstitutions()
    } finally {
      setInstLoading(false)
    }
  }

  const handleCreateStudent = async e => {
    e.preventDefault()
    setCreateLoading(true)
    try {
      await api.post("/admin/students", createForm)
      setCreateForm({ full_name: "", mail: "", username: "", password: "", institution: "" })
      setCreateOpen(false)
      await loadStudents()
    } finally {
      setCreateLoading(false)
    }
  }

  const handleDeleteStudent = async student => {
    if (!window.confirm(`¿Eliminar a ${student.username}? Esta acción no se puede deshacer.`)) return
    await api.delete(`/admin/students/${student.id_student}`)
    await loadStudents()
  }

  const openResetModal = student => {
    setResetTarget(student)
    setResetPassword("")
    setResetOpen(true)
  }

  const handleResetPassword = async e => {
    e.preventDefault()
    if (!resetTarget) return
    setResetLoading(true)
    try {
      await api.put(`/admin/students/${resetTarget.id_student}/password`, { new_password: resetPassword })
      setResetOpen(false)
      setResetTarget(null)
      setResetPassword("")
    } finally {
      setResetLoading(false)
    }
  }

  const StatusBadge = ({ done }) => (
    <span className={`px-2 py-1 rounded-full text-xs font-semibold ${done ? "bg-green-100 text-green-700" : "bg-yellow-100 text-yellow-700"}`}>
      {done ? "✅ Completado" : "⏳ Pendiente"}
    </span>
  )

  const openDetail = async id => {
    setDetailOpen(true)
    setDetailLoading(true)
    setDetailData(null)
    try {
      const { data } = await api.get(`/admin/students/${id}`)
      setDetailData(data)
    } catch {
      setDetailData(null)
    } finally {
      setDetailLoading(false)
    }
  }

  const genderChart = {
    labels: genero.map(g => g.genero || "Sin datos"),
    datasets: [{ data: genero.map(g => g.count), backgroundColor: ["#4e73df","#e74a3b","#1cc88a"] }]
  }

  const edadChart = {
    labels: edadStats.map(e => e.rango || "Sin datos"),
    datasets: [{ label: "Usuarios", data: edadStats.map(e => e.count), backgroundColor: "#36b9cc" }]
  }

  const gradeCounts = filteredStudents.reduce((acc, s) => {
    const key = s.grade || "Sin grado"
    acc[key] = (acc[key] || 0) + 1
    return acc
  }, {})
  const gradeLabels = Object.keys(gradeCounts)
  const gradeChart = {
    labels: gradeLabels,
    datasets: [{ label: "Usuarios", data: gradeLabels.map(k => gradeCounts[k]), backgroundColor: "#1cc88a" }]
  }

  const hexadKeys = ["philanthrop","socialiser","free_spirit","achiever","disruptor","player"]
  const hexadLabels = ["Filántropo","Socializador","Espíritu Libre","Triunfador","Disruptor","Jugador"]
  const hexadChart = hexad ? {
    labels: hexadLabels,
    datasets: [{ label: "Promedio %", data: hexadKeys.map(k => hexad[k] || 0),
      backgroundColor: "rgba(78,115,223,0.2)", borderColor: "#4e73df", pointBackgroundColor: "#4e73df" }]
  } : null

  const learnStylesChart = learnStylesStats ? {
    labels: ["Procesamiento", "Percepción", "Entrada", "Comprensión"],
    datasets: [{
      label: "Promedio (0-11)",
      data: [
        learnStylesStats.avg_processes_val || 0,
        learnStylesStats.avg_perception_val || 0,
        learnStylesStats.avg_input_val || 0,
        learnStylesStats.avg_understand_val || 0,
      ],
      backgroundColor: ["#1cc88a", "#4e73df", "#36b9cc", "#f6c23e"],
    }]
  } : null

  const platChart = {
    labels: plataformas.slice(0,8).map(p => p.plataforma),
    datasets: [{ label: "Usuarios", data: plataformas.slice(0,8).map(p => p.count), backgroundColor: "#36b9cc" }]
  }

  return (
    <div className="space-y-6">
      <h2 className="text-2xl font-bold text-dark">⚙️ Panel Administrador</h2>
      <div className="flex gap-2 border-b">
        {TABS.map((t,i) => (
          <button key={i} onClick={() => setTab(i)}
            className={`px-4 py-2 text-sm font-semibold rounded-t transition ${tab===i?"bg-primary text-white":"text-dark hover:bg-gray-100"}`}>
            {t}
          </button>
        ))}
      </div>

      {tab === 0 && (
        <div className="space-y-6">
          <div className="card flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
              <h4 className="font-bold text-dark">Filtro por institución</h4>
              <p className="text-xs text-gray-500">Las métricas se recalculan con la institución seleccionada.</p>
            </div>
            <div className="flex items-center gap-2">
              <label className="text-sm font-semibold text-dark">Institución</label>
              <select
                className="input-field min-w-48"
                value={institutionFilter}
                onChange={e => setInstitutionFilter(e.target.value)}
              >
                <option value="">Todos</option>
                {institutions.map(inst => (
                  <option key={inst} value={inst}>{inst}</option>
                ))}
              </select>
            </div>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {/* Género */}
            <div className="card">
              <h4 className="font-bold text-dark mb-4">Distribución por Género</h4>
              <div className="max-w-xs mx-auto"><Doughnut data={genderChart} /></div>
            </div>
            {/* Rango de edad */}
            <div className="card">
              <h4 className="font-bold text-dark mb-4">Distribución por Edad</h4>
              <Bar data={edadChart} options={{ responsive:true, plugins:{ legend:{ display:false } } }} />
            </div>
            {/* Hexad promedio */}
            {hexadChart && (
              <div className="card">
                <h4 className="font-bold text-dark mb-4">Promedio Perfiles Hexad</h4>
                <Radar data={hexadChart} options={{ responsive:true, scales:{ r:{ min:0 } } }} />
              </div>
            )}
            {/* Estilos de aprendizaje */}
            {learnStylesChart && (
              <div className="card">
                <h4 className="font-bold text-dark mb-4">Promedio Estilos de Aprendizaje</h4>
                <Bar data={learnStylesChart} options={{ responsive:true, plugins:{ legend:{ display:false } }, scales:{ y:{ min:0, max:11 } } }} />
              </div>
            )}
          </div>
          {/* Plataformas */}
          <div className="card">
            <h4 className="font-bold text-dark mb-4">Uso de Plataformas Digitales</h4>
            <Bar data={platChart} options={{ responsive:true, plugins:{ legend:{ display:false } } }} />
          </div>

          <div className="card">
            <h4 className="font-bold text-dark mb-4">Distribución por Grado / Semestre</h4>
            {gradeLabels.length ? (
              <Bar data={gradeChart} options={{ responsive:true, plugins:{ legend:{ display:false } } }} />
            ) : (
              <p className="text-sm text-gray-500">Sin datos para mostrar.</p>
            )}
          </div>

          <div className="card space-y-4">
            <div>
              <h4 className="font-bold text-dark">Gestión de instituciones</h4>
              <p className="text-xs text-gray-500">Agrega o elimina instituciones disponibles para el registro.</p>
            </div>
            <form onSubmit={handleAddInstitution} className="flex flex-col md:flex-row gap-3">
              <input
                className="input-field flex-1"
                placeholder="Nueva institución"
                value={newInstitution}
                onChange={e => setNewInstitution(e.target.value)}
              />
              <button className="btn-primary" disabled={instLoading}>
                {instLoading ? "Guardando..." : "Agregar"}
              </button>
            </form>
            <div className="overflow-x-auto">
              <table className="w-full text-sm">
                <thead>
                  <tr className="bg-gray-50 text-left">
                    <th className="px-3 py-2 font-semibold text-dark">ID</th>
                    <th className="px-3 py-2 font-semibold text-dark">Institución</th>
                    <th className="px-3 py-2 font-semibold text-dark">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  {institutionsData.length === 0 && (
                    <tr>
                      <td colSpan="3" className="px-3 py-3 text-center text-gray-500">Sin instituciones registradas.</td>
                    </tr>
                  )}
                  {institutionsData.map(inst => (
                    <tr key={inst.id} className="border-t">
                      <td className="px-3 py-2">{inst.id}</td>
                      <td className="px-3 py-2 font-medium">{inst.name}</td>
                      <td className="px-3 py-2">
                        <button
                          type="button"
                          onClick={() => handleDeleteInstitution(inst.id)}
                          className="btn-danger text-xs"
                          disabled={instLoading}
                        >
                          Eliminar
                        </button>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      )}

      {tab === 1 && (
        <div className="space-y-4">
          <div className="card flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
              <h4 className="font-bold text-dark">Lista de Estudiantes ({filteredStudents.length})</h4>
              <p className="text-xs text-gray-500">Filtra por institución para ver el progreso por grupo.</p>
            </div>
            <div className="flex flex-wrap items-center gap-2">
              <button type="button" className="btn-primary" onClick={() => setCreateOpen(true)}>
                Crear estudiante
              </button>
              <button type="button" className="btn-success" onClick={handleExportCsv}>
                Exportar CSV
              </button>
              <label className="text-sm font-semibold text-dark">Institución</label>
              <select
                className="input-field min-w-48"
                value={institutionFilter}
                onChange={e => setInstitutionFilter(e.target.value)}
              >
                <option value="">Todas</option>
                {institutions.map(inst => (
                  <option key={inst} value={inst}>{inst}</option>
                ))}
              </select>
            </div>
          </div>

          {filteredStudents.length === 0 ? (
            <div className="card text-center text-gray-500">No hay estudiantes para esta institución.</div>
          ) : (
            <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
              {filteredStudents.map(s => {
                const completed = [s.has_general, s.has_learn_styles, s.has_type_players].filter(Boolean).length
                const progress = Math.round((completed / 3) * 100)
                return (
                  <div
                    key={s.id_student}
                    onClick={() => openDetail(s.id_student)}
                    className="card text-left hover:shadow-lg transition cursor-pointer"
                  >
                    <div className="flex items-center justify-between">
                      <div>
                        <p className="text-xs text-gray-400">ID {s.id_student}</p>
                        <h5 className="font-bold text-dark text-base">{s.full_name || s.username}</h5>
                        <p className="text-xs text-gray-500">{s.mail || "Sin correo"}</p>
                      </div>
                      <div className="text-2xl">🎯</div>
                    </div>

                    <div className="mt-3">
                      <p className="text-xs text-gray-500">Institución</p>
                      <p className="text-sm font-semibold text-dark">{s.institution || "Sin registrar"}</p>
                    </div>

                    <div className="mt-3 space-y-2">
                      <div className="flex items-center justify-between text-xs text-gray-500">
                        <span>Progreso de exámenes</span>
                        <span>{progress}%</span>
                      </div>
                      <div className="w-full bg-gray-200 rounded-full h-2">
                        <div className="bg-primary h-2 rounded-full" style={{ width: `${progress}%` }}></div>
                      </div>
                      <div className="flex flex-wrap gap-2">
                        <StatusBadge done={s.has_general} />
                        <StatusBadge done={s.has_learn_styles} />
                        <StatusBadge done={s.has_type_players} />
                      </div>
                    </div>
                    <div className="mt-3 flex flex-wrap gap-2">
                      <button
                        type="button"
                        className="btn-primary text-xs"
                        onClick={e => { e.stopPropagation(); openDetail(s.id_student) }}
                      >
                        Ver detalle
                      </button>
                      <button
                        type="button"
                        className="btn-warning text-xs"
                        onClick={e => { e.stopPropagation(); openResetModal(s) }}
                      >
                        Reset contraseña
                      </button>
                      <button
                        type="button"
                        className="btn-danger text-xs"
                        onClick={e => { e.stopPropagation(); handleDeleteStudent(s) }}
                      >
                        Eliminar
                      </button>
                    </div>
                  </div>
                )
              })}
            </div>
          )}
        </div>
      )}

      {createOpen && (
        <div className="fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4">
          <div className="bg-white rounded-xl shadow-xl w-full max-w-lg">
            <div className="flex items-center justify-between border-b px-6 py-4">
              <h3 className="text-lg font-bold text-dark">Crear estudiante</h3>
              <button onClick={() => setCreateOpen(false)} className="text-gray-500 hover:text-dark">✕</button>
            </div>
            <form onSubmit={handleCreateStudent} className="p-6 space-y-4">
              <div>
                <label className="label-text">Nombre completo</label>
                <input className="input-field" value={createForm.full_name}
                  onChange={e => setCreateForm({ ...createForm, full_name: e.target.value })} required />
              </div>
              <div>
                <label className="label-text">Correo</label>
                <input type="email" className="input-field" value={createForm.mail}
                  onChange={e => setCreateForm({ ...createForm, mail: e.target.value })} required />
              </div>
              <div>
                <label className="label-text">Usuario</label>
                <input className="input-field" value={createForm.username}
                  onChange={e => setCreateForm({ ...createForm, username: e.target.value })} required />
              </div>
              <div>
                <label className="label-text">Contraseña</label>
                <input type="password" className="input-field" value={createForm.password}
                  onChange={e => setCreateForm({ ...createForm, password: e.target.value })} required />
              </div>
              <div>
                <label className="label-text">Institución</label>
                <select className="input-field" value={createForm.institution}
                  onChange={e => setCreateForm({ ...createForm, institution: e.target.value })} required>
                  <option value="">-- Selecciona --</option>
                  {institutionsData.map(inst => (
                    <option key={inst.id} value={inst.name}>{inst.name}</option>
                  ))}
                </select>
              </div>
              <button className="btn-primary w-full" disabled={createLoading}>
                {createLoading ? "Creando..." : "Crear estudiante"}
              </button>
            </form>
          </div>
        </div>
      )}

      {resetOpen && (
        <div className="fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4">
          <div className="bg-white rounded-xl shadow-xl w-full max-w-md">
            <div className="flex items-center justify-between border-b px-6 py-4">
              <h3 className="text-lg font-bold text-dark">Resetear contraseña</h3>
              <button onClick={() => setResetOpen(false)} className="text-gray-500 hover:text-dark">✕</button>
            </div>
            <form onSubmit={handleResetPassword} className="p-6 space-y-4">
              <p className="text-sm text-gray-500">Estudiante: <strong>{resetTarget?.full_name || resetTarget?.username}</strong></p>
              <div>
                <label className="label-text">Nueva contraseña</label>
                <input type="password" className="input-field" value={resetPassword}
                  onChange={e => setResetPassword(e.target.value)} required />
              </div>
              <button className="btn-warning w-full" disabled={resetLoading}>
                {resetLoading ? "Actualizando..." : "Actualizar contraseña"}
              </button>
            </form>
          </div>
        </div>
      )}

      <StudentDetail
        open={detailOpen}
        onClose={() => setDetailOpen(false)}
        data={detailData}
        loading={detailLoading}
      />
    </div>
  )
}
