/** @type {import('tailwindcss').Config} */
export default {
  content: ["./index.html","./src/**/*.{js,jsx}"],
  theme: {
    extend: {
      colors: {
        primary: "#4e73df",
        success: "#1cc88a",
        info:    "#36b9cc",
        warning: "#f6c23e",
        danger:  "#e74a3b",
        dark:    "#5a5c69",
        surface: "#f8f9fc",
      }
    }
  },
  plugins: []
}
