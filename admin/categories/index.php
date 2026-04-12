<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();
$pageTitle = 'Blog Categories';

$success = flash('success');
$error   = flash('error');

// Add category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $name = sanitize($_POST['name'] ?? '');
        if (!$name) {
            $error = 'Category name is required.';
        } elseif ($db->categories->findOne(['name' => $name])) {
            $error = 'Category already exists.';
        } else {
            $db->categories->insertOne(['name' => $name, 'created_at' => new MongoDB\BSON\UTCDateTime()]);
            flash('success', 'Category added.');
            header('Location: ' . SITE_URL . '/admin/categories/');
            exit;
        }
    } elseif ($_POST['action'] === 'delete') {
        $id = sanitize($_POST['id'] ?? '');
        try {
            $db->categories->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
            flash('success', 'Category deleted.');
        } catch (Exception $e) { flash('error', 'Could not delete.'); }
        header('Location: ' . SITE_URL . '/admin/categories/');
        exit;
    }
}

$categories = $db->categories->find([], ['sort' => ['name' => 1]])->toArray();
include __DIR__ . '/../partials/header.php';
?>

<div class="max-w-2xl">
  <?php if ($success): ?><div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm"><?= $success ?></div><?php endif; ?>
  <?php if ($error):   ?><div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm"><?= $error ?></div><?php endif; ?>

  <!-- Add Form -->
  <div class="bg-white rounded-2xl shadow p-6 mb-6">
    <h2 class="font-bold text-gray-700 mb-4">Add New Category</h2>
    <form method="POST" class="flex gap-3">
      <input type="hidden" name="action" value="add">
      <input type="text" name="name" placeholder="Category name..." required
        class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
      <button class="bg-blue-700 text-white px-5 py-2 rounded-lg hover:bg-blue-600 text-sm font-semibold">Add</button>
    </form>
  </div>

  <!-- List -->
  <div class="bg-white rounded-2xl shadow overflow-x-auto">
    <table class="w-full min-w-max text-sm">
      <thead class="bg-gray-50 text-gray-400 text-left">
        <tr>
          <th class="px-5 py-3">Category Name</th>
          <th class="px-5 py-3">Blogs</th>
          <th class="px-5 py-3">Action</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        <?php foreach ($categories as $cat): ?>
        <tr class="hover:bg-gray-50">
          <td class="px-5 py-3 font-medium">
            <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs"><?= sanitize($cat['name']) ?></span>
          </td>
          <td class="px-5 py-3 text-gray-500">
            <?= $db->blogs->countDocuments(['category' => $cat['name']]) ?>
          </td>
          <td class="px-5 py-3">
            <form method="POST" onsubmit="return confirm('Delete this category?')">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= (string)$cat['_id'] ?>">
              <button class="text-red-500 hover:underline text-xs">Delete</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($categories)): ?>
          <tr><td colspan="3" class="px-5 py-8 text-center text-gray-400">No categories yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
