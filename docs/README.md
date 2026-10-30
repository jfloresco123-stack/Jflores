# Jflores@: Web de Servicio Técnico y Microsoldadura

Sitio informativo en PHP 8.1+ con Tailwind CSS y SQLite, sin dependencias externas en tiempo de ejecución. Las fuentes, los iconos y las imágenes se sirven desde la propia web.

## Estructura

| Carpeta | Contenido | ¿Se sube al hosting? |
|---|---|---|
| `public/` | Páginas, `.htaccess`, CSS/JS compilados, fuentes e imágenes | Sí, como contenido de `public_html/` |
| `public/assets/img/marcas/` | Logos de marcas por categoría y su índice `marcas.json` (ver su README) | Sí |
| `public/assets/img/trabajos/` | Fotos del trabajo (recortadas de tu flyer) | Sí |
| `app/` | Configuración, base de datos y plantillas PHP | Sí, **fuera** de `public_html/` |
| `src/`, `tailwind.config.js`, `tools/` | Código fuente del CSS, PHP y Tailwind portables | No |
| `.vscode/` | Tareas y ajustes de Visual Studio Code | No |
| `docs/` | Esta documentación y las plantillas | No |
| `img/` | Originales de diseño | No |

## Trabajar con Visual Studio Code

1. Abre la carpeta del proyecto: **Archivo → Abrir carpeta… → `D:\claude\web jflores`**.
2. Instala las extensiones recomendadas cuando VS Code te lo ofrezca (Tailwind CSS IntelliSense, PHP Intelephense, SQLite Viewer).
3. Pulsa **Ctrl+Shift+B** (tarea *Iniciar desarrollo*). Esto arranca a la vez:
   - el servidor PHP en <http://localhost:8000>;
   - Tailwind vigilando cambios: cada vez que guardas un `.php`, el CSS se recompila solo.
4. Para detener las tareas: icono de papelera en el panel Terminal.

Otras tareas en **Terminal → Ejecutar tarea…**: *Tailwind: compilar producción* (CSS minificado antes de publicar).

**Dónde se edita cada cosa:**
- Textos de la portada (servicios, tiempos, planes, preguntas frecuentes): las listas al inicio de `public/index.php`.
- Teléfono, correo, horario y redes: `app/config.php`.
- Colores y fuentes: `tailwind.config.js`. Componentes (botones, tarjetas): `src/input.css`.
- Marcas: `public/assets/img/marcas/` y su `marcas.json`.

## 1. Compilar el CSS

1. El CLI standalone de Tailwind v3.4 (no necesita Node) ya está en `tools/tailwindcss.exe`. Si falta, descárgalo:
   - <https://github.com/tailwindlabs/tailwindcss/releases/download/v3.4.17/tailwindcss-windows-x64.exe>
2. Compila desde la raíz del proyecto:

```powershell
.\tools\tailwindcss.exe -c tailwind.config.js -i src\input.css -o public\assets\css\app.css --minify
# Durante el desarrollo, con recarga automática:
.\tools\tailwindcss.exe -c tailwind.config.js -i src\input.css -o public\assets\css\app.css --watch
```

Vuelve a compilar cada vez que agregues clases nuevas en archivos `.php` o `.js`.

## 2. Probar en local

PHP 8.4 portable ya está en `tools/php/`, con `pdo_sqlite`, `mbstring`, `openssl` y `gd` activados en `tools/php/php.ini`. Arranca el servidor con la tarea de VS Code o con:

```powershell
.\tools\php\php.exe -c tools\php\php.ini -S localhost:8000 -t public
```

El servidor integrado (`cli-server`) no fuerza HTTPS, así que puedes probar en <http://localhost:8000>. La base `app/data/jflores.sqlite` se crea sola la primera vez, con la orden de ejemplo **JF-2026-0001** (dígitos **0651**).

## 3. Subir a la nube (cuando estés listo)

La web funciona en cualquier servicio que ejecute PHP 8.1+. Opciones, de la más simple a la más flexible:

| Opción | Ideal para | Notas |
|---|---|---|
| Hosting compartido con cPanel (Hostinger, Banahosting, etc.) | Empezar barato | Sigue los pasos de abajo. Incluye SSL gratis. |
| VPS (DigitalOcean, Vultr, Lightsail) con Apache o Nginx y PHP-FPM | Más control | Apunta el *document root* a `public/`. En Nginx, replica las reglas del `.htaccess`. |
| Contenedor (Cloud Run, Railway, Render) | Escalar sin administrar servidores | Usa la imagen `php:8.4-apache`. Ojo: SQLite necesita un disco persistente. |

Antes de publicar:
1. Ejecuta la tarea *Tailwind: compilar producción*.
2. Revisa `app/config.php` (redes, horario, ubicación).
3. Borra `app/data/jflores.sqlite` para empezar sin la orden de ejemplo, o cámbiala.
4. No subas `tools/`, `src/`, `docs/`, `img/` ni `.vscode/`.

### Hosting compartido (cPanel)

1. Sube el contenido de `public/` a `public_html/`.
2. Sube la carpeta `app/` al **mismo nivel** que `public_html/` (es decir, `/home/usuario/app`). Así los `require __DIR__ . '/../app/...'` funcionan y la base de datos queda fuera del acceso web.
3. Da permiso de escritura (750) a `app/data/` para el usuario de PHP.
4. Activa AutoSSL o Let's Encrypt en cPanel. El `.htaccess` redirige todo a HTTPS.
5. Selecciona PHP 8.1 o superior con `pdo_sqlite` y `mbstring` activados.
6. Edita `app/config.php`: enlaces reales de Facebook, TikTok y LinkedIn, horario y ubicación.
7. Configura la cuenta de correo del dominio o SMTP para que funcione `mail()`. Los mensajes se guardan siempre en la tabla `mensajes`, aunque el correo falle.

## 4. Lista de verificación de seguridad (después de desplegar)

- [ ] <https://securityheaders.com> → nota A o A+.
- [ ] <https://www.ssllabs.com/ssltest/> → nota A.
- [ ] `https://tu-dominio/app/config.php` y `.../data/jflores.sqlite` → 403 o 404.
- [ ] Un envío del formulario sin token CSRF (por ejemplo con curl) → 403.
- [ ] Seis envíos seguidos → mensaje de límite de intentos.
- [ ] Escribir `<script>alert(1)</script>` en la descripción → se muestra como texto.
- [ ] La consola del navegador no muestra violaciones de CSP.
- [ ] Cambiar o borrar la orden de ejemplo `JF-2026-0001` antes de abrir al público.

## 5. Cargar órdenes reales

Por ahora no hay panel de administración (está previsto para una fase 2). Registra las órdenes y los informes con [DB Browser for SQLite](https://sqlitebrowser.org/) sobre `app/data/jflores.sqlite`, siguiendo las plantillas de `docs/plantillas/`. Los códigos tienen el formato `JF-AAAA-NNNN`.

Más detalles en [SEGURIDAD.md](SEGURIDAD.md) y [PROCESO-SERVICIO.md](PROCESO-SERVICIO.md).
