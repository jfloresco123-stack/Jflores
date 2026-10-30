<?php
// Iconos SVG en línea (trazos estilo Heroicons/Lucide) para no depender de CDNs.
function icon(string $nombre, string $clase = 'h-6 w-6'): string
{
    static $p = [
        'chip'     => '<rect x="7" y="7" width="10" height="10" rx="1"/><path d="M9 2v3M15 2v3M9 19v3M15 19v3M2 9h3M2 15h3M19 9h3M19 15h3"/>',
        'shield'   => '<path d="M12 3l8 3v6c0 4.5-3.4 8.3-8 9-4.6-.7-8-4.5-8-9V6l8-3z"/><path d="M9 12l2 2 4-4"/>',
        'network'  => '<rect x="9" y="2" width="6" height="5" rx="1"/><rect x="2" y="17" width="6" height="5" rx="1"/><rect x="16" y="17" width="6" height="5" rx="1"/><path d="M12 7v5M5 17v-2h14v2"/>',
        'phone'    => '<rect x="7" y="2" width="10" height="20" rx="2"/><path d="M11 18h2"/>',
        'laptop'   => '<rect x="4" y="5" width="16" height="11" rx="1"/><path d="M2 19h20"/>',
        'tv'       => '<rect x="2" y="5" width="20" height="13" rx="2"/><path d="M8 21h8M12 18v3"/>',
        'printer'  => '<path d="M6 9V3h12v6"/><rect x="3" y="9" width="18" height="8" rx="2"/><path d="M6 14h12v7H6z"/>',
        'cube'     => '<path d="M12 2l9 5v10l-9 5-9-5V7l9-5z"/><path d="M3 7l9 5 9-5M12 12v10"/>',
        'board'    => '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8" cy="9" r="1.5"/><path d="M13 8h5M13 12h5M7 15h10"/>',
        'speaker'  => '<rect x="5" y="2" width="14" height="20" rx="2"/><circle cx="12" cy="14" r="4"/><circle cx="12" cy="6" r="1"/>',
        'windows'  => '<path d="M3 5.5l7.5-1v7H3zM12 4.3L21 3v8.5h-9zM3 12.5h7.5v7L3 18.5zM12 12.5h9V21l-9-1.3z"/>',
        'lock'     => '<rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 018 0v3"/>',
        'scale'    => '<path d="M12 3v18M5 7h14M5 7l-3 7a3 3 0 006 0L5 7zM19 7l-3 7a3 3 0 006 0l-3-7zM8 21h8"/>',
        'check'    => '<circle cx="12" cy="12" r="9"/><path d="M8 12l3 3 5-6"/>',
        'eraser'   => '<path d="M20 20H8l-5-5 10-10 8 8-6 6"/><path d="M7 11l6 6"/>',
        'doc'      => '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M8 13h8M8 17h5"/>',
        'clipboard'=> '<rect x="5" y="4" width="14" height="18" rx="2"/><path d="M9 2h6v4H9zM9 12h6M9 16h4"/>',
        'award'    => '<circle cx="12" cy="9" r="6"/><path d="M8.5 14L7 22l5-3 5 3-1.5-8"/>',
        'search'   => '<circle cx="11" cy="11" r="7"/><path d="M21 21l-5-5"/>',
        'wrench'   => '<path d="M14.7 6.3a4 4 0 00-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 005.4-5.4l-2.6 2.6-2.4-.6-.6-2.4z"/>',
        'truck'    => '<path d="M3 6h11v10H3zM14 10h4l3 3v3h-7z"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/>',
        'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'mail'     => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
        'pin'      => '<path d="M12 22s7-6.3 7-12a7 7 0 00-14 0c0 5.7 7 12 7 12z"/><circle cx="12" cy="10" r="2.5"/>',
        'menu'     => '<path d="M4 6h16M4 12h16M4 18h16"/>',
        'x'        => '<path d="M6 6l12 12M18 6L6 18"/>',
        'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'print'    => '<path d="M6 9V3h12v6M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><path d="M6 14h12v8H6z"/>',
        'database' => '<ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v14c0 1.7 3.6 3 8 3s8-1.3 8-3V5M4 12c0 1.7 3.6 3 8 3s8-1.3 8-3"/>',
        'drop'     => '<path d="M12 3s6 6.5 6 11a6 6 0 01-12 0c0-4.5 6-11 6-11z"/>',
        'battery'  => '<rect x="2" y="7" width="17" height="10" rx="2"/><path d="M22 11v2M6 10v4M10 10v4"/>',
        'plug'     => '<path d="M9 2v6M15 2v6M6 8h12v4a6 6 0 01-12 0V8zM12 18v4"/>',
        'gauge'    => '<path d="M12 14l4-4"/><path d="M3.3 17a9 9 0 1117.4 0"/>',
        'star'     => '<path d="M12 3l2.8 5.7 6.2.9-4.5 4.4 1 6.2L12 17.3 6.5 20.2l1-6.2L3 9.6l6.2-.9z"/>',
        'cpu'      => '<rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M1 9h3M1 15h3M20 9h3M20 15h3"/>',
        'refresh'  => '<path d="M21 12a9 9 0 01-15.5 6.2L3 16M3 12a9 9 0 0115.5-6.2L21 8"/><path d="M21 3v5h-5M3 21v-5h5"/>',
        'sun'      => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
        'moon'     => '<path d="M21 12.8A9 9 0 1111.2 3a7 7 0 009.8 9.8z"/>',
        'users'    => '<circle cx="9" cy="8" r="4"/><path d="M2 21a7 7 0 0114 0M16 4a4 4 0 010 8M22 21a7 7 0 00-4-6.3"/>',
        'bug'      => '<rect x="8" y="6" width="8" height="14" rx="4"/><path d="M12 6V3M8 10H4M20 10h-4M8 15H4M20 15h-4M9 3l1.5 3M15 3l-1.5 3"/>',
    ];
    $svg = $p[$nombre] ?? $p['check'];
    return '<svg class="' . $clase . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $svg . '</svg>';
}

/** Iconos de marca rellenos (redes). */
function icon_social(string $red, string $clase = 'h-5 w-5'): string
{
    $p = [
        'whatsapp' => 'M12 2a10 10 0 00-8.6 15.1L2 22l5-1.3A10 10 0 1012 2zm0 18.2c-1.5 0-3-.4-4.3-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1112 20.2zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 01-3.3-2.9c-.3-.4.3-.4.7-1.4.1-.2 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 00-.7.3 3 3 0 00-.9 2.2c0 1.3.9 2.5 1 2.7.1.2 1.8 2.8 4.4 3.9 1.6.7 2.3.8 3.1.6.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.2-1.2-.1-.1-.3-.2-.5-.3z',
        'facebook' => 'M14 8h3V4h-3a5 5 0 00-5 5v2H7v4h2v7h4v-7h3l1-4h-4V9a1 1 0 011-1z',
        'tiktok'   => 'M16.5 3a4.5 4.5 0 004 4v3.2a7.6 7.6 0 01-4-1.2v6.5A6 6 0 1110.5 9.6v3.3a2.8 2.8 0 102 2.6V3h4z',
        'linkedin' => 'M4.5 3a2 2 0 110 4 2 2 0 010-4zM3 9h3v12H3zM9 9h3v1.7A3.8 3.8 0 0115.4 9C19 9 20 11 20 14.6V21h-3v-5.7c0-1.5-.3-3-2.1-3-1.9 0-2.4 1.4-2.4 3V21H9z',
    ];
    return '<svg class="' . $clase . '" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="' . ($p[$red] ?? '') . '"/></svg>';
}

/**
 * Logo oficial de Jflores@ (imagen original con fondo transparente).
 * $fondo: "auto" = cambia con el modo claro/oscuro, "oscuro" = siempre la versión para fondos oscuros (footer), "claro" = siempre la versión clara (documentos).
 */
function logo(string $clase = "h-12", string $fondo = "auto"): string
{
    $alt = "Jflores@ · Servicio Técnico, Microsoldadura";
    $img = fn (string $archivo, string $extra) => "<img src=\"assets/img/$archivo\" alt=\"$alt\" width=\"560\" height=\"155\" class=\"w-auto $clase $extra\">";
    if ($fondo !== "auto") {
        return $img($fondo === "oscuro" ? "logo-oscuro.png" : "logo-color.png", "");
    }
    return $img("logo-color.png", "dark:hidden") . $img("logo-oscuro.png", "hidden dark:block");
}
