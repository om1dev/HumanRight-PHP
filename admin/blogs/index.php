<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();
$pageTitle = 'Manage Blogs';

$success = flash('success');
$error   = flash('error');

$search   = sanitize($_GET['search'] ?? '');
$status   = sanitize($_GET['status'] ?? '');
$filter   = [];
if ($search) $filter['title'] = ['$regex' => $search, '$options' => 'i'];
if ($status === 'published') $filter['published'] = true;
if ($status === 'draft')     $filter['published'] = false;

$blogs = $db->blogs->find($filter, ['sort' => ['created_at' => -1]])->toArray();
include __DIR__ . '/../partials/header.php';
?>

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
  <form method="GET" class="flex gap-2 flex-wrap">
    <input type="text" name="search" value="<?= $search ?>" placeholder="Search blogs..."
      class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
      <option value="">All Status</option>
      <option value="published" <?= $status==='published'?'selected':'' ?>>Published</option>
      <option value="draft"     <?= $status==='draft'?'selected':'' ?>>Draft</option>
    </select>
    <button class="bg-gray-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-600">Filter</button>
    <?php if ($search || $status): ?>
      <a href="<?= SITE_URL ?>/admin/blogs/" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">Clear</a>
    <?php endif; ?>
  </form>
  <a href="<?= SITE_URL ?>/admin/blogs/create" class="bg-blue-700 text-white px-5 py-2 rounded-lg hover:bg-blue-600 text-sm font-semibold">
    <i class="fa-solid fa-plus mr-1"></i> New Blog
  </a>
</div>

<?php if ($success): ?><div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm"><?= $success ?></div><?php endif; ?>
<?php if ($error):   ?><div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm"><?= $error ?></div><?php endif; ?>

<div class="bg-white rounded-2xl shadow overflow-x-auto">
  <table class="w-full min-w-max text-sm">
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
        <td class="px-5 py-3 font-medium max-w-xs">
          <p class="truncate"><?= sanitize($b['title']) ?></p>
          <a href="<?= SITE_URL ?>/single-blog?slug=<?= sanitize($b['slug']) ?>" target="_blank" class="text-xs text-blue-500 hover:underline">View →</a>
        </td>
        <td class="px-5 py-3 text-gray-500"><?= sanitize($b['category'] ?? '—') ?></td>
        <td class="px-5 py-3">
          <?php if ($b['published']): ?>
            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs">Published</span>
          <?php else: ?>
            <span class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full text-xs">Draft</span>
          <?php endif; ?>
        </td>
        <td class="px-5 py-3 text-gray-400"><?= date('M d, Y', $b['created_at']->toDateTime()->getTimestamp()) ?></td>
        <td class="px-5 py-3">
          <div class="flex gap-2 items-center">
            <a href="<?= SITE_URL ?>/admin/blogs/edit?id=<?= (string)$b['_id'] ?>" class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg text-xs hover:bg-blue-100 font-semibold">Edit</a>
            <form method="POST" action="<?= SITE_URL ?>/admin/blogs/delete" onsubmit="return confirm('Delete this blog and all its comments?')">
              <input type="hidden" name="id" value="<?= (string)$b['_id'] ?>">
              <button class="bg-red-50 text-red-500 px-3 py-1 rounded-lg text-xs hover:bg-red-100 font-semibold">Delete</button>
            </form>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($blogs)): ?>
        <tr><td colspan="5" class="px-5 py-12 text-center text-gray-400">No blogs found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
