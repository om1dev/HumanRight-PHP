<?php
require_once __DIR__ . '/includes/init.php';
$pageTitle = 'Insights — ' . SITE_NAME;

$search   = sanitize($_GET['search'] ?? '');
$category = sanitize($_GET['category'] ?? '');
$page     = max(1, (int)($_GET['page'] ?? 1));
$perPage  = 9;
$skip     = ($page - 1) * $perPage;

$filter = ['published' => true];
if ($search)   $filter['title']    = ['$regex' => $search, '$options' => 'i'];
if ($category) $filter['category'] = $category;

$total      = $db->blogs->countDocuments($filter);
$blogs      = $db->blogs->find($filter, ['sort' => ['created_at' => -1], 'limit' => $perPage, 'skip' => $skip])->toArray();
$categories = $db->blogs->distinct('category', ['published' => true]);
$pages      = (int)ceil($total / $perPage);
$featured   = (!$search && !$category && $page === 1 && !empty($blogs)) ? array_shift($blogs) : null;

include __DIR__ . '/includes/header.php';
?>

<!-- Hero -->
<section class="relative bg-ink text-white py-24 px-5 sm:px-8 overflow-hidden">
  <div class="absolute inset-0">
    <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=1600&q=80&auto=format&fit=crop"
      alt="" class="w-full h-full object-cover opacity-15">
    <div class="absolute inset-0 bg-gradient-to-r from-ink/95 to-ink/70"></div>
  </div>
  <div class="relative max-w-7xl mx-auto">
    <span class="inline-flex items-center gap-2 bg-primary/20 text-primary text-xs font-semibold px-3 py-1.5 rounded-full mb-5">
      <i class="fa-solid fa-circle-dot text-[8px]"></i> Knowledge Hub
    </span>
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-5">
      <h1 class="font-serif text-5xl md:text-6xl text-white leading-tight">
        Research &<br class="hidden sm:block"> <span class="text-primary italic">Perspectives</span>
      </h1>
      <p class="text-white/55 max-w-sm text-sm leading-relaxed">
        Analysis, commentary, and field reports from the frontlines of human rights advocacy.
      </p>
    </div>
  </div>
</section>

<!-- Sticky Filter Bar -->
<div class="bg-white border-b border-gray-100 sticky top-[72px] z-30">
  <div class="max-w-7xl mx-auto px-5 sm:px-8 py-3">
    <form method="GET" class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-3">
      <div class="relative">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
        <input type="text" name="search" value="<?= $search ?>" placeholder="Search..."
          class="pl-9 pr-4 py-2 border border-gray-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 bg-mist w-full sm:w-56">
      </div>
      <div class="flex flex-wrap gap-1.5">
        <a href="<?= SITE_URL ?>/blog"
          class="px-4 py-1.5 rounded-full text-xs font-semibold transition <?= !$category ? 'bg-primary text-white' : 'bg-mist text-gray-600 hover:bg-gray-200 border border-gray-200' ?>">
          All
        </a>
        <?php foreach ($categories as $cat): ?>
        <a href="?category=<?= urlencode($cat) ?><?= $search ? '&search='.urlencode($search) : '' ?>"
          class="px-4 py-1.5 rounded-full text-xs font-semibold transition <?= $category === $cat ? 'bg-primary text-white' : 'bg-mist text-gray-600 hover:bg-gray-200 border border-gray-200' ?>">
          <?= sanitize($cat) ?>
        </a>
        <?php endforeach; ?>
      </div>
      <?php if ($search): ?>
        <button class="bg-primary text-white px-5 py-2 rounded-full text-xs font-semibold hover:bg-primary/85 transition w-full sm:w-auto">Go</button>
        <a href="<?= SITE_URL ?>/blog<?= $category ? '?category='.urlencode($category) : '' ?>"
          class="text-gray-400 hover:text-gray-600 text-xs font-semibold flex items-center gap-1 transition">
          <i class="fa-solid fa-xmark"></i> Clear
        </a>
      <?php endif; ?>
    </form>
  </div>
</div>

<div class="max-w-7xl mx-auto px-5 sm:px-8 py-14">

  <?php if (empty($blogs) && !$featured): ?>
    <div class="text-center py-24">
      <div class="w-16 h-16 rounded-2xl bg-mist flex items-center justify-center mx-auto mb-5">
        <i class="fa-solid fa-newspaper text-2xl text-gray-300"></i>
      </div>
      <p class="font-serif text-2xl text-ink mb-2">No articles found.</p>
      <a href="<?= SITE_URL ?>/blog" class="mt-2 inline-block text-primary font-semibold hover:underline text-sm">Clear filters</a>
    </div>

  <?php else: ?>

    <!-- Featured -->
    <?php if ($featured): ?>
    <article class="group grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center mb-16 pb-16 border-b border-gray-100 sr">
      <a href="<?= SITE_URL ?>/single-blog?slug=<?= sanitize($featured['slug']) ?>"
        class="block rounded-2xl overflow-hidden aspect-[4/3] lg:aspect-[16/11] bg-mist flex-shrink-0">
        <?php if (!empty($featured['image'])): ?>
          <img src="<?= SITE_URL ?>/assets/images/<?= sanitize($featured['image']) ?>"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
            alt="<?= sanitize($featured['title']) ?>">
        <?php else: ?>
          <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary/5 to-accent/5">
            <i class="fa-solid fa-newspaper text-5xl text-gray-200"></i>
          </div>
        <?php endif; ?>
      </a>
      <div class="space-y-5">
        <div class="flex items-center gap-3">
          <span class="bg-primary/10 text-primary text-xs font-semibold px-3 py-1.5 rounded-full">
            <?= sanitize($featured['category'] ?? 'General') ?>
          </span>
          <span class="inline-flex items-center gap-1.5 bg-accent/10 text-accent text-xs font-semibold px-3 py-1.5 rounded-full">
            <i class="fa-solid fa-star text-[9px]"></i> Featured
          </span>
        </div>
        <h2 class="font-serif text-3xl sm:text-4xl lg:text-[2.6rem] text-ink leading-tight group-hover:text-primary transition-colors">
          <a href="<?= SITE_URL ?>/single-blog?slug=<?= sanitize($featured['slug']) ?>"><?= sanitize($featured['title']) ?></a>
        </h2>
        <p class="text-gray-500 leading-relaxed">
          <?= sanitize(substr($featured['excerpt'] ?? '', 0, 180)) ?>...
        </p>
        <div class="flex items-center justify-between pt-2">
          <span class="text-gray-400 text-sm">
            <i class="fa-regular fa-calendar mr-1.5"></i><?= date('F d, Y', $featured['created_at']->toDateTime()->getTimestamp()) ?>
          </span>
          <a href="<?= SITE_URL ?>/single-blog?slug=<?= sanitize($featured['slug']) ?>"
            class="inline-flex items-center gap-2 bg-primary text-white font-semibold px-6 py-2.5 rounded-full hover:bg-primary/85 transition text-sm">
            Read Article <i class="fa-solid fa-arrow-right text-xs"></i>
          </a>
        </div>
      </div>
    </article>
    <?php endif; ?>

    <!-- Grid -->
    <?php if (!empty($blogs)): ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($blogs as $i => $blog): ?>
      <article class="group flex flex-col bg-white border border-gray-100 rounded-2xl overflow-hidden lift sr"
        style="transition-delay:<?= ($i % 3) * 70 ?>ms">
        <a href="<?= SITE_URL ?>/single-blog?slug=<?= sanitize($blog['slug']) ?>"
          class="block aspect-[16/10] bg-mist overflow-hidden flex-shrink-0">
          <?php if (!empty($blog['image'])): ?>
            <img src="<?= SITE_URL ?>/assets/images/<?= sanitize($blog['image']) ?>"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              alt="<?= sanitize($blog['title']) ?>" loading="lazy">
          <?php else: ?>
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary/5 to-accent/5">
              <i class="fa-solid fa-newspaper text-3xl text-gray-200"></i>
            </div>
          <?php endif; ?>
        </a>
        <div class="p-5 flex flex-col flex-1">
          <div class="flex items-center gap-2 mb-3">
            <a href="?category=<?= urlencode($blog['category'] ?? '') ?>"
              class="bg-primary/10 text-primary text-xs font-semibold px-3 py-1 rounded-full hover:bg-primary/20 transition">
              <?= sanitize($blog['category'] ?? 'General') ?>
            </a>
            <span class="text-gray-400 text-xs"><?= date('M d, Y', $blog['created_at']->toDateTime()->getTimestamp()) ?></span>
          </div>
          <h2 class="font-serif text-lg text-ink leading-snug mb-3 group-hover:text-primary transition-colors flex-1">
            <a href="<?= SITE_URL ?>/single-blog?slug=<?= sanitize($blog['slug']) ?>"><?= sanitize($blog['title']) ?></a>
          </h2>
          <p class="text-gray-500 text-sm leading-relaxed mb-4">
            <?= sanitize(substr($blog['excerpt'] ?? '', 0, 100)) ?>...
          </p>
          <a href="<?= SITE_URL ?>/single-blog?slug=<?= sanitize($blog['slug']) ?>"
            class="inline-flex items-center gap-1.5 text-primary font-semibold text-sm hover:gap-2.5 transition-all mt-auto">
            Read <i class="fa-solid fa-arrow-right text-xs"></i>
          </a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if ($pages > 1): ?>
    <div class="flex justify-center items-center gap-2 mt-14">
      <?php if ($page > 1): ?>
        <a href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>&category=<?= urlencode($category) ?>"
          class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-500 hover:border-primary hover:text-primary transition">
          <i class="fa-solid fa-chevron-left text-xs"></i>
        </a>
      <?php endif; ?>
      <?php for ($i = 1; $i <= $pages; $i++): ?>
        <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&category=<?= urlencode($category) ?>"
          class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold transition
            <?= $i === $page ? 'bg-primary text-white' : 'border border-gray-200 text-gray-600 hover:border-primary hover:text-primary' ?>">
          <?= $i ?>
        </a>
      <?php endfor; ?>
      <?php if ($page < $pages): ?>
        <a href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>&category=<?= urlencode($category) ?>"
          class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-500 hover:border-primary hover:text-primary transition">
          <i class="fa-solid fa-chevron-right text-xs"></i>
        </a>
      <?php endif; ?>
    </div>
    <?php endif; ?>

  <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
