<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();
$pageTitle = 'Add New Admin';
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
    } elseif ($db->admins->findOne(['email' => $email])) {
        $error = 'An admin with this email already exists.';
    } else {
        $db->admins->insertOne([
            'username'   => $username,
            'email'      => $email,
            'password'   => password_hash($password, PASSWORD_BCRYPT),
            'created_at' => new MongoDB\BSON\UTCDateTime(),
        ]);
        flash('success', "Admin '{$username}' created successfully.");
        header('Location: ' . SITE_URL . '/admin/admins/index.php');
        exit;
    }
}

include __DIR__ . '/../partials/header.php';
?>

<div class="max-w-lg">
  <?php if ($error): ?>
    <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-5 text-sm"><?= $error ?></div>
  <?php endif; ?>

  <div class="bg-white rounded-2xl shadow p-8">
    <div class="flex items-center gap-3 mb-6">
      <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
        <i class="fa-solid fa-user-shield text-blue-700"></i>
      </div>
      <div>
        <h2 class="font-bold text-gray-800">New Admin Account</h2>
        <p class="text-xs text-gray-400">This admin will have full panel access.</p>
      </div>
    </div>

    <form method="POST" class="space-y-4">
      <div>
        <label class="text-sm font-medium text-gray-700">Username <span class="text-red-500">*</span></label>
        <input type="text" name="username" value="<?= sanitize($_POST['username'] ?? '') ?>" required
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
      <div>
        <label class="text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
        <input type="email" name="email" value="<?= sanitize($_POST['email'] ?? '') ?>" required
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
      <div>
        <label class="text-sm font-medium text-gray-700">Password <span class="text-red-500">*</span></label>
        <input type="password" name="password" required minlength="6"
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <p class="text-xs text-gray-400 mt-1">Minimum 6 characters.</p>
      </div>
      <div>
        <label class="text-sm font-medium text-gray-700">Confirm Password <span class="text-red-500">*</span></label>
        <input type="password" name="confirm" required
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
      <div class="flex gap-3 pt-2">
        <button class="bg-blue-700 text-white px-6 py-2 rounded-lg hover:bg-blue-600 font-semibold">
          Create Admin
        </button>
        <a href="<?= SITE_URL ?>/admin/admins/index.php" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200">
          Cancel
        </a>
      </div>
    </form>
  </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
