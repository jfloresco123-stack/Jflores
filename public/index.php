<?php
declare(strict_types=1);
require __DIR__ . '/../app/bootstrap.php';

$enInicio = true;
$ok  = flash_get('ok');
$err = flash_get('error');
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);

// Logos de marcas: assets/img/marcas/marcas.json (ver README de esa carpeta)
$marcas = json_decode((string) file_get_contents(__DIR__ . '/assets/img/marcas/marcas.json'), true) ?: [];
$categoriasMarcas = ['celulares' => 'Celulares', 'computo' => 'Cómputo', 'impresoras' => 'Impresoras', 'audio-video' => 'Audio y video', 'electronica' => 'Electrónica y 3D'];

$servicios = [
    ['phone', 'Celulares y tablets', 'Android y iPhone de todas las marcas.', ['Cambio de pantalla y táctil', 'Pin de carga y batería', 'Cámaras, audio y botones', 'Equipos mojados o que no encienden']],
    ['laptop', 'Laptops y computadoras', 'Portátiles, PC de escritorio y all-in-one.', ['Fallas de encendido y placa', 'Bisagras, teclado y pantalla', 'Limpieza y cambio de pasta térmica', 'Upgrade a SSD y memoria RAM']],
    ['cpu', 'Microsoldadura', 'Reparación a nivel de componente con microscopio.', ['Reballing y cambio de chips', 'Reparación de pistas y conectores', 'Cortocircuitos y daño por líquido', 'Recuperación de placas "sin solución"']],
    ['tv', 'Televisores', 'Smart TV LED, LCD y OLED.', ['Fuente de poder', 'Retroiluminación (backlight)', 'Tarjeta main y T-con', 'Actualización de software']],
    ['printer', 'Impresoras', 'Tinta, tanque de tinta y láser.', ['Limpieza de cabezales', 'Reseteo de almohadillas', 'Atascos y rodillos', 'Instalación de sistema continuo']],
    ['cube', 'Impresoras 3D', 'FDM: Creality, Bambu Lab, Prusa y más.', ['Calibración y nivelación de cama', 'Extrusor y hotend', 'Placas y firmware', 'Mantenimiento preventivo']],
    ['windows', 'Windows y Android', 'Software, sistema y optimización.', ['Formateo e instalación de Windows', 'Drivers y programas', 'Equipos lentos u optimización', 'Restauración de Android']],
    ['board', 'Arduino y electrónica', 'Proyectos, prototipos y módulos.', ['Programación de Arduino', 'Diseño y armado de circuitos', 'Reparación de módulos', 'Asesoría para proyectos']],
    ['speaker', 'Equipos de sonido', 'Audio doméstico y profesional.', ['Amplificadores y parlantes', 'Minicomponentes', 'Consolas y mezcladoras', 'Bluetooth y conectividad']],
];

$frecuentes = [
    ['Cambio de pantalla', 'Celular / tablet', '1 – 3 horas'],
    ['Cambio de pin de carga', 'Celular', '1 – 2 horas'],
    ['Cambio de batería', 'Celular / laptop', '1 hora'],
    ['Equipo mojado (limpieza de placa)', 'Celular / laptop', '24 – 72 horas'],
    ['Formateo e instalación de Windows', 'Laptop / PC', '3 – 5 horas'],
    ['Mantenimiento y pasta térmica', 'Laptop / PC', '2 – 4 horas'],
    ['Cambio a disco SSD con migración', 'Laptop / PC', '2 – 3 horas'],
    ['Reparación de backlight', 'Televisor', '24 – 48 horas'],
    ['Reseteo de almohadillas', 'Impresora', '1 hora'],
    ['Microsoldadura de placa', 'Todos', 'Según diagnóstico'],
];

$planes = [
    'Básico'     => ['precio' => 'Desde una visita', 'sub' => 'Mantenimiento único', 'destacado' => false],
    'Preventivo' => ['precio' => 'Semestral', 'sub' => 'Para hogares y profesionales', 'destacado' => true],
    'Empresas'   => ['precio' => 'Mensual', 'sub' => 'Pymes y oficinas', 'destacado' => false],
];
$filasPlan = [
    ['Limpieza interna y cambio de pasta térmica', true, true, true],
    ['Revisión de hardware (batería, disco, temperatura)', true, true, true],
    ['Optimización del sistema y drivers', true, true, true],
    ['Respaldo de información', false, true, true],
    ['Informe técnico de cada visita', true, true, true],
    ['Atención prioritaria', false, true, true],
    ['Visita a domicilio u oficina', false, false, true],
    ['Soporte por WhatsApp', false, true, true],
    ['Inventario de equipos', false, false, true],
];

$pasos = [
    ['clipboard', 'Recepción', 'Registramos tu equipo, número de serie, estado y la falla que reportas.', 'Orden de servicio'],
    ['search', 'Diagnóstico', 'Pruebas a nivel de hardware y software en 24 a 48 horas.', 'Diagnóstico'],
    ['doc', 'Presupuesto', 'Te enviamos el costo y el plazo. No reparamos sin tu aprobación.', 'Presupuesto'],
    ['wrench', 'Reparación', 'Intervención con herramientas profesionales y repuestos de calidad.', 'Bitácora'],
    ['check', 'Control de calidad', 'Pruebas de carga, temperatura, puertos y funciones.', 'Checklist QA'],
    ['award', 'Entrega y garantía', 'Te explicamos el trabajo y te entregamos la garantía escrita.', 'Informe y garantía'],
];

$beneficios = [
    ['search', 'Diagnóstico honesto', 'Te explicamos la falla real y te damos opciones antes de cobrar.'],
    ['cpu', 'Reparación a nivel de placa', 'Microsoldadura con microscopio y estación de aire caliente: reparamos, no solo cambiamos.'],
    ['star', 'Repuestos de calidad', 'Trabajamos con repuestos probados y te decimos su procedencia.'],
    ['award', 'Garantía escrita', 'Cada reparación incluye un certificado con su vigencia y cobertura.'],
    ['doc', 'Informe técnico', 'Documentamos lo que encontramos, lo que cambiamos y las pruebas realizadas.'],
    ['users', 'Atención personalizada', 'Hablas directamente con el técnico que repara tu equipo.'],
];

$habilidades = [
    'Electrónica y microsoldadura' => ['cpu', [['Diagnóstico a nivel de placa', 'w-[95%]', 95], ['Microsoldadura BGA / SMD', 'w-[92%]', 92], ['Reparación por daño de líquido', 'w-[88%]', 88]]],
    'Hardware y cómputo'           => ['laptop', [['Laptops y PC', 'w-[94%]', 94], ['Impresoras 2D y 3D', 'w-[88%]', 88], ['Recuperación de datos', 'w-[85%]', 85]]],
    'Software y sistemas'          => ['windows', [['Windows: instalación y optimización', 'w-[95%]', 95], ['Android: software y restauración', 'w-[90%]', 90], ['Arduino y programación', 'w-[85%]', 85]]],
];

$faqs = [
    ['¿Cuánto demora el diagnóstico?', 'La mayoría de diagnósticos están listos en 24 a 48 horas. Los equipos mojados o con fallas intermitentes pueden tomar hasta 72 horas. Siempre te avisamos.'],
    ['¿El diagnóstico tiene costo?', 'Tiene un costo mínimo que se descuenta del total si apruebas la reparación. Nunca reparamos sin que apruebes el presupuesto.'],
    ['¿Qué garantía tienen las reparaciones?', 'Todas incluyen garantía escrita, normalmente de 90 días en mano de obra. Los repuestos tienen la garantía de su fabricante. Las condiciones están en tu certificado.'],
    ['¿Reparan equipos mojados o que no encienden?', 'Sí. Es nuestra especialidad: limpiamos la placa y reparamos a nivel de componente con microsoldadura. Mientras antes traigas el equipo, más posibilidades hay de salvarlo.'],
    ['¿Pierdo mi información al reparar?', 'En la mayoría de reparaciones no. Si el trabajo lo requiere (por ejemplo, formatear), te avisamos antes y podemos hacer un respaldo de tus archivos.'],
    ['¿Atienden a domicilio?', 'Sí, para computadoras, impresoras y empresas. Los celulares y las reparaciones de placa se hacen en el taller, donde están las herramientas.'],
];

require __DIR__ . '/../app/partials/header.php';
?>

<!-- ============ HERO ============ -->
<section id="inicio" class="bg-hero relative overflow-hidden">
    <div class="container-x grid items-center gap-12 py-14 lg:grid-cols-2 lg:py-20">
        <div>
            <nav aria-label="Ruta" class="text-xs text-ink-muted">
                <a href="index.php" class="hover:text-blue-brand">Inicio</a> <span class="mx-1">›</span> <span class="text-ink">Servicio técnico y microsoldadura</span>
            </nav>
            <p class="badge mt-5"><?= icon('wrench', 'h-3.5 w-3.5') ?> Taller de reparación electrónica</p>
            <h1 class="mt-4 text-4xl font-extrabold leading-tight sm:text-5xl">
                Reparación de <span class="text-blue-brand">celulares, laptops y PC</span> con microsoldadura profesional
            </h1>
            <p class="mt-5 text-lg leading-relaxed text-ink-muted">Reparamos tus equipos a nivel de placa, no solo cambiamos piezas. Diagnóstico claro, presupuesto antes de reparar y garantía escrita en cada trabajo.</p>
            <ul class="check-list mt-6 grid gap-2 sm:grid-cols-2">
                <li>Diagnóstico en 24 – 48 horas</li>
                <li>Presupuesto antes de reparar</li>
                <li>Informe técnico de tu equipo</li>
                <li>Garantía escrita</li>
            </ul>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="<?= e(wa_link('Hola Jflores@, quiero cotizar una reparación.')) ?>" target="_blank" rel="noopener noreferrer" class="btn-primary px-7 py-4 text-base"><?= icon_social('whatsapp', 'h-5 w-5') ?> Cotizar por WhatsApp</a>
                <a href="#servicios" class="btn-outline px-7 py-4 text-base">Ver servicios <?= icon('arrow', 'h-4 w-4') ?></a>
            </div>
        </div>

        <div class="relative">
            <img src="assets/img/trabajos/reparacion-placa.webp" width="1100" height="723" alt="Técnico de Jflores@ reparando la placa de un iPhone con pinzas de precisión"
                 class="w-full rounded-3xl object-cover shadow-lift ring-8 ring-white">
            <div class="absolute -bottom-6 left-4 flex items-center gap-3 rounded-2xl bg-white p-4 shadow-lift sm:left-8">
                <div class="icon-box bg-primary-light/60 text-primary"><?= icon('cpu') ?></div>
                <div>
                    <p class="font-display text-sm font-bold text-ink">Microsoldadura</p>
                    <p class="text-xs text-ink-muted">Reparación a nivel de componente</p>
                </div>
            </div>
            <div class="absolute -top-5 right-4 hidden items-center gap-3 rounded-2xl bg-white p-4 shadow-lift sm:flex">
                <div class="icon-box"><?= icon('award') ?></div>
                <div>
                    <p class="font-display text-sm font-bold text-ink">Garantía escrita</p>
                    <p class="text-xs text-ink-muted">En todas las reparaciones</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ MARCAS ============ -->
<section class="border-y border-slate-200 bg-white py-10" aria-labelledby="marcas-titulo">
    <div class="container-x">
        <h2 id="marcas-titulo" class="text-center font-display text-sm font-semibold uppercase tracking-wider text-ink-muted">Reparamos equipos de todas las marcas</h2>
        <ul class="mt-8 grid grid-cols-3 gap-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8">
            <?php foreach ($marcas as $cat => $lista): foreach ($lista as $m): ?>
                <li class="group flex flex-col items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-2 py-4 transition hover:-translate-y-0.5 hover:border-blue-accent/40 hover:shadow-card" title="<?= e($categoriasMarcas[$cat] ?? $cat) ?>">
                    <img src="<?= e($m['archivo']) ?>" alt="" width="40" height="40" loading="lazy" class="brand-logo">
                    <span class="text-xs font-medium text-ink-muted group-hover:text-ink"><?= e($m['nombre']) ?></span>
                </li>
            <?php endforeach; endforeach; ?>
        </ul>
    </div>
</section>

<!-- ============ SERVICIOS ============ -->
<section id="servicios" class="section bg-surface">
    <div class="container-x">
        <div class="head-center">
            <p class="eyebrow">Nuestros servicios</p>
            <h2 class="h2">¿Qué <span>reparamos</span>?</h2>
            <p class="lead">Servicio técnico completo para tus equipos electrónicos. Elige tu equipo y cotiza al instante por WhatsApp.</p>
        </div>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($servicios as [$ic, $t, $d, $items]): ?>
                <article class="card card-hover flex flex-col">
                    <div class="flex items-center gap-4">
                        <div class="icon-box h-14 w-14"><?= icon($ic, 'h-7 w-7') ?></div>
                        <div>
                            <h3 class="text-lg font-bold"><?= e($t) ?></h3>
                            <p class="text-sm text-ink-muted"><?= e($d) ?></p>
                        </div>
                    </div>
                    <p class="mt-5 text-xs font-semibold uppercase tracking-wider text-blue-brand">Incluye</p>
                    <ul class="check-list mt-2 space-y-1.5">
                        <?php foreach ($items as $it): ?><li><?= e($it) ?></li><?php endforeach; ?>
                    </ul>
                    <div class="mt-auto pt-6">
                        <a href="<?= e(wa_link("Hola Jflores@, quiero cotizar: $t.")) ?>" target="_blank" rel="noopener noreferrer"
                           class="btn-outline w-full py-2.5">Cotizar <?= e(mb_strtolower($t)) ?></a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ BANNER ============ -->
<section class="relative overflow-hidden bg-ink">
    <img src="assets/img/trabajos/celulares.webp" alt="" width="1400" height="547" loading="lazy" class="absolute inset-0 h-full w-full object-cover opacity-40">
    <div class="absolute inset-0 bg-gradient-to-r from-ink via-ink/85 to-ink/30"></div>
    <div class="container-x relative grid gap-8 py-16 lg:grid-cols-2 lg:py-20">
        <div>
            <p class="eyebrow">Celulares · Laptops · TV · Impresoras</p>
            <h2 class="mt-2 font-display text-3xl font-bold text-white sm:text-4xl">¿Pantalla rota, no carga o se mojó?</h2>
            <p class="mt-4 text-lg text-slate-300">Tráelo hoy. Mientras antes lo revisemos, más fácil y económica es la reparación.</p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="<?= e(wa_link('Hola Jflores@, mi equipo tiene una falla.')) ?>" target="_blank" rel="noopener noreferrer" class="btn-primary px-7 py-4"><?= icon_social('whatsapp', 'h-5 w-5') ?> Escríbenos ahora</a>
                <a href="#frecuentes" class="btn-white px-7 py-4">Ver tiempos de reparación</a>
            </div>
        </div>
        <dl class="grid grid-cols-2 gap-4 self-center">
            <?php foreach ([['24–48 h', 'Diagnóstico'], ['8', 'Tipos de equipo'], ['+20', 'Marcas atendidas'], ['90 días', 'Garantía habitual']] as [$v, $k]): ?>
                <div class="rounded-2xl border border-white/15 bg-white/5 p-5 backdrop-blur">
                    <dt class="text-sm text-slate-300"><?= e($k) ?></dt>
                    <dd class="mt-1 font-display text-3xl font-extrabold text-white"><?= e($v) ?></dd>
                </div>
            <?php endforeach; ?>
        </dl>
    </div>
</section>

<!-- ============ REPARACIONES FRECUENTES ============ -->
<section id="frecuentes" class="section">
    <div class="container-x">
        <div class="grid items-end gap-6 lg:grid-cols-2">
            <div>
                <p class="eyebrow">Tiempos de reparación</p>
                <h2 class="h2">Reparaciones <span>más frecuentes</span></h2>
            </div>
            <p class="text-ink-muted lg:text-right">Tiempos referenciales después del diagnóstico y con el repuesto disponible.</p>
        </div>
        <div class="mt-10 overflow-x-auto rounded-2xl border border-slate-200 shadow-card">
            <table class="tbl">
                <thead><tr><th>Reparación</th><th>Equipo</th><th>Tiempo estimado</th><th class="text-center">Garantía escrita</th></tr></thead>
                <tbody>
                <?php foreach ($frecuentes as [$r, $eq, $tiempo]): ?>
                    <tr>
                        <td class="font-medium text-ink"><?= e($r) ?></td>
                        <td class="text-ink-muted"><?= e($eq) ?></td>
                        <td><span class="inline-flex items-center gap-1.5"><?= icon('clock', 'h-4 w-4 text-blue-accent') ?><?= e($tiempo) ?></span></td>
                        <td class="text-center"><span class="yes">✓</span></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- ============ PLANES ============ -->
<section id="planes" class="section bg-surface">
    <div class="container-x">
        <div class="head-center">
            <p class="eyebrow">Planes de mantenimiento</p>
            <h2 class="h2">Cuida tus equipos <span>antes de que fallen</span></h2>
            <p class="lead">El mantenimiento preventivo alarga la vida de tus equipos y evita reparaciones costosas.</p>
        </div>

        <div class="mt-12 overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-card">
            <table class="tbl">
                <thead>
                <tr>
                    <th class="w-2/5">Características</th>
                    <?php foreach ($planes as $nombre => $p): ?>
                        <th class="text-center <?= $p['destacado'] ? 'bg-blue-brand' : '' ?>">
                            <?php if ($p['destacado']): ?><span class="mb-1 inline-block rounded-full bg-primary px-2 py-0.5 text-[10px] uppercase tracking-wider">Recomendado</span><br><?php endif; ?>
                            <span class="text-lg"><?= e($nombre) ?></span><br>
                            <span class="text-xs font-normal text-slate-300"><?= e($p['sub']) ?></span>
                        </th>
                    <?php endforeach; ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($filasPlan as [$txt, $a, $b, $c]): ?>
                    <tr>
                        <td class="text-ink"><?= e($txt) ?></td>
                        <?php foreach ([$a, $b, $c] as $i => $v): ?>
                            <td class="text-center <?= $i === 1 ? 'bg-blue-soft/60' : '' ?>"><?= $v ? '<span class="yes" aria-label="Incluido">✓</span>' : '<span class="no" aria-label="No incluido">—</span>' ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td class="font-semibold text-ink">Frecuencia</td>
                    <?php foreach ($planes as $p): ?><td class="text-center font-semibold text-blue-brand"><?= e($p['precio']) ?></td><?php endforeach; ?>
                </tr>
                <tr>
                    <td></td>
                    <?php foreach ($planes as $nombre => $p): ?>
                        <td class="text-center">
                            <a href="<?= e(wa_link("Hola Jflores@, quiero información del plan $nombre.")) ?>" target="_blank" rel="noopener noreferrer"
                               class="<?= $p['destacado'] ? 'btn-primary' : 'btn-outline' ?> px-4 py-2 text-xs">Cotizar plan</a>
                        </td>
                    <?php endforeach; ?>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- ============ PROCESO ============ -->
<section id="proceso" class="section">
    <div class="container-x">
        <div class="head-center">
            <p class="eyebrow">Cómo trabajamos</p>
            <h2 class="h2">Un proceso <span>claro y documentado</span></h2>
            <p class="lead">Sabes en todo momento qué le pasa a tu equipo y qué se le hizo.</p>
        </div>
        <ol class="mt-14 grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($pasos as $i => [$ic, $t, $d, $docu]): ?>
                <li class="card relative pt-9">
                    <span class="absolute -top-5 left-6 flex h-10 w-10 items-center justify-center rounded-full bg-primary font-display text-sm font-bold text-white ring-4 ring-white"><?= $i + 1 ?></span>
                    <div class="flex items-center gap-3">
                        <span class="text-blue-brand"><?= icon($ic) ?></span>
                        <h3 class="text-lg font-bold"><?= e($t) ?></h3>
                    </div>
                    <p class="mt-2 text-sm leading-relaxed text-ink-muted"><?= e($d) ?></p>
                    <p class="mt-4 inline-flex items-center gap-1 rounded-full bg-blue-soft px-3 py-1 text-xs font-medium text-blue-brand"><?= icon('doc', 'h-3.5 w-3.5') ?><?= e($docu) ?></p>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>

<!-- ============ POR QUÉ ELEGIRNOS + HABILIDADES ============ -->
<section class="section bg-surface">
    <div class="container-x grid gap-12 lg:grid-cols-2">
        <div>
            <p class="eyebrow">¿Por qué Jflores@?</p>
            <h2 class="h2">Técnicos que <span>reparan de verdad</span></h2>
            <div class="mt-8 grid gap-5 sm:grid-cols-2">
                <?php foreach ($beneficios as [$ic, $t, $d]): ?>
                    <div class="flex gap-4">
                        <div class="icon-box"><?= icon($ic) ?></div>
                        <div>
                            <h3 class="font-bold"><?= e($t) ?></h3>
                            <p class="mt-1 text-sm text-ink-muted"><?= e($d) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div id="habilidades" class="card self-start p-8">
            <h3 class="text-xl font-bold">Habilidades técnicas</h3>
            <p class="mt-1 text-sm text-ink-muted">Formación y práctica continua en electrónica y sistemas.</p>
            <div class="mt-6 space-y-7">
                <?php foreach ($habilidades as $grupo => [$ic, $skills]): ?>
                    <div>
                        <p class="flex items-center gap-2 font-display font-semibold text-blue-brand"><?= icon($ic, 'h-5 w-5') ?><?= e($grupo) ?></p>
                        <ul class="mt-3 space-y-3">
                            <?php foreach ($skills as [$nombre, $ancho, $pct]): ?>
                                <li>
                                    <div class="mb-1 flex justify-between text-sm"><span><?= e($nombre) ?></span><span class="font-semibold text-ink"><?= $pct ?>%</span></div>
                                    <div class="bar" role="progressbar" aria-valuenow="<?= $pct ?>" aria-valuemin="0" aria-valuemax="100" aria-label="<?= e($nombre) ?>"><span class="<?= $ancho ?>"></span></div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- ============ CONSULTA DE EQUIPO ============ -->
<section id="consulta" class="section bg-blue-brand bg-dots text-white">
    <div class="container-x grid items-center gap-10 lg:grid-cols-2">
        <div>
            <p class="font-display text-sm font-semibold uppercase tracking-wider text-primary-light">Seguimiento en línea</p>
            <h2 class="mt-2 font-display text-3xl font-bold text-white sm:text-4xl">Consulta el estado de tu equipo</h2>
            <p class="mt-4 text-lg text-blue-soft">Con el código de tu orden de servicio puedes ver en línea tu <strong>orden</strong>, el <strong>informe técnico</strong> y la <strong>garantía</strong> de la reparación, y descargarlos en PDF.</p>
            <ul class="mt-6 flex flex-wrap gap-2 text-sm">
                <?php foreach (['Orden de servicio', 'Informe técnico', 'Certificado de garantía'] as $d): ?>
                    <li class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1.5"><?= icon('doc', 'h-4 w-4') ?><?= e($d) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <form action="consulta.php" method="post" class="rounded-2xl bg-white p-6 text-ink-text shadow-lift sm:p-8" novalidate data-validate>
            <?= csrf_field() ?>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="label" for="c-codigo">Código de orden</label>
                    <input class="input font-mono uppercase" id="c-codigo" name="codigo" required maxlength="20" pattern="[A-Za-z]{2}-[0-9]{4}-[0-9]{4}" placeholder="JF-2026-0000" autocomplete="off">
                </div>
                <div>
                    <label class="label" for="c-tel">Últimos 4 dígitos de tu celular</label>
                    <input class="input font-mono" id="c-tel" name="tel4" required inputmode="numeric" maxlength="4" pattern="[0-9]{4}" placeholder="0000" autocomplete="off">
                </div>
            </div>
            <button class="btn-primary mt-5 w-full" type="submit"><?= icon('search', 'h-4 w-4') ?> Consultar mi equipo</button>
            <p class="mt-3 text-center text-xs text-ink-muted">Ejemplo: <span class="font-mono text-blue-brand">JF-2026-0001</span> y <span class="font-mono text-blue-brand">0651</span></p>
        </form>
    </div>
</section>

<!-- ============ FAQ ============ -->
<section id="faq" class="section">
    <div class="container-x max-w-4xl">
        <div class="head-center">
            <p class="eyebrow">Preguntas frecuentes</p>
            <h2 class="h2">Resolvemos <span>tus dudas</span></h2>
        </div>
        <div class="mt-10 space-y-3">
            <?php foreach ($faqs as $i => [$q, $a]): ?>
                <details class="group rounded-2xl border border-slate-200 bg-white shadow-card open:border-blue-accent/40" <?= $i === 0 ? 'open' : '' ?>>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 p-5 font-display font-semibold text-ink">
                        <?= e($q) ?>
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-soft text-lg text-blue-brand transition group-open:rotate-45 group-open:bg-primary group-open:text-white">+</span>
                    </summary>
                    <p class="px-5 pb-5 leading-relaxed text-ink-muted"><?= e($a) ?></p>
                </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ CONTACTO ============ -->
<section id="contacto" class="section bg-surface">
    <div class="container-x grid gap-12 lg:grid-cols-12">
        <div class="lg:col-span-5">
            <p class="eyebrow">Contacto</p>
            <h2 class="h2">Cuéntanos qué <span>le pasa a tu equipo</span></h2>
            <p class="lead text-base">Te respondemos con un diagnóstico preliminar y el siguiente paso. Si es urgente, escríbenos directamente por WhatsApp.</p>
            <div class="mt-8 space-y-4">
                <a href="<?= e(wa_link()) ?>" target="_blank" rel="noopener noreferrer" class="card card-hover flex items-center gap-4 p-5">
                    <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#25D366] text-white"><?= icon_social('whatsapp', 'h-6 w-6') ?></span>
                    <span><span class="block text-xs text-ink-muted">WhatsApp</span><span class="font-display text-lg font-bold text-ink"><?= e(cfg('telefono')) ?></span></span>
                </a>
                <a href="mailto:<?= e(cfg('correo')) ?>" class="card card-hover flex items-center gap-4 p-5">
                    <span class="icon-box"><?= icon('mail') ?></span>
                    <span class="min-w-0"><span class="block text-xs text-ink-muted">Correo</span><span class="block truncate font-medium text-ink"><?= e(cfg('correo')) ?></span></span>
                </a>
                <div class="card flex items-center gap-4 p-5">
                    <span class="icon-box"><?= icon('clock') ?></span>
                    <span><span class="block text-xs text-ink-muted">Horario</span><span class="font-medium text-ink"><?= e(cfg('horario')) ?></span></span>
                </div>
            </div>
        </div>

        <div class="lg:col-span-7">
            <form action="contacto.php" method="post" class="card space-y-5 p-6 sm:p-8" novalidate data-validate>
                <?php if ($ok): ?><div class="alert-ok" role="status"><?= e($ok) ?></div><?php endif; ?>
                <?php if ($err): ?><div class="alert-err" role="alert"><?= e($err) ?></div><?php endif; ?>
                <?= csrf_field() ?>
                <div class="hp" aria-hidden="true"><label>No llenar <input type="text" name="sitio_web" tabindex="-1" autocomplete="off"></label></div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="label" for="f-nombre">Nombre completo *</label>
                        <input class="input" id="f-nombre" name="nombre" required maxlength="80" autocomplete="name" value="<?= e($old['nombre'] ?? '') ?>">
                    </div>
                    <fieldset>
                        <legend class="label">Tipo de cliente *</legend>
                        <div class="grid grid-cols-2 gap-2">
                            <?php foreach (['Particular', 'Empresa'] as $t): ?>
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm has-[:checked]:border-blue-brand has-[:checked]:bg-blue-soft">
                                    <input type="radio" name="tipo" value="<?= $t ?>" class="text-blue-brand focus:ring-blue-accent" <?= ($old['tipo'] ?? 'Particular') === $t ? 'checked' : '' ?>> <?= $t ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>
                </div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="label" for="f-correo">Correo electrónico *</label>
                        <input class="input" id="f-correo" name="correo" type="email" required maxlength="120" autocomplete="email" value="<?= e($old['correo'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="label" for="f-tel">Celular</label>
                        <input class="input" id="f-tel" name="telefono" type="tel" maxlength="15" pattern="[0-9 +]{9,15}" autocomplete="tel" value="<?= e($old['telefono'] ?? '') ?>">
                    </div>
                </div>
                <div>
                    <label class="label" for="f-equipo">Equipo</label>
                    <select class="input" id="f-equipo" name="equipo">
                        <?php foreach (['Celular / tablet', 'Laptop / PC', 'Televisor', 'Impresora', 'Impresora 3D', 'Arduino / electrónica', 'Equipo de sonido', 'Plan de mantenimiento', 'Otro'] as $op): ?>
                            <option <?= ($old['equipo'] ?? '') === $op ? 'selected' : '' ?>><?= e($op) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="label" for="f-desc">Describe la falla *</label>
                    <textarea class="input" id="f-desc" name="descripcion" rows="5" required maxlength="1500" placeholder="Marca y modelo, qué pasó, desde cuándo, si hubo golpe o líquido…"><?= e($old['descripcion'] ?? '') ?></textarea>
                </div>
                <label class="flex items-start gap-2 text-sm text-ink-muted">
                    <input type="checkbox" name="acepto" value="1" required class="mt-0.5 h-4 w-4 rounded border-slate-300 text-blue-brand focus:ring-blue-accent">
                    <span>Acepto la <a href="privacidad.php" class="text-blue-brand underline">política de privacidad</a> y el uso de mis datos para atender mi solicitud. *</span>
                </label>
                <button class="btn-primary w-full py-4 text-base" type="submit">Enviar solicitud <?= icon('arrow', 'h-5 w-5') ?></button>
            </form>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../app/partials/footer.php'; ?>
