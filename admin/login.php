<?php
require_once __DIR__ . '/../includes/init.php';
if (isAdmin()) { header('Location: ' . SITE_URL . '/admin/'); exit; }

$pageTitle = 'Admin Login';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $admin = $db->admins->findOne(['email' => $email]);
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['user_id']        = (string)$admin['_id'];
        $_SESSION['username']       = $admin['username'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['is_admin']       = true;
        header('Location: ' . SITE_URL . '/admin/');
        exit;
    }
    $error = 'Invalid credentials.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  .surface-card {
    background: rgba(255, 255, 255, .94);
    border: 1px solid rgba(148, 163, 184, .16);
    border-radius: 2rem;
    box-shadow: 0 24px 60px rgba(15, 23, 42, .22);
    backdrop-filter: blur(18px);
  }
  .ui-input {
    background: rgba(255,255,255,.96);
    border: 1px solid rgba(148, 163, 184, .22);
    border-radius: 1rem;
    transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
  }
  .ui-input:focus {
    outline: none;
    border-color: rgba(59, 130, 246, .42);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, .12);
  }
  .ui-button-primary {
    background: linear-gradient(135deg, #3044c8 0%, #2335a1 100%);
    box-shadow: 0 16px 30px rgba(48, 68, 200, .22);
  }
  .ui-button-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 20px 38px rgba(48, 68, 200, .26);
  }
</style>
</head>
<body class="min-h-screen px-4 bg-gradient-to-br from-[#07101f] via-[#0d1b34] to-[#142d5c] flex items-center justify-center">
  <div class="relative w-full max-w-6xl grid lg:grid-cols-[.95fr_1.05fr] gap-8 items-stretch">
    <div class="relative overflow-hidden rounded-[2rem] text-white p-8 sm:p-10 bg-white/5 border border-white/10 backdrop-blur-xl shadow-2xl">
      <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(59,130,246,.26),_transparent_30%),radial-gradient(circle_at_bottom_left,_rgba(244,114,182,.14),_transparent_28%)]"></div>
      <div class="relative flex flex-col justify-between h-full min-h-[480px]">
        <div>
          <span class="inline-flex items-center gap-2 bg-white/10 border border-white/10 rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-white/75">Admin Portal</span>
          <h1 class="font-serif text-4xl sm:text-5xl mt-6 leading-tight">Control the platform with clarity and confidence.</h1>
          <p class="text-white/65 mt-5 max-w-lg leading-relaxed">
            Manage content, moderation, events, and site settings through a refined workspace built for speed and focus.
          </p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-10">
          <div class="bg-white/8 border border-white/10 rounded-2xl p-4">
            <p class="text-white text-2xl font-serif">CMS</p>
            <p class="text-white/50 text-xs uppercase tracking-widest mt-1">Publishing</p>
          </div>
          <div class="bg-white/8 border border-white/10 rounded-2xl p-4">
            <p class="text-white text-2xl font-serif">Live</p>
            <p class="text-white/50 text-xs uppercase tracking-widest mt-1">Moderation</p>
          </div>
          <div class="bg-white/8 border border-white/10 rounded-2xl p-4">
            <p class="text-white text-2xl font-serif">CSV</p>
            <p class="text-white/50 text-xs uppercase tracking-widest mt-1">Exports</p>
          </div>
        </div>
      </div>
    </div>

    <div class="flex items-center">
      <div class="w-full surface-card p-6 sm:p-8 lg:p-10">
        <div class="text-center mb-8">
          <div class="w-16 h-16 mx-auto rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center mb-4">
            <i class="fa-solid fa-user-shield text-3xl"></i>
          </div>
          <h2 class="font-serif text-3xl text-ink">Admin sign in</h2>
          <p class="text-gray-500 text-sm mt-2"><?= SITE_NAME ?></p>
        </div>
        <?php if ($error): ?>
          <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-2xl mb-5 text-sm flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation"></i><?= $error ?>
          </div>
        <?php endif; ?>
        <form method="POST" class="space-y-4">
          <div>
            <label class="text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" required autofocus
              class="ui-input w-full mt-2 px-4 py-3 text-sm">
          </div>
          <div>
            <label class="text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" required
              class="ui-input w-full mt-2 px-4 py-3 text-sm">
          </div>
          <button class="ui-button-primary w-full text-white py-3.5 rounded-2xl font-semibold transition-all">
            Login to Admin Panel
          </button>
        </form>
        <p class="text-center mt-5 text-sm">
          <a href="<?= SITE_URL ?>/index.php" class="text-primary font-semibold hover:underline">Back to website</a>
        </p>
      </div>
    </div>
  </div>
</body>
</html>
