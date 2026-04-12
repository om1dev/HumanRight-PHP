<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?? 'Admin — ' . SITE_NAME ?></title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
  :root { color-scheme: light; }
  body {
    background:
      radial-gradient(circle at top left, rgba(48, 68, 200, .08), transparent 24%),
      radial-gradient(circle at top right, rgba(209, 73, 91, .05), transparent 20%),
      linear-gradient(180deg, #f4f7fd 0%, #eef3fb 100%);
  }
  #sidebar { transition: transform 0.25s ease, box-shadow .25s ease; }
  @media (max-width: 1023px) {
    #sidebar { transform: translateX(-100%); }
    #sidebar.open { transform: translateX(0); }
  }
  .admin-panel { min-height: 100vh; }
  .admin-sidebar {
    background:
      radial-gradient(circle at top right, rgba(48, 68, 200, .16), transparent 30%),
      linear-gradient(180deg, #0b1730 0%, #0d1b34 45%, #0f2142 100%);
    box-shadow: 18px 0 54px rgba(15, 23, 42, .18);
  }
  .admin-surface {
    background: rgba(255,255,255,.86);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(148, 163, 184, .16);
    box-shadow: 0 16px 44px rgba(13, 27, 42, .08);
  }
  .admin-navlink { position: relative; overflow: hidden; }
  .admin-navlink::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(48,68,200,.22), rgba(255,255,255,0));
    opacity: 0;
    transition: opacity .2s ease;
  }
  .admin-navlink:hover::before { opacity: 1; }
</style>
</head>
<body class="admin-panel min-h-screen text-gray-800">

<!-- Mobile Overlay -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-20 hidden lg:hidden" onclick="closeSidebar()"></div>

<!-- Sidebar -->
<aside id="sidebar" class="admin-sidebar w-64 text-white flex flex-col h-screen fixed top-0 left-0 z-30 overflow-y-auto">
  <div class="px-6 py-5 border-b border-white/10 flex items-center justify-between">
    <div>
      <p class="text-xs text-white/45 uppercase tracking-[0.24em]">Admin Panel</p>
      <p class="font-bold text-base mt-0.5 leading-tight"><?= SITE_NAME ?></p>
    </div>
    <button onclick="closeSidebar()" class="lg:hidden text-white/70 hover:text-white p-1 rounded-full bg-white/10">
      <i class="fa-solid fa-xmark text-lg"></i>
    </button>
  </div>
  <nav class="flex-1 px-4 py-4 space-y-0.5 text-sm">
    <?php
    $current = basename($_SERVER['PHP_SELF']);
    $dir     = basename(dirname($_SERVER['PHP_SELF']));
    function navLink(string $href, string $icon, string $label, bool $active, string $badge = ''): string {
      $cls = $active ? 'bg-white/14 text-white shadow-sm border border-white/10' : 'text-white/75 hover:text-white hover:bg-white/8';
      $b   = $badge ? "<span class=\"ml-auto bg-rose-500/95 text-white text-[11px] px-1.5 py-0.5 rounded-full\">{$badge}</span>" : '';
      return "<a href=\"$href\" class=\"admin-navlink flex items-center gap-3 px-4 py-2.5 rounded-xl $cls transition\" onclick=\"closeSidebar()\">
          <i class=\"fa-solid $icon w-4 text-center\"></i> $label $b</a>";
    }
    function navGroup(string $label): string {
      return "<p class=\"text-xs text-white/35 uppercase tracking-[0.22em] px-4 pt-4 pb-1\">{$label}</p>";
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
  <div class="px-4 py-4 border-t border-white/10 space-y-1">
    <a href="<?= SITE_URL ?>/" target="_blank" class="flex items-center gap-3 px-4 py-2 rounded-xl hover:bg-white/8 transition text-sm text-white/70 hover:text-white">
      <i class="fa-solid fa-arrow-up-right-from-square w-4"></i> View Website
    </a>
    <a href="<?= SITE_URL ?>/admin/logout" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-rose-500/15 transition text-sm text-white/75 hover:text-white">
      <i class="fa-solid fa-right-from-bracket w-4"></i> Logout
    </a>
  </div>
</aside>

<!-- Main Wrapper -->
<div class="lg:ml-64 flex flex-col min-h-screen">

<!-- Top Header -->
<header class="admin-surface px-4 sm:px-6 py-3 flex items-center justify-between sticky top-0 z-20 gap-3 m-3 rounded-2xl">
  <!-- Hamburger + Title -->
  <div class="flex items-center gap-3 min-w-0">
    <button onclick="openSidebar()" class="lg:hidden text-gray-500 hover:text-blue-700 p-2 rounded-xl bg-white shadow-sm flex-shrink-0">
      <i class="fa-solid fa-bars text-xl"></i>
    </button>
    <div>
      <p class="text-xs uppercase tracking-[0.22em] text-gray-400">Workspace</p>
      <h1 class="text-base sm:text-lg font-bold text-gray-800 truncate"><?= $pageTitle ?? 'Dashboard' ?></h1>
    </div>
  </div>

  <div class="flex items-center gap-3 flex-shrink-0">
    <!-- Bell Notification -->
    <a href="<?= SITE_URL ?>/admin/messages/" class="relative text-gray-400 hover:text-blue-700 bg-white rounded-xl p-2 shadow-sm border border-gray-100">
      <i class="fa-solid fa-bell text-xl"></i>
      <?php if (!empty($unreadCount)): ?>
        <span class="absolute -top-1 -right-1 bg-rose-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center"><?= $unreadCount ?></span>
      <?php endif; ?>
    </a>

    <!-- Admin Profile Dropdown -->
    <?php
      $adminData = $db->admins->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);
    ?>
    <div class="relative" id="adminDropdownWrap">
      <button id="adminDropdownBtn"
        class="flex items-center gap-2 bg-white hover:bg-gray-50 px-3 py-2 rounded-2xl transition focus:outline-none border border-gray-100 shadow-sm">
        <?php if (!empty($adminData['photo'])): ?>
          <img src="<?= sanitize($adminData['photo']) ?>" class="w-8 h-8 rounded-full object-cover border-2 border-blue-300 flex-shrink-0">
        <?php else: ?>
          <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-700 to-blue-500 text-white flex items-center justify-center text-sm font-bold flex-shrink-0">
            <?= strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)) ?>
          </div>
        <?php endif; ?>
        <div class="text-left hidden sm:block">
          <p class="text-sm font-semibold text-gray-700 leading-tight"><?= sanitize($_SESSION['admin_username'] ?? 'Admin') ?></p>
          <p class="text-xs text-gray-400 leading-tight">Administrator</p>
        </div>
        <i class="fa-solid fa-chevron-down text-xs text-gray-400 ml-1 hidden sm:block"></i>
      </button>

      <!-- Dropdown Menu -->
      <div id="adminDropdownMenu"
        class="hidden absolute right-0 mt-2 w-64 bg-white rounded-3xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
        <div class="px-5 py-4 bg-gradient-to-br from-blue-50 to-white border-b border-gray-100 flex items-center gap-3">
          <?php if (!empty($adminData['photo'])): ?>
            <img src="<?= sanitize($adminData['photo']) ?>" class="w-12 h-12 rounded-full object-cover border-2 border-blue-200 flex-shrink-0">
          <?php else: ?>
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-700 to-blue-500 text-white flex items-center justify-center text-xl font-bold flex-shrink-0">
              <?= strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)) ?>
            </div>
          <?php endif; ?>
          <div class="min-w-0">
            <p class="font-bold text-gray-800 text-sm truncate"><?= sanitize($_SESSION['admin_username'] ?? 'Admin') ?></p>
            <p class="text-xs text-gray-400 truncate"><?= sanitize($adminData['email'] ?? '') ?></p>
            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-semibold mt-0.5 inline-block">Administrator</span>
          </div>
        </div>
        <div class="py-2">
          <a href="<?= SITE_URL ?>/admin/profile" class="flex items-center gap-3 px-5 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
            <i class="fa-solid fa-user w-4 text-blue-500"></i> My Profile
          </a>
          <a href="<?= SITE_URL ?>/admin/settings/" class="flex items-center gap-3 px-5 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
            <i class="fa-solid fa-gear w-4 text-gray-400"></i> Settings
          </a>
          <a href="<?= SITE_URL ?>/admin/admins/" class="flex items-center gap-3 px-5 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
            <i class="fa-solid fa-user-shield w-4 text-purple-500"></i> Manage Admins
          </a>
          <a href="<?= SITE_URL ?>/" target="_blank" class="flex items-center gap-3 px-5 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
            <i class="fa-solid fa-arrow-up-right-from-square w-4 text-green-500"></i> View Website
          </a>
          <a href="<?= SITE_URL ?>/admin/activity/" class="flex items-center gap-3 px-5 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
            <i class="fa-solid fa-clock-rotate-left w-4 text-yellow-500"></i> Activity Log
          </a>
        </div>
        <div class="border-t border-gray-100 py-2">
          <a href="<?= SITE_URL ?>/admin/logout" class="flex items-center gap-3 px-5 py-2.5 text-sm text-red-600 hover:bg-red-50 transition font-semibold">
            <i class="fa-solid fa-right-from-bracket w-4"></i> Logout
          </a>
        </div>
      </div>
    </div>
  </div>
</header>

<main class="flex-1 p-4 sm:p-6 lg:p-8">

<script>
  function openSidebar() {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('sidebarOverlay').classList.remove('hidden');
  }
  function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.add('hidden');
  }
  const btn  = document.getElementById('adminDropdownBtn');
  const menu = document.getElementById('adminDropdownMenu');
  btn.addEventListener('click', (e) => { e.stopPropagation(); menu.classList.toggle('hidden'); });
  document.addEventListener('click', () => menu.classList.add('hidden'));
  menu.addEventListener('click', (e) => e.stopPropagation());
</script>
