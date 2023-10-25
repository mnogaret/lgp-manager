/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    colors: {
//      highlight: '#FF4343'
    },
    extend: {},
  },
  plugins: [
    require('flowbite/plugin')
  ],
}

