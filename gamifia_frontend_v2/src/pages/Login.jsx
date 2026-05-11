import { useState } from "react"
import { useNavigate, Link } from "react-router-dom"
import { useAuth } from "../context/AuthContext"

export default function Login() {
  const { login } = useAuth()
  const nav = useNavigate()
  const [form, setForm] = useState({ username: "", password: "" })
  const [error, setError] = useState("")
  const [loading, setLoading] = useState(false)

  const handleSubmit = async e => {
    e.preventDefault()
    setError("")
    setLoading(true)
    try {
      const data = await login(form.username, form.password)
      nav(data.type_user === 1 ? "/admin" : "/dashboard")
    } catch {
      setError("Usuario o contraseña incorrectos")
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-surface">
      <div className="card w-full max-w-md">
        <h2 className="text-2xl font-bold text-primary mb-2 text-center">🎮 GamifyTest</h2>
        <p className="text-center text-gray-500 text-sm mb-6">Plataforma de evaluación gamificada</p>
        {error && <div className="bg-red-100 text-red-700 rounded px-4 py-2 mb-4 text-sm">{error}</div>}
        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="label-text">Usuario</label>
            <input className="input-field" value={form.username}
              onChange={e => setForm({...form, username: e.target.value})} required />
          </div>
          <div>
            <label className="label-text">Contraseña</label>
            <input type="password" className="input-field" value={form.password}
              onChange={e => setForm({...form, password: e.target.value})} required />
          </div>
          <button className="btn-primary w-full" disabled={loading}>
            {loading ? "Ingresando..." : "Ingresar"}
          </button>
        </form>
        <div className="text-center text-sm mt-4 text-gray-500 space-y-2">
          <p>
            ¿No tienes cuenta? <Link to="/register" className="text-primary font-semibold">Regístrate</Link>
          </p>
          <p>
            <Link to="/forgot-password" className="text-primary font-semibold">¿Olvidaste tu contraseña?</Link>
          </p>
        </div>
      </div>
    </div>
  )
}
