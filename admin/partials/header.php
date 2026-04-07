<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?? 'Admin — ' . SITE_NAME ?></title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 flex min-h-screen">

<!-- Sidebar -->
<aside class="w-64 bg-blue-900 text-white flex flex-col min-h-screen fixed top-0 left-0 z-30 overflow-y-auto">
  <div class="px-6 py-5 border-b border-blue-800">
    <p class="text-xs text-blue-300 uppercase tracking-widest">Admin Panel</p>
    <p class="font-bold text-lg mt-1"><?= SITE_NAME ?></p>
  </div>
  <nav class="flex-1 px-4 py-4 space-y-0.5 text-sm">
    <?php
    $current = basename($_SERVER['PHP_SELF']);
    $dir     = basename(dirname($_SERVER['PHP_SELF']));
    function navLink(string $href, string $icon, string $label, bool $active, string $badge = ''): string {
        $cls = $active ? 'bg-blue-700' : 'hover:bg-blue-800';
        $b   = $badge ? "<span class=\"ml-auto bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full\">{$badge}</span>" : '';
        return "<a href=\"$href\" class=\"flex items-center gap-3 px-4 py-2.5 rounded-lg $cls transition\">
          <i class=\"fa-solid $icon w-4 text-center\"></i> $label $b</a>";
    }
    function navGroup(string $label): string {
        return "<p class=\"text-xs text-blue-400 uppercase tracking-widest px-4 pt-4 pb-1\">{$label}</p>";
    }

    $unreadCount = '';
    try {
        global $db;
        $uc = $db->contacts->countDocuments(['read' => false]);
        if ($uc > 0) $unreadCount = (string)$uc;
        $pendingComments = $db->comments->countDocuments(['approved' => false]);
        $pendingBadge = $pendingComments > 0 ? (string)$pendingComments : '';
    } catch(Exception $e) { $pendingBadge = ''; }

    echo navGroup('Main');
    echo navLink(SITE_URL.'/admin/',                   'fa-gauge',        'Dashboard',   $current==='index.php' && $dir==='admin');
    echo navGroup('Content');
    echo navLink(SITE_URL.'/admin/blogs/',             'fa-newspaper',    'Blogs',       $dir==='blogs');
    echo navLink(SITE_URL.'/admin/categories/',        'fa-tags',         'Categories',  $dir==='categories');
    echo navLink(SITE_URL.'/admin/comments/',          'fa-comments',     'Comments',    $dir==='comments', $pendingBadge);
    echo navLink(SITE_URL.'/admin/events/',            'fa-calendar-star','Events',      $dir==='events');
    echo navGroup('People');
    echo navLink(SITE_URL.'/admin/users/',             'fa-users',        'Users',       $dir==='users');
    echo navLink(SITE_URL.'/admin/admins/',            'fa-user-shield',  'Admins',      $dir==='admins');
    echo navGroup('Communication');
    echo navLink(SITE_URL.'/admin/messages/',          'fa-envelope',     'Messages',    $dir==='messages', $unreadCount);
    echo navGroup('System');
    echo navLink(SITE_URL.'/admin/activity/',          'fa-clock-rotate-left', 'Activity Log', $dir==='activity');
    echo navLink(SITE_URL.'/admin/settings/',          'fa-gear',         'Settings',    $dir==='settings');
    ?>
  </nav>
  <div class="px-4 py-4 border-t border-blue-800 space-y-1">
    <a href="<?= SITE_URL ?>/" target="_blank" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-800 transition text-sm text-blue-300">
      <i class="fa-solid fa-arrow-up-right-from-square w-4"></i> View Website
    </a>
    <a href="<?= SITE_URL ?>/admin/logout" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-red-700 transition text-sm">
      <i class="fa-solid fa-right-from-bracket w-4"></i> Logout
    </a>
  </div>
</aside>

<!-- Main -->
<div class="ml-64 flex-1 flex flex-col">
<header class="bg-white shadow px-8 py-4 flex items-center justify-between sticky top-0 z-20">
  <h1 class="text-lg font-bold text-gray-700"><?= $pageTitle ?? 'Dashboard' ?></h1>
  <div class="flex items-center gap-4">

    <!-- Bell Notification -->
    <a href="<?= SITE_URL ?>/admin/messages/" class="relative text-gray-400 hover:text-blue-700">
      <i class="fa-solid fa-bell text-xl"></i>
      <?php if (!empty($unreadCount)): ?>
        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center"><?= $unreadCount ?></span>
      <?php endif; ?>
    </a>

    <!-- Admin Profile Dropdown -->
    <?php
      $adminData = $db->admins->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);
    ?>
    <div class="relative" id="adminDropdownWrap">
      <!-- Trigger Button -->
      <button id="adminDropdownBtn"
        class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-xl transition focus:outline-none">
        <?php if (!empty($adminData['photo'])): ?>
          <img src="<?= sanitize($adminData['photo']) ?>" class="w-8 h-8 rounded-full object-cover border-2 border-blue-300">
        <?php else: ?>
          <div class="w-8 h-8 rounded-full bg-blue-700 text-white flex items-center justify-center text-sm font-bold">
            <?= strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)) ?>
          </div>
        <?php endif; ?>
        <div class="text-left hidden sm:block">
          <p class="text-sm font-semibold text-gray-700 leading-tight"><?= sanitize($_SESSION['admin_username'] ?? 'Admin') ?></p>
          <p class="text-xs text-gray-400 leading-tight">Administrator</p>
        </div>
        <i class="fa-solid fa-chevron-down text-xs text-gray-400 ml-1"></i>
      </button>

      <!-- Dropdown Menu -->
      <div id="adminDropdownMenu"
        class="hidden absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden">

        <!-- Header -->
        <div class="px-5 py-4 bg-blue-50 border-b border-gray-100 flex items-center gap-3">
          <?php if (!empty($adminData['photo'])): ?>
            <img src="<?= sanitize($adminData['photo']) ?>" class="w-12 h-12 rounded-full object-cover border-2 border-blue-200">
          <?php else: ?>
            <div class="w-12 h-12 rounded-full bg-blue-700 text-white flex items-center justify-center text-xl font-bold">
              <?= strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)) ?>
            </div>
          <?php endif; ?>
          <div>
            <p class="font-bold text-gray-800 text-sm"><?= sanitize($_SESSION['admin_username'] ?? 'Admin') ?></p>
            <p class="text-xs text-gray-400"><?= sanitize($adminData['email'] ?? '') ?></p>
            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-semibold mt-0.5 inline-block">Administrator</span>
          </div>
        </div>

        <!-- Menu Items -->
        <div class="py-2">
          <a href="<?= SITE_URL ?>/admin/profile"
            class="flex items-center gap-3 px-5 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
            <i class="fa-solid fa-user w-4 text-blue-500"></i> My Profile
          </a>
          <a href="<?= SITE_URL ?>/admin/settings/"
            class="flex items-center gap-3 px-5 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
            <i class="fa-solid fa-gear w-4 text-gray-400"></i> Settings
          </a>
          <a href="<?= SITE_URL ?>/admin/admins/"
            class="flex items-center gap-3 px-5 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
            <i class="fa-solid fa-user-shield w-4 text-purple-500"></i> Manage Admins
          </a>
          <a href="<?= SITE_URL ?>/" target="_blank"
            class="flex items-center gap-3 px-5 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
            <i class="fa-solid fa-arrow-up-right-from-square w-4 text-green-500"></i> View Website
          </a>
          <a href="<?= SITE_URL ?>/admin/activity/"
            class="flex items-center gap-3 px-5 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
            <i class="fa-solid fa-clock-rotate-left w-4 text-yellow-500"></i> Activity Log
          </a>
        </div>

        <!-- Logout -->
        <div class="border-t border-gray-100 py-2">
          <a href="<?= SITE_URL ?>/admin/logout"
            class="flex items-center gap-3 px-5 py-2.5 text-sm text-red-600 hover:bg-red-50 transition font-semibold">
            <i class="fa-solid fa-right-from-bracket w-4"></i> Logout
          </a>
        </div>
      </div>
    </div>

  </div>

  <script>
    const btn  = document.getElementById('adminDropdownBtn');
    const menu = document.getElementById('adminDropdownMenu');
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      menu.classList.toggle('hidden');
    });
    document.addEventListener('click', () => menu.classList.add('hidden'));
    menu.addEventListener('click', (e) => e.stopPropagation());
  </script>
</header>
<main class="flex-1 p-8">
