<?php
require_once __DIR__ . '/../includes/init.php';
if (isLoggedIn()) { header('Location: ' . SITE_URL . '/index.php'); exit; }

$pageTitle = 'Sign Up — ' . SITE_NAME;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $email    = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if (!$username || !$email || !$password) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif ($db->users->findOne(['email' => $email])) {
        $error = 'Email already registered.';
    } else {
        $db->users->insertOne([
            'username'   => $username,
            'email'      => $email,
            'password'   => password_hash($password, PASSWORD_BCRYPT),
            'created_at' => new MongoDB\BSON\UTCDateTime(),
        ]);
        flash('success', 'Account created! Please login.');
        header('Location: ' . SITE_URL . '/auth/login.php');
        exit;
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
  <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-8">
    <div class="text-center mb-6">
      <i class="fa-solid fa-user-plus text-4xl text-blue-700 mb-2"></i>
      <h1 class="text-2xl font-bold text-blue-900">Create Account</h1>
    </div>
    <?php if ($error): ?>
      <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST" class="space-y-4">
      <div>
        <label class="text-sm font-medium text-gray-700">Username</label>
        <input type="text" name="username" value="<?= sanitize($_POST['username'] ?? '') ?>" required
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
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
      <div>
        <label class="text-sm font-medium text-gray-700">Confirm Password</label>
        <input type="password" name="confirm" required
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
      <button class="w-full bg-blue-800 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
        Create Account
      </button>
    </form>
    <p class="text-center text-sm text-gray-500 mt-5">
      Already have an account? <a href="<?= SITE_URL ?>/auth/login.php" class="text-blue-700 font-semibold hover:underline">Login</a>
    </p>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
