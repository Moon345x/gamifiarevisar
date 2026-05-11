import { Outlet, NavLink, useNavigate } from "react-router-dom"
import { useAuth } from "../context/AuthContext"

export default function Layout() {
  const { user, logout } = useAuth()
  const nav = useNavigate()

  const handleLogout = () => { logout(); nav("/login") }

  const linkClass = ({ isActive }) =>
    `flex items-center gap-2 px-4 py-2 rounded transition text-sm font-semibold ` +
    (isActive ? "bg-primary text-white" : "text-dark hover:bg-gray-100")

  return (
    <div className="flex min-h-screen">
      {/* Sidebar */}
      <aside className="w-64 bg-white shadow-lg flex flex-col">
        <div className="p-6 border-b">
          <h1 className="text-xl font-bold text-primary">🎮 GamifyTest</h1>
          <p className="text-xs text-gray-500 mt-1">Bienvenido, {user?.username}</p>
        </div>
        <nav className="flex-1 p-4 space-y-1">
          <NavLink to="/dashboard" className={linkClass}>🏠 Inicio</NavLink>
          <NavLink to="/quiz/general" className={linkClass}>📋 Datos Generales</NavLink>
          <NavLink to="/quiz/estilos" className={linkClass}>🧠 Estilos de Aprendizaje</NavLink>
          <NavLink to="/quiz/jugador" className={linkClass}>🎯 Tipo de Jugador</NavLink>
          <NavLink to="/resultados" className={linkClass}>📊 Mis Resultados</NavLink>
          {user?.type_user === 1 && (
            <NavLink to="/admin" className={linkClass}>⚙️ Panel Admin</NavLink>
          )}
        </nav>
        <div className="p-4 border-t">
          <button onClick={handleLogout} className="btn-danger w-full text-sm">
            🚪 Cerrar sesión
          </button>
        </div>
      </aside>

      {/* Main */}
      <main className="flex-1 p-8 bg-surface overflow-auto">
        <Outlet />
      </main>
    </div>
  )
}
