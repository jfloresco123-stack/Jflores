<?php
/** @var string $titulo  @var string $descripcion */
require_once __DIR__ . '/icons.php';
$titulo = $titulo ?? 'Jflores@ · Servicio Técnico y Microsoldadura';
$descripcion = $descripcion ?? 'Reparación de celulares, laptops, PC, televisores, impresoras e impresoras 3D. Microsoldadura profesional, diagnóstico en 24–48 h, informe técnico y garantía escrita.';
$enInicio = $enInicio ?? false;
$base = $enInicio ? '' : 'index.php';
$nav = [
    'Inicio'     => $base . '#inicio',
    'Servicios'  => $base . '#servicios',
    'Planes'     => $base . '#planes',
    'Proceso'    => $base . '#proceso',
    'Mi equipo'  => $base . '#consulta',
    'Contacto'   => $base . '#contacto',
];
?><!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo) ?></title>
    <meta name="description" content="<?= e($descripcion) ?>">
    <meta name="theme-color" content="#0454AC">
    <meta property="og:title" content="<?= e($titulo) ?>">
    <meta property="og:description" content="<?= e($descripcion) ?>">
    <meta property="og:image" content="assets/img/tarjeta.jpg">
    <link rel="preload" href="assets/img/logo-color.png" as="image">
    <link rel="icon" href="assets/img/favicon.png" type="image/png">
    <link rel="apple-touch-icon" href="assets/img/apple-touch-icon.png">
    <link rel="preload" href="assets/fonts/poppins-700.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="assets/css/app.css">
    <script src="assets/js/theme.js"></script>
    <script src="assets/js/main.js" defer></script>
</head>
<body>
<a href="#contenido" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded focus:bg-primary focus:px-4 focus:py-2 focus:text-white">Saltar al contenido</a>

<!-- Barra superior -->
<div class="no-print bg-ink text-xs text-slate-200">
    <div class="container-x flex h-10 items-center justify-between gap-4">
        <p class="min-w-0 truncate"><span class="font-semibold text-primary">Diagnóstico en 24–48 h</span> · Reparamos celulares, laptops, PC, TV e impresoras con garantía escrita</p>
        <div class="hidden items-center gap-5 md:flex">
            <span class="flex items-center gap-1.5"><?= icon('clock', 'h-3.5 w-3.5') ?><?= e(cfg('horario')) ?></span>
            <a class="flex items-center gap-1.5 hover:text-white" href="<?= e(wa_link()) ?>" target="_blank" rel="noopener noreferrer"><?= icon_social('whatsapp', 'h-3.5 w-3.5') ?><?= e(cfg('telefono')) ?></a>
        </div>
    </div>
</div>

<header class="no-print sticky top-0 z-40 border-b border-slate-200 bg-white/95 shadow-sm backdrop-blur" id="site-header">
    <div class="container-x flex h-20 items-center justify-between gap-4">
        <a href="index.php#inicio" aria-label="Jflores@ – Inicio"><?= logo('h-11 sm:h-14') ?></a>

        <nav class="hidden lg:block" aria-label="Principal">
            <ul class="flex items-center gap-1" data-scrollspy>
                <?php foreach ($nav as $txt => $href): ?>
                    <li><a class="nav-link rounded-full px-4 py-2 font-display text-sm font-medium text-ink transition hover:bg-blue-soft hover:text-blue-brand" href="<?= e($href) ?>"><?= e($txt) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <div class="flex items-center gap-2">
            <button type="button" data-theme-toggle aria-pressed="false" aria-label="Cambiar a modo oscuro"
                    class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 text-ink transition hover:border-blue-accent hover:text-blue-brand">
                <span class="dark:hidden"><?= icon('moon', 'h-5 w-5') ?></span>
                <span class="hidden text-primary dark:inline"><?= icon('sun', 'h-5 w-5') ?></span>
            </button>
            <a href="<?= e(wa_link('Hola Jflores@, quiero cotizar una reparación.')) ?>" target="_blank" rel="noopener noreferrer" class="btn-primary hidden sm:inline-flex"><?= icon_social('whatsapp', 'h-4 w-4') ?> Cotizar reparación</a>
            <button type="button" class="rounded-lg p-2 text-ink lg:hidden" data-menu-toggle aria-controls="menu-movil" aria-expanded="false" aria-label="Abrir menú">
                <?= icon('menu') ?>
            </button>
        </div>
    </div>

    <nav id="menu-movil" class="hidden border-t border-slate-200 bg-white lg:hidden" aria-label="Móvil">
        <ul class="container-x flex flex-col py-3">
            <?php foreach ($nav as $txt => $href): ?>
                <li><a class="block rounded-lg px-3 py-3 font-display text-base text-ink hover:bg-blue-soft" href="<?= e($href) ?>"><?= e($txt) ?></a></li>
            <?php endforeach; ?>
            <li class="pt-2"><a href="<?= e(wa_link('Hola Jflores@, quiero cotizar una reparación.')) ?>" target="_blank" rel="noopener noreferrer" class="btn-primary w-full">Cotizar por WhatsApp</a></li>
        </ul>
    </nav>
</header>
<main id="contenido">
