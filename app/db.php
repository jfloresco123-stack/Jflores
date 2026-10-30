<?php
declare(strict_types=1);

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $ruta = cfg('db_path');
    $nueva = !is_file($ruta);
    if (!is_dir(dirname($ruta))) {
        mkdir(dirname($ruta), 0750, true);
    }

    $pdo = new PDO('sqlite:' . $ruta, null, null, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
    $pdo->exec('PRAGMA foreign_keys = ON');

    if ($nueva) {
        crear_esquema($pdo);
        sembrar_demo($pdo);
    }
    return $pdo;
}

function crear_esquema(PDO $pdo): void
{
    $pdo->exec(<<<SQL
    CREATE TABLE IF NOT EXISTS ordenes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        codigo TEXT NOT NULL UNIQUE,
        cliente TEXT NOT NULL,
        telefono TEXT NOT NULL,
        equipo TEXT NOT NULL,
        marca_modelo TEXT,
        serie TEXT,
        estado_estetico TEXT,
        accesorios TEXT,
        falla_reportada TEXT NOT NULL,
        estado TEXT NOT NULL DEFAULT 'Recibido',
        fecha_ingreso TEXT NOT NULL
    );
    CREATE TABLE IF NOT EXISTS informes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        orden_id INTEGER NOT NULL UNIQUE REFERENCES ordenes(id) ON DELETE CASCADE,
        diagnostico TEXT NOT NULL,
        trabajo_realizado TEXT NOT NULL,
        componentes TEXT,
        pruebas_qa TEXT,
        tecnico TEXT NOT NULL,
        fecha_entrega TEXT,
        garantia_dias INTEGER NOT NULL DEFAULT 90,
        recomendaciones TEXT
    );
    CREATE TABLE IF NOT EXISTS mensajes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nombre TEXT NOT NULL,
        tipo TEXT NOT NULL,
        correo TEXT NOT NULL,
        telefono TEXT,
        equipo TEXT,
        descripcion TEXT NOT NULL,
        creado TEXT NOT NULL
    );
    CREATE TABLE IF NOT EXISTS intentos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        ip TEXT NOT NULL,
        accion TEXT NOT NULL,
        creado INTEGER NOT NULL
    );
    CREATE INDEX IF NOT EXISTS idx_intentos ON intentos (ip, accion, creado);
    SQL);
}

function sembrar_demo(PDO $pdo): void
{
    $pdo->prepare('INSERT INTO ordenes (codigo, cliente, telefono, equipo, marca_modelo, serie, estado_estetico, accesorios, falla_reportada, estado, fecha_ingreso)
                   VALUES (?,?,?,?,?,?,?,?,?,?,?)')
        ->execute([
            'JF-2026-0001', 'Cliente Demo', '999990651', 'Laptop', 'ASUS VivoBook 15 X1502Z', 'N3NRCV05X123456',
            'Rayones leves en tapa, bisagra derecha floja', 'Cargador original 65W',
            'No enciende tras derrame de líquido. Equipo con información importante.', 'Entregado', '2026-10-14',
        ]);
    $id = (int) $pdo->lastInsertId();
    $pdo->prepare('INSERT INTO informes (orden_id, diagnostico, trabajo_realizado, componentes, pruebas_qa, tecnico, fecha_entrega, garantia_dias, recomendaciones)
                   VALUES (?,?,?,?,?,?,?,?,?)')
        ->execute([
            $id,
            'Corrosión en zona de alimentación (PU8211). Cortocircuito en línea de 19V por MOSFET dañado. Disco SSD íntegro.',
            "Limpieza ultrasónica de placa.\nReemplazo de MOSFET de entrada por microsoldadura.\nRespaldo cifrado del SSD antes de la intervención.\nAjuste de bisagra derecha.",
            'MOSFET AON7408 (nuevo) · Pasta térmica',
            "Encendido y carga: OK\nPrueba de estrés CPU 30 min: OK (máx. 78 °C)\nTeclado, puertos USB, Wi-Fi y pantalla: OK\nSMART del SSD: OK",
            'J. Flores',
            '2026-10-18',
            90,
            "Evitar líquidos cerca del equipo.\nActivar BitLocker y copias de seguridad semanales.\nMantener Windows y antivirus actualizados.",
        ]);
}
