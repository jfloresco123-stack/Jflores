<?php
declare(strict_types=1);
require __DIR__ . '/../app/bootstrap.php';
$titulo = 'Términos de Servicio · Jflores@';
require __DIR__ . '/../app/partials/header.php';
?>
<section class="section">
    <div class="container-x max-w-3xl">
        <p class="eyebrow">Legal</p>
        <h1 class="h2">Términos de Servicio</h1>
        
        <div class="prose-legal">
            <h2>1. Recepción y diagnóstico</h2>
            <p>Todo equipo se registra en una Orden de Servicio con su número de serie, estado estético, accesorios y falla reportada. El cliente recibe un código para consultar el estado en línea.</p>

            <h2>2. Presupuesto y autorización</h2>
            <p>Ninguna reparación se realiza sin la aprobación del presupuesto por parte del cliente. Si el cliente no aprueba la reparación, solo se cobra el diagnóstico.</p>

            <h2>3. Riesgos propios de la reparación</h2>
            <p>Los equipos con daño por líquido, golpes severos o intervenciones previas pueden presentar fallas adicionales durante o después de la reparación. Estos casos se informan antes de intervenir.</p>

            <h2>4. Respaldo de información</h2>
            <p>Se recomienda al cliente respaldar su información antes de entregar el equipo. Cuando el servicio lo requiera, jflores@ podrá realizar un respaldo cifrado con autorización del cliente.</p>

            <h2 id="garantia">5. Garantía</h2>
            <ul>
                <li>La mano de obra tiene garantía escrita (habitualmente 90 días) desde la fecha de entrega. El plazo exacto figura en el Certificado de Garantía.</li>
                <li>Los repuestos tienen la garantía que indique su fabricante o proveedor.</li>
                <li>La garantía no cubre golpes, humedad, manipulación por terceros, variaciones eléctricas ni fallas distintas a la reparada.</li>
                <li>Para hacerla efectiva, presenta el código de tu orden.</li>
            </ul>

            <h2>6. Equipos no recogidos</h2>
            <p>Transcurridos 90 días desde el aviso de entrega sin que el cliente recoja el equipo, se aplicará un costo de almacenamiento, y el equipo podrá disponerse conforme a la normativa vigente previa comunicación al cliente.</p>

            <h2>7. Libro de reclamaciones</h2>
            <p>Conforme al Código de Protección y Defensa del Consumidor, puedes registrar un reclamo o queja escribiendo a <?= e(cfg('correo')) ?>.</p>
        </div>
    </div>
</section>
<?php require __DIR__ . '/../app/partials/footer.php'; ?>
