<?php
declare(strict_types=1);
require __DIR__ . '/../app/bootstrap.php';

$codigo = strtoupper(limpiar($_GET['codigo'] ?? '', 20));
$tipo   = $_GET['tipo'] ?? 'orden';
if (!in_array($tipo, ['orden', 'informe', 'garantia'], true)) {
    $tipo = 'orden';
}

// Solo se muestra si esta sesión pasó la verificación en consulta.php (30 min).
$autorizado = $_SESSION['ordenes_autorizadas'][$codigo] ?? 0;
if (!$autorizado || time() - $autorizado > 1800) {
    flash('consulta', 'Tu sesión de consulta expiró o no está autorizada. Vuelve a ingresar tus datos.');
    redirigir('consulta.php');
}

$st = db()->prepare('SELECT o.*, i.diagnostico, i.trabajo_realizado, i.componentes, i.pruebas_qa, i.tecnico,
                            i.fecha_entrega, i.garantia_dias, i.recomendaciones
                     FROM ordenes o LEFT JOIN informes i ON i.orden_id = o.id WHERE o.codigo = ?');
$st->execute([$codigo]);
$o = $st->fetch();
if (!$o) {
    http_response_code(404);
    exit('Orden no encontrada.');
}

$tieneInforme = $o['diagnostico'] !== null;
if (!$tieneInforme) {
    $tipo = 'orden';
}

$vence = $tieneInforme && $o['fecha_entrega']
    ? date('Y-m-d', strtotime($o['fecha_entrega'] . ' +' . (int) $o['garantia_dias'] . ' days'))
    : null;
$vigente = $vence && $vence >= date('Y-m-d');

$titulos = ['orden' => 'Orden de Servicio', 'informe' => 'Informe Técnico de Reparación', 'garantia' => 'Certificado de Garantía'];
$titulo = $titulos[$tipo] . ' ' . $codigo . ' · Jflores@';
$telMask = str_repeat('•', max(0, strlen($o['telefono']) - 4)) . substr($o['telefono'], -4);

/** Convierte texto multilínea en lista HTML escapada. */
function lista(?string $txt): string
{
    $lineas = array_filter(array_map('trim', explode("\n", (string) $txt)));
    return '<ul class="list-disc space-y-1 pl-5">' . implode('', array_map(fn ($l) => '<li>' . e($l) . '</li>', $lineas)) . '</ul>';
}

require __DIR__ . '/../app/partials/header.php';
?>
<section class="py-10">
    <div class="container-x">
        <div class="no-print mx-auto mb-6 flex max-w-4xl flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap gap-2" role="tablist">
                <?php foreach ($titulos as $t => $nom):
                    if ($t !== 'orden' && !$tieneInforme) continue; ?>
                    <a href="documento.php?codigo=<?= e(rawurlencode($codigo)) ?>&amp;tipo=<?= $t ?>" role="tab" aria-selected="<?= $t === $tipo ? 'true' : 'false' ?>"
                       class="<?= $t === $tipo ? 'btn-primary' : 'btn-outline' ?> py-2"><?= e($nom) ?></a>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn-outline py-2" data-print><?= icon('print', 'h-4 w-4') ?> Imprimir / PDF</button>
        </div>

        <article class="doc-sheet">
            <header class="flex flex-col justify-between gap-4 border-b-4 border-primary pb-6 sm:flex-row sm:items-end">
                <div>
                    <?= logo("h-14", "claro") ?>
                    <p class="text-xs text-slate-500"><?= e(cfg('eslogan')) ?></p>
                    <p class="text-xs text-slate-500">WhatsApp <?= e(cfg('telefono')) ?> · <?= e(cfg('correo')) ?></p>
                </div>
                <div class="sm:text-right">
                    <h1 class="text-xl font-bold uppercase tracking-wide"><?= e($titulos[$tipo]) ?></h1>
                    <p class="font-mono text-sm text-slate-600">N.° <?= e($o['codigo']) ?></p>
                    <p class="text-xs text-slate-500">Estado: <strong class="text-blue-brand"><?= e($o['estado']) ?></strong></p>
                </div>
            </header>

            <?php if ($tipo === 'orden'): ?>
                <h2 class="mt-8 text-sm font-bold uppercase tracking-wider">1. Datos del cliente</h2>
                <table class="mt-3"><tbody>
                    <tr><th>Cliente</th><td><?= e($o['cliente']) ?></td></tr>
                    <tr><th>Teléfono</th><td class="font-mono"><?= e($telMask) ?></td></tr>
                    <tr><th>Fecha de ingreso</th><td><?= e($o['fecha_ingreso']) ?></td></tr>
                </tbody></table>
                <h2 class="mt-8 text-sm font-bold uppercase tracking-wider">2. Registro del equipo</h2>
                <table class="mt-3"><tbody>
                    <tr><th>Tipo de equipo</th><td><?= e($o['equipo']) ?></td></tr>
                    <tr><th>Marca / modelo</th><td><?= e($o['marca_modelo']) ?></td></tr>
                    <tr><th>N.° de serie</th><td class="font-mono"><?= e($o['serie']) ?></td></tr>
                    <tr><th>Estado estético</th><td><?= e($o['estado_estetico']) ?></td></tr>
                    <tr><th>Accesorios</th><td><?= e($o['accesorios']) ?></td></tr>
                    <tr><th>Falla reportada</th><td><?= e($o['falla_reportada']) ?></td></tr>
                </tbody></table>
                <p class="mt-6 text-xs leading-relaxed text-slate-500">El cliente autoriza el diagnóstico del equipo. Ninguna reparación se realiza sin aprobación previa del presupuesto. Los datos personales se tratan conforme a la Ley N.° 29733. Equipos no recogidos en 90 días tras el aviso de entrega se rigen por los Términos de Servicio.</p>

            <?php elseif ($tipo === 'informe'): ?>
                <table class="mt-8"><tbody>
                    <tr><th>Equipo</th><td><?= e($o['equipo'] . ' · ' . $o['marca_modelo']) ?></td></tr>
                    <tr><th>N.° de serie</th><td class="font-mono"><?= e($o['serie']) ?></td></tr>
                    <tr><th>Técnico responsable</th><td><?= e($o['tecnico']) ?></td></tr>
                    <tr><th>Fecha de entrega</th><td><?= e($o['fecha_entrega']) ?></td></tr>
                </tbody></table>
                <h2 class="mt-8 text-sm font-bold uppercase tracking-wider">Diagnóstico</h2>
                <p class="mt-2 text-sm leading-relaxed"><?= e($o['diagnostico']) ?></p>
                <h2 class="mt-6 text-sm font-bold uppercase tracking-wider">Trabajo realizado</h2>
                <div class="mt-2 text-sm"><?= lista($o['trabajo_realizado']) ?></div>
                <h2 class="mt-6 text-sm font-bold uppercase tracking-wider">Componentes sustituidos</h2>
                <p class="mt-2 text-sm"><?= e($o['componentes'] ?: '—') ?></p>
                <h2 class="mt-6 text-sm font-bold uppercase tracking-wider">Control de calidad (QA)</h2>
                <div class="mt-2 text-sm"><?= lista($o['pruebas_qa']) ?></div>

            <?php else: ?>
                <div class="mt-8 rounded-lg border-2 <?= $vigente ? 'border-blue-brand bg-blue-soft' : 'border-slate-300 bg-slate-50' ?> p-6 text-center">
                    <p class="text-sm uppercase tracking-wider text-slate-500">Garantía de mano de obra</p>
                    <p class="mt-1 text-3xl font-extrabold text-ink"><?= (int) $o['garantia_dias'] ?> días</p>
                    <p class="mt-1 text-sm">Desde <?= e($o['fecha_entrega']) ?> hasta <strong><?= e($vence) ?></strong> ·
                        <span class="font-semibold <?= $vigente ? 'text-blue-brand' : 'text-slate-500' ?>"><?= $vigente ? 'VIGENTE' : 'VENCIDA' ?></span></p>
                </div>
                <h2 class="mt-8 text-sm font-bold uppercase tracking-wider">Cobertura</h2>
                <p class="mt-2 text-sm leading-relaxed">Cubre la mano de obra y los componentes sustituidos indicados en el Informe Técnico (<?= e($o['componentes'] ?: '—') ?>). Los repuestos tienen la garantía que indique su fabricante.</p>
                <h2 class="mt-6 text-sm font-bold uppercase tracking-wider">Exclusiones</h2>
                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                    <li>Golpes, caídas, humedad o líquidos posteriores a la entrega.</li>
                    <li>Manipulación del equipo por terceros o sellos de garantía rotos.</li>
                    <li>Fallas distintas a la reparada o por variaciones eléctricas.</li>
                    <li>Software, virus o pérdida de datos posteriores a la entrega.</li>
                </ul>
                <h2 class="mt-6 text-sm font-bold uppercase tracking-wider">Recomendaciones de seguridad</h2>
                <div class="mt-2 text-sm"><?= lista($o['recomendaciones']) ?></div>
            <?php endif; ?>

            <footer class="mt-12 grid gap-8 border-t border-slate-200 pt-6 text-xs text-slate-500 sm:grid-cols-2">
                <div>
                    <div class="h-10 border-b border-slate-400"></div>
                    <p class="mt-1">Firma técnico · Jflores@</p>
                </div>
                <div>
                    <div class="h-10 border-b border-slate-400"></div>
                    <p class="mt-1">Firma cliente</p>
                </div>
                <p class="sm:col-span-2">Documento generado el <?= date('Y-m-d H:i') ?> · Verificable en la web de jflores@ con el código <?= e($o['codigo']) ?>.</p>
            </footer>
        </article>
    </div>
</section>
<?php require __DIR__ . '/../app/partials/footer.php'; ?>
