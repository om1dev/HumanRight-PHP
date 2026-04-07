<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();
$pageTitle = 'Settings';

$success = flash('success');
$error   = flash('error');

$settings = $db->settings->findOne(['_id' => 'site']) ?? [];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'site') {
        $db->settings->updateOne(
            ['_id' => 'site'],
            ['$set' => [
                'site_name'        => sanitize($_POST['site_name'] ?? ''),
                'site_description' => sanitize($_POST['site_description'] ?? ''),
                'contact_email'    => sanitize($_POST['contact_email'] ?? ''),
                'facebook'         => sanitize($_POST['facebook'] ?? ''),
                'twitter'          => sanitize($_POST['twitter'] ?? ''),
                'instagram'        => sanitize($_POST['instagram'] ?? ''),
            ]],
            ['upsert' => true]
        );
        $db->activity->insertOne(['message' => 'Site settings updated by ' . ($_SESSION['admin_username'] ?? 'Admin'), 'icon' => 'fa-gear', 'created_at' => new MongoDB\BSON\UTCDateTime()]);
        flash('success', 'Site settings saved.');
        header('Location: ' . SITE_URL . '/admin/settings/');
        exit;

    } elseif ($action === 'password') {
        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $admin = $db->admins->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);
        if (!$admin || !password_verify($current, $admin['password'])) {
            $error = 'Current password is incorrect.';
        } elseif (strlen($new) < 6) {
            $error = 'New password must be at least 6 characters.';
        } elseif ($new !== $confirm) {
            $error = 'New passwords do not match.';
        } else {
            $db->admins->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])],
                ['$set' => ['password' => password_hash($new, PASSWORD_BCRYPT)]]
            );
            $db->activity->insertOne(['message' => 'Admin password changed by ' . ($_SESSION['admin_username'] ?? 'Admin'), 'icon' => 'fa-lock', 'created_at' => new MongoDB\BSON\UTCDateTime()]);
            flash('success', 'Password changed successfully.');
            header('Location: ' . SITE_URL . '/admin/settings/');
            exit;
        }
    }
}

include __DIR__ . '/../partials/header.php';
?>

<?php if ($success): ?><div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-5 text-sm"><?= $success ?></div><?php endif; ?>
<?php if ($error):   ?><div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-5 text-sm"><?= $error ?></div><?php endif; ?>

<div class="max-w-3xl space-y-6">

  <!-- Site Settings -->
  <div class="bg-white rounded-2xl shadow p-6">
    <h2 class="font-bold text-gray-700 mb-5 flex items-center gap-2">
      <i class="fa-solid fa-globe text-blue-600"></i> Site Settings
    </h2>
    <form method="POST" class="space-y-4">
      <input type="hidden" name="action" value="site">
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="text-sm font-medium text-gray-700">Site Name</label>
          <input type="text" name="site_name" value="<?= sanitize($settings['site_name'] ?? SITE_NAME) ?>"
            class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700">Contact Email</label>
          <input type="email" name="contact_email" value="<?= sanitize($settings['contact_email'] ?? ADMIN_EMAIL) ?>"
            class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
        </div>
      </div>
      <div>
        <label class="text-sm font-medium text-gray-700">Site Description</label>
        <textarea name="site_description" rows="2"
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none"><?= sanitize($settings['site_description'] ?? '') ?></textarea>
      </div>
      <div class="grid grid-cols-3 gap-4">
        <?php foreach (['facebook' => 'Facebook URL', 'twitter' => 'Twitter URL', 'instagram' => 'Instagram URL'] as $key => $label): ?>
        <div>
          <label class="text-sm font-medium text-gray-700"><?= $label ?></label>
          <input type="url" name="<?= $key ?>" value="<?= sanitize($settings[$key] ?? '') ?>"
            placeholder="https://..."
            class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
        </div>
        <?php endforeach; ?>
      </div>
      <button class="bg-blue-700 text-white px-6 py-2 rounded-lg hover:bg-blue-600 font-semibold text-sm">Save Settings</button>
    </form>
  </div>

  <!-- Change Password -->
  <div class="bg-white rounded-2xl shadow p-6">
    <h2 class="font-bold text-gray-700 mb-5 flex items-center gap-2">
      <i class="fa-solid fa-lock text-blue-600"></i> Change Password
    </h2>
    <form method="POST" class="space-y-4 max-w-sm">
      <input type="hidden" name="action" value="password">
      <div>
        <label class="text-sm font-medium text-gray-700">Current Password</label>
        <input type="password" name="current_password" required
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
      </div>
      <div>
        <label class="text-sm font-medium text-gray-700">New Password</label>
        <input type="password" name="new_password" required minlength="6"
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
      </div>
      <div>
        <label class="text-sm font-medium text-gray-700">Confirm New Password</label>
        <input type="password" name="confirm_password" required
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
      </div>
      <button class="bg-blue-700 text-white px-6 py-2 rounded-lg hover:bg-blue-600 font-semibold text-sm">Update Password</button>
    </form>
  </div>

  <!-- Export Data -->
  <div class="bg-white rounded-2xl shadow p-6">
    <h2 class="font-bold text-gray-700 mb-5 flex items-center gap-2">
      <i class="fa-solid fa-download text-blue-600"></i> Export Data
    </h2>
    <div class="flex flex-wrap gap-3">
      <a href="<?= SITE_URL ?>/admin/settings/export?type=users"
        class="bg-green-50 text-green-700 px-5 py-2.5 rounded-lg hover:bg-green-100 font-semibold text-sm flex items-center gap-2">
        <i class="fa-solid fa-users"></i> Export Users CSV
      </a>
      <a href="<?= SITE_URL ?>/admin/settings/export?type=blogs"
        class="bg-blue-50 text-blue-700 px-5 py-2.5 rounded-lg hover:bg-blue-100 font-semibold text-sm flex items-center gap-2">
        <i class="fa-solid fa-newspaper"></i> Export Blogs CSV
      </a>
      <a href="<?= SITE_URL ?>/admin/settings/export?type=messages"
        class="bg-purple-50 text-purple-700 px-5 py-2.5 rounded-lg hover:bg-purple-100 font-semibold text-sm flex items-center gap-2">
        <i class="fa-solid fa-envelope"></i> Export Messages CSV
      </a>
    </div>
  </div>

</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
