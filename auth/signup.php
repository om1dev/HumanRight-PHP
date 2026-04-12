<?php
require_once __DIR__ . '/../includes/init.php';
if (isLoggedIn()) { header('Location: ' . SITE_URL . '/'); exit; }

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
        header('Location: ' . SITE_URL . '/auth/login');
        exit;
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="relative py-12 px-4 sm:px-8">
  <div class="max-w-2xl mx-auto">
    <div class="surface-card p-6 sm:p-8 lg:p-10">
      <div class="text-center mb-8">
        <div class="w-14 h-14 mx-auto rounded-2xl bg-primary/10 text-primary flex items-center justify-center mb-4">
          <i class="fa-solid fa-user-plus text-2xl"></i>
        </div>
        <span class="section-chip mb-4">New Member</span>
        <h1 class="font-serif text-3xl sm:text-4xl text-ink">Create account</h1>
        <p class="text-gray-500 text-sm mt-2">Join the community and start participating in the conversation.</p>
      </div>

      <?php if ($error): ?>
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-2xl mb-5 text-sm flex items-center gap-2">
          <i class="fa-solid fa-triangle-exclamation"></i><?= $error ?>
        </div>
      <?php endif; ?>

      <form method="POST" class="space-y-4">
        <div>
          <label class="text-sm font-medium text-gray-700">Username</label>
          <input type="text" name="username" value="<?= sanitize($_POST['username'] ?? '') ?>" required
            class="ui-input w-full mt-2 px-4 py-3 text-sm">
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700">Email</label>
          <input type="email" name="email" value="<?= sanitize($_POST['email'] ?? '') ?>" required
            class="ui-input w-full mt-2 px-4 py-3 text-sm">
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700">Password</label>
          <input type="password" name="password" required
            class="ui-input w-full mt-2 px-4 py-3 text-sm">
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700">Confirm Password</label>
          <input type="password" name="confirm" required
            class="ui-input w-full mt-2 px-4 py-3 text-sm">
        </div>
        <button class="ui-button-primary w-full text-white py-3.5 rounded-2xl font-semibold transition-all">
          Create account
        </button>
      </form>

      <p class="text-center text-sm text-gray-500 mt-6">
        Already have an account? <a href="<?= SITE_URL ?>/auth/login.php" class="text-primary font-semibold hover:underline">Login</a>
      </p>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-8">
        <div class="surface-card-soft p-4 text-center">
          <p class="text-xl font-serif text-ink">Secure</p>
          <p class="text-xs uppercase tracking-widest text-gray-500 mt-1">Password</p>
        </div>
        <div class="surface-card-soft p-4 text-center">
          <p class="text-xl font-serif text-ink">Visible</p>
          <p class="text-xs uppercase tracking-widest text-gray-500 mt-1">Profile</p>
        </div>
        <div class="surface-card-soft p-4 text-center">
          <p class="text-xl font-serif text-ink">Fast</p>
          <p class="text-xs uppercase tracking-widest text-gray-500 mt-1">Setup</p>
        </div>
      </div>
    </div>
  </div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
