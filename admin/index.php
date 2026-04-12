<?php
require_once __DIR__ . '/../includes/init.php';
requireAdmin();
$pageTitle = 'Dashboard';

$totalUsers     = $db->users->countDocuments();
$totalBlogs     = $db->blogs->countDocuments();
$totalComments  = $db->comments->countDocuments();
$totalMessages  = $db->contacts->countDocuments();
$unreadMessages = $db->contacts->countDocuments(['read' => false]);
$pendingComments= $db->comments->countDocuments(['approved' => false]);
$publishedBlogs = $db->blogs->countDocuments(['published' => true]);
$draftBlogs     = $db->blogs->countDocuments(['published' => false]);
$suspendedUsers = $db->users->countDocuments(['suspended_until' => ['$gt' => new MongoDB\BSON\UTCDateTime(time() * 1000)]]);

$recentBlogs    = $db->blogs->find([], ['sort' => ['created_at' => -1], 'limit' => 5])->toArray();
$recentUsers    = $db->users->find([], ['sort' => ['created_at' => -1], 'limit' => 5])->toArray();
$recentActivity = $db->activity->find([], ['sort' => ['created_at' => -1], 'limit' => 8])->toArray();

// Build last 7 days blog chart data
$blogChartLabels = [];
$blogChartData   = [];
for ($i = 6; $i >= 0; $i--) {
    $day   = strtotime("-{$i} days");
    $start = new MongoDB\BSON\UTCDateTime(strtotime('today', $day) * 1000);
    $end   = new MongoDB\BSON\UTCDateTime((strtotime('today', $day) + 86400) * 1000);
    $blogChartLabels[] = date('M d', $day);
    $blogChartData[]   = $db->blogs->countDocuments(['created_at' => ['$gte' => $start, '$lt' => $end]]);
}

// Build last 7 days user chart data
$userChartData = [];
for ($i = 6; $i >= 0; $i--) {
    $day   = strtotime("-{$i} days");
    $start = new MongoDB\BSON\UTCDateTime(strtotime('today', $day) * 1000);
    $end   = new MongoDB\BSON\UTCDateTime((strtotime('today', $day) + 86400) * 1000);
    $userChartData[] = $db->users->countDocuments(['created_at' => ['$gte' => $start, '$lt' => $end]]);
}

include __DIR__ . '/partials/header.php';
?>

<!-- Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
  <?php foreach ([
    ['fa-users',     'Total Users',    $totalUsers,     'bg-blue-500',   SITE_URL.'/admin/users/'],
    ['fa-newspaper', 'Total Blogs',    $totalBlogs,     'bg-green-500',  SITE_URL.'/admin/blogs/'],
    ['fa-comments',  'Total Comments', $totalComments,  'bg-yellow-500', SITE_URL.'/admin/comments/'],
    ['fa-envelope',  'Messages',       $totalMessages,  'bg-purple-500', SITE_URL.'/admin/messages/'],
  ] as [$icon, $label, $count, $color, $link]): ?>
  <a href="<?= $link ?>" class="bg-white rounded-2xl shadow p-4 sm:p-6 flex items-center gap-3 sm:gap-4 hover:shadow-md transition">
    <div class="<?= $color ?> text-white w-12 h-12 rounded-xl flex items-center justify-center text-xl flex-shrink-0">
      <i class="fa-solid <?= $icon ?>"></i>
    </div>
    <div>
      <p class="text-2xl font-bold"><?= $count ?></p>
      <p class="text-gray-500 text-sm"><?= $label ?></p>
    </div>
  </a>
  <?php endforeach; ?>
</div>

<!-- Secondary Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
  <?php foreach ([
    ['Published Blogs',   $publishedBlogs,  'text-green-600',  'bg-green-50'],
    ['Draft Blogs',       $draftBlogs,      'text-gray-500',   'bg-gray-50'],
    ['Pending Comments',  $pendingComments, 'text-yellow-600', 'bg-yellow-50'],
    ['Suspended Users',   $suspendedUsers,  'text-red-600',    'bg-red-50'],
  ] as [$label, $count, $textColor, $bgColor]): ?>
  <div class="<?= $bgColor ?> rounded-2xl p-5">
    <p class="text-2xl font-bold <?= $textColor ?>"><?= $count ?></p>
    <p class="text-gray-500 text-sm mt-1"><?= $label ?></p>
  </div>
  <?php endforeach; ?>
</div>

<!-- Alerts -->
<?php if ($unreadMessages > 0): ?>
<div class="bg-yellow-50 border border-yellow-300 text-yellow-800 px-5 py-3 rounded-xl mb-6 text-sm flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
  <span><i class="fa-solid fa-bell mr-2"></i>You have <strong><?= $unreadMessages ?></strong> unread message(s).</span>
  <a href="<?= SITE_URL ?>/admin/messages/" class="underline font-semibold">View</a>
</div>
<?php endif; ?>
<?php if ($pendingComments > 0): ?>
<div class="bg-blue-50 border border-blue-300 text-blue-800 px-5 py-3 rounded-xl mb-6 text-sm flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
  <span><i class="fa-solid fa-comment-dots mr-2"></i><strong><?= $pendingComments ?></strong> comment(s) awaiting approval.</span>
  <a href="<?= SITE_URL ?>/admin/comments/" class="underline font-semibold">Review</a>
</div>
<?php endif; ?>

<!-- Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
  <div class="bg-white rounded-2xl shadow p-6">
    <h2 class="font-bold text-gray-700 mb-4">Blogs Published (Last 7 Days)</h2>
    <canvas id="blogChart" height="120"></canvas>
  </div>
  <div class="bg-white rounded-2xl shadow p-6">
    <h2 class="font-bold text-gray-700 mb-4">New Users (Last 7 Days)</h2>
    <canvas id="userChart" height="120"></canvas>
  </div>
</div>

<!-- Blog Status Donut + Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
  <div class="bg-white rounded-2xl shadow p-6 flex flex-col items-center">
    <h2 class="font-bold text-gray-700 mb-4 self-start">Blog Status</h2>
    <canvas id="donutChart" height="180"></canvas>
    <div class="flex gap-4 mt-4 text-sm">
      <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span> Published</span>
      <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-gray-300 inline-block"></span> Draft</span>
    </div>
  </div>
  <div class="bg-white rounded-2xl shadow p-6 lg:col-span-2">
    <h2 class="font-bold text-gray-700 mb-4">Recent Activity</h2>
    <?php if (empty($recentActivity)): ?>
      <p class="text-gray-400 text-sm text-center py-6">No activity yet.</p>
    <?php else: ?>
    <div class="space-y-3">
      <?php foreach ($recentActivity as $act): ?>
      <div class="flex items-start gap-3 text-sm">
        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center flex-shrink-0 mt-0.5">
          <i class="fa-solid <?= sanitize($act['icon'] ?? 'fa-circle-dot') ?> text-xs"></i>
        </div>
        <div class="flex-1">
          <p class="text-gray-700"><?= sanitize($act['message']) ?></p>
          <p class="text-gray-400 text-xs"><?= date('M d, Y H:i', $act['created_at']->toDateTime()->getTimestamp()) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- Recent Blogs & Users -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
  <div class="bg-white rounded-2xl shadow p-6">
    <div class="flex items-center justify-between mb-4">
      <h2 class="font-bold text-gray-700">Recent Blogs</h2>
      <a href="<?= SITE_URL ?>/admin/blogs/" class="text-blue-600 text-sm hover:underline">View All</a>
    </div>
    <div class="space-y-3">
      <?php foreach ($recentBlogs as $b): ?>
      <div class="flex items-center justify-between text-sm">
        <div class="flex-1 min-w-0">
          <p class="font-medium truncate"><?= sanitize($b['title']) ?></p>
          <p class="text-gray-400 text-xs"><?= date('M d, Y', $b['created_at']->toDateTime()->getTimestamp()) ?></p>
        </div>
        <?php if ($b['published']): ?>
          <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs ml-2 flex-shrink-0">Published</span>
        <?php else: ?>
          <span class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full text-xs ml-2 flex-shrink-0">Draft</span>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="bg-white rounded-2xl shadow p-6">
    <div class="flex items-center justify-between mb-4">
      <h2 class="font-bold text-gray-700">Recent Users</h2>
      <a href="<?= SITE_URL ?>/admin/users/" class="text-blue-600 text-sm hover:underline">View All</a>
    </div>
    <div class="space-y-3">
      <?php foreach ($recentUsers as $u): ?>
      <div class="flex items-center gap-3 text-sm">
        <div class="w-8 h-8 rounded-full bg-blue-700 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
          <?= strtoupper(substr($u['username'], 0, 1)) ?>
        </div>
        <div class="flex-1 min-w-0">
          <p class="font-medium"><?= sanitize($u['username']) ?></p>
          <p class="text-gray-400 text-xs"><?= sanitize($u['email']) ?></p>
        </div>
        <p class="text-gray-400 text-xs flex-shrink-0"><?= date('M d', $u['created_at']->toDateTime()->getTimestamp()) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<script>
const labels = <?= json_encode($blogChartLabels) ?>;
new Chart(document.getElementById('blogChart'), {
  type: 'bar',
  data: { labels, datasets: [{ label: 'Blogs', data: <?= json_encode($blogChartData) ?>, backgroundColor: '#3b82f6', borderRadius: 6 }] },
  options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});
new Chart(document.getElementById('userChart'), {
  type: 'line',
  data: { labels, datasets: [{ label: 'Users', data: <?= json_encode($userChartData) ?>, borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.1)', fill: true, tension: 0.4 }] },
  options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});
new Chart(document.getElementById('donutChart'), {
  type: 'doughnut',
  data: { labels: ['Published', 'Draft'], datasets: [{ data: [<?= $publishedBlogs ?>, <?= $draftBlogs ?>], backgroundColor: ['#22c55e', '#d1d5db'], borderWidth: 0 }] },
  options: { plugins: { legend: { display: false } }, cutout: '70%' }
});
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
