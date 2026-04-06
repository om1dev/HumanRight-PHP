<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();
$pageTitle = 'Contact Messages';

// Mark all as read
$db->contacts->updateMany(['read' => false], ['$set' => ['read' => true]]);

$messages = $db->contacts->find([], ['sort' => ['created_at' => -1]])->toArray();
include __DIR__ . '/../partials/header.php';
?>

<p class="text-gray-500 text-sm mb-6"><?= count($messages) ?> total messages</p>

<div class="space-y-4">
  <?php foreach ($messages as $m): ?>
  <div class="bg-white rounded-2xl shadow p-6">
    <div class="flex items-start justify-between gap-4">
      <div>
        <p class="font-semibold"><?= sanitize($m['name']) ?>
          <span class="text-gray-400 font-normal text-sm ml-2">&lt;<?= sanitize($m['email']) ?>&gt;</span>
        </p>
        <?php if (!empty($m['subject'])): ?>
          <p class="text-blue-700 text-sm font-medium mt-0.5"><?= sanitize($m['subject']) ?></p>
        <?php endif; ?>
        <p class="text-gray-600 text-sm mt-2"><?= sanitize($m['message']) ?></p>
      </div>
      <p class="text-gray-400 text-xs whitespace-nowrap"><?= date('M d, Y H:i', $m['created_at']->toDateTime()->getTimestamp()) ?></p>
    </div>
  </div>
  <?php endforeach; ?>
  <?php if (empty($messages)): ?>
    <p class="text-center text-gray-400 py-12">No messages yet.</p>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
