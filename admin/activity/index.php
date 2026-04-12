<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();
$pageTitle = 'Activity Log';

$page    = max(1, (int)($_GET['page'] ?? 1));
$perPage = 20;
$skip    = ($page - 1) * $perPage;
$total   = $db->activity->countDocuments();
$pages   = (int)ceil($total / $perPage);

$logs = $db->activity->find([], [
    'sort'  => ['created_at' => -1],
    'limit' => $perPage,
    'skip'  => $skip,
])->toArray();

include __DIR__ . '/../partials/header.php';
?>

<div class="flex items-center justify-between mb-6">
  <p class="text-gray-500 text-sm"><?= $total ?> total log entries</p>
  <form method="POST" action="<?= SITE_URL ?>/admin/activity/clear" onsubmit="return confirm('Clear all activity logs?')">
    <button class="bg-red-50 text-red-500 px-4 py-2 rounded-lg text-sm hover:bg-red-100 font-semibold">
      <i class="fa-solid fa-trash mr-1"></i> Clear All Logs
    </button>
  </form>
</div>

<div class="bg-white rounded-2xl shadow overflow-x-auto">
  <div class="divide-y">
    <?php foreach ($logs as $log): ?>
    <div class="flex items-start gap-4 px-6 py-4 hover:bg-gray-50">
      <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center flex-shrink-0 mt-0.5">
        <i class="fa-solid <?= sanitize($log['icon'] ?? 'fa-circle-dot') ?> text-sm"></i>
      </div>
      <div class="flex-1">
        <p class="text-sm text-gray-700"><?= sanitize($log['message']) ?></p>
        <p class="text-xs text-gray-400 mt-0.5"><?= date('M d, Y H:i:s', $log['created_at']->toDateTime()->getTimestamp()) ?></p>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($logs)): ?>
      <p class="text-center text-gray-400 py-12">No activity logged yet.</p>
    <?php endif; ?>
  </div>
</div>

<?php if ($pages > 1): ?>
<div class="flex justify-center gap-2 mt-6">
  <?php for ($i = 1; $i <= $pages; $i++): ?>
    <a href="?page=<?= $i ?>" class="px-4 py-2 rounded-lg text-sm <?= $i===$page ? 'bg-blue-700 text-white' : 'bg-white border hover:bg-gray-50' ?>"><?= $i ?></a>
  <?php endfor; ?>
</div>
<?php endif; ?>

<?php include __DIR__ . '/../partials/footer.php'; ?>
