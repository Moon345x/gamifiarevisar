import { useState } from "react"
import { Link, useNavigate } from "react-router-dom"
import api from "../api/axiosClient"

export default function ForgotPassword() {
  const nav = useNavigate()
  const [step, setStep] = useState(1)
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState("")
  const [info, setInfo] = useState("")
  const [mail, setMail] = useState("")
  const [tokenValue, setTokenValue] = useState("")
  const [newPassword, setNewPassword] = useState("")

  const handleSendToken = async e => {
    e.preventDefault()
    setError("")
    setInfo("")
    setLoading(true)
    try {
      await api.post("/auth/forgot-password", { mail })
      setTokenValue("")
      setInfo("Hemos enviado un token temporal a tu correo.")
      setStep(2)
    } catch (err) {
      setError(err.response?.data?.detail || "No pudimos generar el token")
    } finally {
      setLoading(false)
    }
  }

  const handleResetPassword = async e => {
    e.preventDefault()
    setError("")
    setInfo("")
    setLoading(true)
    try {
      await api.post("/auth/reset-password", {
        mail,
        token: tokenValue,
        new_password: newPassword,
      })
      setInfo("Contraseña actualizada. Redirigiendo al inicio de sesión...")
      setTimeout(() => nav("/login"), 1200)
    } catch (err) {
      setError(err.response?.data?.detail || "No pudimos actualizar la contraseña")
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-surface">
      <div className="card w-full max-w-md">
        <h2 className="text-2xl font-bold text-primary mb-2 text-center">🔐 Recuperar contraseña</h2>
        <p className="text-center text-gray-500 text-sm mb-6">Te ayudamos a recuperar tu acceso</p>
        {error && <div className="bg-red-100 text-red-700 rounded px-4 py-2 mb-4 text-sm">{error}</div>}
        {info && <div className="bg-green-100 text-green-700 rounded px-4 py-2 mb-4 text-sm">{info}</div>}

        {step === 1 && (
          <form onSubmit={handleSendToken} className="space-y-4">
            <div>
              <label className="label-text">Correo</label>
              <input
                className="input-field"
                type="email"
                value={mail}
                onChange={e => setMail(e.target.value)}
                required
              />
            </div>
            <button className="btn-primary w-full" disabled={loading}>
              {loading ? "Enviando..." : "Enviar token"}
            </button>
          </form>
        )}

        {step === 2 && (
          <form onSubmit={handleResetPassword} className="space-y-4">
            <div>
              <label className="label-text">Token</label>
              <input
                className="input-field"
                value={tokenValue}
                onChange={e => setTokenValue(e.target.value)}
                required
              />
              <p className="text-xs text-gray-500 mt-1">Revisa tu correo e ingresa el token recibido.</p>
            </div>
            <div>
              <label className="label-text">Nueva contraseña</label>
              <input
                className="input-field"
                type="password"
                value={newPassword}
                onChange={e => setNewPassword(e.target.value)}
                required
              />
            </div>
            <button className="btn-primary w-full" disabled={loading}>
              {loading ? "Actualizando..." : "Actualizar contraseña"}
            </button>
          </form>
        )}

        <p className="text-center text-sm mt-4 text-gray-500">
          ¿Ya recuerdas tu contraseña? <Link to="/login" className="text-primary font-semibold">Volver a iniciar sesión</Link>
        </p>
      </div>
    </div>
  )
}
