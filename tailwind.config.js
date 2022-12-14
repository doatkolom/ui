/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './src/**',
    './assets/js/doatkolom-ui.js',
  ],
  theme: {
    extend: {
      colors: {
        'primary': '#4f46e5'
      },
      fontFamily: {
        'primary': ['Poppins', 'Oswald']
      },
    },
  },
 
  plugins: [],
}
