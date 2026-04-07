<?php
require_once __DIR__ . '/../includes/init.php';
requireAdmin();
$pageTitle = 'My Profile';

$adminId = $_SESSION['user_id'];
$admin   = $db->admins->findOne(['_id' => new MongoDB\BSON\ObjectId($adminId)]);
$success = flash('success');
$error   = flash('error');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'profile') {
        $username = sanitize($_POST['username'] ?? '');
        $bio      = sanitize($_POST['bio'] ?? '');
        $phone    = sanitize($_POST['phone'] ?? '');

        if (!$username) { $error = 'Username is required.'; }
        else {
            $update = [
                'username'   => $username,
                'bio'        => $bio,
                'phone'      => $phone,
                'updated_at' => new MongoDB\BSON\UTCDateTime(),
            ];

            // Photo upload to Cloudinary
            if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] === 0) {
                $ext     = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg','jpeg','png','webp'];
                if (!in_array($ext, $allowed)) {
                    $error = 'Invalid image format.';
                } elseif ($_FILES['photo']['size'] > 5 * 1024 * 1024) {
                    $error = 'Image must be under 5MB.';
                } else {
                    try {
                        $update['photo'] = uploadToCloudinary($_FILES['photo']['tmp_name'], 'humanrights/admins');
                    } catch (Exception $e) {
                        $error = 'Photo upload failed: ' . $e->getMessage();
                    }
                }
            }

            if (!$error) {
                $db->admins->updateOne(
                    ['_id' => new MongoDB\BSON\ObjectId($adminId)],
                    ['$set' => $update]
                );
                $_SESSION['admin_username'] = $username;
                $_SESSION['username']       = $username;
                $admin = $db->admins->findOne(['_id' => new MongoDB\BSON\ObjectId($adminId)]);
                flash('success', 'Profile updated successfully.');
                header('Location: ' . SITE_URL . '/admin/profile');
                exit;
            }
        }

    } elseif ($action === 'password') {
        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (!password_verify($current, $admin['password'])) {
            $error = 'Current password is incorrect.';
        } elseif (strlen($new) < 6) {
            $error = 'New password must be at least 6 characters.';
        } elseif ($new !== $confirm) {
            $error = 'Passwords do not match.';
        } else {
            $db->admins->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($adminId)],
                ['$set' => ['password' => password_hash($new, PASSWORD_BCRYPT)]]
            );
            flash('success', 'Password changed successfully.');
            header('Location: ' . SITE_URL . '/admin/profile');
            exit;
        }
    }
}

include __DIR__ . '/partials/header.php';
?>

<?php if ($success): ?>
  <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm"><?= $success ?></div>
<?php endif; ?>
<?php if ($error): ?>
  <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm"><?= $error ?></div>
<?php endif; ?>

<div class="max-w-3xl space-y-6">

  <!-- Profile Form -->
  <div class="bg-white rounded-2xl shadow p-8">
    <h2 class="text-lg font-bold text-gray-700 mb-6 flex items-center gap-2">
      <i class="fa-solid fa-user-pen text-blue-600"></i> Edit My Profile
    </h2>
    <form method="POST" enctype="multipart/form-data" class="space-y-5">
      <input type="hidden" name="action" value="profile">

      <!-- Photo -->
      <div class="flex items-center gap-6">
        <div>
          <?php if (!empty($admin['photo'])): ?>
            <img id="photoPreview" src="<?= sanitize($admin['photo']) ?>"
              class="w-24 h-24 rounded-full object-cover border-4 border-blue-100">
          <?php else: ?>
            <div id="photoPlaceholder" class="w-24 h-24 rounded-full bg-blue-700 text-white flex items-center justify-center text-3xl font-bold border-4 border-blue-100">
              <?= strtoupper(substr($admin['username'], 0, 1)) ?>
            </div>
            <img id="photoPreview" src="" class="w-24 h-24 rounded-full object-cover border-4 border-blue-100 hidden">
          <?php endif; ?>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Profile Photo</label>
          <input type="file" name="photo" id="photoInput" accept="image/*"
            class="text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-semibold hover:file:bg-blue-100 cursor-pointer">
          <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP up to 5MB. Uploaded to Cloudinary.</p>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="text-sm font-medium text-gray-700">Username <span class="text-red-500">*</span></label>
          <input type="text" name="username" value="<?= sanitize($admin['username']) ?>" required
            class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700">Email</label>
          <input type="email" value="<?= sanitize($admin['email']) ?>" disabled
            class="w-full mt-1 border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 text-gray-400 text-sm cursor-not-allowed">
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700">Phone</label>
          <input type="text" name="phone" value="<?= sanitize($admin['phone'] ?? '') ?>"
            class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
        </div>
      </div>

      <div>
        <label class="text-sm font-medium text-gray-700">Bio</label>
        <textarea name="bio" rows="3"
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none"><?= sanitize($admin['bio'] ?? '') ?></textarea>
      </div>

      <button class="bg-blue-700 text-white px-8 py-2.5 rounded-lg hover:bg-blue-600 font-semibold text-sm">
        <i class="fa-solid fa-floppy-disk mr-1"></i> Save Profile
      </button>
    </form>
  </div>

  <!-- Change Password -->
  <div class="bg-white rounded-2xl shadow p-8">
    <h2 class="text-lg font-bold text-gray-700 mb-6 flex items-center gap-2">
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
      <button class="bg-blue-700 text-white px-6 py-2.5 rounded-lg hover:bg-blue-600 font-semibold text-sm">
        Update Password
      </button>
    </form>
  </div>

</div>

<script>
document.getElementById('photoInput').addEventListener('change', function () {
  const file = this.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    const preview     = document.getElementById('photoPreview');
    const placeholder = document.getElementById('photoPlaceholder');
    preview.src = e.target.result;
    preview.classList.remove('hidden');
    if (placeholder) placeholder.classList.add('hidden');
  };
  reader.readAsDataURL(file);
});
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
