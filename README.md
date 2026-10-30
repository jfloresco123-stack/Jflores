# Jflores@ · Web de Servicio Técnico

Web informativa de **Jflores@**: reparación de celulares, laptops, PC, TV, impresoras e impresoras 3D, con microsoldadura.

## Inicio rápido (Visual Studio Code)

1. Abre esta carpeta en VS Code.
2. Pulsa **Ctrl+Shift+B**.
3. Abre <http://localhost:8000>.

Para probar la consulta de documentos: orden `JF-2026-0001`, dígitos `0651`.

## Versión publicada en GitHub Pages

Cada `git push` a `main` genera una versión estática (sin PHP) con `scripts/build-estatico.php` y la publica en <https://jfloresco123-stack.github.io/Jflores/>. En esa versión, el formulario de contacto y la consulta de órdenes se envían por WhatsApp. Para generarla en local: `tools\php\php.exe -c tools\php\php.ini scripts\build-estatico.php` (sale en `site/`).

## Documentación

- [docs/README.md](docs/README.md): estructura, edición, compilación y cómo subir la web a la nube.
- [docs/PROCESO-SERVICIO.md](docs/PROCESO-SERVICIO.md): flujo de trabajo del taller.
- [docs/plantillas/](docs/plantillas/): orden de servicio, informe técnico y garantía.
- [docs/SEGURIDAD.md](docs/SEGURIDAD.md): protecciones técnicas de la web (formularios, cabeceras).
- [public/assets/img/marcas/README.md](public/assets/img/marcas/README.md): logos de marcas.
