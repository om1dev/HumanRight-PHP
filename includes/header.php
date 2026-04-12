<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?? SITE_NAME ?></title>
<meta name="description" content="Shifting Power. Advancing Justice. Building Equitable Futures — a global human rights advocacy platform.">
<meta property="og:title" content="<?= $pageTitle ?? SITE_NAME ?>">
<meta property="og:description" content="Shifting Power. Advancing Justice. Building Equitable Futures.">
<meta property="og:type" content="website">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;0,700;0,900;1,400;1,700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          ink:     '#0C1724',
          primary: '#3044C8',
          accent:  '#D1495B',
          mist:    '#F4F7FD',
          blush:   '#FFF4F5',
        },
        fontFamily: {
          serif: ['Fraunces', 'Georgia', 'serif'],
          sans:  ['Inter', 'system-ui', 'sans-serif'],
        }
      }
    }
  }
</script>
<style>
  *, body { font-family: 'Inter', system-ui, sans-serif; }
  html { scroll-behavior: smooth; }
  body {
    color: #243041;
    background:
      radial-gradient(circle at top left, rgba(48, 68, 200, .08), transparent 30%),
      radial-gradient(circle at top right, rgba(209, 73, 91, .07), transparent 24%),
      linear-gradient(180deg, #f8fbff 0%, #ffffff 34%, #f7f9fe 100%);
  }
  ::selection { background: rgba(48, 68, 200, .18); color: #0c1724; }
  h1,h2,h3,h4,.font-serif { font-family: 'Fraunces', Georgia, serif; }
  .site-shell { position: relative; isolation: isolate; }
  .site-shell::before {
    content: '';
    position: fixed;
    inset: 0;
    pointer-events: none;
    opacity: .45;
    z-index: -1;
    background-image: radial-gradient(rgba(48, 68, 200, .08) 1px, transparent 1px);
    background-size: 28px 28px;
    mask-image: linear-gradient(180deg, rgba(0,0,0,.28), transparent 40%);
  }
  .glass-panel {
    background: rgba(255, 255, 255, .78);
    backdrop-filter: blur(18px);
    border: 1px solid rgba(148, 163, 184, .18);
    box-shadow: 0 18px 50px rgba(13, 27, 42, .08);
  }
  .section-chip {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .45rem .8rem;
    border-radius: 999px;
    border: 1px solid rgba(48, 68, 200, .12);
    background: rgba(48, 68, 200, .08);
    color: #3044c8;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .14em;
    text-transform: uppercase;
  }
  .surface-card {
    background: rgba(255,255,255,.9);
    border: 1px solid rgba(148, 163, 184, .14);
    border-radius: 1.5rem;
    box-shadow: 0 18px 50px rgba(13, 27, 42, .08);
  }
  .surface-card-soft {
    background: rgba(244, 247, 253, .9);
    border: 1px solid rgba(148, 163, 184, .12);
    border-radius: 1.5rem;
  }
  .text-balance { text-wrap: balance; }

  /* ── Scroll reveal ── */
  .sr { opacity:0; transform:translateY(36px); transition:opacity .7s cubic-bezier(.16,1,.3,1), transform .7s cubic-bezier(.16,1,.3,1); }
  .sr.on { opacity:1; transform:none; }
  .sr-l { opacity:0; transform:translateX(-36px); transition:opacity .7s cubic-bezier(.16,1,.3,1), transform .7s cubic-bezier(.16,1,.3,1); }
  .sr-l.on { opacity:1; transform:none; }
  .sr-r { opacity:0; transform:translateX(36px); transition:opacity .7s cubic-bezier(.16,1,.3,1), transform .7s cubic-bezier(.16,1,.3,1); }
  .sr-r.on { opacity:1; transform:none; }

  /* ── Navbar ── */
  #nav { transition: background .3s, box-shadow .3s, backdrop-filter .3s; }
  #nav.scrolled { background: rgba(255,255,255,.95) !important; backdrop-filter: blur(12px); box-shadow: 0 1px 0 rgba(0,0,0,.06), 0 4px 24px rgba(13,27,42,.06); }

  /* ── Nav link hover ── */
  .nl { position:relative; }
  .nl::after { content:''; position:absolute; bottom:-2px; left:0; width:0; height:1.5px; background:#2D3A8C; transition:width .25s ease; }
  .nl:hover::after { width:100%; }

  /* ── Reading bar ── */
  #rbar { position:fixed; top:0; left:0; height:2.5px; background:linear-gradient(90deg,#2D3A8C,#C0392B); width:0; z-index:9999; transition:width .08s linear; }

  /* ── Card hover ── */
  .lift { transition:transform .3s cubic-bezier(.16,1,.3,1), box-shadow .3s ease; }
  .lift:hover { transform:translateY(-6px); box-shadow:0 20px 60px rgba(13,27,42,.12); }

  /* ── Prose ── */
  .prose-body { font-size:1.075rem; line-height:1.9; color:#374151; }
  .prose-body h2 { font-family:'Fraunces',serif; font-size:1.7rem; color:#0D1B2A; margin:2.5rem 0 .8rem; }
  .prose-body h3 { font-family:'Fraunces',serif; font-size:1.3rem; color:#0D1B2A; margin:2rem 0 .5rem; }
  .prose-body p  { margin-bottom:1.4rem; }
  .prose-body ul { list-style:disc; padding-left:1.5rem; margin-bottom:1.4rem; }
  .prose-body ol { list-style:decimal; padding-left:1.5rem; margin-bottom:1.4rem; }
  .prose-body blockquote { border-left:3px solid #2D3A8C; padding:1rem 1.5rem; background:#F4F5FB; margin:2rem 0; font-style:italic; color:#4B5563; border-radius:0 10px 10px 0; }
  .prose-body a { color:#2D3A8C; text-decoration:underline; text-underline-offset:3px; }
  .prose-body strong { color:#0D1B2A; font-weight:600; }
  .prose-body img { border-radius:14px; margin:1.5rem 0; width:100%; }

  /* ── Hero counter animation ── */
  @keyframes countUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
  .stat-num { animation: countUp .6s cubic-bezier(.16,1,.3,1) both; }

  /* ── Floating badge ── */
  @keyframes floatY { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
  .float { animation: floatY 4s ease-in-out infinite; }
  .float-d { animation: floatY 4s ease-in-out 1.5s infinite; }

  /* ── Tag pill hover ── */
  .tag-pill { transition: background .2s, color .2s, border-color .2s; }
  .tag-pill:hover { background:#2D3A8C; color:#fff; border-color:#2D3A8C; }
  .ui-input, .ui-textarea, .ui-select {
    background: rgba(255,255,255,.94);
    border: 1px solid rgba(148, 163, 184, .22);
    border-radius: 1rem;
    transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
  }
  .ui-input:focus, .ui-textarea:focus, .ui-select:focus {
    outline: none;
    border-color: rgba(48, 68, 200, .35);
    box-shadow: 0 0 0 4px rgba(48, 68, 200, .12);
  }
  .ui-button-primary {
    background: linear-gradient(135deg, #3044c8 0%, #2335a1 100%);
    box-shadow: 0 16px 30px rgba(48, 68, 200, .22);
  }
  .ui-button-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 20px 38px rgba(48, 68, 200, .26);
  }
  .ui-button-soft {
    background: rgba(255,255,255,.9);
    border: 1px solid rgba(148, 163, 184, .2);
    box-shadow: 0 10px 28px rgba(13, 27, 42, .05);
  }
  .ui-button-soft:hover {
    border-color: rgba(48, 68, 200, .22);
    color: #3044c8;
    transform: translateY(-1px);
  }
</style>
</head>
<body class="site-shell bg-white text-gray-800 flex flex-col min-h-screen antialiased">

<div id="rbar"></div>

<!-- ══════════════════════════════════════════
     NAVBAR
══════════════════════════════════════════ -->
<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$currentPath = rtrim($currentPath, '/');
$currentPath = $currentPath === '' ? '/' : $currentPath;

$navIsActive = function (array $patterns) use ($currentPath): bool {
  foreach ($patterns as $pattern) {
    if ($pattern === '/' && $currentPath === '/') {
      return true;
    }
    if ($pattern !== '/' && ($currentPath === $pattern || strpos($currentPath, $pattern . '/') === 0)) {
      return true;
    }
  }
  return false;
};

$navItems = [
  ['label' => 'Home', 'url' => SITE_URL . '/',        'patterns' => ['/', '/index.php']],
  ['label' => 'About', 'url' => SITE_URL . '/about',   'patterns' => ['/about', '/about.php']],
  ['label' => 'Insights', 'url' => SITE_URL . '/blog', 'patterns' => ['/blog', '/blog.php', '/single-blog', '/single-blog.php']],
  ['label' => 'Contact', 'url' => SITE_URL . '/contact', 'patterns' => ['/contact', '/contact.php']],
];
?>
<nav id="nav" class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-xl border-b border-white/60 shadow-[0_8px_32px_rgba(15,23,42,.06)]">
  <div class="max-w-7xl mx-auto px-4 sm:px-8 h-[78px] sm:h-[72px] flex items-center justify-between gap-3">

    <!-- Logo -->
    <a href="<?= SITE_URL ?>/" class="flex items-center gap-2.5 sm:gap-3 min-w-0 flex-1 sm:flex-none group pr-2">
      <!-- SVG Logo mark -->
      <div class="relative w-9 h-9 sm:w-10 sm:h-10 flex-shrink-0">
        <svg viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
          <rect width="36" height="36" rx="10" fill="#0D1B2A"/>
          <path d="M18 7 L18 29" stroke="#2D3A8C" stroke-width="2" stroke-linecap="round"/>
          <path d="M10 13 L18 7 L26 13" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M10 13 L10 22 Q10 25 13 25 L23 25 Q26 25 26 22 L26 13" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
          <circle cx="18" cy="29" r="1.5" fill="#C0392B"/>
        </svg>
      </div>
      <div class="leading-tight min-w-0">
        <p class="font-serif text-ink text-[0.95rem] sm:text-[1.05rem] tracking-tight whitespace-normal break-words max-w-[220px] sm:max-w-none"><?= SITE_NAME ?></p>
        <p class="text-primary/85 text-[8px] sm:text-[9px] font-semibold tracking-[0.16em] uppercase mt-0.5 whitespace-nowrap">Justice · Equity · Rights</p>
      </div>
    </a>

    <!-- Desktop nav -->
    <ul class="hidden md:flex items-center gap-2 text-[0.875rem] font-medium text-gray-500 bg-white/75 border border-gray-100 rounded-full px-2 py-2 shadow-sm">
      <?php foreach ($navItems as $item): ?>
        <?php $isActive = $navIsActive($item['patterns']); ?>
        <li>
          <a href="<?= $item['url'] ?>"
             class="nl px-4 py-2 rounded-full transition-colors <?= $isActive ? 'bg-ink text-white shadow-sm hover:text-white' : 'hover:text-ink' ?>"
             <?= $isActive ? 'aria-current="page"' : '' ?>><?= $item['label'] ?></a>
        </li>
      <?php endforeach; ?>
    </ul>

    <!-- Auth -->
    <div class="hidden md:flex items-center gap-3">
      <?php if (isLoggedIn()): ?>
        <?php $navUser = $db->users->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]); ?>
        <div class="flex items-center gap-2 bg-white/80 border border-gray-100 rounded-full px-2 py-1 shadow-sm">
          <?php if (!empty($navUser['photo'])): ?>
            <img src="<?= sanitize($navUser['photo']) ?>" class="w-7 h-7 rounded-full object-cover ring-2 ring-primary/30">
          <?php else: ?>
            <div class="w-7 h-7 rounded-full bg-ink text-white flex items-center justify-center text-xs font-semibold"><?= strtoupper(substr($_SESSION['username'],0,1)) ?></div>
          <?php endif; ?>
          <a href="<?= SITE_URL ?>/user/profile" class="text-sm text-gray-700 hover:text-ink font-medium transition-colors pr-2"><?= sanitize($_SESSION['username']) ?></a>
        </div>
        <a href="<?= SITE_URL ?>/auth/logout" class="text-sm text-gray-500 hover:text-red-500 transition-colors font-medium">Logout</a>
      <?php else: ?>
        <a href="<?= SITE_URL ?>/auth/login" class="text-sm text-gray-600 hover:text-ink transition-colors font-medium">Login</a>
        <a href="<?= SITE_URL ?>/auth/signup" class="ui-button-primary text-white text-sm font-semibold px-5 py-2.5 rounded-full transition-all">Join Us</a>
      <?php endif; ?>
    </div>

    <!-- Hamburger -->
    <button id="menuBtn" class="md:hidden inline-flex items-center justify-center w-11 h-11 text-gray-600 hover:text-ink transition-colors rounded-full bg-white border border-gray-200 shadow-sm flex-shrink-0" aria-label="Menu">
      <i class="fa-solid fa-bars text-lg"></i>
    </button>
  </div>

  <!-- Mobile menu -->
  <div id="mobileMenu" class="hidden md:hidden border-t border-gray-100 bg-white/98 backdrop-blur-xl px-4 sm:px-5 py-4 shadow-lg">
    <div class="grid grid-cols-2 gap-2 mb-3">
      <?php if (isLoggedIn()): ?>
        <a href="<?= SITE_URL ?>/user/profile" class="ui-button-soft flex items-center justify-center gap-2 px-4 py-3 rounded-2xl text-sm font-semibold text-ink">
          <i class="fa-solid fa-user text-primary text-xs"></i> Profile
        </a>
        <a href="<?= SITE_URL ?>/auth/logout" class="ui-button-soft flex items-center justify-center gap-2 px-4 py-3 rounded-2xl text-sm font-semibold text-red-500">
          <i class="fa-solid fa-right-from-bracket text-xs"></i> Logout
        </a>
      <?php else: ?>
        <a href="<?= SITE_URL ?>/auth/login" class="ui-button-soft flex items-center justify-center gap-2 px-4 py-3 rounded-2xl text-sm font-semibold text-ink">
          <i class="fa-solid fa-right-to-bracket text-primary text-xs"></i> Login
        </a>
        <a href="<?= SITE_URL ?>/auth/signup" class="ui-button-primary flex items-center justify-center gap-2 px-4 py-3 rounded-2xl text-sm font-semibold text-white">
          <i class="fa-solid fa-user-plus text-xs"></i> Join
        </a>
      <?php endif; ?>
    </div>
    <div class="space-y-1">
      <?php foreach ($navItems as $item): ?>
        <?php $isActive = $navIsActive($item['patterns']); ?>
        <a href="<?= $item['url'] ?>"
           class="flex items-center justify-between py-3.5 px-3 text-sm font-medium rounded-2xl transition-colors <?= $isActive ? 'bg-primary/10 text-primary border border-primary/20' : 'text-gray-700 hover:text-ink hover:bg-gray-50' ?>"
           <?= $isActive ? 'aria-current="page"' : '' ?>>
          <span><?= $item['label'] ?></span>
          <i class="fa-solid fa-chevron-right text-xs <?= $isActive ? 'text-primary/70' : 'text-gray-300' ?>"></i>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</nav>

<div class="h-[78px] sm:h-[72px]"></div>
<main class="flex-1">

<script>
  const publicMenuBtn = document.getElementById('menuBtn');
  const publicMobileMenu = document.getElementById('mobileMenu');
  if (publicMenuBtn && publicMobileMenu) {
    publicMenuBtn.addEventListener('click', () => publicMobileMenu.classList.toggle('hidden'));
  }
  window.addEventListener('scroll',()=>{
    document.getElementById('nav').classList.toggle('scrolled', window.scrollY > 30);
    const b=document.getElementById('rbar');
    if(b){const d=document.documentElement;b.style.width=(d.scrollTop/(d.scrollHeight-d.clientHeight)*100)+'%';}
  });
  const sro=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting)e.target.classList.add('on');}),{threshold:.08});
  document.addEventListener('DOMContentLoaded',()=>document.querySelectorAll('.sr,.sr-l,.sr-r').forEach(el=>sro.observe(el)));
</script>
