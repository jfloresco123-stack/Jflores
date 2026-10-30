<?php
declare(strict_types=1);
require __DIR__ . '/../app/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Vista GET: formulario de consulta con el mensaje de error, si lo hay.
    $msg = flash_get('consulta');
    $titulo = 'Consultar orden · Jflores@';
    require __DIR__ . '/../app/partials/header.php';
    ?>
    <section class="section">
        <div class="container-x max-w-xl">
            <p class="eyebrow">Seguimiento</p>
            <h1 class="h2">Consulta tu orden de servicio</h1>
            
            <form action="consulta.php" method="post" class="card mt-10 space-y-5" novalidate data-validate>
                <?php if ($msg): ?><div class="alert-err" role="alert"><?= e($msg) ?></div><?php endif; ?>
                <?= csrf_field() ?>
                <div>
                    <label class="label" for="c-codigo">Código de orden</label>
                    <input class="input font-mono uppercase" id="c-codigo" name="codigo" required maxlength="20" pattern="[A-Za-z]{2}-[0-9]{4}-[0-9]{4}" placeholder="JF-2026-0000" autocomplete="off">
                </div>
                <div>
                    <label class="label" for="c-tel">Últimos 4 dígitos del celular</label>
                    <input class="input font-mono" id="c-tel" name="tel4" required inputmode="numeric" maxlength="4" pattern="[0-9]{4}" placeholder="0000" autocomplete="off">
                </div>
                <button class="btn-primary w-full" type="submit"><?= icon('search', 'h-4 w-4') ?> Consultar</button>
            </form>
        </div>
    </section>
    <?php
    require __DIR__ . '/../app/partials/footer.php';
    exit;
}

csrf_check();

$volver = function (string $msg): void {
    flash('consulta', $msg);
    redirigir('consulta.php');
};

if (limite_superado('consulta')) {
    $volver('Demasiados intentos. Por seguridad, espera 15 minutos antes de volver a consultar.');
}

$codigo = strtoupper(limpiar($_POST['codigo'] ?? '', 20));
$tel4   = limpiar($_POST['tel4'] ?? '', 4);

if (!preg_match('/^[A-Z]{2}-\d{4}-\d{4}$/', $codigo) || !preg_match('/^\d{4}$/', $tel4)) {
    $volver('Formato no válido. El código tiene la forma JF-2026-0000 y se requieren 4 dígitos.');
}

$st = db()->prepare('SELECT id, telefono FROM ordenes WHERE codigo = ?');
$st->execute([$codigo]);
$orden = $st->fetch();

// Mismo mensaje para "no existe" y "teléfono incorrecto": no revelamos qué códigos son válidos.
if (!$orden || !hash_equals(substr(preg_replace('/\D/', '', $orden['telefono']), -4), $tel4)) {
    $volver('No encontramos una orden con esos datos. Verifica el código y los dígitos.');
}

// Autoriza la vista de esta orden solo para esta sesión (evita acceso directo por URL).
session_regenerate_id(true);
$_SESSION['ordenes_autorizadas'][$codigo] = time();
redirigir('documento.php?codigo=' . rawurlencode($codigo));
