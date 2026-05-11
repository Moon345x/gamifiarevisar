import { Routes, Route, Navigate } from "react-router-dom"
import { AuthProvider } from "./context/AuthContext"
import ProtectedRoute from "./components/ProtectedRoute"
import AdminRoute from "./components/AdminRoute"

import Login from "./pages/Login"
import Register from "./pages/Register"
import ForgotPassword from "./pages/ForgotPassword"
import Dashboard from "./pages/Dashboard"
import QuizGeneral from "./pages/QuizGeneral"
import QuizLearnStyles from "./pages/QuizLearnStyles"
import QuizTypePlayers from "./pages/QuizTypePlayers"
import Results from "./pages/Results"
import AdminPanel from "./pages/AdminPanel"
import Layout from "./components/Layout"

export default function App() {
  return (
    <AuthProvider>
      <Routes>
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />
        <Route path="/forgot-password" element={<ForgotPassword />} />
        <Route path="/" element={<ProtectedRoute><Layout /></ProtectedRoute>}>
          <Route index element={<Navigate to="/dashboard" replace />} />
          <Route path="dashboard" element={<Dashboard />} />
          <Route path="quiz/general" element={<QuizGeneral />} />
          <Route path="quiz/estilos" element={<QuizLearnStyles />} />
          <Route path="quiz/jugador" element={<QuizTypePlayers />} />
          <Route path="resultados" element={<Results />} />
          <Route path="admin" element={<AdminRoute><AdminPanel /></AdminRoute>} />
        </Route>
        <Route path="*" element={<Navigate to="/dashboard" replace />} />
      </Routes>
    </AuthProvider>
  )
}
