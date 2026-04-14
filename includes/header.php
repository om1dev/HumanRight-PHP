<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?? SITE_NAME ?></title>
<meta name="description" content="Human rights advocacy, legal aid, and policy research — working in 40+ countries since 2009.">
<meta property="og:title" content="<?= $pageTitle ?? SITE_NAME ?>">
<meta property="og:description" content="Human rights advocacy, legal aid, and policy research — working in 40+ countries since 2009.">
<meta property="og:type" content="website">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          ink:     '#111827',
          navy:    '#1E3A5F',
          blue:    '#2563EB',
          red:     '#DC2626',
          slate:   '#F8FAFC',
          warm:    '#F9F7F4',
        },
        fontFamily: {
          serif: ['Lora', 'Georgia', 'serif'],
          sans:  ['Inter', 'system-ui', 'sans-serif'],
        }
      }
    }
  }
</script>
<style>
  *, body { font-family: 'Inter', system-ui, sans-serif; }
  html { scroll-behavior: smooth; }
  body { color: #1f2937; background: #ffffff; }
  h1, h2, h3, h4, .font-serif { font-family: 'Lora', Georgia, serif; }
  ::selection { background: #DBEAFE; color: #1E3A5F; }

  /* Scroll reveal */
  .sr  { opacity:0; transform:translateY(24px); transition:opacity .6s ease, transform .6s ease; }
  .sr.on  { opacity:1; transform:none; }
  .sr-l { opacity:0; transform:translateX(-24px); transition:opacity .6s ease, transform .6s ease; }
  .sr-l.on { opacity:1; transform:none; }
  .sr-r { opacity:0; transform:translateX(24px); transition:opacity .6s ease, transform .6s ease; }
  .sr-r.on { opacity:1; transform:none; }

  /* Navbar */
  #nav { transition: box-shadow .2s ease, background .2s ease; }
  #nav.scrolled { box-shadow: 0 1px 12px rgba(0,0,0,.08); background: rgba(255,255,255,.98) !important; }

  /* Reading bar */
  #rbar { position:fixed; top:0; left:0; height:3px; background:#2563EB; width:0; z-index:9999; transition:width .08s linear; }

  /* Card hover */
  .card-hover { transition: box-shadow .2s ease, transform .2s ease; }
  .card-hover:hover { box-shadow: 0 8px 30px rgba(0,0,0,.1); transform: translateY(-2px); }

  /* Shared auth/forms UI */
  .surface-card {
    background: #ffffff;
    border: 1px solid #E5E7EB;
    border-radius: 1rem;
    box-shadow: 0 10px 28px rgba(17, 24, 39, .06);
  }
  .surface-card-soft {
    background: #F8FAFC;
    border: 1px solid #E5E7EB;
    border-radius: .85rem;
  }
  .section-chip {
    display: inline-flex;
    align-items: center;
    border: 1px solid #DBEAFE;
    background: #EFF6FF;
    color: #1D4ED8;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    border-radius: 999px;
    padding: .38rem .7rem;
  }
  .ui-input,
  .ui-textarea,
  .ui-select {
    width: 100%;
    background: #ffffff;
    color: #111827;
    border: 1px solid #CBD5E1;
    border-radius: .75rem;
  }
  .ui-input::placeholder,
  .ui-textarea::placeholder { color: #9CA3AF; }
  .ui-input:focus,
  .ui-textarea:focus,
  .ui-select:focus {
    outline: none;
    border-color: #2563EB;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, .15);
  }
  .ui-button-primary {
    background: #1E3A5F;
  }
  .ui-button-primary:hover {
    background: #2563EB;
  }

  /* Article prose */
  .prose-body { font-size: 1.0625rem; line-height: 1.85; color: #374151; }
  .prose-body h2 { font-family:'Lora',serif; font-size:1.6rem; font-weight:600; color:#111827; margin:2.5rem 0 .75rem; }
  .prose-body h3 { font-family:'Lora',serif; font-size:1.25rem; font-weight:600; color:#111827; margin:2rem 0 .5rem; }
  .prose-body p  { margin-bottom:1.4rem; }
  .prose-body ul { list-style:disc; padding-left:1.5rem; margin-bottom:1.4rem; }
  .prose-body ol { list-style:decimal; padding-left:1.5rem; margin-bottom:1.4rem; }
  .prose-body blockquote { border-left:4px solid #2563EB; padding:.875rem 1.25rem; background:#EFF6FF; margin:2rem 0; font-style:italic; color:#374151; }
  .prose-body a { color:#2563EB; text-decoration:underline; text-underline-offset:2px; }
  .prose-body strong { color:#111827; font-weight:600; }
  .prose-body img { border-radius:8px; margin:1.5rem 0; width:100%; }

  /* Marquee */
  @keyframes marquee { 0%{transform:translateX(0)} 100%{transform:translateX(-50%)} }
</style>
</head>
<body class="bg-white text-gray-800 flex flex-col min-h-screen antialiased">

<div id="rbar"></div>

<!-- NAVBAR -->
<?php
$currentPath = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/', '/') ?: '/';
$navItems = [
  ['Home',     SITE_URL.'/',        ['/', '/index.php']],
  ['About',    SITE_URL.'/about',   ['/about']],
  ['Blog', SITE_URL.'/blog',    ['/blog', '/single-blog']],
  ['Contact',  SITE_URL.'/contact', ['/contact']],
];
$isActive = fn($patterns) => in_array($currentPath, $patterns) || array_filter($patterns, fn($p) => $p !== '/' && str_starts_with($currentPath, $p));
?>
<nav id="nav" class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200">
  <div class="max-w-6xl mx-auto px-5 sm:px-8 h-16 flex items-center justify-between gap-6">

    <!-- Logo -->
    <a href="<?= SITE_URL ?>/" class="flex items-center gap-3 flex-shrink-0">
      <div class="w-8 h-8 bg-navy rounded flex items-center justify-center flex-shrink-0">
        <i class="fa-solid fa-scale-balanced text-white text-xs"></i>
      </div>
      <div>
        <span class="font-serif font-semibold text-navy text-[0.95rem] leading-none block"><?= SITE_NAME ?></span>
        <span class="text-[9px] text-gray-400 tracking-widest uppercase leading-none block mt-0.5">Est. 2009</span>
      </div>
    </a>

    <!-- Desktop nav -->
    <ul class="hidden md:flex items-center gap-1">
      <?php foreach ($navItems as [$label, $url, $patterns]): ?>
        <?php $active = $isActive($patterns); ?>
        <li>
          <a href="<?= $url ?>"
             class="px-4 py-2 text-sm font-medium rounded transition-colors <?= $active ? 'text-blue bg-blue/8' : 'text-gray-600 hover:text-ink hover:bg-gray-50' ?>"
             <?= $active ? 'aria-current="page"' : '' ?>><?= $label ?></a>
        </li>
      <?php endforeach; ?>
    </ul>

    <!-- Auth + CTA -->
    <div class="hidden md:flex items-center gap-3">
      <?php if (isLoggedIn()): ?>
        <?php $navUser = $db->users->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]); ?>
        <div class="flex items-center gap-2">
          <?php if (!empty($navUser['photo'])): ?>
            <img src="<?= sanitize($navUser['photo']) ?>" class="w-7 h-7 rounded-full object-cover border border-gray-200">
          <?php else: ?>
            <div class="w-7 h-7 rounded-full bg-navy text-white flex items-center justify-center text-xs font-semibold"><?= strtoupper(substr($_SESSION['username'],0,1)) ?></div>
          <?php endif; ?>
          <a href="<?= SITE_URL ?>/user/profile" class="text-sm text-gray-700 hover:text-ink font-medium"><?= sanitize($_SESSION['username']) ?></a>
        </div>
        <a href="<?= SITE_URL ?>/auth/logout" class="text-sm text-gray-400 hover:text-red-500">Logout</a>
      <?php else: ?>
        <a href="<?= SITE_URL ?>/auth/login" class="text-sm text-gray-600 hover:text-ink font-medium">Login</a>
        <a href="<?= SITE_URL ?>/auth/signup" class="bg-navy text-white text-sm font-semibold px-4 py-2 rounded hover:bg-blue transition-colors">Join Us</a>
      <?php endif; ?>
    </div>

    <!-- Hamburger -->
    <button id="menuBtn" class="md:hidden p-2 text-gray-600 hover:text-ink" aria-label="Menu">
      <i class="fa-solid fa-bars text-lg"></i>
    </button>
  </div>

  <!-- Mobile menu -->
  <div id="mobileMenu" class="hidden md:hidden border-t border-gray-100 bg-white px-5 py-4">
    <?php foreach ($navItems as [$label, $url, $patterns]): ?>
      <?php $active = $isActive($patterns); ?>
      <a href="<?= $url ?>" class="flex items-center justify-between py-3 text-sm font-medium border-b border-gray-50 <?= $active ? 'text-blue' : 'text-gray-700 hover:text-ink' ?>">
        <?= $label ?> <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
      </a>
    <?php endforeach; ?>
    <div class="pt-4 flex gap-3">
      <?php if (isLoggedIn()): ?>
        <a href="<?= SITE_URL ?>/user/profile" class="text-sm text-navy font-semibold"><?= sanitize($_SESSION['username']) ?></a>
        <a href="<?= SITE_URL ?>/auth/logout" class="text-sm text-red-500">Logout</a>
      <?php else: ?>
        <a href="<?= SITE_URL ?>/auth/login" class="text-sm text-gray-600 font-medium">Login</a>
        <a href="<?= SITE_URL ?>/auth/signup" class="bg-navy text-white text-sm font-semibold px-4 py-2 rounded">Join Us</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div class="h-16"></div>
<main class="flex-1">

<script>
  document.getElementById('menuBtn').addEventListener('click', () => document.getElementById('mobileMenu').classList.toggle('hidden'));
  window.addEventListener('scroll', () => {
    document.getElementById('nav').classList.toggle('scrolled', window.scrollY > 20);
    const b = document.getElementById('rbar');
    if (b) { const d = document.documentElement; b.style.width = (d.scrollTop / (d.scrollHeight - d.clientHeight) * 100) + '%'; }
  });
  const sro = new IntersectionObserver(es => es.forEach(e => { if (e.isIntersecting) e.target.classList.add('on'); }), { threshold: 0.08 });
  document.addEventListener('DOMContentLoaded', () => document.querySelectorAll('.sr,.sr-l,.sr-r').forEach(el => sro.observe(el)));
</script>
