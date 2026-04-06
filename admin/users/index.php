<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();
$pageTitle = 'Manage Users';

$users = $db->users->find([], ['sort' => ['created_at' => -1]])->toArray();
include __DIR__ . '/../partials/header.php';
?>

<p class="text-gray-500 text-sm mb-6"><?= count($users) ?> registered users</p>

<div class="bg-white rounded-2xl shadow overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-gray-50 text-gray-400 text-left">
      <tr>
        <th class="px-5 py-3">Username</th>
        <th class="px-5 py-3">Email</th>
        <th class="px-5 py-3">Joined</th>
      </tr>
    </thead>
    <tbody class="divide-y">
      <?php foreach ($users as $u): ?>
      <tr class="hover:bg-gray-50">
        <td class="px-5 py-3 font-medium flex items-center gap-2">
          <div class="w-8 h-8 rounded-full bg-blue-700 text-white flex items-center justify-center text-xs font-bold">
            <?= strtoupper(substr($u['username'], 0, 1)) ?>
          </div>
          <?= sanitize($u['username']) ?>
        </td>
        <td class="px-5 py-3 text-gray-500"><?= sanitize($u['email']) ?></td>
        <td class="px-5 py-3 text-gray-400"><?= date('M d, Y', $u['created_at']->toDateTime()->getTimestamp()) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
