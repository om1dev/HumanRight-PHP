<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();
$pageTitle = 'Manage Blogs';

$success = flash('success');
$error   = flash('error');

$blogs = $db->blogs->find([], ['sort' => ['created_at' => -1]])->toArray();
include __DIR__ . '/../partials/header.php';
?>

<div class="flex items-center justify-between mb-6">
  <p class="text-gray-500 text-sm"><?= count($blogs) ?> total blogs</p>
  <a href="<?= SITE_URL ?>/admin/blogs/create.php" class="bg-blue-700 text-white px-5 py-2 rounded-lg hover:bg-blue-600 text-sm font-semibold">
    <i class="fa-solid fa-plus mr-1"></i> New Blog
  </a>
</div>

<?php if ($success): ?><div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm"><?= $success ?></div><?php endif; ?>
<?php if ($error):   ?><div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm"><?= $error ?></div><?php endif; ?>

<div class="bg-white rounded-2xl shadow overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-gray-50 text-gray-400 text-left">
      <tr>
        <th class="px-5 py-3">Title</th>
        <th class="px-5 py-3">Category</th>
        <th class="px-5 py-3">Status</th>
        <th class="px-5 py-3">Date</th>
        <th class="px-5 py-3">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y">
      <?php foreach ($blogs as $b): ?>
      <tr class="hover:bg-gray-50">
        <td class="px-5 py-3 font-medium max-w-xs truncate"><?= sanitize($b['title']) ?></td>
        <td class="px-5 py-3 text-gray-500"><?= sanitize($b['category'] ?? '—') ?></td>
        <td class="px-5 py-3">
          <?php if ($b['published']): ?>
            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs">Published</span>
          <?php else: ?>
            <span class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full text-xs">Draft</span>
          <?php endif; ?>
        </td>
        <td class="px-5 py-3 text-gray-400"><?= date('M d, Y', $b['created_at']->toDateTime()->getTimestamp()) ?></td>
        <td class="px-5 py-3 flex gap-2">
          <a href="<?= SITE_URL ?>/admin/blogs/edit.php?id=<?= (string)$b['_id'] ?>" class="text-blue-600 hover:underline">Edit</a>
          <form method="POST" action="<?= SITE_URL ?>/admin/blogs/delete.php" onsubmit="return confirm('Delete this blog?')">
            <input type="hidden" name="id" value="<?= (string)$b['_id'] ?>">
            <button class="text-red-500 hover:underline">Delete</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
