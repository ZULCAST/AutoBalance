<?php
require_once __DIR__ . "/../config/db.php";

$stmt = $pdo->query("SELECT id, nombre, descripcion, precio_desde FROM servicios WHERE activo = 1");
$servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

/**
 * Imagen / boceto por servicio, según el nombre
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
  <link rel="icon" href="img/favicon.png" type="image/png">
  <meta charset="UTF-8">
  <title>Servicios | Autobalance</title>
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

    .servicio-card {
      border-radius: var(--radius-lg);
      overflow: hidden;
      transition: all var(--transition-normal);
      border: 2px solid var(--color-border);
    }

    .servicio-card:hover {
      transform: translateY(-8px);
      box-shadow: var(--shadow-lg);
      border-color: var(--color-primary);
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

    .servicio-card .card-body {
      padding: 16px 18px 18px;
    }

    .servicio-card .card-title {
      margin-bottom: .35rem;
      font-weight: var(--font-weight-bold);
      color: var(--color-primary);
    }

    .servicio-card .card-text {
      font-size: .9rem;
      color: var(--color-text-light);
      min-height: 54px;
      text-align: justify;
    }

    .servicio-precio {
      font-size: 1rem;
      color: var(--color-accent);
      font-weight: var(--font-weight-bold);
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

    @keyframes pulse {

      0%,
      100% {
        transform: scale(1);
      }

      50% {
        transform: scale(1.05);
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

    .servicio-card {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .servicio-card.animate {
      opacity: 1;
      transform: translateY(0);
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
          <li class="nav-item"><a class="nav-link active" href="index.php">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="servicios.php">Servicios</a></li>
          <li class="nav-item"><a class="nav-link" href="productos.php">Productos</a></li>
          <li class="nav-item"><a class="nav-link" href="nosotros.php">Nosotros</a></li>
          <li class="nav-item"><a class="nav-link" href="index.php#contacto">Contacto</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="page-header">
    <div class="container">
      <h1>Nuestros Servicios</h1>
      <p>Estos son algunos de los servicios que ofrecemos.</p>
    </div>
  </div>

  <div class="container mb-5">

    <div class="row">
      <?php foreach ($servicios as $index => $s):
        $img = imagenServicio($s['nombre']);
        ?>
        <div class="col-md-4 col-lg-3 mb-4">
          <div class="card h-100 shadow-sm servicio-card reveal" style="animation-delay: <?php echo $index * 0.1; ?>s;">
            <div class="servicio-img-wrapper">
              <img src="<?php echo htmlspecialchars($img); ?>" class="servicio-img"
                alt="Servicio <?php echo htmlspecialchars($s['nombre']); ?>">
            </div>
            <div class="card-body d-flex flex-column">
              <h5 class="card-title fw-bold"><?php echo htmlspecialchars($s['nombre']); ?></h5>
              <p class="card-text">
                <?php echo nl2br(htmlspecialchars($s['descripcion'])); ?>
              </p>

              <?php if (!is_null($s['precio_desde'])): ?>
                <p class="fw-bold mb-2 servicio-precio">
                  Desde $<?php echo number_format($s['precio_desde'], 2); ?> MXN
                </p>
              <?php else: ?>
                <p class="text-muted mb-2 servicio-precio">Cotización personalizada</p>
              <?php endif; ?>

              <a href="servicio.php?id=<?php echo $s['id']; ?>" class="btn btn-outline-primary btn-sm mt-auto">
                Ver más
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
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

    // Intersection Observer para animaciones
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function (entries) {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('active');
          if (entry.target.classList.contains('servicio-card')) {
            entry.target.classList.add('animate');
          }
        }
      });
    }, observerOptions);

    document.addEventListener('DOMContentLoaded', function () {
      const reveals = document.querySelectorAll('.reveal');
      reveals.forEach(el => observer.observe(el));

      const serviceCards = document.querySelectorAll('.servicio-card');
      serviceCards.forEach(card => observer.observe(card));

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