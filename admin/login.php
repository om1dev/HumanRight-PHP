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
</head>
<body class="bg-blue-900 min-h-screen flex items-center justify-center px-4">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8">
    <div class="text-center mb-6">
      <i class="fa-solid fa-user-shield text-5xl text-blue-700 mb-3"></i>
      <h1 class="text-2xl font-bold text-blue-900">Admin Login</h1>
      <p class="text-gray-400 text-sm mt-1"><?= SITE_NAME ?></p>
    </div>
    <?php if ($error): ?>
      <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST" class="space-y-4">
      <div>
        <label class="text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" required autofocus
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
      <div>
        <label class="text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" required
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
      <button class="w-full bg-blue-800 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
        Login to Admin Panel
      </button>
    </form>
    <p class="text-center mt-4 text-sm">
      <a href="<?= SITE_URL ?>/index.php" class="text-blue-600 hover:underline">Back to Website</a>
    </p>
  </div>
</body>
</html>
