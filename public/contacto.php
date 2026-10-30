<?php
declare(strict_types=1);
require __DIR__ . '/../app/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('index.php#contacto');
}

csrf_check();

// Honeypot: los bots rellenan el campo oculto. Respondemos "ok" para no darles pistas.
if (!empty($_POST['sitio_web'])) {
    flash('ok', 'Gracias. Hemos recibido tu solicitud.');
    redirigir('index.php#contacto');
}

if (limite_superado('contacto')) {
    flash('error', 'Has realizado demasiados envíos. Inténtalo de nuevo en unos minutos o escríbenos por WhatsApp.');
    redirigir('index.php#contacto');
}

$datos = [
    'nombre'      => limpiar($_POST['nombre'] ?? '', 80),
    'tipo'        => limpiar($_POST['tipo'] ?? '', 20),
    'correo'      => limpiar($_POST['correo'] ?? '', 120),
    'telefono'    => limpiar($_POST['telefono'] ?? '', 15),
    'equipo'      => limpiar($_POST['equipo'] ?? '', 40),
    'descripcion' => limpiar($_POST['descripcion'] ?? '', 1500),
];

$errores = [];
if (mb_strlen($datos['nombre']) < 2) {
    $errores[] = 'nombre';
}
if (!in_array($datos['tipo'], ['Particular', 'Empresa'], true)) {
    $errores[] = 'tipo de cliente';
}
if (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'correo';
}
if ($datos['telefono'] !== '' && !preg_match('/^[0-9 +]{9,15}$/', $datos['telefono'])) {
    $errores[] = 'celular';
}
if (mb_strlen($datos['descripcion']) < 10) {
    $errores[] = 'descripción (mínimo 10 caracteres)';
}
if (empty($_POST['acepto'])) {
    $errores[] = 'aceptación de la política de privacidad';
}

if ($errores) {
    $_SESSION['old'] = $datos;
    flash('error', 'Revisa los siguientes campos: ' . implode(', ', $errores) . '.');
    redirigir('index.php#contacto');
}

db()->prepare('INSERT INTO mensajes (nombre, tipo, correo, telefono, equipo, descripcion, creado) VALUES (?,?,?,?,?,?,?)')
    ->execute([$datos['nombre'], $datos['tipo'], $datos['correo'], $datos['telefono'], $datos['equipo'], $datos['descripcion'], date('c')]);

// Aviso por correo. Las cabeceras no llevan datos del usuario salvo Reply-To,
// cuyo valor ya pasó FILTER_VALIDATE_EMAIL (sin saltos de línea → sin inyección de cabeceras).
$cuerpo = "Nueva solicitud desde la web jflores@\n\n"
    . "Nombre: {$datos['nombre']}\nTipo: {$datos['tipo']}\nCorreo: {$datos['correo']}\n"
    . "Celular: {$datos['telefono']}\nEquipo: {$datos['equipo']}\n\nIncidente:\n{$datos['descripcion']}\n";
$cabeceras = implode("\r\n", [
    'From: jflores@ Web <no-reply@' . preg_replace('/[^A-Za-z0-9.\-]/', '', $_SERVER['SERVER_NAME'] ?? 'localhost') . '>',
    'Reply-To: ' . $datos['correo'],
    'Content-Type: text/plain; charset=UTF-8',
]);
@mail(cfg('correo'), '=?UTF-8?B?' . base64_encode('Nueva solicitud: ' . $datos['equipo']) . '?=', $cuerpo, $cabeceras);

// Nuevo token tras un envío correcto.
unset($_SESSION['csrf']);
flash('ok', '¡Gracias, ' . $datos['nombre'] . '! Recibimos tu solicitud y te contactaremos en menos de 24 horas hábiles.');
redirigir('index.php#contacto');
