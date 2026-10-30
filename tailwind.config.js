/** @type {import('tailwindcss').Config} */
module.exports = {
  // Modo oscuro por clase en <html> (botón claro/oscuro); los colores oscuros están en src/input.css
  darkMode: 'class',
  content: ['./public/**/*.php', './app/**/*.php', './public/assets/js/**/*.js'],
  theme: {
    extend: {
      colors: {
        // Paleta inspirada en altacomdigital.com
        primary: { DEFAULT: '#FF7300', dark: '#E06500', light: '#FFDABA' },
        blue: { brand: '#0454AC', accent: '#1F82F2', soft: '#E6F3FF' },
        ink: { DEFAULT: '#122438', 2: '#324A6D', text: '#333333', muted: '#736F6F' },
        surface: '#F8F8F8',
        logo: '#14603F',
      },
      fontFamily: {
        sans: ['Roboto', 'Segoe UI', 'system-ui', 'sans-serif'],
        display: ['Poppins', 'Segoe UI', 'system-ui', 'sans-serif'],
      },
      boxShadow: {
        card: '0 4px 24px -6px rgba(18,36,56,.12)',
        lift: '0 18px 40px -12px rgba(4,84,172,.28)',
      },
    },
  },
  // El CLI standalone incluye este plugin oficial (no requiere npm).
  plugins: [require('@tailwindcss/forms')],
};
