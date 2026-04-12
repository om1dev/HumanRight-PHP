<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();
$pageTitle = 'Manage Admins';

$success = flash('success');
$error   = flash('error');

$admins = $db->admins->find([], ['sort' => ['created_at' => -1]])->toArray();
include __DIR__ . '/../partials/header.php';
?>

<div class="flex items-center justify-between mb-6">
  <p class="text-gray-500 text-sm"><?= count($admins) ?> admin(s) registered</p>
  <a href="<?= SITE_URL ?>/admin/admins/create.php" class="bg-blue-700 text-white px-5 py-2 rounded-lg hover:bg-blue-600 text-sm font-semibold">
    <i class="fa-solid fa-plus mr-1"></i> Add New Admin
  </a>
</div>

<?php if ($success): ?>
  <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm"><?= $success ?></div>
<?php endif; ?>
<?php if ($error): ?>
  <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm"><?= $error ?></div>
<?php endif; ?>

<div class="bg-white rounded-2xl shadow overflow-x-auto">
  <table class="w-full min-w-max text-sm">
    <thead class="bg-gray-50 text-gray-400 text-left">
      <tr>
        <th class="px-5 py-3">Username</th>
        <th class="px-5 py-3">Email</th>
        <th class="px-5 py-3">Created</th>
        <th class="px-5 py-3">Action</th>
      </tr>
    </thead>
    <tbody class="divide-y">
      <?php foreach ($admins as $a): ?>
      <tr class="hover:bg-gray-50">
        <td class="px-5 py-3 font-medium flex items-center gap-2">
          <div class="w-8 h-8 rounded-full bg-blue-700 text-white flex items-center justify-center text-xs font-bold">
            <?= strtoupper(substr($a['username'], 0, 1)) ?>
          </div>
          <?= sanitize($a['username']) ?>
          <?php if ((string)$a['_id'] === $_SESSION['user_id']): ?>
            <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full">You</span>
          <?php endif; ?>
        </td>
        <td class="px-5 py-3 text-gray-500"><?= sanitize($a['email']) ?></td>
        <td class="px-5 py-3 text-gray-400"><?= date('M d, Y', $a['created_at']->toDateTime()->getTimestamp()) ?></td>
        <td class="px-5 py-3">
          <?php if ((string)$a['_id'] !== $_SESSION['user_id']): ?>
          <form method="POST" action="<?= SITE_URL ?>/admin/admins/delete.php" onsubmit="return confirm('Remove this admin?')">
            <input type="hidden" name="id" value="<?= (string)$a['_id'] ?>">
            <button class="text-red-500 hover:underline text-xs">Remove</button>
          </form>
          <?php else: ?>
            <span class="text-gray-300 text-xs">—</span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
