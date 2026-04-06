<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?? 'Admin — ' . SITE_NAME ?></title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex min-h-screen">

<!-- Sidebar -->
<aside class="w-64 bg-blue-900 text-white flex flex-col min-h-screen fixed top-0 left-0 z-30">
  <div class="px-6 py-5 border-b border-blue-800">
    <p class="text-xs text-blue-300 uppercase tracking-widest">Admin Panel</p>
    <p class="font-bold text-lg mt-1"><?= SITE_NAME ?></p>
  </div>
  <nav class="flex-1 px-4 py-6 space-y-1 text-sm">
    <?php
    $current = basename($_SERVER['PHP_SELF']);
    $dir     = basename(dirname($_SERVER['PHP_SELF']));
    function navLink(string $href, string $icon, string $label, bool $active): string {
        $cls = $active ? 'bg-blue-700' : 'hover:bg-blue-800';
        return "<a href=\"$href\" class=\"flex items-center gap-3 px-4 py-2.5 rounded-lg $cls transition\">
          <i class=\"fa-solid $icon w-4\"></i> $label</a>";
    }
    echo navLink(SITE_URL.'/admin/index.php',          'fa-gauge',     'Dashboard', $current==='index.php' && $dir==='admin');
    echo navLink(SITE_URL.'/admin/blogs/index.php',    'fa-newspaper', 'Blogs',     $dir==='blogs');
    echo navLink(SITE_URL.'/admin/users/index.php',    'fa-users',     'Users',     $dir==='users');
    echo navLink(SITE_URL.'/admin/comments/index.php', 'fa-comments',  'Comments',  $dir==='comments');
    echo navLink(SITE_URL.'/admin/messages/index.php', 'fa-envelope',  'Messages',  $dir==='messages');
    ?>
  </nav>
  <div class="px-4 py-4 border-t border-blue-800">
    <a href="<?= SITE_URL ?>/admin/logout.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-red-700 transition text-sm">
      <i class="fa-solid fa-right-from-bracket w-4"></i> Logout
    </a>
  </div>
</aside>

<!-- Main -->
<div class="ml-64 flex-1 flex flex-col">
<header class="bg-white shadow px-8 py-4 flex items-center justify-between">
  <h1 class="text-lg font-bold text-gray-700"><?= $pageTitle ?? 'Dashboard' ?></h1>
  <span class="text-sm text-gray-500"><i class="fa-solid fa-user-shield mr-1 text-blue-700"></i><?= sanitize($_SESSION['admin_username'] ?? 'Admin') ?></span>
</header>
<main class="flex-1 p-8">
