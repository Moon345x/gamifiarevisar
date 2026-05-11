import { Navigate } from "react-router-dom"
import { useAuth } from "../context/AuthContext"

export default function AdminRoute({ children }) {
  const { user } = useAuth()
  return user?.type_user === 1 ? children : <Navigate to="/dashboard" replace />
}
