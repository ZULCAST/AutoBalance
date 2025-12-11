<?php
require_once __DIR__ . "/../config/db.php";

/* Obtener TODOS los productos activos */
$stmt = $pdo->query("
    SELECT id, nombre, categoria, marca, precio, stock 
    FROM productos 
    WHERE activo = 1 
    ORDER BY categoria, nombre
");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Agrupar por categoría */
$secciones = [];
foreach ($productos as $p) {
  $cat = $p['categoria'] ?: 'Otros';
  $catLower = mb_strtolower(trim($cat), 'UTF-8');

  // Normalizar categorías para evitar duplicados
  $categoriasNormalizadas = [
    // Aceites
    'aceite' => 'Aceites',
    'aceites' => 'Aceites',
    // Llantas
    'llanta' => 'Llantas',
    'llantas' => 'Llantas',
    // Balatas
    'balata' => 'Balatas',
    'balatas' => 'Balatas',
    // Filtros
    'filtro' => 'Filtros',
    'filtros' => 'Filtros',
    // Amortiguadores
    'amortiguador' => 'Amortiguadores',
    'amortiguadores' => 'Amortiguadores',
    // Suspensión
    'suspensión' => 'Suspensión',
    'suspension' => 'Suspensión',
    // Frenos
    'freno' => 'Frenos',
    'frenos' => 'Frenos',
    // Refrigerantes
    'refrigerante' => 'Refrigerantes',
    'refrigerantes' => 'Refrigerantes',
    'anticongelante' => 'Refrigerantes',
    'anticongelantes' => 'Refrigerantes',
    // Baterías
    'batería' => 'Baterías',
    'baterias' => 'Baterías',
    'bateria' => 'Baterías',
    'baterías' => 'Baterías',
    // Limpiaparabrisas
    'limpiaparabrisas' => 'Limpiaparabrisas',
    // Bujías
    'bujía' => 'Bujías',
    'bujias' => 'Bujías',
    'bujia' => 'Bujías',
    'bujías' => 'Bujías',
    // Motor / Bandas
    'motor' => 'Motor y Bandas',
    'banda' => 'Motor y Bandas',
    'bandas' => 'Motor y Bandas',
  ];

  // Buscar coincidencia en el mapa de normalización
  $catNormalizada = null;
  foreach ($categoriasNormalizadas as $key => $valor) {
    if ($catLower === $key || strpos($catLower, $key) !== false) {
      $catNormalizada = $valor;
      break;
    }
  }

  $cat = $catNormalizada ?: ucfirst($cat);

  if (!isset($secciones[$cat])) {
    $secciones[$cat] = [];
  }
  $secciones[$cat][] = $p;
}

/* Función para asignar imagen automática según categoría / nombre */
function imagenProducto($categoria, $nombre)
{
  $categoria = mb_strtolower($categoria ?? '', 'UTF-8');
  $nombre = mb_strtolower($nombre ?? '', 'UTF-8');

  // 1) MAPEO ESPECÍFICO POR PRODUCTO
  // IMPORTANTE: Los nombres más específicos (largos) PRIMERO para evitar coincidencias parciales
  $map = [
    // LLANTAS (ordenados de más específico a menos específico)
    'llanta 215/60r16 energy saver' => 'img/llanta_215_60_16_energy.png',
    'llanta 205/55r16 advantage' => 'img/llanta_205_55_16_advantage.png',
    'llanta 195/65r15 turanza' => 'img/llanta_195_65_15_turanza.png',
    'llanta 31" todo terreno' => 'img/llanta_todo_terreno.png',
    'llanta 205/55r16' => 'img/llanta_205_55_16.png',

    // ACEITES (ordenados de más específico a menos específico)
    'aceite 5w30 sintético 4l' => 'img/aceite_5w30_4l.png',
    'aceite 5w30 sintetico 4l' => 'img/aceite_5w30_4l.png',
    'aceite 5w30 sintético 1l' => 'img/aceite_5w30_1l.png',
    'aceite 5w30 sintetico 1l' => 'img/aceite_5w30_1l.png',
    'aceite 15w40 mineral 1l' => 'img/aceite_15w40_mineral.png',
    'aceite 5w30 sintético' => 'img/aceite_5w30_sintetico.png',
    'aceite 5w30 sintetico' => 'img/aceite_5w30_sintetico.png',

    // BALATAS / FRENOS (ordenados de más específico a menos específico)
    'juego de balatas delanteras jetta a4' => 'img/balatas_jetta_a4.png',
    'juego de balatas traseras versa' => 'img/balatas_versa_traseras.png',
    'juego de balatas delanteras' => 'img/balatas_delanteras.png',
    'juego de frenos delanteros' => 'img/kit_frenos_delanteros.png',
    'disco de freno ventilado jetta a4' => 'img/disco_freno_jetta_a4.png',
    'líquido de frenos dot 4 946 ml' => 'img/liquido_frenos_dot4.png',
    'liquido de frenos dot 4 946 ml' => 'img/liquido_frenos_dot4.png',
    'líquido de frenos dot 4 500ml' => 'img/liquido_frenos_dot4_500ml.png',
    'liquido de frenos dot 4 500ml' => 'img/liquido_frenos_dot4_500ml.png',

    // FILTROS
    'filtro de aceite compacto' => 'img/filtro_aceite_compacto.png',
    'filtro de aceite tsuru' => 'img/filtro_aceite_tsuru.png',
    'filtro de aceite versa' => 'img/filtro_aceite_versa.png',
    'filtro de aire para motor' => 'img/filtro_aire_motor.png',
    'filtro de aire tsuru' => 'img/filtro_aire_tsuru.png',
    'filtro de aire sentra' => 'img/filtro_aire_sentra.png',

    // AMORTIGUADORES / SUSPENSIÓN (ordenados de más específico a menos específico)
    'amortiguador delantero pointer' => 'img/amortiguador_pointer.png',
    'amortiguador trasero aveo' => 'img/amortiguador_aveo_trasero.png',
    'amortiguador delantero' => 'img/amortiguador_delantero.png',
    'kit de suspensión completo chevy' => 'img/kit_suspension_chevy.png',
    'kit de suspension completo chevy' => 'img/kit_suspension_chevy.png',
    'kit de suspensión delantera' => 'img/kit_suspension_delantera.png',
    'kit de suspension delantera' => 'img/kit_suspension_delantera.png',

    // REFRIGERANTES / ANTICONGELANTES
    'refrigerante listo para usar verde 3.78l' => 'img/refrigerante_verde_378.png',
    'refrigerante listo verde 3.78l' => 'img/refrigerante_verde_378.png',
    'anticongelante concentrado rojo 1l' => 'img/anticongelante_rojo_1l.png',
    'anticongelante 50/50 verde 1l' => 'img/anticongelante_verde_1l.png',

    // BATERÍAS (ordenados de más específico a menos específico)
    'batería 12v 600 cca libre de mantenimiento' => 'img/bateria_12v_600cca_libre.png',
    'bateria 12v 600 cca libre de mantenimiento' => 'img/bateria_12v_600cca_libre.png',
    'batería 12v 450 cca compacta' => 'img/bateria_12v_450cca.png',
    'bateria 12v 450 cca compacta' => 'img/bateria_12v_450cca.png',
    'batería 12v 600 cca' => 'img/bateria_12v_600cca.png',
    'bateria 12v 600 cca' => 'img/bateria_12v_600cca.png',

    // LIMPIAPARABRISAS
    'limpiaparabrisas 22" universal' => 'img/limpiaparabrisas_22.png',
    'plumilla limpiaparabrisas 22"' => 'img/limpiaparabrisas_22_b.png',
    'plumilla limpiaparabrisas 18"' => 'img/limpiaparabrisas_18.png',

    // BUJÍAS
    'juego de bujías de iridio (4 pzas)' => 'img/bujias_iridio_4pz.png',
    'juego de bujias de iridio (4 pzas)' => 'img/bujias_iridio_4pz.png',
    'bujía de iridio para jetta 2.0' => 'img/bujia_iridio_jetta.png',
    'bujia de iridio para jetta 2.0' => 'img/bujia_iridio_jetta.png',
    'bujía convencional para tsuru' => 'img/bujia_tsuru.png',
    'bujia convencional para tsuru' => 'img/bujia_tsuru.png',

    // BANDAS / MOTOR
    'banda de tiempo para motor 4 cil.' => 'img/banda_tiempo_4cil.png',
    'banda de tiempo tsuru 1.6' => 'img/banda_tiempo_tsuru.png',
    'banda poly-v versa' => 'img/banda_polyv_versa.png',
  ];

  // Ordenar el mapa por longitud de clave (más largo primero) para mayor seguridad
  uksort($map, function ($a, $b) {
    return strlen($b) - strlen($a);
  });

  // Buscar coincidencia por nombre
  foreach ($map as $needle => $ruta) {
    if (strpos($nombre, $needle) !== false) {
      return $ruta;
    }
  }

  // 2) FALLBACK POR CATEGORÍA (lógica anterior)

  // LLANTAS
  if (strpos($categoria, 'llanta') !== false) {
    if (strpos($nombre, 'todo terreno') !== false) {
      return 'img/llanta_31_todoterreno.png';
    }
    return 'img/llanta.png';
  }

  // ACEITES
  if (strpos($categoria, 'aceite') !== false) {
    return 'img/aceite.png';
  }

  // BALATAS
  if (strpos($categoria, 'balata') !== false) {
    return 'img/balatas.png';
  }

  // FILTROS (aceite / aire)
  if (strpos($categoria, 'filtro') !== false) {
    if (strpos($nombre, 'aire') !== false) {
      return 'img/filtro_aire.png';
    }
    return 'img/filtro.png';
  }

  // AMORTIGUADOR / SUSPENSIÓN
  if (strpos($categoria, 'amortiguador') !== false) {
    return 'img/amortiguador.png';
  }
  if (strpos($categoria, 'suspensión') !== false || strpos($categoria, 'suspension') !== false) {
    return 'img/kit_suspension.png';
  }

  // FRENOS
  if (strpos($categoria, 'freno') !== false) {
    if (strpos($nombre, 'líquido') !== false || strpos($nombre, 'liquido') !== false) {
      return 'img/liquido_frenos.png';
    }
    return 'img/freno.png';
  }

  // REFRIGERANTE / ANTICONGELANTE
  if (strpos($categoria, 'refrigerante') !== false || strpos($nombre, 'anticongelante') !== false) {
    return 'img/anticongelante.png';
  }

  // BATERÍA
  if (strpos($categoria, 'batería') !== false || strpos($categoria, 'bateria') !== false) {
    return 'img/bateria.png';
  }

  // LIMPIAPARABRISAS
  if (strpos($categoria, 'limpiaparabrisas') !== false) {
    return 'img/limpiaparabrisas.png';
  }

  // BUJÍAS
  if (strpos($categoria, 'bujía') !== false || strpos($categoria, 'bujia') !== false) {
    return 'img/bujia.jpg';
  }

  // MOTOR / BANDA
  if (strpos($categoria, 'motor') !== false || strpos($nombre, 'banda') !== false) {
    return 'img/banda_tiempo.png';
  }

  // Por defecto
  return 'img/producto.png';
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <link rel="icon" href="img/favicon.png" type="image/png">
  <meta charset="UTF-8">
  <title>Productos | Autobalance</title>
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

    /* Secciones por categoría */
    .seccion-categoria {
      margin-bottom: var(--spacing-xl);
    }

    .seccion-titulo {
      font-size: 1.5rem;
      font-weight: var(--font-weight-bold);
      color: var(--color-primary);
      margin-bottom: var(--spacing-lg);
      padding-bottom: var(--spacing-sm);
      border-bottom: 3px solid var(--color-accent);
      display: inline-block;
    }

    .seccion-contador {
      font-size: .9rem;
      color: var(--color-text-light);
      font-weight: var(--font-weight-normal);
      margin-left: var(--spacing-sm);
    }

    .producto-card {
      border-radius: var(--radius-lg);
      overflow: hidden;
      transition: all var(--transition-normal);
      border: 1px solid var(--color-border);
      background: #ffffff;
    }

    .producto-card:hover {
      transform: translateY(-8px);
      box-shadow: var(--shadow-lg);
      border-color: var(--color-primary);
    }

    .producto-img-wrapper {
      background: #ffffff;
      padding: 20px 14px;
      border-bottom: 1px solid rgba(30, 58, 95, 0.08);
      position: relative;
    }

    .producto-img-wrapper::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 10%;
      right: 10%;
      height: 1px;
      background: linear-gradient(90deg, transparent, var(--color-primary), transparent);
      opacity: 0.2;
    }

    .producto-img {
      width: 100%;
      height: 140px;
      object-fit: contain;
      display: block;
    }

    .producto-card .card-body {
      padding: 16px 18px 18px;
    }

    .producto-card .card-title {
      margin-bottom: .35rem;
      font-weight: var(--font-weight-bold);
      color: var(--color-primary);
      font-size: 1rem;
    }

    .producto-card .card-text {
      font-size: .85rem;
      color: var(--color-text-light);
    }

    .producto-marca {
      font-size: .8rem;
      color: var(--color-text-light);
      margin-bottom: .5rem;
    }

    .producto-precio {
      font-size: 1.1rem;
      color: var(--color-accent);
      font-weight: var(--font-weight-bold);
    }

    .producto-stock {
      font-size: .8rem;
      color: var(--color-text-light);
    }

    .stock-ok {
      color: #065f46;
    }

    .stock-bajo {
      color: #991b1b;
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

    .seccion-categoria {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .seccion-categoria.animate {
      opacity: 1;
      transform: translateY(0);
    }

    .producto-card {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .producto-card.animate {
      opacity: 1;
      transform: translateY(0);
    }

    .producto-card:hover {
      transform: translateY(-12px) scale(1.02);
      animation: pulse 2s ease-in-out infinite;
    }

    .producto-img-wrapper {
      position: relative;
      overflow: hidden;
    }

    .producto-img {
      transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .producto-card:hover .producto-img {
      transform: scale(1.15) rotate(2deg);
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
          <li class="nav-item"><a class="nav-link active" href="productos.php">Productos</a></li>
          <li class="nav-item"><a class="nav-link" href="nosotros.php">Nosotros</a></li>
          <li class="nav-item"><a class="nav-link" href="index.php#contacto">Contacto</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="page-header">
    <div class="container">
      <h1>Nuestros Productos</h1>
      <p>Refacciones, llantas, aceites y más para mantener tu vehículo en excelente estado.</p>
    </div>
  </div>

  <div class="container mb-5">

    <?php foreach ($secciones as $categoria => $items): ?>
      <section class="seccion-categoria reveal">
        <h2 class="seccion-titulo">
          <?php echo htmlspecialchars($categoria); ?>
          <span class="seccion-contador">(<?php echo count($items); ?>
            producto<?php echo count($items) !== 1 ? 's' : ''; ?>)</span>
        </h2>

        <div class="row">
          <?php foreach ($items as $index => $p):
            $img = imagenProducto($p['categoria'], $p['nombre']);
            $stock = (int) $p['stock'];
            $stockClass = $stock <= 3 ? 'stock-bajo' : 'stock-ok';
            $stockText = $stock <= 3 ? 'Últimas piezas' : 'Disponible';
            ?>
            <div class="col-md-4 col-lg-3 mb-4">
              <div class="card h-100 shadow-sm producto-card" style="animation-delay: <?php echo $index * 0.05; ?>s;">
                <div class="producto-img-wrapper">
                  <img src="<?php echo htmlspecialchars($img); ?>" class="producto-img"
                    alt="Producto <?php echo htmlspecialchars($p['nombre']); ?>">
                </div>
                <div class="card-body d-flex flex-column">
                  <h5 class="card-title"><?php echo htmlspecialchars($p['nombre']); ?></h5>

                  <p class="producto-marca mb-2">
                    Marca: <strong><?php echo htmlspecialchars($p['marca'] ?: 'N/A'); ?></strong>
                  </p>

                  <div class="mt-auto">
                    <p class="producto-precio mb-1">
                      $<?php echo number_format($p['precio'], 2); ?> MXN
                    </p>
                    <p class="producto-stock mb-0 <?php echo $stockClass; ?>">
                      <?php echo $stockText; ?>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endforeach; ?>

    <!-- Tarjeta especial final -->
    <div class="row mt-4">
      <div class="col-md-8 col-lg-6 mx-auto mb-4">
        <div class="card h-100 reveal" style="background: var(--color-primary); color: white; border: none;">
          <div class="card-body text-center p-4">
            <h4 class="fw-bold">¿No ves la pieza que buscas?</h4>
            <p class="mb-3">
              Nuestro catálogo en línea muestra solo una parte del inventario.
              Envíanos un mensaje con la marca, modelo y año de tu vehículo
              y te ayudamos a encontrar la refacción adecuada.
            </p>
            <a href="index.php#contacto" class="btn btn-light text-dark fw-bold">
              Preguntar por disponibilidad
            </a>
          </div>
        </div>
      </div>
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
          if (entry.target.classList.contains('producto-card')) {
            entry.target.classList.add('animate');
          }
          if (entry.target.classList.contains('seccion-categoria')) {
            entry.target.classList.add('animate');
            // Animar las cards dentro de la sección
            const cards = entry.target.querySelectorAll('.producto-card');
            cards.forEach((card, i) => {
              setTimeout(() => {
                card.classList.add('animate');
              }, i * 100);
            });
          }
        }
      });
    }, observerOptions);

    document.addEventListener('DOMContentLoaded', function () {
      const reveals = document.querySelectorAll('.reveal');
      reveals.forEach(el => observer.observe(el));

      const sections = document.querySelectorAll('.seccion-categoria');
      sections.forEach(section => observer.observe(section));

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