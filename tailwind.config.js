/** @type {import('tailwindcss').Config} */
export default {
    content: [
      './resources/**/*.blade.php',
      './resources/**/*.js',
    ],
    theme: {
      extend: {
        fontFamily: {
          playfair: ['"Playfair Display"', 'serif'],
          inter: ['Inter', 'sans-serif'],
        },
        colors: {
          primary: '#ff3366',
          secondary: '#00152a',
        },
      },
    },
    plugins: [],
  }
  