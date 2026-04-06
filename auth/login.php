<?php
require_once __DIR__ . '/../includes/init.php';
if (isLoggedIn()) { header('Location: ' . SITE_URL . '/index.php'); exit; }

$pageTitle = 'Login — ' . SITE_NAME;
$error     = '';
$success   = flash('success');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = $db->users->findOne(['email' => $email]);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']  = (string)$user['_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = false;
        header('Location: ' . SITE_URL . '/index.php');
        exit;
    }
    $error = 'Invalid email or password.';
}

include __DIR__ . '/../includes/header.php';
?>

<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
  <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-8">
    <div class="text-center mb-6">
      <i class="fa-solid fa-right-to-bracket text-4xl text-blue-700 mb-2"></i>
      <h1 class="text-2xl font-bold text-blue-900">Welcome Back</h1>
    </div>
    <?php if ($success): ?>
      <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm"><?= $success ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST" class="space-y-4">
      <div>
        <label class="text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" value="<?= sanitize($_POST['email'] ?? '') ?>" required
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
      <div>
        <label class="text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" required
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
      <button class="w-full bg-blue-800 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
        Login
      </button>
    </form>
    <p class="text-center text-sm text-gray-500 mt-5">
      Don't have an account? <a href="<?= SITE_URL ?>/auth/signup.php" class="text-blue-700 font-semibold hover:underline">Sign Up</a>
    </p>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
