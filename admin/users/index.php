<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();
$pageTitle = 'Manage Users';

$success = flash('success');
$error   = flash('error');

$users = $db->users->find([], ['sort' => ['created_at' => -1]])->toArray();
$now   = time();

include __DIR__ . '/../partials/header.php';
?>

<div class="flex items-center justify-between mb-6">
  <p class="text-gray-500 text-sm"><?= count($users) ?> registered users</p>
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
        <th class="px-5 py-3">User</th>
        <th class="px-5 py-3">Email</th>
        <th class="px-5 py-3">Joined</th>
        <th class="px-5 py-3">Status</th>
        <th class="px-5 py-3">Suspend</th>
        <th class="px-5 py-3">Delete</th>
      </tr>
    </thead>
    <tbody class="divide-y">
      <?php foreach ($users as $u): ?>
      <?php
        $uid         = (string)$u['_id'];
        $suspendedUntil = isset($u['suspended_until']) ? $u['suspended_until']->toDateTime()->getTimestamp() : 0;
        $isSuspended = $suspendedUntil > $now;
        $remainingLabel = '';
        if ($isSuspended) {
            $diff = $suspendedUntil - $now;
            if ($diff >= 86400) $remainingLabel = round($diff/86400, 1) . 'd left';
            elseif ($diff >= 3600) $remainingLabel = round($diff/3600, 1) . 'h left';
            else $remainingLabel = round($diff/60) . 'm left';
        }
      ?>
      <tr class="hover:bg-gray-50 <?= $isSuspended ? 'bg-red-50' : '' ?>">
        <td class="px-5 py-3 font-medium">
          <div class="flex items-center gap-2">
            <?php if (!empty($u['photo'])): ?>
              <img src="<?= sanitize($u['photo']) ?>" class="w-8 h-8 rounded-full object-cover flex-shrink-0 border border-gray-200">
            <?php else: ?>
              <div class="w-8 h-8 rounded-full <?= $isSuspended ? 'bg-red-400' : 'bg-blue-700' ?> text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                <?= strtoupper(substr($u['username'], 0, 1)) ?>
              </div>
            <?php endif; ?>
            <div>
              <p><?= sanitize($u['username']) ?></p>
              <a href="<?= SITE_URL ?>/admin/users/view?id=<?= $uid ?>" class="text-xs text-blue-500 hover:underline">View Profile</a>
            </div>
          </div>
        </td>
        <td class="px-5 py-3 text-gray-500"><?= sanitize($u['email']) ?></td>
        <td class="px-5 py-3 text-gray-400"><?= date('M d, Y', $u['created_at']->toDateTime()->getTimestamp()) ?></td>
        <td class="px-5 py-3">
          <?php if ($isSuspended): ?>
            <span class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs font-semibold">
              Suspended · <?= $remainingLabel ?>
            </span>
          <?php else: ?>
            <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs font-semibold">Active</span>
          <?php endif; ?>
        </td>

        <!-- Suspend Actions -->
        <td class="px-5 py-3">
          <?php if ($isSuspended): ?>
            <form method="POST" action="<?= SITE_URL ?>/admin/users/suspend.php">
              <input type="hidden" name="id" value="<?= $uid ?>">
              <input type="hidden" name="hours" value="0">
              <button class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-lg hover:bg-green-200 font-semibold">
                Unsuspend
              </button>
            </form>
          <?php else: ?>
            <form method="POST" action="<?= SITE_URL ?>/admin/users/suspend.php" class="flex flex-wrap gap-1">
              <input type="hidden" name="id" value="<?= $uid ?>">
              <?php foreach ([1 => '1h', 2 => '2h', 3 => '3h', 4 => '4h', 24 => '1 Day'] as $hrs => $label): ?>
                <button type="submit" name="hours" value="<?= $hrs ?>"
                  class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-lg hover:bg-yellow-200 font-semibold">
                  <?= $label ?>
                </button>
              <?php endforeach; ?>
            </form>
          <?php endif; ?>
        </td>

        <!-- Delete Permanently -->
        <td class="px-5 py-3">
          <form method="POST" action="<?= SITE_URL ?>/admin/users/delete.php"
            onsubmit="return confirm('Permanently delete <?= sanitize($u['username']) ?>? This cannot be undone.')">
            <input type="hidden" name="id" value="<?= $uid ?>">
            <button class="text-xs bg-red-100 text-red-600 px-3 py-1 rounded-lg hover:bg-red-200 font-semibold">
              Delete
            </button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
