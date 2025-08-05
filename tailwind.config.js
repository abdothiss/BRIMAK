/** @type {import('tailwindcss').Config} */
module.exports = {
  // ** THIS IS THE CRITICAL FIX **
  // We are telling Tailwind to enable dark mode based on the 'dark' class in the HTML.
  darkMode: 'class',

  content: [
    "./**/*.php",
    "./assets/js/**/*.js"
  ],
  safelist: [
    'hidden',
    '-translate-x-full',
    'overflow-hidden'
  ],
  theme: {
    extend: {
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