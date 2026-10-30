// Aplica el tema guardado (o el del sistema) antes de pintar la página para evitar parpadeos.
// Se carga sin "defer" en <head>; archivo externo para cumplir la CSP (sin JS en línea).
(function () {
  var guardado = null;
  try { guardado = localStorage.getItem('tema'); } catch (e) { /* almacenamiento bloqueado */ }
  var oscuro = guardado ? guardado === 'oscuro'
    : window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
  document.documentElement.classList.toggle('dark', oscuro);
})();
