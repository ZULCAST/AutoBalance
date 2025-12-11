<?php
require_once __DIR__ . "/../config/db.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT id, nombre, descripcion, precio_desde FROM servicios WHERE id = :id AND activo = 1");
$stmt->execute([':id' => $id]);
$servicio = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$servicio) {
    http_response_code(404);
}

/**
 * Texto dinámico: ¿cuándo es necesario este servicio?
 */
function textoCuandoEsNecesario($nombreServicio)
{
    $n = mb_strtolower($nombreServicio ?? '', 'UTF-8');

    // CAMBIO DE ACEITE
    if (strpos($n, 'aceite') !== false) {
        return 'El aceite es la “sangre” del motor. Si pasa demasiado tiempo sin cambiarse, pierde sus propiedades, el motor trabaja forzado y aumenta el riesgo de daños costosos. '
            . 'Es momento de hacer el servicio si ya recorriste entre 5,000 y 10,000 km, si el aceite se ve muy oscuro, si el motor suena más de lo normal o si no recuerdas cuándo fue tu último cambio.';
    }

    // FRENOS / BALATAS / DISCOS
    if (strpos($n, 'freno') !== false || strpos($n, 'balata') !== false || strpos($n, 'pastilla') !== false) {
        return 'Los frenos son el sistema de seguridad más importante del vehículo. Si escuchas rechinidos, el pedal se siente muy duro o muy suave, '
            . 'el coche tarda más en detenerse o vibra al frenar, es una señal clara de que necesitas servicio. '
            . 'No esperes a que falle en una emergencia: una revisión a tiempo puede evitar accidentes y reparaciones mucho más caras.';
    }

    // ALINEACIÓN
    if (strpos($n, 'alineación') !== false || strpos($n, 'alineacion') !== false) {
        return 'Si el volante no está centrado, el coche se jala hacia un lado o sientes que no va derecho, es momento de revisar la alineación. '
            . 'Una mala alineación desgasta las llantas de forma dispareja, aumenta el consumo de combustible y hace que el manejo sea incómodo. '
            . 'Se recomienda alinear después de golpear un bache fuerte, cambiar llantas o cada 10,000 km.';
    }

    // BALANCEO
    if (strpos($n, 'balanceo') !== false || strpos($n, 'balancear') !== false) {
        return 'Si sientes vibraciones en el volante o en el asiento a ciertas velocidades, es probable que las llantas estén desbalanceadas. '
            . 'Un mal balanceo provoca desgaste irregular, afecta la suspensión y hace que el viaje sea incómodo. '
            . 'Se recomienda balancear cada vez que cambies o rotes las llantas, o cuando notes vibraciones.';
    }

    // VENTA DE LLANTAS
    if (strpos($n, 'llanta') !== false || strpos($n, 'llantas') !== false) {
        return 'Las llantas son el único punto de contacto entre tu vehículo y el camino. Si el dibujo está muy gastado, tienen grietas o ya tienen más de 5 años, es momento de cambiarlas. '
            . 'Unas llantas en mal estado aumentan la distancia de frenado, reducen el agarre en lluvia y ponen en riesgo tu seguridad. '
            . 'Te ayudamos a elegir las llantas ideales para tu vehículo y estilo de manejo.';
    }

    // SUSPENSIÓN / AMORTIGUADORES
    if (
        strpos($n, 'suspensión') !== false || strpos($n, 'suspension') !== false ||
        strpos($n, 'amortiguador') !== false
    ) {
        return 'Si el coche “brinca” demasiado, se hunde al frenar, suena metálico al pasar topes o lo sientes inestable en curvas, es probable que la suspensión ya no esté en buen estado. '
            . 'Una suspensión desgastada no solo afecta la comodidad: también reduce el agarre y alarga la distancia de frenado. '
            . 'Revisarla a tiempo te da un manejo más seguro y evita que otras piezas se dañen.';
    }

    // BANDA DE TIEMPO / BANDAS
    if (
        strpos($n, 'banda de tiempo') !== false || strpos($n, 'banda') !== false ||
        strpos($n, 'correa') !== false
    ) {
        return 'La banda de tiempo y las demás bandas del motor tienen una vida útil limitada. '
            . 'Cuando se resecan o agrietan, pueden romperse sin avisar y provocar daños muy graves al motor. '
            . 'Si ya tiene muchos kilómetros, presenta desgaste visible o no sabes cuándo se cambió, es mejor prevenir y reemplazarla antes de que falle.';
    }

    // BATERÍA
    if (strpos($n, 'batería') !== false || strpos($n, 'bateria') !== false) {
        return 'Una batería débil suele avisar: el auto tarda en arrancar, las luces se ven bajas o a veces no da marcha. '
            . 'Si tu batería tiene varios años o ya te ha dejado tirado alguna vez, es momento de revisarla y considerar el cambio. '
            . 'Un chequeo a tiempo te evita quedarte varado en el peor momento.';
    }

    // DIAGNÓSTICO POR ESCÁNER
    if (
        strpos($n, 'escáner') !== false || strpos($n, 'escaner') !== false ||
        strpos($n, 'diagnóstico') !== false || strpos($n, 'diagnostico') !== false
    ) {
        return 'Si se encendió el “check engine”, el coche perdió fuerza, presenta jaloneos o gasta más gasolina, '
            . 'un diagnóstico por escáner es la forma más rápida y precisa de saber qué está pasando. '
            . 'También es muy recomendable antes de un viaje largo o al comprar un auto usado, para asegurarte de que todo está en orden.';
    }

    // MANGUERAS
    if (strpos($n, 'manguera') !== false || strpos($n, 'mangueras') !== false) {
        return 'Las mangueras aguantan temperatura, presión y diferentes tipos de fluidos. Con el tiempo se resecan, se cuartean o empiezan a fugar. '
            . 'Si ves charcos debajo del coche, olor a anticongelante, el motor se calienta más de lo normal o notas alguna manguera hinchada, es momento de revisarlas. '
            . 'Cambiar una manguera a tiempo es mucho más barato que reparar un motor por sobrecalentamiento.';
    }

    // AFINACIÓN / SERVICIO MAYOR / MENOR / MANTENIMIENTO GENERAL
    if (
        strpos($n, 'afinación') !== false || strpos($n, 'afinacion') !== false ||
        strpos($n, 'servicio mayor') !== false || strpos($n, 'servicio menor') !== false ||
        strpos($n, 'mantenimiento') !== false
    ) {
        return 'Si tu coche gasta más gasolina, le falta fuerza, tarda en encender o se siente “pesado”, es probable que ya necesite una afinación. '
            . 'Este servicio pone al día filtros, bujías y puntos clave del motor para que vuelva a respirar y trabajar como debe. '
            . 'Un mantenimiento periódico evita fallas sorpresivas y alarga la vida del vehículo.';
    }

    // CLUTCH / EMBRAGUE
    if (strpos($n, 'clutch') !== false || strpos($n, 'embrague') !== false) {
        return 'Cuando aceleras y el motor revoluciona pero el coche no responde igual, las velocidades entran duras o escuchas ruidos al hacer los cambios, '
            . 'es señal de que el clutch está cerca del final de su vida útil. '
            . 'Atenderlo a tiempo evita que te quedes sin poder cambiar velocidades y te da una conducción mucho más suave.';
    }

    // INYECTORES / LIMPIEZA INYECTORES
    if (strpos($n, 'inyector') !== false || strpos($n, 'inyección') !== false || strpos($n, 'inyeccion') !== false) {
        return 'Inyectores sucios provocan tirones, pérdida de potencia, humo y mayor consumo de combustible. '
            . 'Si notas que tu coche ya no responde igual al acelerar o visitas más seguido la gasolinera, una limpieza de inyectores puede marcar la diferencia. '
            . 'Es un servicio clave para recuperar rendimiento y ahorrar gasolina.';
    }

    // AIRE ACONDICIONADO
    if (strpos($n, 'aire acondicionado') !== false || strpos($n, 'clima') !== false) {
        return 'Si el aire tarda mucho en enfriar, ya no enfría como antes o salen olores desagradables de las ventilas, '
            . 'es momento de revisar el sistema de aire acondicionado. '
            . 'Un mantenimiento adecuado mejora el confort, cuida el compresor y mantiene el interior del auto más limpio y saludable.';
    }

    // SERVICIO GENÉRICO (fallback)
    return 'Se recomienda programar este servicio cuando notes ruidos, vibraciones, luces de advertencia en el tablero o cualquier comportamiento extraño. '
        . 'Atenderlo a tiempo evita fallas más grandes y mantiene tu vehículo seguro y confiable.';
}

/**
 * Texto dinámico: ¿Qué incluye este servicio?
 */
function textoQueIncluye($nombreServicio)
{
    $n = mb_strtolower($nombreServicio ?? '', 'UTF-8');

    if (strpos($n, 'aceite') !== false) {
        return 'Cambio de aceite del motor según especificaciones, reemplazo de filtro de aceite, revisión visual de posibles fugas y chequeo rápido de niveles básicos (anticongelante, frenos, dirección, etc.).';
    }

    if (strpos($n, 'freno') !== false || strpos($n, 'balata') !== false || strpos($n, 'pastilla') !== false) {
        return 'Revisión de balatas, discos, limpieza de componentes, rectificado o cambio de piezas (según lo requiera el vehículo), purga de sistema si es necesario, y prueba de frenado.';
    }

    // ALINEACIÓN
    if (strpos($n, 'alineación') !== false || strpos($n, 'alineacion') !== false) {
        return 'Medición y ajuste de los ángulos de las ruedas (camber, caster, toe), centrado del volante, prueba de manejo para verificar que el vehículo vaya derecho y recomendaciones si se detectan piezas de suspensión dañadas.';
    }

    // BALANCEO
    if (strpos($n, 'balanceo') !== false) {
        return 'Desmontaje de llantas, balanceo en máquina computarizada, colocación de contrapesos donde se requiera, verificación de presión de aire y reinstalación correcta de las llantas.';
    }

    // VENTA DE LLANTAS
    if (strpos($n, 'llanta') !== false || strpos($n, 'llantas') !== false) {
        return 'Asesoría para elegir la llanta adecuada según tu vehículo y uso, instalación profesional, balanceo incluido, ajuste de presión y revisión de válvulas. Manejamos diversas marcas y medidas.';
    }

    if (
        strpos($n, 'suspensión') !== false || strpos($n, 'suspension') !== false ||
        strpos($n, 'amortiguador') !== false
    ) {
        return 'Inspección de amortiguadores, bujes, horquillas, rótulas y demás componentes de suspensión, así como reemplazo de piezas dañadas y prueba de manejo para verificar el comportamiento del vehículo.';
    }

    if (strpos($n, 'banda de tiempo') !== false || strpos($n, 'banda') !== false || strpos($n, 'correa') !== false) {
        return 'Reemplazo de la banda correspondiente, inspección de tensores y poleas, verificación de la correcta sincronización del motor y revisión de fugas o desgaste en la zona de trabajo.';
    }

    if (strpos($n, 'batería') !== false || strpos($n, 'bateria') !== false) {
        return 'Prueba de carga de la batería, revisión del sistema de arranque, alternador, limpieza de terminales, y en caso de reemplazo, instalación de una batería nueva correctamente calibrada.';
    }

    if (
        strpos($n, 'escáner') !== false || strpos($n, 'escaner') !== false ||
        strpos($n, 'diagnóstico') !== false || strpos($n, 'diagnostico') !== false
    ) {
        return 'Escaneo completo de la computadora del vehículo, lectura de códigos de falla, interpretación profesional de los resultados y explicación clara de las reparaciones o ajustes recomendados.';
    }

    if (strpos($n, 'manguera') !== false || strpos($n, 'mangueras') !== false) {
        return 'Revisión de mangueras de anticongelante, refrigerante, dirección y otros fluidos, detección de fugas, cambio de mangueras dañadas y prueba de presión y temperatura del sistema de enfriamiento.';
    }

    if (
        strpos($n, 'afinación') !== false || strpos($n, 'afinacion') !== false ||
        strpos($n, 'servicio mayor') !== false || strpos($n, 'servicio menor') !== false ||
        strpos($n, 'mantenimiento') !== false
    ) {
        return 'Cambio de bujías (según aplique), limpieza o cambio de filtros, revisión de sistema de encendido, alimentación, escaneo básico, y ajustes necesarios para que el motor recupere su buen desempeño.';
    }

    if (strpos($n, 'clutch') !== false || strpos($n, 'embrague') !== false) {
        return 'Revisión del sistema de clutch, cambio de disco, collarín o prensa según diagnóstico, purga del sistema hidráulico (si aplica) y prueba de manejo para verificar que los cambios entren suaves.';
    }

    if (strpos($n, 'inyector') !== false || strpos($n, 'inyección') !== false || strpos($n, 'inyeccion') !== false) {
        return 'Limpieza de inyectores con equipo especializado, revisión de presión de combustible, verificación de bujías y escaneo para comprobar que la mezcla de aire/combustible sea la adecuada.';
    }

    if (strpos($n, 'aire acondicionado') !== false || strpos($n, 'clima') !== false) {
        return 'Revisión de presión de gas, inspección de compresor, líneas y evaporador, búsqueda de fugas, limpieza de ductos y, en caso necesario, recarga de gas y cambio de filtro de cabina.';
    }

    // Genérico
    return 'Revisión detallada del sistema relacionado con este servicio, diagnóstico profesional, mano de obra especializada y recomendaciones de mantenimiento para que tu vehículo siga en óptimas condiciones.';
}

/**
 * Texto dinámico: Beneficios
 * Devuelve un arreglo de bullets para el <ul>
 */
function beneficiosServicio($nombreServicio)
{
    $n = mb_strtolower($nombreServicio ?? '', 'UTF-8');

    // ACEITE
    if (strpos($n, 'aceite') !== false) {
        return [
            'Proteges el motor y evitas desgaste prematuro.',
            'Mejoras el rendimiento y la respuesta del vehículo.',
            'Se reduce el riesgo de reparaciones costosas a futuro.'
        ];
    }

    // FRENOS
    if (strpos($n, 'freno') !== false || strpos($n, 'balata') !== false || strpos($n, 'pastilla') !== false) {
        return [
            'Mayor seguridad para ti y tus acompañantes.',
            'Menor distancia de frenado en situaciones de emergencia.',
            'Menos vibraciones, ruidos y sensación de inseguridad al frenar.'
        ];
    }

    // ALINEACIÓN
    if (strpos($n, 'alineación') !== false || strpos($n, 'alineacion') !== false) {
        return [
            'El vehículo va derecho y el volante queda centrado.',
            'Desgaste parejo de las llantas, lo que alarga su vida útil.',
            'Mejor control y seguridad al manejar.'
        ];
    }

    // BALANCEO
    if (strpos($n, 'balanceo') !== false) {
        return [
            'Eliminas vibraciones molestas en el volante y asiento.',
            'Proteges la suspensión y dirección del vehículo.',
            'Viajes más cómodos y suaves a cualquier velocidad.'
        ];
    }

    // VENTA DE LLANTAS
    if (strpos($n, 'llanta') !== false || strpos($n, 'llantas') !== false) {
        return [
            'Mayor agarre y seguridad en todo tipo de camino.',
            'Mejor frenado, especialmente en piso mojado.',
            'Llantas nuevas que cumplen con las especificaciones de tu vehículo.'
        ];
    }

    // SUSPENSIÓN
    if (
        strpos($n, 'suspensión') !== false || strpos($n, 'suspension') !== false ||
        strpos($n, 'amortiguador') !== false
    ) {
        return [
            'Manejo más suave y controlado, incluso en caminos malos.',
            'Mayor estabilidad al frenar y tomar curvas.',
            'Menos golpes y ruidos que dañan otras partes del coche.'
        ];
    }

    // BANDAS
    if (strpos($n, 'banda de tiempo') !== false || strpos($n, 'banda') !== false || strpos($n, 'correa') !== false) {
        return [
            'Previenes fallas graves y costosas en el motor.',
            'Te da tranquilidad al manejar en carretera o viajes largos.',
            'Mantienes la sincronización correcta del motor.'
        ];
    }

    // BATERÍA
    if (strpos($n, 'batería') !== false || strpos($n, 'bateria') !== false) {
        return [
            'Evitas quedarte varado por una batería descargada.',
            'Arranques más seguros y confiables todos los días.',
            'Mayor vida útil del sistema eléctrico del vehículo.'
        ];
    }

    // ESCÁNER / DIAGNÓSTICO
    if (
        strpos($n, 'escáner') !== false || strpos($n, 'escaner') !== false ||
        strpos($n, 'diagnóstico') !== false || strpos($n, 'diagnostico') !== false
    ) {
        return [
            'Sabes exactamente qué está fallando en tu auto.',
            'Evitas “adivinar” y cambiar piezas innecesarias.',
            'Tomas decisiones informadas sobre las reparaciones.'
        ];
    }

    // MANGUERAS
    if (strpos($n, 'manguera') !== false || strpos($n, 'mangueras') !== false) {
        return [
            'Previenes fugas que puedan provocar sobrecalentamiento.',
            'Proteges el motor y el sistema de enfriamiento.',
            'Tienes un vehículo más confiable para el día a día.'
        ];
    }

    // AFINACIÓN / SERVICIO
    if (
        strpos($n, 'afinación') !== false || strpos($n, 'afinacion') !== false ||
        strpos($n, 'servicio mayor') !== false || strpos($n, 'servicio menor') !== false ||
        strpos($n, 'mantenimiento') !== false
    ) {
        return [
            'Recuperas fuerza y respuesta del motor.',
            'Mejoras el rendimiento de combustible.',
            'Se reducen las posibilidades de fallas inesperadas.'
        ];
    }

    // CLUTCH
    if (strpos($n, 'clutch') !== false || strpos($n, 'embrague') !== false) {
        return [
            'Cambios de velocidad más suaves y precisos.',
            'Menor riesgo de quedarse sin clutch en el tráfico o en una subida.',
            'Mayor comodidad y control al manejar.'
        ];
    }

    // INYECTORES
    if (strpos($n, 'inyector') !== false || strpos($n, 'inyección') !== false || strpos($n, 'inyeccion') !== false) {
        return [
            'Mejor respuesta al acelerar.',
            'Ahorro de combustible al mejorar la combustión.',
            'Menos humo y emisiones contaminantes.'
        ];
    }

    // AIRE ACONDICIONADO
    if (strpos($n, 'aire acondicionado') !== false || strpos($n, 'clima') !== false) {
        return [
            'Mayor confort dentro del vehículo, especialmente en días calurosos.',
            'Aire más limpio y con menos olores dentro de la cabina.',
            'Proteges el compresor y prolongas la vida del sistema de A/C.'
        ];
    }

    // Genérico
    return [
        'Mayor seguridad y confianza al manejar.',
        'Reducción de fallas y reparaciones imprevistas.',
        'Conservas mejor el valor de tu vehículo a largo plazo.'
    ];
}

/**
 * Imagen / boceto por servicio, según el nombre
 */
function imagenServicio($nombreServicio)
{
    $n = mb_strtolower($nombreServicio ?? '', 'UTF-8');

    // Cambio de aceite
    if (strpos($n, 'aceite') !== false) {
        return 'img/servicio_aceite.png';
    }

    // Frenos
    if (strpos($n, 'freno') !== false || strpos($n, 'balata') !== false || strpos($n, 'disco') !== false) {
        return 'img/servicio_frenos.png';
    }

    // Alineación y balanceo
    if (
        strpos($n, 'alineación') !== false || strpos($n, 'alineacion') !== false ||
        strpos($n, 'balanceo') !== false
    ) {
        return 'img/servicio_alineacion.png';
    }

    // Suspensión / amortiguadores
    if (
        strpos($n, 'suspensión') !== false || strpos($n, 'suspension') !== false ||
        strpos($n, 'amortiguador') !== false
    ) {
        return 'img/servicio_suspension.png';
    }

    // Banda de tiempo / bandas / motor
    if (strpos($n, 'banda') !== false || strpos($n, 'correa') !== false || strpos($n, 'motor') !== false) {
        return 'img/servicio_motor.png';
    }

    // Batería
    if (strpos($n, 'batería') !== false || strpos($n, 'bateria') !== false) {
        return 'img/servicio_bateria.png';
    }

    // Aire acondicionado / clima
    if (strpos($n, 'aire acondicionado') !== false || strpos($n, 'clima') !== false) {
        return 'img/servicio_aire.png';
    }

    // Escáner / diagnóstico
    if (
        strpos($n, 'escáner') !== false || strpos($n, 'escaner') !== false ||
        strpos($n, 'diagnóstico') !== false || strpos($n, 'diagnostico') !== false
    ) {
        return 'img/servicio_escaner.png';
    }

    // Mangueras
    if (strpos($n, 'manguera') !== false || strpos($n, 'mangueras') !== false) {
        return 'img/servicio_mangueras.png';
    }

    // Llantas
    if (strpos($n, 'llanta') !== false || strpos($n, 'llantas') !== false) {
        return 'img/servicio_llantas.png';
    }

    // Afinación / servicio mayor / menor / mantenimiento
    if (
        strpos($n, 'afinación') !== false || strpos($n, 'afinacion') !== false ||
        strpos($n, 'mantenimiento') !== false || strpos($n, 'servicio') !== false
    ) {
        return 'img/servicio_afinacion.png';
    }

    // Clutch
    if (strpos($n, 'clutch') !== false || strpos($n, 'embrague') !== false) {
        return 'img/servicio_clutch.png';
    }

    // Genérico
    return 'img/servicio_generico.png';
}

$imgServicio = $servicio ? imagenServicio($servicio['nombre']) : null;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="icon" href="img/favicon.png" type="image/png">
    <meta charset="UTF-8">
    <title>
        <?php echo $servicio ? htmlspecialchars($servicio['nombre']) . ' | Autobalance' : 'Servicio no encontrado'; ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">

    <style>
        body {
            margin-top: 56px;
            background-color: var(--color-bg);
        }

        .servicio-wrapper {
            max-width: 1100px;
            margin: 0 auto 40px auto;
        }

        .servicio-card {
            background: var(--color-bg-light);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            padding: var(--spacing-xl);
            border: 1px solid var(--color-border-light);
        }

        @media (min-width: 992px) {
            .servicio-card {
                padding: var(--spacing-xxl);
            }
        }

        .servicio-hero {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        @media (min-width: 768px) {
            .servicio-hero {
                flex-direction: row;
                align-items: center;
                gap: 2rem;
            }
        }

        .servicio-hero-text {
            flex: 1 1 0;
        }

        .servicio-hero-img {
            flex: 0 0 280px;
            max-width: 320px;
            margin: 0 auto;
        }

        .servicio-hero-img-inner {
            background: linear-gradient(135deg, rgba(30, 58, 95, 0.05) 0%, rgba(255, 107, 53, 0.05) 100%);
            border-radius: var(--radius-lg);
            padding: 14px 14px 10px;
            border: 2px solid var(--color-border);
            box-shadow: var(--shadow-md);
        }

        .servicio-hero-img img {
            width: 100%;
            height: 210px;
            object-fit: contain;
            display: block;
        }

        .servicio-titulo {
            font-size: 2rem;
            font-weight: var(--font-weight-extrabold);
            margin-bottom: 0.25rem;
            color: var(--color-primary);
        }

        .servicio-precio {
            font-size: 1.25rem;
            color: var(--color-accent);
            font-weight: var(--font-weight-bold);
            margin-bottom: 1.5rem;
        }

        .servicio-card h4 {
            margin-top: 1.2rem;
            margin-bottom: .4rem;
            font-weight: var(--font-weight-bold);
            color: var(--color-primary);
            border-bottom: 2px solid var(--color-accent);
            padding-bottom: .5rem;
            display: inline-block;
        }

        .servicio-card ul {
            margin-top: .3rem;
            list-style: none;
            padding-left: 0;
        }

        .servicio-card li {
            margin-bottom: .5rem;
            padding-left: var(--spacing-lg);
            position: relative;
            color: var(--color-text);
        }

        .servicio-card p {
            text-align: justify;
        }

        .servicio-card li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--color-accent);
            font-weight: var(--font-weight-bold);
            font-size: 1.2rem;
        }

        .servicio-divider {
            border-top: 2px solid var(--color-border);
            margin: 1.3rem 0 1.1rem;
        }

        .servicio-actions {
            margin-top: 1.8rem;
        }

        .breadcrumb-servicio {
            font-size: 0.9rem;
            margin-bottom: 0.8rem;
            color: var(--color-text-light);
        }

        .breadcrumb-servicio a {
            text-decoration: none;
            color: var(--color-primary);
            font-weight: var(--font-weight-medium);
        }

        .breadcrumb-servicio a:hover {
            color: var(--color-accent);
        }

        .badge-servicio {
            display: inline-block;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: .3rem .8rem;
            border-radius: var(--radius-full);
            background: linear-gradient(135deg, rgba(30, 58, 95, 0.1), rgba(255, 107, 53, 0.1));
            color: var(--color-primary);
            margin-bottom: .4rem;
            font-weight: var(--font-weight-semibold);
            border: 1px solid rgba(30, 58, 95, 0.2);
        }

        /* ===================== */
        /* ANIMACIONES */
        /* ===================== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .servicio-card {
            opacity: 0;
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .breadcrumb-servicio {
            animation: fadeIn 0.5s ease-out 0.2s both;
        }

        .badge-servicio {
            animation: scaleIn 0.5s ease-out 0.4s both;
        }

        .servicio-titulo {
            animation: fadeInUp 0.8s ease-out 0.6s both;
        }

        .servicio-precio {
            animation: fadeInUp 0.8s ease-out 0.8s both;
        }

        .servicio-hero-text p {
            animation: fadeInUp 0.8s ease-out 1s both;
        }

        .servicio-hero-img {
            animation: slideInRight 0.8s ease-out 0.6s both;
        }

        .servicio-hero-img img {
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .servicio-card:hover .servicio-hero-img img {
            transform: scale(1.05) rotate(2deg);
        }

        .servicio-card h4 {
            animation: fadeInUp 0.6s ease-out both;
        }

        .servicio-card h4:nth-of-type(1) {
            animation-delay: 1.2s;
        }

        .servicio-card h4:nth-of-type(2) {
            animation-delay: 1.4s;
        }

        .servicio-card h4:nth-of-type(3) {
            animation-delay: 1.6s;
        }

        .servicio-card p {
            animation: fadeInUp 0.6s ease-out both;
        }

        .servicio-card p:nth-of-type(1) {
            animation-delay: 1.3s;
        }

        .servicio-card p:nth-of-type(2) {
            animation-delay: 1.5s;
        }

        .servicio-card p:nth-of-type(3) {
            animation-delay: 1.7s;
        }

        .servicio-card li {
            opacity: 0;
            transform: translateX(-20px);
            transition: all 0.4s ease;
        }

        .servicio-card.animate li {
            opacity: 1;
            transform: translateX(0);
        }

        .servicio-card li:nth-child(1) {
            transition-delay: 1.4s;
        }

        .servicio-card li:nth-child(2) {
            transition-delay: 1.5s;
        }

        .servicio-card li:nth-child(3) {
            transition-delay: 1.6s;
        }

        .servicio-actions {
            animation: fadeInUp 0.8s ease-out 1.8s both;
        }

        .servicio-actions .btn {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .servicio-actions .btn:hover {
            transform: translateY(-3px);
        }

        img {
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        img.loaded {
            opacity: 1;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
                <img src="img/logo.png" alt="Logo AutoBalance" style="height: 40px; width: auto; margin-right: 10px;">
                Autobalance
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="servicios.php">Servicios</a></li>
                    <li class="nav-item"><a class="nav-link" href="productos.php">Productos</a></li>
                    <li class="nav-item"><a class="nav-link" href="nosotros.php">Nosotros</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#contacto">Contacto</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="servicio-wrapper">
        <?php if (!$servicio): ?>
            <div class="servicio-card mt-4">
                <h1 class="servicio-titulo">Servicio no encontrado</h1>
                <p class="mb-3">El servicio que estás buscando no existe o no está disponible en este momento.</p>
                <a href="index.php" class="btn btn-primary">Volver a servicios</a>
            </div>
        <?php else: ?>

            <div class="servicio-card mt-4">

                <div class="breadcrumb-servicio">
                    <a href="servicios.php">Servicios</a> ·
                    <span><?php echo htmlspecialchars($servicio['nombre']); ?></span>
                </div>

                <div class="servicio-hero">
                    <div class="servicio-hero-text">
                        <span class="badge-servicio">Servicio Autobalance</span>
                        <h1 class="servicio-titulo">
                            <?php echo htmlspecialchars($servicio['nombre']); ?>
                        </h1>

                        <?php if (!is_null($servicio['precio_desde'])): ?>
                            <div class="servicio-precio">
                                Desde $<?php echo number_format($servicio['precio_desde'], 2); ?> MXN
                            </div>
                        <?php endif; ?>

                        <p><?php echo nl2br(htmlspecialchars($servicio['descripcion'])); ?></p>
                    </div>

                    <div class="servicio-hero-img">
                        <div class="servicio-hero-img-inner">
                            <img src="<?php echo htmlspecialchars($imgServicio); ?>"
                                alt="Servicio <?php echo htmlspecialchars($servicio['nombre']); ?>">
                        </div>
                    </div>
                </div>

                <div class="servicio-divider"></div>

                <h4>¿Qué incluye este servicio?</h4>
                <p>
                    <?php echo nl2br(htmlspecialchars(textoQueIncluye($servicio['nombre']))); ?>
                </p>

                <h4>Beneficios</h4>
                <ul>
                    <?php foreach (beneficiosServicio($servicio['nombre']) as $beneficio): ?>
                        <li><?php echo htmlspecialchars($beneficio); ?></li>
                    <?php endforeach; ?>
                </ul>

                <h4>¿Cuándo es necesario este servicio?</h4>
                <p>
                    <?php echo nl2br(htmlspecialchars(textoCuandoEsNecesario($servicio['nombre']))); ?>
                </p>

                <div class="servicio-actions">
                    <a href="servicios.php" class="btn btn-secondary me-2">Volver a la lista de servicios</a>
                    <a href="index.php#contacto" class="btn btn-primary">Agendar este servicio</a>
                </div>

            </div>

        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Hacer la navbar más opaca al hacer scroll
        window.addEventListener('scroll', function () {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Intersection Observer para animaciones de lista
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (entry.target.classList.contains('servicio-card')) {
                        entry.target.classList.add('animate');
                    }
                }
            });
        }, observerOptions);

        document.addEventListener('DOMContentLoaded', function () {
            const servicioCard = document.querySelector('.servicio-card');
            if (servicioCard) {
                observer.observe(servicioCard);
            }

            // Cargar imágenes con animación
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                if (img.complete) {
                    img.classList.add('loaded');
                } else {
                    img.addEventListener('load', function () {
                        this.classList.add('loaded');
                    });
                }
            });

            // Efecto ripple en botones
            document.querySelectorAll('.btn').forEach(button => {
                button.addEventListener('click', function (e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');

                    this.appendChild(ripple);
                    setTimeout(() => ripple.remove(), 600);
                });
            });
        });

        // Estilo para ripple
        const style = document.createElement('style');
        style.textContent = `
    .btn { position: relative; overflow: hidden; }
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }
    @keyframes ripple-animation {
        to { transform: scale(4); opacity: 0; }
    }
`;
        document.head.appendChild(style);
    </script>

</body>

</html>