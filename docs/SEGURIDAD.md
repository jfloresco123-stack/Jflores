# Controles de seguridad implementados

| Pilar | Control | Dónde | Cómo verificar |
|---|---|---|---|
| Cifrado SSL/TLS | Redirección 301 a HTTPS | `public/.htaccess`, `app/bootstrap.php` | `curl -I http://dominio` → `Location: https://…` |
| | HSTS de 1 año | `.htaccess`, `bootstrap.php` | La cabecera `Strict-Transport-Security` aparece en la respuesta |
| | Cookie de sesión `Secure`, `HttpOnly`, `SameSite=Strict` | `bootstrap.php` | DevTools → Application → Cookies |
| Protección de formularios | Token CSRF de 256 bits por sesión, comparado con `hash_equals` | `csrf_token()` / `csrf_check()` | Un POST sin token responde 403 |
| | Sanitización de entrada: `strip_tags`, quitar caracteres de control y recortar longitud | `limpiar()` | Los campos largos se recortan |
| | Validación en servidor: correo, lista blanca del tipo de cliente, teléfono y longitudes | `contacto.php`, `consulta.php` | Errores por campo |
| | Escape de salida contra XSS | `e()` con `htmlspecialchars` | `<script>` se muestra como texto |
| | Consultas PDO preparadas sin emulación | `app/db.php` | Ninguna consulta concatena datos del usuario |
| | Honeypot contra bots | Campo `sitio_web` oculto | Si se rellena, se descarta en silencio |
| | Correo sin inyección de cabeceras | `Reply-To` validado con `FILTER_VALIDATE_EMAIL` | — |
| Hardening del servidor | CSP estricta sin `unsafe-inline` | `.htaccess`, `bootstrap.php` | La consola del navegador no muestra violaciones |
| | `X-Frame-Options: DENY` y `frame-ancestors 'none'` | idem | La web no carga dentro de un iframe |
| | `nosniff`, `Referrer-Policy`, `Permissions-Policy` | idem | securityheaders.com |
| | Listado de directorios desactivado; acceso bloqueado a archivos ocultos, `.sqlite`, `.md` y otros | `.htaccess` | 403 |
| | `app/` fuera del webroot | Despliegue | 404 |
| Fuerza bruta | 5 intentos cada 15 minutos por IP (con hash) y por acción | `limite_superado()` | El sexto intento queda bloqueado |
| | Mensaje genérico en la consulta (no revela si el código existe) | `consulta.php` | — |
| | Regeneración del ID de sesión al autorizar una consulta | `consulta.php` | — |
| Acceso a documentos | La vista requiere una verificación previa en la sesión, válida 30 minutos (evita IDOR) | `documento.php` | Abrir directamente `documento.php?codigo=…` redirige a la consulta |
| Privacidad | Teléfono enmascarado en el documento; IP guardada como hash | `documento.php`, `ip_cliente()` | — |

## Mantenimiento

- Actualiza PHP cuando el hosting ofrezca versiones de seguridad.
- Haz copias semanales de `app/data/jflores.sqlite` y guárdalas cifradas.
- Revisa la tabla `mensajes` y borra los registros que tengan más de 6 meses, según la Política de Privacidad.
- Si cambias o agregas recursos externos (fuentes, mapas, analítica), actualiza la CSP en **los dos** sitios: `.htaccess` y `bootstrap.php`.
