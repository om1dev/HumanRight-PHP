<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?? SITE_NAME ?></title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

<!-- Navbar -->
<nav class="bg-blue-900 text-white shadow-lg">
  <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
    <a href="<?= SITE_URL ?>/" class="text-xl font-bold tracking-wide">
      <i class="fa-solid fa-scale-balanced mr-2"></i><?= SITE_NAME ?>
    </a>
    <button id="menuBtn" class="md:hidden focus:outline-none">
      <i class="fa-solid fa-bars text-2xl"></i>
    </button>
    <ul id="navMenu" class="hidden md:flex gap-6 text-sm font-medium items-center">
      <li><a href="<?= SITE_URL ?>/" class="hover:text-yellow-300">Home</a></li>
      <li><a href="<?= SITE_URL ?>/about" class="hover:text-yellow-300">About</a></li>
      <li><a href="<?= SITE_URL ?>/blog" class="hover:text-yellow-300">Blog</a></li>
      <li><a href="<?= SITE_URL ?>/contact" class="hover:text-yellow-300">Contact</a></li>
      <?php if (isLoggedIn()): ?>
        <?php
          $navUser = $db->users->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);
        ?>
        <li class="flex items-center gap-2">
          <?php if (!empty($navUser['photo'])): ?>
            <img src="<?= sanitize($navUser['photo']) ?>" class="w-7 h-7 rounded-full object-cover border-2 border-yellow-300">
          <?php else: ?>
            <div class="w-7 h-7 rounded-full bg-yellow-400 text-blue-900 flex items-center justify-center text-xs font-bold"><?= strtoupper(substr($_SESSION['username'], 0, 1)) ?></div>
          <?php endif; ?>
          <a href="<?= SITE_URL ?>/user/profile" class="text-yellow-300 hover:underline text-sm"><?= sanitize($_SESSION['username']) ?></a>
        </li>
        <li><a href="<?= SITE_URL ?>/auth/logout" class="bg-red-500 px-3 py-1 rounded hover:bg-red-600">Logout</a></li>
      <?php else: ?>
        <li><a href="<?= SITE_URL ?>/auth/login" class="hover:text-yellow-300">Login</a></li>
        <li><a href="<?= SITE_URL ?>/auth/signup" class="bg-yellow-400 text-blue-900 px-3 py-1 rounded font-bold hover:bg-yellow-300">Sign Up</a></li>
      <?php endif; ?>
    </ul>
  </div>
  <!-- Mobile menu -->
  <div id="mobileMenu" class="hidden md:hidden px-4 pb-3 space-y-2 text-sm">
    <a href="<?= SITE_URL ?>/" class="block hover:text-yellow-300">Home</a>
    <a href="<?= SITE_URL ?>/about" class="block hover:text-yellow-300">About</a>
    <a href="<?= SITE_URL ?>/blog" class="block hover:text-yellow-300">Blog</a>
    <a href="<?= SITE_URL ?>/contact" class="block hover:text-yellow-300">Contact</a>
    <?php if (isLoggedIn()): ?>
      <a href="<?= SITE_URL ?>/auth/logout" class="block text-red-300">Logout</a>
    <?php else: ?>
      <a href="<?= SITE_URL ?>/auth/login" class="block hover:text-yellow-300">Login</a>
      <a href="<?= SITE_URL ?>/auth/signup" class="block hover:text-yellow-300">Sign Up</a>
    <?php endif; ?>
  </div>
</nav>
<main class="flex-1">
