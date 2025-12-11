<?php
require_once __DIR__ . "/../config/db.php";

// Obtener servicios activos para mostrar en inicio
$stmt = $pdo->query("SELECT id, nombre, descripcion, precio_desde FROM servicios WHERE activo = 1");
$servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$ok = isset($_GET['ok']);
$error = isset($_GET['error']);

/**
 * Imagen / boceto por servicio, según el nombre
 * (misma función que en servicios.php)
 */
function imagenServicio($nombreServicio)
{
    $n = mb_strtolower($nombreServicio ?? '', 'UTF-8');

    // Alineación
    if (strpos($n, 'alineación') !== false || strpos($n, 'alineacion') !== false) {
        return 'img/servicio_alineacion.png';
    }

    // Balanceo
    if (strpos($n, 'balanceo') !== false || strpos($n, 'balancear') !== false) {
        return 'img/servicio_balanceo.png';
    }

    // Afinación / mantenimiento
    if (
        strpos($n, 'afinación') !== false || strpos($n, 'afinacion') !== false ||
        strpos($n, 'mantenimiento') !== false
    ) {
        return 'img/servicio_afinacion.png';
    }

    // Frenos
    if (strpos($n, 'freno') !== false || strpos($n, 'balata') !== false || strpos($n, 'disco') !== false) {
        return 'img/servicio_frenos.png';
    }

    // Clutch
    if (strpos($n, 'clutch') !== false || strpos($n, 'embrague') !== false) {
        return 'img/servicio_clutch.png';
    }

    // Suspensión
    if (
        strpos($n, 'suspensión') !== false || strpos($n, 'suspension') !== false ||
        strpos($n, 'amortiguador') !== false
    ) {
        return 'img/servicio_suspension.png';
    }

    // Venta de llantas
    if (strpos($n, 'llanta') !== false || strpos($n, 'llantas') !== false) {
        return 'img/servicio_llantas.png';
    }

    // Mangueras de alta presión
    if (
        strpos($n, 'manguera') !== false || strpos($n, 'mangueras') !== false ||
        strpos($n, 'alta presión') !== false || strpos($n, 'alta presion') !== false
    ) {
        return 'img/servicio_mangueras.png';
    }

    // Genérico
    return 'img/servicio_generico.png';
}
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <style>
        /* ===================== */
        /*     PUBLICIDAD HOME   */
        /* ===================== */
        #carouselHomePublicidad {
            max-width: 700px;
            margin: 0 auto;
        }

        /* ===== HEADER DE "NUESTROS SERVICIOS" MÁS LIMPIO ===== */
        #servicios {
            background: var(--color-bg);
            padding-top: 4rem;
            padding-bottom: 4rem;
        }

        #servicios .section-header {
            background: rgba(255, 255, 255, 0.96);
            padding: 1.75rem 2.5rem;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            max-width: 780px;
            margin: 0 auto 2.5rem;
            text-align: center;
            /* OJO: NO tocamos opacity ni animation,
       para que siga usando la animación global .section-header */
        }

        #servicios .section-header h2 {
            color: var(--color-primary);
            margin-bottom: 0.5rem;
            text-shadow: none;
        }

        #servicios .section-header p {
            color: var(--color-text-light);
            margin-bottom: 0;
            text-shadow: none;
        }



        .banner-img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
        }

        #carouselHomePublicidad .carousel-item {
            text-align: center;
        }

        /* ===================== */
        /* ICONOS DE CONTACTO    */
        /* ===================== */
        .icono-contacto {
            width: 28px;
            height: 28px;
            vertical-align: middle;
        }

        /* ===================== */
        /* HERO TEXT             */
        /* ===================== */
        .hero-text {
            display: inline-block;
            background: rgba(0, 0, 0, 0.85);
            padding: 12px 28px 16px;
            border-radius: 16px;
        }

        .hero-text h1,
        .hero-text p {
            text-shadow: 0 0 10px rgba(0, 0, 0, 1);
        }

        /* ===================== */
        /* TARJETAS DE SERVICIOS */
        /* ===================== */
        .servicio-card {
            border-radius: var(--radius-lg);
            border: 2px solid var(--color-border);
            transition: all var(--transition-normal);
            padding-top: 0;
            overflow: hidden;
        }

        .servicio-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: var(--color-primary);
        }

        .servicio-card .card-title {
            font-size: 1.25rem;
            font-weight: var(--font-weight-bold);
            color: var(--color-primary);
        }

        .servicio-card .card-text {
            color: var(--color-text-light);
            text-align: justify;
        }

        .servicio-precio {
            font-size: 1rem;
            color: var(--color-accent);
            font-weight: var(--font-weight-bold);
        }

        .servicio-card .btn {
            border-radius: var(--radius-full);
            padding: 6px 18px;
            font-weight: var(--font-weight-semibold);
        }

        .servicio-img-wrapper {
            background: linear-gradient(135deg, rgba(30, 58, 95, 0.05) 0%, rgba(255, 107, 53, 0.05) 100%);
            padding: 14px 14px 8px;
        }

        .servicio-img {
            width: 100%;
            height: 140px;
            object-fit: contain;
            display: block;
        }

        /* ===================== */
        /* TARJETA CONTACTO      */
        /* ===================== */
        .contact-card {
            background: var(--color-bg-light);
            border-radius: var(--radius-lg);
            padding: var(--spacing-lg) var(--spacing-xl);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--color-border-light);
        }

        .contact-item {
            margin-bottom: 1rem;
        }

        .contact-label {
            font-weight: var(--font-weight-semibold);
            margin-bottom: 0.15rem;
            color: var(--color-primary);
        }

        .fb-page {
            width: 100% !important;
        }

        .contact-map {
            margin-top: 1rem;
            border-radius: 10px;
            overflow: hidden;
        }
    </style>

    <link rel="icon" href="img/favicon.png" type="image/png">
    <meta charset="UTF-8">
    <title>Autobalance | Refaccionaria y Servicios Automotrices</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">

    <style>
        body {
            margin-top: 56px;
        }

        /* Navbar transparente mejorada */
        .navbar {
            background: rgba(30, 58, 95, 0.85) !important;
            backdrop-filter: blur(15px) saturate(180%);
            -webkit-backdrop-filter: blur(15px) saturate(180%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(30, 58, 95, 0.95) !important;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.3);
        }

        .navbar-brand,
        .navbar-nav .nav-link {
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .navbar-nav .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
        }

        /* Hero section mejorado */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            padding-top: 56px;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(30, 58, 95, 0.4);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-text {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            padding: 2rem 3rem;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 768px) {
            .hero-text {
                padding: 1.5rem 2rem;
            }
        }

        /* ===================== */
        /* ANIMACIONES GENERALES */
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

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }

            100% {
                background-position: 1000px 0;
            }
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Hero animations */
        .hero-text {
            animation: fadeInUp 1s ease-out;
        }

        .hero-text h1 {
            animation: fadeInUp 1s ease-out 0.2s both;
        }

        .hero-text p {
            animation: fadeInUp 1s ease-out 0.4s both;
        }

        .hero-text .btn {
            animation: fadeInUp 1s ease-out 0.6s both;
            position: relative;
            overflow: hidden;
        }

        .hero-text .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .hero-text .btn:hover::before {
            left: 100%;
        }

        /* Section headers */
        .section-header {
            opacity: 0;
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .section-header h2 {
            position: relative;
            overflow: hidden;
        }

        .section-header h2::after {
            animation: slideInLeft 0.8s ease-out 0.3s both;
        }

        /* Service cards animations */
        .servicio-card {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .servicio-card.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .servicio-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .servicio-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .servicio-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .servicio-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .servicio-card:nth-child(5) {
            animation-delay: 0.5s;
        }

        .servicio-card:nth-child(6) {
            animation-delay: 0.6s;
        }

        .servicio-card:nth-child(7) {
            animation-delay: 0.7s;
        }

        .servicio-card:nth-child(8) {
            animation-delay: 0.8s;
        }

        .servicio-card:hover {
            transform: translateY(-12px) scale(1.02);
            animation: pulse 2s ease-in-out infinite;
        }

        .servicio-img-wrapper {
            position: relative;
            overflow: hidden;
        }

        .servicio-img {
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .servicio-card:hover .servicio-img {
            transform: scale(1.15) rotate(2deg);
        }

        .servicio-card .btn {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .servicio-card .btn::after {
            content: '→';
            position: absolute;
            right: -20px;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .servicio-card .btn:hover::after {
            right: 10px;
            opacity: 1;
        }

        .servicio-card .btn:hover {
            padding-right: 30px;
        }

        /* Carousel animations */
        .carousel-item {
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .carousel-item.active .banner-img {
            animation: scaleIn 0.8s ease-out;
        }

        .banner-img {
            transition: transform 0.5s ease;
        }

        .carousel:hover .banner-img {
            transform: scale(1.02);
        }

        .carousel-control-prev,
        .carousel-control-next {
            transition: all 0.3s ease;
            opacity: 0.7;
        }

        .carousel:hover .carousel-control-prev,
        .carousel:hover .carousel-control-next {
            opacity: 1;
            transform: scale(1.1);
        }

        /* Contact form animations */
        .contact-card {
            opacity: 0;
            transform: translateX(-30px);
            transition: all 0.6s ease-out;
        }

        .contact-card.animate {
            opacity: 1;
            transform: translateX(0);
        }

        .contact-card:nth-child(2) {
            transform: translateX(30px);
        }

        .form-control {
            transition: all 0.3s ease;
            position: relative;
        }

        .form-control:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 58, 95, 0.2);
        }

        .form-control::placeholder {
            transition: opacity 0.3s ease;
        }

        .form-control:focus::placeholder {
            opacity: 0.5;
        }

        .btn-primary {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-primary:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.4);
        }

        /* Alert animations */
        .alert {
            animation: slideInRight 0.5s ease-out;
            position: relative;
            overflow: hidden;
        }

        .alert::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: shimmer 2s infinite;
        }

        /* Footer animation */
        footer {
            opacity: 0;
            animation: fadeIn 1s ease-out 0.5s forwards;
        }

        /* Floating elements */
        @keyframes floatSlow {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            33% {
                transform: translateY(-15px) rotate(5deg);
            }

            66% {
                transform: translateY(-5px) rotate(-5deg);
            }
        }

        .hero-section::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 107, 53, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            top: 20%;
            right: 10%;
            animation: floatSlow 6s ease-in-out infinite;
            z-index: 1;
        }

        /* Scroll reveal */
        .reveal {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Icon animations */
        .icono-contacto {
            transition: all 0.3s ease;
            display: inline-block;
        }

        .contact-item a:hover .icono-contacto {
            transform: scale(1.2) rotate(10deg);
            animation: pulse 1s ease-in-out infinite;
        }

        /* Section background animations */
        section {
            position: relative;
            overflow: hidden;
        }

        section::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(30, 58, 95, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 107, 53, 0.03) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
            animation: floatSlow 20s ease-in-out infinite;
        }

        section>* {
            position: relative;
            z-index: 1;
        }

        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }

        /* Loading animation for images */
        img {
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        img.loaded {
            opacity: 1;
        }

        /* Navbar brand animation */
        .navbar-brand img {
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover img {
            transform: rotate(-5deg) scale(1.1);
        }

        /* Carousel indicators animation */
        .carousel-indicators button {
            transition: all 0.3s ease;
        }

        .carousel-indicators button.active {
            transform: scale(1.3);
            background-color: var(--color-accent);
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
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
                    <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section id="inicio" class="hero-section text-center"
        style="background-image: url('http://localhost/AutoBalance/public/img/fotos_local.jpg'); background-size: cover; background-position: center;">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="display-4 fw-bold">Autobalance</h1>
                    <p class="lead mb-0">Especialistas en llantas, refacciones, mangueras hidráulicas y servicios
                        mecánicos.</p>
                    <a href="#contacto" class="btn btn-primary btn-lg mt-3">Agenda tu Servicio</a>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICIOS -->
    <section id="servicios" class="py-5">
        <div class="container">
            <div class="section-header">
                <h2>Nuestros Servicios</h2>
                <p>Estos son los servicios que ofrecemos.</p>
            </div>

            <div class="row">
                <?php foreach ($servicios as $index => $s):
                    $img = imagenServicio($s['nombre']);
                    ?>
                    <div class="col-md-3 mb-4">
                        <div class="card h-100 shadow-sm servicio-card reveal"
                            style="animation-delay: <?php echo $index * 0.1; ?>s;">
                            <!-- Imagen igual que en servicios.php -->
                            <div class="servicio-img-wrapper">
                                <img src="<?php echo htmlspecialchars($img); ?>" class="servicio-img"
                                    alt="Servicio <?php echo htmlspecialchars($s['nombre']); ?>">
                            </div>

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold"><?php echo htmlspecialchars($s['nombre']); ?></h5>
                                <p class="card-text small mb-3"><?php echo nl2br(htmlspecialchars($s['descripcion'])); ?>
                                </p>

                                <?php if (!is_null($s['precio_desde'])): ?>
                                    <p class="fw-bold servicio-precio mb-2">Desde
                                        $<?php echo number_format($s['precio_desde'], 2); ?> MXN</p>
                                <?php else: ?>
                                    <p class="text-muted mb-2">Cotización personalizada</p>
                                <?php endif; ?>

                                <a href="servicio.php?id=<?php echo $s['id']; ?>"
                                    class="btn btn-outline-primary btn-sm mt-auto">Ver más</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>

    <!-- PUBLICIDAD HOME -->
    <section id="publicidad-home" class="py-5" style="background: var(--color-bg);">
        <div class="container">
            <div class="section-header reveal">
                <h2>Promociones y Publicidad</h2>
            </div>

            <div id="carouselHomePublicidad" class="carousel slide mb-4 reveal" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselHomePublicidad" data-bs-slide-to="0"
                        class="active"></button>
                    <button type="button" data-bs-target="#carouselHomePublicidad" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#carouselHomePublicidad" data-bs-slide-to="2"></button>
                    <button type="button" data-bs-target="#carouselHomePublicidad" data-bs-slide-to="3"></button>
                    <button type="button" data-bs-target="#carouselHomePublicidad" data-bs-slide-to="4"></button>
                </div>

                <div class="carousel-inner">
                    <div class="carousel-item active"><img src="img/banner1.png" class="d-block mx-auto banner-img"
                            alt="Banner 1"></div>
                    <div class="carousel-item"><img src="img/banner2.png" class="d-block mx-auto banner-img"
                            alt="Banner 2"></div>
                    <div class="carousel-item"><img src="img/banner3.png" class="d-block mx-auto banner-img"
                            alt="Banner 3"></div>
                    <div class="carousel-item"><img src="img/banner4.png" class="d-block mx-auto banner-img"
                            alt="Banner 4"></div>
                    <div class="carousel-item"><img src="img/banner5.png" class="d-block mx-auto banner-img"
                            alt="Banner 5"></div>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carouselHomePublicidad"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselHomePublicidad"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>

            <div class="text-center">
                <a href="productos.php" class="btn btn-primary">Ver más productos</a>
            </div>
        </div>
    </section>

    <!-- CONTACTO -->
    <section id="contacto" class="py-5">
        <div class="container">
            <div class="section-header">
                <h2>Contáctanos</h2>
            </div>

            <?php if ($ok): ?>
                <div class="alert alert-success">Gracias, tu mensaje fue enviado correctamente.</div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger">Ocurrió un error. Revisa tus datos e intenta nuevamente.</div>
            <?php endif; ?>

            <div class="row g-4">
                <!-- FORMULARIO -->
                <div class="col-md-6">
                    <form action="contacto.php" method="post" class="contact-card reveal">
                        <div class="mb-3">
                            <label class="form-label">Nombre*</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mensaje*</label>
                            <textarea name="mensaje" rows="4" class="form-control" required></textarea>
                        </div>

                        <button class="btn btn-primary">Enviar Mensaje</button>
                    </form>
                </div>

                <!-- INFORMACIÓN DE CONTACTO -->
                <div class="col-md-6">
                    <div class="contact-card reveal">

                        <!-- Logo grande -->
                        <div class="text-center mb-4">
                            <img src="img/logo.png" alt="Logo Autobalance" style="height: 80px; width: auto;">
                            <h4 class="mt-2 mb-0" style="color: var(--color-primary); font-weight: 700;">Autobalance
                            </h4>
                            <p class="text-muted mb-0" style="font-size: 0.9rem;">Refaccionaria y Servicios Automotrices
                            </p>
                        </div>

                        <!-- Horario destacado -->
                        <div class="contact-item"
                            style="background: linear-gradient(135deg, var(--color-primary) 0%, #152a47 100%); color: white; padding: 1.25rem; border-radius: 12px; margin-bottom: 1.25rem;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <span style="font-size: 2rem;">🕐</span>
                                <div>
                                    <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 2px;">Horario de
                                        Atención</div>
                                    <p class="mb-0" style="font-size: 1rem; opacity: 0.95;">Lunes a Sábado ·
                                        <strong>9:00 AM - 7:00 PM</strong>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- WhatsApp destacado -->
                        <div class="contact-item"
                            style="background: #25D366; color: white; padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1rem;">
                            <a href="https://wa.me/524531035374" target="_blank" class="text-decoration-none"
                                style="color: white; display: flex; align-items: center; gap: 12px;">
                                <img src="img/whatsapp.png" class="icono-contacto" alt="WhatsApp"
                                    style="width: 36px; height: 36px; filter: brightness(10);">
                                <div>
                                    <div style="font-weight: 700; font-size: 0.85rem;">WhatsApp</div>
                                    <div style="font-size: 1.25rem; font-weight: 600;">453 103 5374</div>
                                </div>
                            </a>
                        </div>

                        <div class="contact-item">
                            <div class="contact-label" style="font-size: 1rem; margin-bottom: 0.5rem;">
                                <span style="margin-right: 8px;">📍</span> Facebook
                            </div>

                            <!-- fb-root + SDK -->
                            <div id="fb-root"></div>
                            <script async defer crossorigin="anonymous"
                                src="https://connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v21.0"
                                nonce="autobalance123">
                                </script>

                            <!-- Plugin de página -->
                            <div class="fb-page" data-href="https://www.facebook.com/profile.php?id=61584453161748"
                                data-tabs="timeline" data-width="600" data-height="300" data-small-header="false"
                                data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">

                                <blockquote cite="https://www.facebook.com/AutoBalanceOficial"
                                    class="fb-xfbml-parse-ignore">
                                    <a href="https://www.facebook.com/AutoBalanceOficial">
                                        Refaccionaria Autobalance
                                    </a>
                                </blockquote>
                            </div>
                        </div>

                        <div class="contact-item mt-3">
                            <div class="contact-label" style="font-size: 1rem; margin-bottom: 0.5rem;">
                                <span style="margin-right: 8px;">📍</span> Ubicación
                            </div>
                            <div class="contact-map"
                                style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                <iframe width="100%" height="200" style="border:0;" loading="lazy" allowfullscreen
                                    src="https://www.google.com/maps?q=Refaccionaria%20Autobalance%20Buenavista%20Michoacan&z=19&output=embed">
                                </iframe>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-dark text-light py-3 text-center">
        <small>&copy; <?= date('Y') ?> Autobalance · Todos los derechos reservados.</small>
    </footer>

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

        // Intersection Observer para animaciones al hacer scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    // Para las tarjetas de servicio
                    if (entry.target.classList.contains('servicio-card')) {
                        entry.target.classList.add('animate');
                    }
                    // Para las tarjetas de contacto
                    if (entry.target.classList.contains('contact-card')) {
                        entry.target.classList.add('animate');
                    }
                }
            });
        }, observerOptions);

        // Observar todos los elementos con clase reveal
        document.addEventListener('DOMContentLoaded', function () {
            const reveals = document.querySelectorAll('.reveal');
            reveals.forEach(el => observer.observe(el));

            const serviceCards = document.querySelectorAll('.servicio-card');
            serviceCards.forEach(card => observer.observe(card));

            const contactCards = document.querySelectorAll('.contact-card');
            contactCards.forEach(card => observer.observe(card));

            // Animación del hero al cargar
            const heroText = document.querySelector('.hero-text');
            if (heroText) {
                heroText.style.opacity = '0';
                setTimeout(() => {
                    heroText.style.opacity = '1';
                }, 100);
            }

            // Efecto parallax suave en el hero
            const heroSection = document.querySelector('.hero-section');
            if (heroSection) {
                window.addEventListener('scroll', function () {
                    const scrolled = window.pageYOffset;
                    const rate = scrolled * 0.5;
                    heroSection.style.transform = `translateY(${rate}px)`;
                });
            }

            // Animación de números (si hay precios)
            const animateNumbers = function () {
                const precioElements = document.querySelectorAll('.servicio-precio');
                precioElements.forEach(el => {
                    const text = el.textContent;
                    const match = text.match(/\$([\d,]+)/);
                    if (match) {
                        const number = parseInt(match[1].replace(/,/g, ''));
                        let current = 0;
                        const increment = number / 30;
                        const timer = setInterval(() => {
                            current += increment;
                            if (current >= number) {
                                current = number;
                                clearInterval(timer);
                            }
                            el.textContent = text.replace(/\$[\d,]+/, '$' + Math.floor(current).toLocaleString());
                        }, 30);
                    }
                });
            };

            // Activar animación de números cuando las tarjetas sean visibles
            const precioObserver = new IntersectionObserver(function (entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateNumbers();
                        precioObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });

            const firstServiceCard = document.querySelector('.servicio-card');
            if (firstServiceCard) {
                precioObserver.observe(firstServiceCard);
            }

            // Efecto de typing en el título del hero (opcional)
            const heroTitle = document.querySelector('.hero-text h1');
            if (heroTitle) {
                const text = heroTitle.textContent;
                heroTitle.textContent = '';
                let i = 0;
                const typeWriter = function () {
                    if (i < text.length) {
                        heroTitle.textContent += text.charAt(i);
                        i++;
                        setTimeout(typeWriter, 100);
                    }
                };
                setTimeout(typeWriter, 500);
            }

            // Animación de entrada para el botón del hero
            const heroButton = document.querySelector('.hero-text .btn');
            if (heroButton) {
                heroButton.addEventListener('mouseenter', function () {
                    this.style.animation = 'pulse 1s ease-in-out';
                });
                heroButton.addEventListener('mouseleave', function () {
                    this.style.animation = '';
                });
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

            // Efecto de ondas al hacer clic en botones
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

                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });

        // Agregar estilo para el efecto ripple
        const style = document.createElement('style');
        style.textContent = `
    .btn {
        position: relative;
        overflow: hidden;
    }
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
        document.head.appendChild(style);
    </script>

</body>

</html>