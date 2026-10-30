<?php
declare(strict_types=1);

$CFG = require __DIR__ . '/config.php';

$esHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

// --- Forzar HTTPS (respaldo de .htaccess) ---
if ($CFG['forzar_https'] && !$esHttps && PHP_SAPI !== 'cli-server') {
    $host = preg_replace('/[^A-Za-z0-9.\-:]/', '', $_SERVER['HTTP_HOST'] ?? '');
    header('Location: https://' . $host . ($_SERVER['REQUEST_URI'] ?? '/'), true, 301);
    exit;
}

// --- Cabeceras de seguridad ---
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; font-src 'self'; connect-src 'self'; form-action 'self'; frame-ancestors 'none'; base-uri 'self'; object-src 'none'");
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()');
header('Cross-Origin-Opener-Policy: same-origin');
if ($esHttps) {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}
header_remove('X-Powered-By');

// --- Sesión segura ---
session_name('JFSESSID');
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => $esHttps,
    'httponly' => true,
    'samesite' => 'Strict',
]);
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
session_start();

require __DIR__ . '/db.php';

// --- Helpers ---

/** Escapa para salida HTML (anti-XSS). */
function e(?string $v): string
{
    return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function cfg(string $clave)
{
    global $CFG;
    return $CFG[$clave] ?? null;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

/** Corta la petición con 403 si el token no coincide. */
function csrf_check(): void
{
    $enviado = $_POST['csrf'] ?? '';
    if (!is_string($enviado) || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $enviado)) {
        http_response_code(403);
        exit('Solicitud no válida. Recarga la página e inténtalo de nuevo.');
    }
}

/** Limpia texto de entrada: quita etiquetas y caracteres de control, recorta longitud. */
function limpiar(?string $v, int $max = 255): string
{
    $v = strip_tags((string) $v);
    $v = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $v) ?? '';
    return mb_substr(trim($v), 0, $max);
}

function flash(string $tipo, string $msg): void
{
    $_SESSION['flash'][$tipo] = $msg;
}

function flash_get(string $tipo): ?string
{
    $msg = $_SESSION['flash'][$tipo] ?? null;
    unset($_SESSION['flash'][$tipo]);
    return $msg;
}

function ip_cliente(): string
{
    return hash('sha256', ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0') . session_name());
}

/** true si la IP superó el límite de intentos para la acción indicada. Registra el intento. */
function limite_superado(string $accion): bool
{
    $rl = cfg('rate_limit');
    $pdo = db();
    $desde = time() - $rl['ventana'];
    $pdo->prepare('DELETE FROM intentos WHERE creado < ?')->execute([$desde]);

    $st = $pdo->prepare('SELECT COUNT(*) FROM intentos WHERE ip = ? AND accion = ? AND creado >= ?');
    $st->execute([ip_cliente(), $accion, $desde]);
    $total = (int) $st->fetchColumn();

    $pdo->prepare('INSERT INTO intentos (ip, accion, creado) VALUES (?, ?, ?)')
        ->execute([ip_cliente(), $accion, time()]);

    return $total >= $rl['max'];
}

function redirigir(string $url): void
{
    header('Location: ' . $url, true, 303);
    exit;
}

function wa_link(string $texto = ''): string
{
    return 'https://wa.me/' . cfg('whatsapp') . ($texto !== '' ? '?text=' . rawurlencode($texto) : '');
}
