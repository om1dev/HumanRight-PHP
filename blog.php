<?php
require_once __DIR__ . '/includes/init.php';
$pageTitle = 'Blog — ' . SITE_NAME;

$search   = sanitize($_GET['search'] ?? '');
$category = sanitize($_GET['category'] ?? '');
$page     = max(1, (int)($_GET['page'] ?? 1));
$perPage  = 6;
$skip     = ($page - 1) * $perPage;

$filter = ['published' => true];
if ($search)   $filter['title'] = ['$regex' => $search, '$options' => 'i'];
if ($category) $filter['category'] = $category;

$total = $db->blogs->countDocuments($filter);
$blogs = $db->blogs->find($filter, [
    'sort'  => ['created_at' => -1],
    'limit' => $perPage,
    'skip'  => $skip,
])->toArray();

$categories = $db->blogs->distinct('category', ['published' => true]);
$pages = (int)ceil($total / $perPage);

include __DIR__ . '/includes/header.php';
?>

<section class="bg-blue-900 text-white py-14 text-center px-4">
  <h1 class="text-4xl font-extrabold mb-2">Our Blog</h1>
  <p class="text-blue-200">Insights, stories, and updates on human rights issues.</p>
</section>

<section class="max-w-6xl mx-auto px-4 py-10">
  <!-- Search & Filter -->
  <form method="GET" class="flex flex-wrap gap-3 mb-8">
    <input type="text" name="search" value="<?= $search ?>" placeholder="Search articles..."
      class="flex-1 min-w-[200px] border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
    <select name="category" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
      <option value="">All Categories</option>
      <?php foreach ($categories as $cat): ?>
        <option value="<?= sanitize($cat) ?>" <?= $category === $cat ? 'selected' : '' ?>><?= sanitize($cat) ?></option>
      <?php endforeach; ?>
    </select>
    <button class="bg-blue-800 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Search</button>
    <?php if ($search || $category): ?>
      <a href="<?= SITE_URL ?>/blog.php" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300">Clear</a>
    <?php endif; ?>
  </form>

  <?php if (empty($blogs)): ?>
    <p class="text-center text-gray-500 py-16">No articles found.</p>
  <?php else: ?>
  <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
    <?php foreach ($blogs as $blog): ?>
    <div class="bg-white rounded-2xl shadow overflow-hidden hover:shadow-lg transition flex flex-col">
      <?php if (!empty($blog['image'])): ?>
        <img src="<?= SITE_URL ?>/assets/images/<?= sanitize($blog['image']) ?>" class="w-full h-48 object-cover" alt="">
      <?php else: ?>
        <div class="w-full h-48 bg-blue-50 flex items-center justify-center">
          <i class="fa-solid fa-newspaper text-5xl text-blue-200"></i>
        </div>
      <?php endif; ?>
      <div class="p-6 flex flex-col flex-1">
        <span class="text-xs text-blue-600 font-semibold uppercase"><?= sanitize($blog['category'] ?? 'General') ?></span>
        <h3 class="font-bold text-lg mt-1 mb-2 flex-1"><?= sanitize($blog['title']) ?></h3>
        <p class="text-gray-500 text-sm mb-4"><?= sanitize(substr($blog['excerpt'] ?? '', 0, 90)) ?>...</p>
        <div class="flex items-center justify-between text-xs text-gray-400 mt-auto">
          <span><i class="fa-regular fa-calendar mr-1"></i><?= date('M d, Y', $blog['created_at']->toDateTime()->getTimestamp()) ?></span>
          <a href="<?= SITE_URL ?>/single-blog.php?slug=<?= sanitize($blog['slug']) ?>" class="text-blue-700 font-semibold hover:underline">Read More →</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Pagination -->
  <?php if ($pages > 1): ?>
  <div class="flex justify-center gap-2 mt-10">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
      <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&category=<?= urlencode($category) ?>"
        class="px-4 py-2 rounded-lg <?= $i === $page ? 'bg-blue-800 text-white' : 'bg-white border hover:bg-gray-50' ?>">
        <?= $i ?>
      </a>
    <?php endfor; ?>
  </div>
  <?php endif; ?>
  <?php endif; ?>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
