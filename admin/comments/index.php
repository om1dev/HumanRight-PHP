<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();
$pageTitle = 'Manage Comments';

$success = flash('success');
$comments = $db->comments->find([], ['sort' => ['created_at' => -1]])->toArray();

// Fetch blog titles for display
$blogIds = array_unique(array_map(fn($c) => $c['blog_id'], $comments));
$blogMap = [];
foreach ($blogIds as $bid) {
    try {
        $b = $db->blogs->findOne(['_id' => new MongoDB\BSON\ObjectId($bid)]);
        if ($b) $blogMap[$bid] = $b['title'];
    } catch (Exception $e) {}
}

include __DIR__ . '/../partials/header.php';
?>

<?php if ($success): ?><div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm"><?= $success ?></div><?php endif; ?>

<div class="bg-white rounded-2xl shadow overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-gray-50 text-gray-400 text-left">
      <tr>
        <th class="px-5 py-3">User</th>
        <th class="px-5 py-3">Comment</th>
        <th class="px-5 py-3">Blog</th>
        <th class="px-5 py-3">Date</th>
        <th class="px-5 py-3">Action</th>
      </tr>
    </thead>
    <tbody class="divide-y">
      <?php foreach ($comments as $c): ?>
      <tr class="hover:bg-gray-50">
        <td class="px-5 py-3 font-medium"><?= sanitize($c['username']) ?></td>
        <td class="px-5 py-3 text-gray-600 max-w-xs truncate"><?= sanitize(substr($c['body'], 0, 80)) ?></td>
        <td class="px-5 py-3 text-gray-500 max-w-[150px] truncate"><?= sanitize($blogMap[$c['blog_id']] ?? 'Unknown') ?></td>
        <td class="px-5 py-3 text-gray-400"><?= date('M d, Y', $c['created_at']->toDateTime()->getTimestamp()) ?></td>
        <td class="px-5 py-3">
          <form method="POST" action="<?= SITE_URL ?>/admin/comments/delete.php" onsubmit="return confirm('Delete this comment?')">
            <input type="hidden" name="id" value="<?= (string)$c['_id'] ?>">
            <button class="text-red-500 hover:underline text-xs">Delete</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
