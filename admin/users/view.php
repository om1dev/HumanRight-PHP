<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();

$id = sanitize($_GET['id'] ?? '');
if (!$id) { header('Location: ' . SITE_URL . '/admin/users/'); exit; }

try {
    $user = $db->users->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
} catch (Exception $e) { $user = null; }
if (!$user) { header('Location: ' . SITE_URL . '/admin/users/'); exit; }

$pageTitle   = 'User: ' . $user['username'];
$now         = time();
$comments    = $db->comments->find(['user_id' => $id], ['sort' => ['created_at' => -1]])->toArray();
$isSuspended = !empty($user['suspended_until']) && $user['suspended_until']->toDateTime()->getTimestamp() > $now;

include __DIR__ . '/../partials/header.php';
?>

<div class="max-w-4xl">
  <a href="<?= SITE_URL ?>/admin/users/" class="text-blue-600 text-sm hover:underline mb-6 inline-block">
    <i class="fa-solid fa-arrow-left mr-1"></i> Back to Users
  </a>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Profile Card -->
    <div class="md:col-span-1">
      <div class="bg-white rounded-2xl shadow p-6 text-center">
        <?php if (!empty($user['photo'])): ?>
          <img src="<?= sanitize($user['photo']) ?>" class="w-24 h-24 rounded-full object-cover border-4 border-blue-100 mx-auto mb-3">
        <?php else: ?>
          <div class="w-24 h-24 rounded-full bg-blue-700 text-white flex items-center justify-center text-3xl font-bold mx-auto mb-3 border-4 border-blue-100">
            <?= strtoupper(substr($user['username'], 0, 1)) ?>
          </div>
        <?php endif; ?>

        <h2 class="font-bold text-lg text-blue-900"><?= sanitize($user['username']) ?></h2>
        <p class="text-gray-400 text-sm"><?= sanitize($user['email']) ?></p>

        <div class="mt-3 space-y-1 text-xs text-gray-500 text-left">
          <?php foreach ([
            ['fa-phone',        $user['phone'] ?? ''],
            ['fa-briefcase',    $user['occupation'] ?? ''],
            ['fa-location-dot', $user['location'] ?? ''],
            ['fa-link',         $user['website'] ?? ''],
          ] as [$icon, $val]): ?>
            <?php if ($val): ?>
              <p><i class="fa-solid <?= $icon ?> w-4 text-blue-400"></i> <?= sanitize($val) ?></p>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>

        <?php if (!empty($user['bio'])): ?>
          <p class="text-gray-500 text-xs mt-3 text-left leading-relaxed"><?= sanitize($user['bio']) ?></p>
        <?php endif; ?>

        <div class="mt-4 pt-4 border-t text-xs text-gray-400 space-y-1 text-left">
          <p>Joined: <?= date('M d, Y', $user['created_at']->toDateTime()->getTimestamp()) ?></p>
          <p>Status:
            <?php if ($isSuspended): ?>
              <span class="text-red-500 font-semibold">Suspended</span>
            <?php else: ?>
              <span class="text-green-600 font-semibold">Active</span>
            <?php endif; ?>
          </p>
          <p>Comments: <span class="font-semibold text-gray-600"><?= count($comments) ?></span></p>
        </div>
      </div>
    </div>

    <!-- Comments -->
    <div class="md:col-span-2">
      <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="font-bold text-gray-700 mb-4">Comments by <?= sanitize($user['username']) ?></h3>
        <?php if (empty($comments)): ?>
          <p class="text-gray-400 text-sm text-center py-8">No comments yet.</p>
        <?php else: ?>
          <div class="space-y-3">
            <?php foreach ($comments as $c): ?>
            <?php
              $blog = null;
              try { $blog = $db->blogs->findOne(['_id' => new MongoDB\BSON\ObjectId($c['blog_id'])]); } catch(Exception $e) {}
            ?>
            <div class="border border-gray-100 rounded-xl p-4 flex items-start justify-between gap-3">
              <div class="flex-1">
                <p class="text-sm text-gray-700"><?= sanitize(substr($c['body'], 0, 120)) ?><?= strlen($c['body']) > 120 ? '...' : '' ?></p>
                <div class="flex items-center gap-3 mt-1 flex-wrap">
                  <?php if ($blog): ?>
                    <a href="<?= SITE_URL ?>/single-blog?slug=<?= sanitize($blog['slug']) ?>" target="_blank" class="text-blue-500 text-xs hover:underline"><?= sanitize($blog['title']) ?></a>
                  <?php endif; ?>
                  <span class="text-gray-400 text-xs"><?= date('M d, Y', $c['created_at']->toDateTime()->getTimestamp()) ?></span>
                  <?php if (!($c['approved'] ?? false)): ?>
                    <span class="bg-yellow-100 text-yellow-600 text-xs px-2 py-0.5 rounded-full">Pending</span>
                  <?php endif; ?>
                </div>
              </div>
              <form method="POST" action="<?= SITE_URL ?>/admin/comments/delete" onsubmit="return confirm('Delete?')">
                <input type="hidden" name="id" value="<?= (string)$c['_id'] ?>">
                <button class="text-red-400 hover:text-red-600 text-xs"><i class="fa-solid fa-trash"></i></button>
              </form>
            </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
