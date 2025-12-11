<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="icon" href="img/favicon.png" type="image/png">
    <meta charset="UTF-8">
    <title>Nosotros | Autobalance</title>
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

        .page-header {
            background: var(--gradient-hero);
            color: white;
            padding: calc(56px + var(--spacing-xl)) 0 var(--spacing-xl);
            text-align: center;
            margin-bottom: var(--spacing-xl);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.05"><path d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/></g></g></svg>');
            opacity: 0.3;
        }

        .page-header .container {
            position: relative;
            z-index: 1;
        }

        .page-header h1 {
            font-weight: var(--font-weight-extrabold);
            font-size: 2.5rem;
            margin-bottom: var(--spacing-md);
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .page-header p {
            font-size: 1.1rem;
            opacity: 0.95;
            text-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
        }

        /* Sección título */
        .seccion-titulo {
            text-align: center;
            margin-bottom: var(--spacing-xl);
        }

        .seccion-titulo h2 {
            font-size: 2rem;
            font-weight: var(--font-weight-extrabold);
            color: var(--color-primary);
            margin-bottom: var(--spacing-sm);
        }

        .seccion-titulo p {
            color: var(--color-text-light);
            font-size: 1.05rem;
        }

        /* Cards estilo servicios */
        .info-card {
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: all var(--transition-normal);
            border: 2px solid var(--color-border);
            background: #ffffff;
        }

        .info-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: var(--color-primary);
        }

        .info-img-wrapper {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 0;
            position: relative;
            overflow: hidden;
            height: 180px;
        }

        .info-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .info-card:hover .info-img {
            transform: scale(1.1);
        }

        .info-card .card-body {
            padding: 20px;
        }

        .info-card .card-title {
            margin-bottom: .5rem;
            font-weight: var(--font-weight-bold);
            color: var(--color-primary);
            font-size: 1.1rem;
        }

        .info-card .card-text {
            font-size: .9rem;
            color: var(--color-text);
            line-height: 1.7;
            text-align: justify;
        }

        /* Historia card especial */
        .historia-card {
            background: linear-gradient(135deg, var(--color-primary) 0%, #152a47 100%);
            border-radius: var(--radius-xl);
            overflow: hidden;
            margin-bottom: var(--spacing-xl);
        }

        .historia-card .row {
            align-items: center;
        }

        .historia-img {
            width: 100%;
            height: 100%;
            min-height: 300px;
            object-fit: cover;
        }

        .historia-content {
            padding: var(--spacing-xl);
            color: white;
        }

        .historia-content h2 {
            font-weight: var(--font-weight-extrabold);
            font-size: 2rem;
            margin-bottom: var(--spacing-lg);
        }

        .historia-content p {
            opacity: 0.95;
            line-height: 1.8;
            font-size: 1rem;
        }

        /* Organigrama */
        .organigrama-section {
            background: var(--color-bg-light);
            padding: var(--spacing-xl) var(--spacing-lg);
            margin-top: var(--spacing-xl);
            border-radius: var(--radius-xl);
        }

        .organigrama-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .organigrama-img {
            max-width: 90%;
            height: auto;
            border-radius: var(--radius-lg);
            transition: transform 0.5s ease;
            background: transparent;
        }

        .organigrama-img:hover {
            transform: scale(1.02);
        }

        @media (max-width: 768px) {
            .organigrama-img {
                max-width: 100%;
            }
        }

        /* CTA Section */
        .cta-card {
            background: linear-gradient(135deg, var(--color-accent) 0%, #e55a2b 100%);
            border-radius: var(--radius-xl);
            padding: var(--spacing-xl);
            text-align: center;
            color: white;
            margin-top: var(--spacing-xl);
        }

        .cta-card h3 {
            font-weight: var(--font-weight-bold);
            font-size: 1.5rem;
            margin-bottom: var(--spacing-md);
        }

        .cta-card p {
            opacity: 0.95;
            margin-bottom: var(--spacing-lg);
        }

        .cta-card .btn {
            background: white;
            color: var(--color-accent);
            font-weight: var(--font-weight-bold);
            padding: var(--spacing-md) var(--spacing-xl);
            border-radius: var(--radius-full);
            transition: all 0.3s ease;
        }

        .cta-card .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            background: var(--color-primary);
            color: white;
        }

        /* Animaciones */
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

        .page-header {
            animation: fadeInUp 0.8s ease-out;
        }

        .page-header h1 {
            animation: fadeInUp 1s ease-out 0.2s both;
        }

        .page-header p {
            animation: fadeInUp 1s ease-out 0.4s both;
        }

        .info-card {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .info-card.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
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
                    <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="servicios.php">Servicios</a></li>
                    <li class="nav-item"><a class="nav-link" href="productos.php">Productos</a></li>
                    <li class="nav-item"><a class="nav-link active" href="nosotros.php">Nosotros</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#contacto">Contacto</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="page-header">
        <div class="container">
            <h1>Conoce Autobalance</h1>
            <p>Más de una década brindando soluciones automotrices con honestidad y profesionalismo.</p>
        </div>
    </div>

    <div class="container mb-5">

        <!-- Historia -->
        <div class="historia-card reveal">
            <div class="row g-0">
                <div class="col-lg-5">
                    <img src="img/fotos_local.jpg" alt="Instalaciones Autobalance" class="historia-img">
                </div>
                <div class="col-lg-7">
                    <div class="historia-content">
                        <h2>Nuestra Historia</h2>
                        <p>
                            Autobalance nace como un proyecto familiar en Buenavista, Michoacán, con la intención de
                            ofrecer un servicio honesto y profesional en el área de refacciones, llantas y mecánica
                            automotriz.
                        </p>
                        <p>
                            Al inicio se contaba con un espacio reducido y herramientas básicas, pero con el paso del
                            tiempo y el apoyo de los clientes, el taller fue creciendo hasta convertirse en una
                            refaccionaria y centro de servicio más completo. Hoy combinamos la venta de refacciones
                            con un taller mecánico en el mismo lugar.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filosofía: Misión y Visión -->
        <div class="seccion-titulo reveal">
            <h2>Nuestra Filosofía</h2>
            <p>Los principios que guían nuestro trabajo día a día</p>
        </div>

        <div class="row mb-5">
            <div class="col-md-6 col-lg-6 mb-4">
                <div class="card h-100 shadow-sm info-card">
                    <div class="info-img-wrapper">
                        <img src="img/mision.png" class="info-img" alt="Misión">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Misión</h5>
                        <p class="card-text">
                            Brindar a nuestros clientes soluciones integrales en refacciones, llantas y servicios
                            automotrices, con atención personalizada, precios justos y mano de obra calificada que
                            garantice la seguridad y el buen funcionamiento de sus vehículos.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 mb-4">
                <div class="card h-100 shadow-sm info-card">
                    <div class="info-img-wrapper">
                        <img src="img/vision.png" class="info-img" alt="Visión">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Visión</h5>
                        <p class="card-text">
                            Consolidarnos como la refaccionaria y taller mecánico de referencia en Buenavista y la
                            región de Tierra Caliente, distinguiéndonos por la calidad de nuestros productos, la
                            confianza de nuestros clientes y la mejora continua de nuestros procesos.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Valores -->
        <div class="seccion-titulo reveal">
            <h2>Nuestros Valores</h2>
            <p>Lo que nos define como empresa</p>
        </div>

        <div class="row">
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm info-card">
                    <div class="info-img-wrapper">
                        <img src="img/honestidad.png" class="info-img" alt="Honestidad">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Honestidad</h5>
                        <p class="card-text">
                            Diagnósticos claros y transparencia en todos los trabajos realizados. Sin sorpresas ni
                            cargos ocultos.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm info-card">
                    <div class="info-img-wrapper">
                        <img src="img/responsabilidad.png" class="info-img" alt="Responsabilidad">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Responsabilidad</h5>
                        <p class="card-text">
                            Compromiso total con la seguridad y satisfacción del cliente en cada servicio que
                            realizamos.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm info-card">
                    <div class="info-img-wrapper">
                        <img src="img/servicio.png" class="info-img" alt="Servicio">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Servicio</h5>
                        <p class="card-text">
                            Trato cercano, atención rápida y soluciones prácticas para que tu vehículo esté en
                            óptimas condiciones.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm info-card">
                    <div class="info-img-wrapper">
                        <img src="img/trabajo_equipo.png" class="info-img" alt="Trabajo en Equipo">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Trabajo en Equipo</h5>
                        <p class="card-text">
                            Coordinación perfecta entre refaccionaria y taller para ofrecerte un servicio integral.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Organigrama -->
        <div class="organigrama-section reveal">
            <div class="seccion-titulo">
                <h2>Estructura Organizacional</h2>
                <p>Nuestro equipo está organizado para brindarte la mejor atención</p>
            </div>
            <div class="organigrama-container">
                <img src="img/organigrama.jpg" alt="Organigrama Autobalance" class="organigrama-img">
            </div>
        </div>

        <!-- CTA -->
        <div class="cta-card reveal">
            <h3>¿Listo para conocernos?</h3>
            <p>Visítanos en Buenavista, Michoacán o contáctanos para cualquier duda sobre nuestros servicios.</p>
            <a href="index.php#contacto" class="btn">Contáctanos Ahora</a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function () {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Intersection Observer
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    if (entry.target.classList.contains('info-card')) {
                        entry.target.classList.add('animate');
                    }
                }
            });
        }, observerOptions);

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
            document.querySelectorAll('.info-card').forEach((card, i) => {
                card.style.animationDelay = (i * 0.1) + 's';
                observer.observe(card);
            });

            // Image load animation
            document.querySelectorAll('img').forEach(img => {
                if (img.complete) {
                    img.classList.add('loaded');
                } else {
                    img.addEventListener('load', function () {
                        this.classList.add('loaded');
                    });
                }
            });
        });
    </script>

</body>

</html>