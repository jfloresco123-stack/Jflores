# Proceso de servicio técnico: jflores@

```
Recepción → Diagnóstico → Presupuesto → Reparación → Control de calidad → Entrega → Garantía
   │            │             │              │               │               │          │
 Orden de    Hallazgos     Aprobación     Bitácora       Checklist QA     Informe   Certificado
 Servicio                  del cliente                                    Técnico   de Garantía
```

## 1. Recepción (Orden de Servicio)
- Asignar el código `JF-AAAA-NNNN`, que es correlativo por año.
- Registrar:
  - Cliente.
  - Celular: se usa para verificar las consultas en línea.
  - Equipo, marca y modelo.
  - Número de serie o IMEI.
- Fotografiar el estado estético y anotar golpes, rayones y piezas faltantes.
- Anotar los accesorios entregados.
- Escribir la falla tal como la describe el cliente.
- Preguntar si el equipo tiene información importante y si autoriza un respaldo.
- Entregar al cliente el código y la URL de consulta.
- Plantilla: [plantillas/orden-servicio.md](plantillas/orden-servicio.md).

## 2. Diagnóstico
- Inspección visual con microscopio (corrosión, componentes quemados).
- Mediciones: consumo en la fuente regulada, líneas de voltaje y modo diodo.
- Software: SMART, memoria y escaneo de malware, si corresponde.
- Plazo objetivo: de 24 a 48 horas.

## 3. Presupuesto
- Enviar por WhatsApp el diagnóstico resumido, el costo, el plazo y los riesgos.
- **No se interviene sin la aprobación escrita del cliente.** Un mensaje de WhatsApp vale como aprobación.

## 4. Reparación
- Proteger con ESD y hacer un respaldo cifrado si hay datos y el cliente lo autorizó.
- Registrar los componentes sustituidos con su código o número de parte.

## 5. Control de calidad (QA)
Checklist mínimo según el tipo de equipo:

| Laptop / PC | Celular | Impresora | Red |
|---|---|---|---|
| Encendido y carga | Carga y consumo | Prueba de inyectores | Conexión WAN/LAN |
| Estrés 30 min + temperatura | Táctil y pantalla | Alimentación del papel | Wi-Fi: cobertura y velocidad |
| Teclado, puertos y Wi-Fi | Cámaras, micrófono y altavoz | Escaneo | Reglas del firewall |
| SMART del disco | Señal, Wi-Fi y Bluetooth | Prueba de color | VPN funcionando |

## 6. Entrega (Informe Técnico)
- Completar el informe: diagnóstico, trabajo realizado, componentes y resultados del QA.
- Explicar el trabajo al cliente y borrar de forma segura los respaldos temporales.
- Plantilla: [plantillas/informe-tecnico.md](plantillas/informe-tecnico.md).

## 7. Garantía
- Por defecto: 90 días de mano de obra. Los repuestos tienen la garantía del fabricante.
- Agregar recomendaciones de seguridad personalizadas.
- Plantilla: [plantillas/garantia.md](plantillas/garantia.md).

## Estados de la orden
`Recibido` → `En diagnóstico` → `Esperando aprobación` → `En reparación` → `Control de calidad` → `Listo para entrega` → `Entregado`
(otros estados posibles: `No aprobado` y `Sin reparación posible`)
