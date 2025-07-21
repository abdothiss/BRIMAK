/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.php",
    "./assets/js/**/*.js"
  ],
  theme: {
    extend: {
      // THIS IS THE CRITICAL ADDITION
      colors: {
        'brick-red': '#B22222',
        'success-green': '#28A745',
        'paused-yellow': '#FBBF24',
        'danger-red': '#DC2626',
      },
    },
  },
  plugins: [],
}