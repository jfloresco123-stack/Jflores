</main>

<footer class="no-print bg-ink text-slate-300">
    <div class="container-x grid gap-10 py-14 sm:grid-cols-2 lg:grid-cols-4">
        <div>
            <a href="index.php#inicio" class="inline-block" aria-label="Jflores@ – Inicio"><?= logo('h-14', 'oscuro') ?></a>
            <p class="mt-5 text-sm leading-relaxed text-slate-400">Servicio técnico de celulares, computadoras y electrónica. Microsoldadura profesional con diagnóstico documentado y garantía escrita.</p>
            <div class="mt-5 flex gap-3">
                <?php foreach (['facebook' => 'Facebook', 'tiktok' => 'TikTok', 'linkedin' => 'LinkedIn'] as $red => $nombre): ?>
                    <a href="<?= e(cfg('redes')[$red]) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= e($nombre) ?>"
                       class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition hover:bg-primary hover:text-white"><?= icon_social($red) ?></a>
                <?php endforeach; ?>
                <a href="<?= e(wa_link()) ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"
                   class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition hover:bg-[#25D366] hover:text-white"><?= icon_social('whatsapp') ?></a>
            </div>
        </div>

        <div>
            <h2 class="font-display text-base font-semibold text-white">Reparaciones</h2>
            <ul class="mt-4 space-y-2 text-sm">
                <li><a class="hover:text-primary" href="index.php#servicios">Celulares y tablets</a></li>
                <li><a class="hover:text-primary" href="index.php#servicios">Laptops y computadoras</a></li>
                <li><a class="hover:text-primary" href="index.php#servicios">Televisores</a></li>
                <li><a class="hover:text-primary" href="index.php#servicios">Impresoras e impresoras 3D</a></li>
                <li><a class="hover:text-primary" href="index.php#servicios">Microsoldadura</a></li>
            </ul>
        </div>

        <div>
            <h2 class="font-display text-base font-semibold text-white">Información</h2>
            <ul class="mt-4 space-y-2 text-sm">
                <li><a class="hover:text-primary" href="index.php#planes">Planes de mantenimiento</a></li>
                <li><a class="hover:text-primary" href="index.php#consulta">Consultar mi equipo</a></li>
                <li><a class="hover:text-primary" href="index.php#faq">Preguntas frecuentes</a></li>
                <li><a class="hover:text-primary" href="terminos.php">Términos y garantía</a></li>
                <li><a class="hover:text-primary" href="privacidad.php">Política de privacidad</a></li>
            </ul>
        </div>

        <div>
            <h2 class="font-display text-base font-semibold text-white">Contacto</h2>
            <ul class="mt-4 space-y-3 text-sm">
                <li class="flex items-start gap-2"><?= icon_social('whatsapp', 'mt-0.5 h-4 w-4 text-primary') ?><a class="hover:text-primary" href="<?= e(wa_link()) ?>" target="_blank" rel="noopener noreferrer"><?= e(cfg('telefono')) ?></a></li>
                <li class="flex items-start gap-2"><?= icon('mail', 'mt-0.5 h-4 w-4 text-primary') ?><a class="break-all hover:text-primary" href="mailto:<?= e(cfg('correo')) ?>"><?= e(cfg('correo')) ?></a></li>
                <li class="flex items-start gap-2"><?= icon('clock', 'mt-0.5 h-4 w-4 text-primary') ?><?= e(cfg('horario')) ?></li>
                <li class="flex items-start gap-2"><?= icon('pin', 'mt-0.5 h-4 w-4 text-primary') ?><?= e(cfg('ubicacion')) ?></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-white/10">
        <div class="container-x flex flex-col items-center justify-between gap-2 py-5 text-xs text-slate-400 sm:flex-row">
            <p>© <?= date('Y') ?> Jflores@ · Servicio Técnico y Microsoldadura. Todos los derechos reservados.</p>
            <p>Las marcas mostradas pertenecen a sus respectivos dueños.</p>
        </div>
    </div>
</footer>

<a href="<?= e(wa_link('Hola Jflores@, quiero solicitar un diagnóstico.')) ?>" target="_blank" rel="noopener noreferrer"
   class="wa-float no-print" aria-label="Escríbenos por WhatsApp"><?= icon_social('whatsapp', 'h-7 w-7') ?></a>
</body>
</html>
