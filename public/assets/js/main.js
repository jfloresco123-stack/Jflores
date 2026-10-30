// jflores@ – interacciones de la interfaz. Sin dependencias ni JS en línea (compatible con CSP estricto).
(function () {
  'use strict';

  // --- Menú móvil ---
  const toggle = document.querySelector('[data-menu-toggle]');
  const menu = document.getElementById('menu-movil');
  if (toggle && menu) {
    toggle.addEventListener('click', () => {
      const abierto = menu.classList.toggle('hidden') === false;
      toggle.setAttribute('aria-expanded', String(abierto));
      toggle.setAttribute('aria-label', abierto ? 'Cerrar menú' : 'Abrir menú');
    });
    menu.addEventListener('click', (ev) => {
      if (ev.target.closest('a')) {
        menu.classList.add('hidden');
        toggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  // --- Scrollspy del menú principal ---
  const spy = document.querySelector('[data-scrollspy]');
  if (spy && 'IntersectionObserver' in window) {
    const links = new Map();
    spy.querySelectorAll('a[href^="#"]').forEach((a) => links.set(a.getAttribute('href').slice(1), a));
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        links.forEach((a) => a.classList.remove('is-active'));
        const activo = links.get(entry.target.id);
        if (activo) activo.classList.add('is-active');
      });
    }, { rootMargin: '-40% 0px -55% 0px' });
    links.forEach((_, id) => {
      const sec = document.getElementById(id);
      if (sec) observer.observe(sec);
    });
  }

  // --- Validación en cliente (la validación real ocurre en el servidor) ---
  document.querySelectorAll('form[data-validate]').forEach((form) => {
    form.addEventListener('submit', (ev) => {
      let valido = true;
      form.querySelectorAll('input, textarea, select').forEach((campo) => {
        if (campo.closest('.hp') || campo.type === 'hidden') return;
        const ok = campo.checkValidity();
        campo.classList.toggle('border-red-500', !ok);
        campo.setAttribute('aria-invalid', String(!ok));
        if (!ok && valido) {
          campo.focus();
          valido = false;
        }
      });
      if (!valido) {
        ev.preventDefault();
        return;
      }
      const btn = form.querySelector('button[type="submit"]');
      if (btn) {
        btn.disabled = true;
        btn.classList.add('opacity-70');
      }
    });
  });

  // --- Botón claro / oscuro ---
  const raiz = document.documentElement;
  const botonesTema = document.querySelectorAll('[data-theme-toggle]');
  const pintarBotones = () => {
    const oscuro = raiz.classList.contains('dark');
    botonesTema.forEach((b) => {
      b.setAttribute('aria-pressed', String(oscuro));
      b.setAttribute('aria-label', oscuro ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro');
      b.title = oscuro ? 'Modo claro' : 'Modo oscuro';
    });
  };
  botonesTema.forEach((b) => b.addEventListener('click', () => {
    const oscuro = raiz.classList.toggle('dark');
    try { localStorage.setItem('tema', oscuro ? 'oscuro' : 'claro'); } catch (e) { /* sin almacenamiento */ }
    pintarBotones();
  }));
  pintarBotones();

  // --- Botón imprimir en documentos ---
  document.querySelectorAll('[data-print]').forEach((b) => b.addEventListener('click', () => window.print()));
})();
