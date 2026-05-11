import { useState } from "react"
import { useNavigate, Link } from "react-router-dom"
import { useAuth } from "../context/AuthContext"

export default function Register() {
  const { register } = useAuth()
  const nav = useNavigate()
  const [form, setForm] = useState({ username: "", mail: "", password: "", confirm: "" })
  const [error, setError] = useState("")
  const [loading, setLoading] = useState(false)

  const handleSubmit = async e => {
    e.preventDefault()
    setError("")
    if (form.password !== form.confirm) { setError("Las contraseñas no coinciden"); return }
    setLoading(true)
    try {
      await register(form.username, form.mail, form.password)
      nav("/dashboard")
    } catch (err) {
      setError(err.response?.data?.detail || "Error al registrar")
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-surface">
      <div className="card w-full max-w-md">
        <h2 className="text-2xl font-bold text-primary mb-6 text-center">Crear cuenta</h2>
        {error && <div className="bg-red-100 text-red-700 rounded px-4 py-2 mb-4 text-sm">{error}</div>}
        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="label-text">Usuario</label>
            <input className="input-field" value={form.username}
              onChange={e => setForm({...form, username: e.target.value})} required />
          </div>
          <div>
            <label className="label-text">Correo (opcional)</label>
            <input type="email" className="input-field" value={form.mail}
              onChange={e => setForm({...form, mail: e.target.value})} />
          </div>
          <div>
            <label className="label-text">Contraseña</label>
            <input type="password" className="input-field" value={form.password}
              onChange={e => setForm({...form, password: e.target.value})} required />
          </div>
          <div>
            <label className="label-text">Confirmar contraseña</label>
            <input type="password" className="input-field" value={form.confirm}
              onChange={e => setForm({...form, confirm: e.target.value})} required />
          </div>
          <button className="btn-success w-full" disabled={loading}>
            {loading ? "Registrando..." : "Registrarme"}
          </button>
        </form>
        <p className="text-center text-sm mt-4 text-gray-500">
          ¿Ya tienes cuenta? <Link to="/login" className="text-primary font-semibold">Inicia sesión</Link>
        </p>
      </div>
    </div>
  )
}
