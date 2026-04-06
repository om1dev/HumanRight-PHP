<?php
require_once __DIR__ . '/../includes/init.php';
requireAdmin();
$pageTitle = 'Dashboard';

$totalUsers    = $db->users->countDocuments();
$totalBlogs    = $db->blogs->countDocuments();
$totalComments = $db->comments->countDocuments();
$totalMessages = $db->contacts->countDocuments();
$unreadMessages = $db->contacts->countDocuments(['read' => false]);
$recentBlogs   = $db->blogs->find([], ['sort' => ['created_at' => -1], 'limit' => 5])->toArray();

include __DIR__ . '/partials/header.php';
?>

<!-- Stat Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
  <?php foreach ([
    ['fa-users',    'Users',    $totalUsers,    'bg-blue-500'],
    ['fa-newspaper','Blogs',    $totalBlogs,    'bg-green-500'],
    ['fa-comments', 'Comments', $totalComments, 'bg-yellow-500'],
    ['fa-envelope', 'Messages', $totalMessages, 'bg-purple-500'],
  ] as [$icon, $label, $count, $color]): ?>
  <div class="bg-white rounded-2xl shadow p-6 flex items-center gap-4">
    <div class="<?= $color ?> text-white w-12 h-12 rounded-xl flex items-center justify-center text-xl flex-shrink-0">
      <i class="fa-solid <?= $icon ?>"></i>
    </div>
    <div>
      <p class="text-2xl font-bold"><?= $count ?></p>
      <p class="text-gray-500 text-sm"><?= $label ?></p>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<?php if ($unreadMessages > 0): ?>
<div class="bg-yellow-50 border border-yellow-300 text-yellow-800 px-5 py-3 rounded-xl mb-6 text-sm">
  <i class="fa-solid fa-bell mr-2"></i>You have <strong><?= $unreadMessages ?></strong> unread message(s).
  <a href="<?= SITE_URL ?>/admin/messages/index.php" class="underline ml-2">View Messages</a>
</div>
<?php endif; ?>

<!-- Recent Blogs -->
<div class="bg-white rounded-2xl shadow p-6">
  <div class="flex items-center justify-between mb-4">
    <h2 class="font-bold text-gray-700">Recent Blogs</h2>
    <a href="<?= SITE_URL ?>/admin/blogs/index.php" class="text-blue-600 text-sm hover:underline">View All</a>
  </div>
  <table class="w-full text-sm">
    <thead>
      <tr class="text-left text-gray-400 border-b">
        <th class="pb-2">Title</th>
        <th class="pb-2">Category</th>
        <th class="pb-2">Status</th>
        <th class="pb-2">Date</th>
      </tr>
    </thead>
    <tbody class="divide-y">
      <?php foreach ($recentBlogs as $b): ?>
      <tr class="hover:bg-gray-50">
        <td class="py-2 font-medium"><?= sanitize($b['title']) ?></td>
        <td class="py-2 text-gray-500"><?= sanitize($b['category'] ?? '—') ?></td>
        <td class="py-2">
          <?php if ($b['published']): ?>
            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs">Published</span>
          <?php else: ?>
            <span class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full text-xs">Draft</span>
          <?php endif; ?>
        </td>
        <td class="py-2 text-gray-400"><?= date('M d, Y', $b['created_at']->toDateTime()->getTimestamp()) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
