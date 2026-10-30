<?php
// Datos del negocio. Reemplaza los enlaces marcados con "TODO".
return [
    'marca'       => 'jflores@',
    'eslogan'     => 'Servicio Técnico y Microsoldadura',
    'correo'      => 'jflorestecnico2025@gmail.com',
    'whatsapp'    => '51949730651',          // formato internacional sin "+"
    'telefono'    => '949 730 651',
    'horario'     => 'Lun – Sáb · 8:30 a. m. – 8:00 p. m.',
    'ubicacion'   => 'Lima, Perú',
    'redes' => [
        'facebook' => 'https://www.facebook.com/',   // TODO: URL real
        'tiktok'   => 'https://www.tiktok.com/',     // TODO: URL real
        'linkedin' => 'https://www.linkedin.com/',   // TODO: URL real
    ],
    'db_path'     => __DIR__ . '/data/jflores.sqlite',
    // Límite anti fuerza bruta: N intentos por ventana (segundos).
    'rate_limit'  => ['max' => 5, 'ventana' => 900],
    // Pon en false solo para pruebas locales sin HTTPS.
    'forzar_https' => true,
];
