<?php
declare(strict_types=1);
require __DIR__ . '/../app/bootstrap.php';
$titulo = 'Política de Privacidad · Jflores@';
require __DIR__ . '/../app/partials/header.php';
?>
<section class="section">
    <div class="container-x max-w-3xl">
        <p class="eyebrow">Legal</p>
        <h1 class="h2">Política de Privacidad</h1>
        
        <div class="prose-legal">
            <p>Última actualización: <?= date('Y') ?>. En Jflores@ tratamos tus datos personales conforme a la <strong>Ley N.° 29733, Ley de Protección de Datos Personales</strong> del Perú y su Reglamento (D.S. N.° 016-2024-JUS). Para clientes de la Unión Europea, seguimos además los principios del RGPD.</p>

            <h2>1. Responsable del tratamiento</h2>
            <p>jflores@ · Servicio técnico y microsoldadura. Correo: <?= e(cfg('correo')) ?> · WhatsApp: <?= e(cfg('telefono')) ?>.</p>

            <h2>2. Datos que recopilamos</h2>
            <ul>
                <li>Identificación y contacto: nombre, correo, celular, tipo de cliente.</li>
                <li>Datos del equipo: marca, modelo, número de serie, estado y falla reportada.</li>
                <li>Datos técnicos mínimos de navegación (dirección IP cifrada con hash) para seguridad y prevención de abuso.</li>
            </ul>

            <h2>3. Finalidad</h2>
            <ul>
                <li>Atender tu solicitud, diagnosticar y reparar tu equipo.</li>
                <li>Emitir la Orden de Servicio, el Informe Técnico y la Garantía.</li>
                <li>Contactarte sobre el estado de tu servicio.</li>
            </ul>
            <p>No vendemos ni cedemos tus datos a terceros, ni los usamos para publicidad sin tu consentimiento expreso.</p>

            <h2>4. Archivos y contenido de tus dispositivos</h2>
            <p>No accedemos a fotos, documentos ni cuentas salvo que sea imprescindible para el servicio y con tu autorización. Si realizamos un respaldo, se guarda cifrado y se elimina de forma segura al entregar el equipo o en un plazo máximo de 15 días.</p>

            <h2>5. Conservación</h2>
            <p>Los registros de servicio se conservan durante la vigencia de la garantía y hasta 2 años para fines de soporte, salvo obligación legal distinta. Los mensajes de contacto sin servicio asociado se eliminan a los 6 meses.</p>

            <h2>6. Seguridad</h2>
            <p>Aplicamos cifrado TLS, control de acceso, protección contra ataques automatizados y copias de seguridad. </p>

            <h2>7. Tus derechos (ARCO)</h2>
            <p>Puedes ejercer tus derechos de Acceso, Rectificación, Cancelación y Oposición escribiendo a <?= e(cfg('correo')) ?>. Responderemos en los plazos que establece la ley. También puedes acudir a la Autoridad Nacional de Protección de Datos Personales.</p>
        </div>
    </div>
</section>
<?php require __DIR__ . '/../app/partials/footer.php'; ?>
