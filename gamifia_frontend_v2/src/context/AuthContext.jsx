import { createContext, useContext, useState, useEffect } from "react"
import api from "../api/axiosClient"

const AuthContext = createContext(null)

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    const token = localStorage.getItem("token")
    const stored = localStorage.getItem("user")
    if (token && stored) {
      setUser(JSON.parse(stored))
    }
    setLoading(false)
  }, [])

  const login = async (username, password) => {
    const params = new URLSearchParams()
    params.append("username", username)
    params.append("password", password)
    const { data } = await api.post("/auth/login", params, {
      headers: { "Content-Type": "application/x-www-form-urlencoded" }
    })
    localStorage.setItem("token", data.access_token)
    localStorage.setItem("user", JSON.stringify(data))
    setUser(data)
    return data
  }

  const register = async (username, mail, password) => {
    const { data } = await api.post("/auth/register", { username, mail, password })
    localStorage.setItem("token", data.access_token)
    localStorage.setItem("user", JSON.stringify(data))
    setUser(data)
    return data
  }

  const logout = () => {
    localStorage.clear()
    setUser(null)
  }

  return (
    <AuthContext.Provider value={{ user, login, register, logout, loading }}>
      {children}
    </AuthContext.Provider>
  )
}

export const useAuth = () => useContext(AuthContext)
