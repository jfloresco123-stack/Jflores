<?php
// Genera la versión estática de la web en site/ para publicarla en GitHub Pages.
// Uso: php scripts/build-estatico.php
// Las páginas PHP se renderizan a HTML; los formularios pasan a enviarse por WhatsApp.
declare(strict_types=1);

define('ESTATICO', true);
$_SERVER['HTTPS'] = 'on';   // evita la redirección a HTTPS de bootstrap.php

$raiz    = dirname(__DIR__);
$publico = $raiz . '/public';
$destino = $raiz . '/site';

// Páginas que no necesitan servidor (consulta.php, contacto.php y documento.php sí lo necesitan).
$paginas = ['index', 'privacidad', 'terminos'];

function borrar_dir(string $dir): void
{
    if (!is_dir($dir)) {
        return;
    }
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($it as $f) {
        $f->isDir() ? rmdir($f->getPathname()) : unlink($f->getPathname());
    }
    rmdir($dir);
}

function copiar_dir(string $origen, string $dest): void
{
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($origen, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($it as $f) {
        $ruta = $dest . '/' . substr($f->getPathname(), strlen($origen) + 1);
        $f->isDir() ? @mkdir($ruta, 0755, true) : copy($f->getPathname(), $ruta);
    }
}

// Modo hijo: renderiza una sola página a la salida estándar.
if (isset($argv[1])) {
    require "$publico/{$argv[1]}.php";
    exit;
}

/** Renderiza cada página en un proceso propio (bootstrap.php declara funciones y no admite cargarse dos veces). */
function renderizar(string $pagina): string
{
    $ini = php_ini_loaded_file();
    $cmd = escapeshellarg(PHP_BINARY) . ($ini ? ' -c ' . escapeshellarg($ini) : '')
         . ' ' . escapeshellarg(__FILE__) . ' ' . escapeshellarg($pagina);
    exec($cmd, $salida, $codigo);
    if ($codigo !== 0) {
        fwrite(STDERR, "Error al generar $pagina\n" . implode("\n", $salida) . "\n");
        exit(1);
    }
    return implode("\n", $salida) . "\n";
}

borrar_dir($destino);
mkdir($destino, 0755, true);
copiar_dir($publico . '/assets', $destino . '/assets');
touch($destino . '/.nojekyll');

foreach ($paginas as $p) {
    $html = renderizar($p);
    // Enlaces internos: pagina.php -> pagina.html
    $html = preg_replace('/(href|action)="(index|privacidad|terminos|consulta|contacto)\.php/', '$1="$2.html', $html);
    file_put_contents("$destino/$p.html", $html);
    echo "site/$p.html\n";
}
echo "Listo: versión estática en site/\n";
