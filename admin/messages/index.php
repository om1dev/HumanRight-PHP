<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();
$pageTitle = 'Contact Messages';

$success = flash('success');
$filter_status = sanitize($_GET['status'] ?? 'all');

$filter = [];
if ($filter_status === 'unread') $filter['read'] = false;
if ($filter_status === 'read')   $filter['read'] = true;

$messages   = $db->contacts->find($filter, ['sort' => ['created_at' => -1]])->toArray();
$totalUnread = $db->contacts->countDocuments(['read' => false]);
$totalRead   = $db->contacts->countDocuments(['read' => true]);

include __DIR__ . '/../partials/header.php';
?>

<!-- Tabs -->
<div class="flex gap-2 mb-6">
  <?php foreach (['all' => 'All', 'unread' => "Unread ({$totalUnread})", 'read' => "Read ({$totalRead})"] as $val => $label): ?>
    <a href="?status=<?= $val ?>"
      class="px-4 py-2 rounded-lg text-sm font-semibold <?= $filter_status===$val ? 'bg-blue-700 text-white' : 'bg-white text-gray-600 hover:bg-gray-50 shadow-sm' ?>">
      <?= $label ?>
    </a>
  <?php endforeach; ?>
</div>

<?php if ($success): ?><div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm"><?= $success ?></div><?php endif; ?>

<div class="space-y-4">
  <?php foreach ($messages as $m): ?>
  <div class="bg-white rounded-2xl shadow p-6 <?= !$m['read'] ? 'border-l-4 border-blue-500' : '' ?>">
    <div class="flex items-start justify-between gap-4">
      <div class="flex-1">
        <div class="flex items-center gap-2 flex-wrap mb-1">
          <p class="font-semibold"><?= sanitize($m['name']) ?></p>
          <span class="text-gray-400 text-sm">&lt;<?= sanitize($m['email']) ?>&gt;</span>
          <?php if (!$m['read']): ?>
            <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full font-semibold">New</span>
          <?php endif; ?>
        </div>
        <?php if (!empty($m['subject'])): ?>
          <p class="text-blue-700 text-sm font-medium mb-1"><?= sanitize($m['subject']) ?></p>
        <?php endif; ?>
        <p class="text-gray-600 text-sm"><?= sanitize($m['message']) ?></p>
        <p class="text-gray-400 text-xs mt-2"><?= date('M d, Y H:i', $m['created_at']->toDateTime()->getTimestamp()) ?></p>
      </div>
      <div class="flex flex-col gap-2 flex-shrink-0">
        <a href="mailto:<?= sanitize($m['email']) ?>?subject=Re: <?= urlencode($m['subject'] ?? 'Your Message') ?>"
          class="bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg text-xs hover:bg-blue-100 font-semibold text-center">
          <i class="fa-solid fa-reply mr-1"></i>Reply
        </a>
        <?php if ($m['read']): ?>
        <form method="POST" action="<?= SITE_URL ?>/admin/messages/toggle-read">
          <input type="hidden" name="id" value="<?= (string)$m['_id'] ?>">
          <input type="hidden" name="read" value="0">
          <button class="w-full bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg text-xs hover:bg-gray-200 font-semibold">
            Mark Unread
          </button>
        </form>
        <?php else: ?>
        <form method="POST" action="<?= SITE_URL ?>/admin/messages/toggle-read">
          <input type="hidden" name="id" value="<?= (string)$m['_id'] ?>">
          <input type="hidden" name="read" value="1">
          <button class="w-full bg-green-50 text-green-700 px-3 py-1.5 rounded-lg text-xs hover:bg-green-100 font-semibold">
            Mark Read
          </button>
        </form>
        <?php endif; ?>
        <form method="POST" action="<?= SITE_URL ?>/admin/messages/delete" onsubmit="return confirm('Delete this message?')">
          <input type="hidden" name="id" value="<?= (string)$m['_id'] ?>">
          <button class="w-full bg-red-50 text-red-500 px-3 py-1.5 rounded-lg text-xs hover:bg-red-100 font-semibold">
            <i class="fa-solid fa-trash mr-1"></i>Delete
          </button>
        </form>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php if (empty($messages)): ?>
    <p class="text-center text-gray-400 py-12">No messages found.</p>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
