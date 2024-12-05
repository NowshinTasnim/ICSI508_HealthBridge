/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
    ],
    theme: {
      extend: {
        colors: {
          purple: {
            800: '#4B0082',
            900: '#3B0764',
          }
        }
      },
    },
    plugins: [],
  }
