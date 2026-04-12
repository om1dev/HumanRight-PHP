<?php
require_once __DIR__ . '/../includes/init.php';
requireLogin();
$pageTitle = 'My Profile — ' . SITE_NAME;

$userId = $_SESSION['user_id'];
try {
    $user = $db->users->findOne(['_id' => new MongoDB\BSON\ObjectId($userId)]);
} catch (Exception $e) { $user = null; }

if (!$user) {
    session_destroy();
    header('Location: ' . SITE_URL . '/auth/login');
    exit;
}
$success = flash('success');
$error   = flash('error');

$myComments = $db->comments->find(
    ['user_id' => $userId],
    ['sort' => ['created_at' => -1], 'limit' => 10]
)->toArray();

include __DIR__ . '/../includes/header.php';
?>

<div class="max-w-4xl mx-auto px-4 py-12">
  <?php if ($success): ?>
    <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm"><?= $success ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm"><?= $error ?></div>
  <?php endif; ?>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <!-- Left: Avatar + Info -->
    <div class="md:col-span-1">
      <div class="bg-white rounded-2xl shadow p-6 text-center">
        <?php if (!empty($user['photo'])): ?>
          <img src="<?= sanitize($user['photo']) ?>" alt="Profile"
            class="w-28 h-28 rounded-full object-cover border-4 border-blue-100 mx-auto mb-4">
        <?php else: ?>
          <div class="w-28 h-28 rounded-full bg-blue-700 text-white flex items-center justify-center text-4xl font-bold mx-auto mb-4 border-4 border-blue-100">
            <?= strtoupper(substr($user['username'], 0, 1)) ?>
          </div>
        <?php endif; ?>

        <h2 class="text-xl font-bold text-blue-900"><?= sanitize($user['username']) ?></h2>
        <p class="text-gray-400 text-sm mt-1"><?= sanitize($user['email']) ?></p>

        <?php if (!empty($user['bio'])): ?>
          <p class="text-gray-600 text-sm mt-3 leading-relaxed"><?= sanitize($user['bio']) ?></p>
        <?php endif; ?>

        <div class="mt-3 space-y-1 text-xs text-gray-400 break-words">
          <?php if (!empty($user['phone'])): ?>
            <p><i class="fa-solid fa-phone mr-1"></i><?= sanitize($user['phone']) ?></p>
          <?php endif; ?>
          <?php if (!empty($user['location'])): ?>
            <p><i class="fa-solid fa-location-dot mr-1"></i><?= sanitize($user['location']) ?></p>
          <?php endif; ?>
          <?php if (!empty($user['occupation'])): ?>
            <p><i class="fa-solid fa-briefcase mr-1"></i><?= sanitize($user['occupation']) ?></p>
          <?php endif; ?>
          <?php if (!empty($user['website'])): ?>
            <p><i class="fa-solid fa-link mr-1"></i>
              <a href="<?= sanitize($user['website']) ?>" target="_blank" class="text-blue-500 hover:underline break-all"><?= sanitize($user['website']) ?></a>
            </p>
          <?php endif; ?>
        </div>

        <!-- Social Links -->
        <?php if (!empty($user['facebook']) || !empty($user['twitter']) || !empty($user['instagram'])): ?>
        <div class="flex flex-wrap justify-center gap-3 mt-4">
          <?php if (!empty($user['facebook'])): ?>
            <a href="<?= sanitize($user['facebook']) ?>" target="_blank" class="text-blue-600 hover:text-blue-800 text-lg"><i class="fa-brands fa-facebook"></i></a>
          <?php endif; ?>
          <?php if (!empty($user['twitter'])): ?>
            <a href="<?= sanitize($user['twitter']) ?>" target="_blank" class="text-sky-500 hover:text-sky-700 text-lg"><i class="fa-brands fa-twitter"></i></a>
          <?php endif; ?>
          <?php if (!empty($user['instagram'])): ?>
            <a href="<?= sanitize($user['instagram']) ?>" target="_blank" class="text-pink-500 hover:text-pink-700 text-lg"><i class="fa-brands fa-instagram"></i></a>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <p class="text-gray-300 text-xs mt-4">Joined <?= date('M Y', $user['created_at']->toDateTime()->getTimestamp()) ?></p>
        <a href="<?= SITE_URL ?>/user/edit-profile"
          class="mt-4 inline-block w-full bg-blue-700 text-white py-2 rounded-lg text-sm font-semibold hover:bg-blue-600 transition">
          <i class="fa-solid fa-pen mr-1"></i> Edit Profile
        </a>
      </div>
    </div>

    <!-- Right: Stats + Comments -->
    <div class="md:col-span-2 space-y-6">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white rounded-2xl shadow p-5 text-center">
          <p class="text-3xl font-bold text-blue-700"><?= $db->comments->countDocuments(['user_id' => $userId]) ?></p>
          <p class="text-gray-500 text-sm mt-1">Total Comments</p>
        </div>
        <div class="bg-white rounded-2xl shadow p-5 text-center">
          <p class="text-3xl font-bold text-blue-700"><?= date('M Y', $user['created_at']->toDateTime()->getTimestamp()) ?></p>
          <p class="text-gray-500 text-sm mt-1">Member Since</p>
        </div>
      </div>

      <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="font-bold text-gray-700 mb-4">My Recent Comments</h3>
        <?php if (empty($myComments)): ?>
          <p class="text-gray-400 text-sm text-center py-6">No comments yet.</p>
        <?php else: ?>
          <div class="space-y-3">
            <?php foreach ($myComments as $c): ?>
            <?php
              $blog = null;
              try { $blog = $db->blogs->findOne(['_id' => new MongoDB\BSON\ObjectId($c['blog_id'])]); } catch(Exception $e) {}
            ?>
            <div class="border border-gray-100 rounded-xl p-4">
              <p class="text-gray-700 text-sm"><?= sanitize(substr($c['body'], 0, 120)) ?><?= strlen($c['body']) > 120 ? '...' : '' ?></p>
              <div class="flex items-center justify-between mt-2 flex-wrap gap-1">
                <?php if ($blog): ?>
                  <a href="<?= SITE_URL ?>/single-blog?slug=<?= sanitize($blog['slug']) ?>" class="text-blue-600 text-xs hover:underline truncate max-w-[200px]">
                    <?= sanitize($blog['title']) ?>
                  </a>
                <?php endif; ?>
                <span class="text-gray-400 text-xs"><?= date('M d, Y', $c['created_at']->toDateTime()->getTimestamp()) ?></span>
              </div>
              <?php if (!($c['approved'] ?? false)): ?>
                <span class="text-xs bg-yellow-100 text-yellow-600 px-2 py-0.5 rounded-full mt-1 inline-block">Pending approval</span>
              <?php endif; ?>
            </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
