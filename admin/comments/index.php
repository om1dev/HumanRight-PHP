<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();
$pageTitle = 'Manage Comments';

$success = flash('success');
$filter_status = sanitize($_GET['status'] ?? 'all');

$filter = [];
if ($filter_status === 'pending')  $filter['approved'] = false;
if ($filter_status === 'approved') $filter['approved'] = true;

$comments = $db->comments->find($filter, ['sort' => ['created_at' => -1]])->toArray();

$blogIds = array_unique(array_map(fn($c) => $c['blog_id'], $comments));
$blogMap = [];
foreach ($blogIds as $bid) {
    try {
        $b = $db->blogs->findOne(['_id' => new MongoDB\BSON\ObjectId($bid)]);
        if ($b) $blogMap[$bid] = ['title' => $b['title'], 'slug' => $b['slug']];
    } catch (Exception $e) {}
}

$totalPending  = $db->comments->countDocuments(['approved' => false]);
$totalApproved = $db->comments->countDocuments(['approved' => true]);

include __DIR__ . '/../partials/header.php';
?>

<!-- Tabs -->
<div class="flex gap-2 mb-6">
  <?php foreach (['all' => 'All', 'pending' => "Pending ({$totalPending})", 'approved' => "Approved ({$totalApproved})"] as $val => $label): ?>
    <a href="?status=<?= $val ?>"
      class="px-4 py-2 rounded-lg text-sm font-semibold <?= $filter_status===$val ? 'bg-blue-700 text-white' : 'bg-white text-gray-600 hover:bg-gray-50 shadow-sm' ?>">
      <?= $label ?>
    </a>
  <?php endforeach; ?>
</div>

<?php if ($success): ?><div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm"><?= $success ?></div><?php endif; ?>

<div class="space-y-4">
  <?php foreach ($comments as $c): ?>
  <?php $isApproved = $c['approved'] ?? false; ?>
  <div class="bg-white rounded-2xl shadow p-5 <?= !$isApproved ? 'border-l-4 border-yellow-400' : '' ?>">
    <div class="flex items-start justify-between gap-4">
      <div class="flex items-start gap-3 flex-1">
        <div class="w-9 h-9 rounded-full bg-blue-700 text-white flex items-center justify-center font-bold text-sm flex-shrink-0">
          <?= strtoupper(substr($c['username'], 0, 1)) ?>
        </div>
        <div class="flex-1">
          <div class="flex items-center gap-2 flex-wrap">
            <p class="font-semibold text-sm"><?= sanitize($c['username']) ?></p>
            <?php if (!$isApproved): ?>
              <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded-full">Pending</span>
            <?php else: ?>
              <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full">Approved</span>
            <?php endif; ?>
            <span class="text-gray-400 text-xs"><?= date('M d, Y H:i', $c['created_at']->toDateTime()->getTimestamp()) ?></span>
          </div>
          <p class="text-gray-600 text-sm mt-1"><?= sanitize($c['body']) ?></p>
          <?php if (isset($blogMap[$c['blog_id']])): ?>
            <p class="text-xs text-blue-500 mt-1">
              On: <a href="<?= SITE_URL ?>/single-blog?slug=<?= sanitize($blogMap[$c['blog_id']]['slug']) ?>" target="_blank" class="hover:underline"><?= sanitize($blogMap[$c['blog_id']]['title']) ?></a>
            </p>
          <?php endif; ?>
        </div>
      </div>
      <div class="flex gap-2 flex-shrink-0">
        <?php if (!$isApproved): ?>
        <form method="POST" action="<?= SITE_URL ?>/admin/comments/approve">
          <input type="hidden" name="id" value="<?= (string)$c['_id'] ?>">
          <button class="bg-green-100 text-green-700 px-3 py-1.5 rounded-lg text-xs hover:bg-green-200 font-semibold">
            <i class="fa-solid fa-check mr-1"></i>Approve
          </button>
        </form>
        <?php endif; ?>
        <form method="POST" action="<?= SITE_URL ?>/admin/comments/delete" onsubmit="return confirm('Delete this comment?')">
          <input type="hidden" name="id" value="<?= (string)$c['_id'] ?>">
          <button class="bg-red-100 text-red-500 px-3 py-1.5 rounded-lg text-xs hover:bg-red-200 font-semibold">
            <i class="fa-solid fa-trash mr-1"></i>Delete
          </button>
        </form>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php if (empty($comments)): ?>
    <p class="text-center text-gray-400 py-12">No comments found.</p>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
