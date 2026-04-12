<?php
require_once __DIR__ . '/../includes/init.php';
if (isLoggedIn()) { header('Location: ' . SITE_URL . '/'); exit; }

$pageTitle = 'Login — ' . SITE_NAME;
$error     = '';
$success   = flash('success');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = $db->users->findOne(['email' => $email]);
    if ($user && password_verify($password, $user['password'])) {

        // Check suspension
        if (!empty($user['suspended_until'])) {
            $until = $user['suspended_until']->toDateTime()->getTimestamp();
            if ($until > time()) {
                $diff = $until - time();
                if ($diff >= 86400)      $timeLeft = round($diff / 86400, 1) . ' day(s)';
                elseif ($diff >= 3600)   $timeLeft = round($diff / 3600, 1) . ' hour(s)';
                else                     $timeLeft = round($diff / 60) . ' minute(s)';
                $error = "Your account is suspended. Try again in {$timeLeft}.";
            } else {
                // Suspension expired — auto-clear and login
                $db->users->updateOne(
                    ['_id' => $user['_id']],
                    ['$unset' => ['suspended_until' => '']]
                );
                goto login_success;
            }
        } else {
            login_success:
            $_SESSION['user_id']  = (string)$user['_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = false;
            header('Location: ' . SITE_URL . '/');
            exit;
        }
    } else {
        $error = 'Invalid email or password.';
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="relative py-12 px-4 sm:px-8">
  <div class="max-w-2xl mx-auto">
    <div class="surface-card p-6 sm:p-8 lg:p-10">
      <div class="text-center mb-8">
        <div class="w-14 h-14 mx-auto rounded-2xl bg-primary/10 text-primary flex items-center justify-center mb-4">
          <i class="fa-solid fa-right-to-bracket text-2xl"></i>
        </div>
        <span class="section-chip mb-4">Member Access</span>
        <h1 class="font-serif text-3xl sm:text-4xl text-ink">Sign in</h1>
        <p class="text-gray-500 text-sm mt-2">Access your account in a clean, secure environment.</p>
      </div>

      <?php if ($success): ?>
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl mb-5 text-sm"><?= $success ?></div>
      <?php endif; ?>
      <?php if ($error): ?>
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-2xl mb-5 text-sm flex items-center gap-2">
          <i class="fa-solid fa-triangle-exclamation"></i><?= $error ?>
        </div>
      <?php endif; ?>

      <form method="POST" class="space-y-4">
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
        <button class="ui-button-primary w-full text-white py-3.5 rounded-2xl font-semibold transition-all">
          Login
        </button>
      </form>

      <p class="text-center text-sm text-gray-500 mt-6">
        Don't have an account? <a href="<?= SITE_URL ?>/auth/signup" class="text-primary font-semibold hover:underline">Create one</a>
      </p>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-8">
        <div class="surface-card-soft p-4 text-center">
          <p class="text-xl font-serif text-ink">Community</p>
          <p class="text-xs uppercase tracking-widest text-gray-500 mt-1">Join the discussion</p>
        </div>
        <div class="surface-card-soft p-4 text-center">
          <p class="text-xl font-serif text-ink">Research</p>
          <p class="text-xs uppercase tracking-widest text-gray-500 mt-1">Stay informed</p>
        </div>
        <div class="surface-card-soft p-4 text-center">
          <p class="text-xl font-serif text-ink">Action</p>
          <p class="text-xs uppercase tracking-widest text-gray-500 mt-1">Take part</p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
