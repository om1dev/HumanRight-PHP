<?php
require_once __DIR__ . '/includes/init.php';

$slug = sanitize($_GET['slug'] ?? '');
if (!$slug) { header('Location: ' . SITE_URL . '/blog'); exit; }

$blog = $db->blogs->findOne(['slug' => $slug, 'published' => true]);
if (!$blog) { header('Location: ' . SITE_URL . '/blog'); exit; }

$blogId = (string)$blog['_id'];
$pageTitle = sanitize($blog['title']) . ' — ' . SITE_NAME;

$commentError = $commentSuccess = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
    $body = trim($_POST['comment'] ?? '');
    if (strlen($body) < 3) {
        $commentError = 'Comment is too short.';
    } else {
        $db->comments->insertOne([
            'blog_id'    => $blogId,
            'user_id'    => $_SESSION['user_id'],
            'username'   => $_SESSION['username'],
            'body'       => htmlspecialchars($body, ENT_QUOTES, 'UTF-8'),
            'approved'   => false,
            'created_at' => new MongoDB\BSON\UTCDateTime(),
        ]);
        $commentSuccess = 'Comment submitted and awaiting approval.';
    }
}

$comments = $db->comments->find(
    ['blog_id' => $blogId, 'approved' => true],
    ['sort' => ['created_at' => -1]]
)->toArray();

$related = $db->blogs->find(
    ['published' => true, 'category' => $blog['category'] ?? '', '_id' => ['$ne' => $blog['_id']]],
    ['sort' => ['created_at' => -1], 'limit' => 3]
)->toArray();

$readTime = max(1, (int)(str_word_count(strip_tags($blog['content'])) / 200));

include __DIR__ . '/includes/header.php';
?>

<!-- Article Header -->
<div class="bg-mist border-b border-gray-100">
  <div class="max-w-4xl mx-auto px-5 sm:px-8 pt-10 pb-12">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-gray-400 mb-8">
      <a href="<?= SITE_URL ?>/" class="hover:text-primary transition-colors">Home</a>
      <i class="fa-solid fa-chevron-right text-[9px]"></i>
      <a href="<?= SITE_URL ?>/blog" class="hover:text-primary transition-colors">Insights</a>
      <i class="fa-solid fa-chevron-right text-[9px]"></i>
      <span class="text-gray-600 truncate max-w-[220px]"><?= sanitize($blog['title']) ?></span>
    </nav>

    <!-- Meta -->
    <div class="flex flex-wrap items-center gap-3 mb-6">
      <a href="<?= SITE_URL ?>/blog?category=<?= urlencode($blog['category'] ?? '') ?>"
        class="bg-primary/10 text-primary text-xs font-semibold px-4 py-1.5 rounded-full hover:bg-primary/20 transition">
        <?= sanitize($blog['category'] ?? 'General') ?>
      </a>
      <span class="text-gray-400 text-sm">
        <i class="fa-regular fa-calendar mr-1.5"></i><?= date('F d, Y', $blog['created_at']->toDateTime()->getTimestamp()) ?>
      </span>
      <span class="text-gray-300">·</span>
      <span class="text-gray-400 text-sm">
        <i class="fa-regular fa-clock mr-1.5"></i><?= $readTime ?> min read
      </span>
    </div>

    <!-- Title -->
    <h1 class="font-serif text-4xl sm:text-5xl text-ink leading-tight mb-6">
      <?= sanitize($blog['title']) ?>
    </h1>

    <?php if (!empty($blog['excerpt'])): ?>
    <p class="text-gray-500 text-lg leading-relaxed border-l-[3px] border-primary pl-5 max-w-2xl">
      <?= sanitize($blog['excerpt']) ?>
    </p>
    <?php endif; ?>
  </div>
</div>

<!-- Featured Image -->
<?php if (!empty($blog['image'])): ?>
<div class="max-w-5xl mx-auto px-5 sm:px-8 py-8">
  <img src="<?= SITE_URL ?>/assets/images/<?= sanitize($blog['image']) ?>"
    class="w-full rounded-2xl object-cover max-h-[520px] shadow-sm border border-gray-100"
    alt="<?= sanitize($blog['title']) ?>">
</div>
<?php endif; ?>

<!-- Body + Sidebar -->
<div class="max-w-7xl mx-auto px-5 sm:px-8 py-10">
  <div class="grid grid-cols-1 lg:grid-cols-[1fr_260px] gap-12 xl:gap-16 items-start">

    <!-- Article -->
    <div>
      <div class="prose-body" id="articleBody">
        <?= $blog['content'] ?>
      </div>

      <!-- Share row -->
      <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-5">
        <div class="flex flex-wrap gap-2">
          <span class="text-xs text-gray-400 font-semibold self-center">Category:</span>
          <a href="<?= SITE_URL ?>/blog?category=<?= urlencode($blog['category'] ?? '') ?>"
            class="bg-primary/10 text-primary text-xs font-semibold px-3 py-1.5 rounded-full hover:bg-primary/20 transition">
            <?= sanitize($blog['category'] ?? 'General') ?>
          </a>
        </div>
        <div class="flex items-center gap-2">
          <span class="text-xs text-gray-400 font-semibold">Share:</span>
          <?php foreach ([
            ['fa-twitter',  'https://twitter.com/intent/tweet?text='.urlencode($blog['title']).'&url='.urlencode(SITE_URL.'/single-blog?slug='.$blog['slug'])],
            ['fa-linkedin', 'https://www.linkedin.com/sharing/share-offsite/?url='.urlencode(SITE_URL.'/single-blog?slug='.$blog['slug'])],
            ['fa-facebook', 'https://www.facebook.com/sharer/sharer.php?u='.urlencode(SITE_URL.'/single-blog?slug='.$blog['slug'])],
          ] as [$icon,$url]): ?>
          <a href="<?= $url ?>" target="_blank" rel="noopener"
            class="w-9 h-9 rounded-full border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-primary hover:text-white hover:border-primary transition text-sm">
            <i class="fa-brands <?= $icon ?> text-xs"></i>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Sidebar -->
    <aside class="hidden lg:block space-y-5 sticky top-24">
      <div class="bg-mist rounded-2xl p-6 border border-gray-100">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">About this Article</p>
        <div class="space-y-3 text-sm text-gray-600">
          <div class="flex items-center gap-2.5">
            <i class="fa-regular fa-calendar text-primary w-4"></i>
            <span><?= date('F d, Y', $blog['created_at']->toDateTime()->getTimestamp()) ?></span>
          </div>
          <div class="flex items-center gap-2.5">
            <i class="fa-regular fa-clock text-primary w-4"></i>
            <span><?= $readTime ?> min read</span>
          </div>
          <div class="flex items-center gap-2.5">
            <i class="fa-solid fa-tag text-primary w-4"></i>
            <a href="<?= SITE_URL ?>/blog?category=<?= urlencode($blog['category'] ?? '') ?>"
              class="text-primary hover:underline"><?= sanitize($blog['category'] ?? 'General') ?></a>
          </div>
          <div class="flex items-center gap-2.5">
            <i class="fa-solid fa-comments text-primary w-4"></i>
            <span><?= count($comments) ?> comment<?= count($comments) !== 1 ? 's' : '' ?></span>
          </div>
        </div>
      </div>

      <div class="bg-mist rounded-2xl p-6 border border-gray-100">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">Share</p>
        <div class="flex gap-2">
          <?php foreach ([
            ['fa-twitter',  'https://twitter.com/intent/tweet?text='.urlencode($blog['title']).'&url='.urlencode(SITE_URL.'/single-blog?slug='.$blog['slug'])],
            ['fa-linkedin', 'https://www.linkedin.com/sharing/share-offsite/?url='.urlencode(SITE_URL.'/single-blog?slug='.$blog['slug'])],
            ['fa-facebook', 'https://www.facebook.com/sharer/sharer.php?u='.urlencode(SITE_URL.'/single-blog?slug='.$blog['slug'])],
          ] as [$icon,$url]): ?>
          <a href="<?= $url ?>" target="_blank" rel="noopener"
            class="flex-1 flex items-center justify-center py-2.5 rounded-xl border border-gray-200 text-gray-500 hover:bg-primary hover:text-white hover:border-primary transition text-xs font-semibold">
            <i class="fa-brands <?= $icon ?>"></i>
          </a>
          <?php endforeach; ?>
        </div>
      </div>

      <a href="<?= SITE_URL ?>/blog"
        class="flex items-center gap-2 text-sm text-gray-500 hover:text-primary transition font-medium">
        <i class="fa-solid fa-arrow-left text-xs"></i> Back to Insights
      </a>
    </aside>
  </div>
</div>


<!-- Related Articles -->
<?php if (!empty($related)): ?>
<section class="bg-mist border-t border-gray-100 py-16 px-5 sm:px-8">
  <div class="max-w-7xl mx-auto">
    <h2 class="font-serif text-3xl text-ink mb-10">Related Insights</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($related as $r): ?>
      <article class="group bg-white rounded-2xl overflow-hidden lift border border-gray-100">
        <a href="<?= SITE_URL ?>/single-blog?slug=<?= sanitize($r['slug']) ?>"
          class="block aspect-[16/9] bg-mist overflow-hidden">
          <?php if (!empty($r['image'])): ?>
            <img src="<?= SITE_URL ?>/assets/images/<?= sanitize($r['image']) ?>"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              alt="<?= sanitize($r['title']) ?>" loading="lazy">
          <?php else: ?>
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary/5 to-accent/5">
              <i class="fa-solid fa-newspaper text-3xl text-gray-200"></i>
            </div>
          <?php endif; ?>
        </a>
        <div class="p-5">
          <span class="text-primary text-xs font-semibold"><?= sanitize($r['category'] ?? 'General') ?></span>
          <h3 class="font-serif text-lg text-ink mt-2 mb-3 leading-snug group-hover:text-primary transition-colors">
            <a href="<?= SITE_URL ?>/single-blog?slug=<?= sanitize($r['slug']) ?>"><?= sanitize($r['title']) ?></a>
          </h3>
          <a href="<?= SITE_URL ?>/single-blog?slug=<?= sanitize($r['slug']) ?>"
            class="inline-flex items-center gap-1 text-primary text-xs font-semibold hover:gap-2 transition-all">
            Read <i class="fa-solid fa-arrow-right text-[10px]"></i>
          </a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- Comments -->
<section class="py-16 px-5 sm:px-8 bg-white border-t border-gray-100">
  <div class="max-w-3xl mx-auto">
    <h2 class="font-serif text-3xl text-ink mb-8">
      Discussion
      <span class="text-gray-300 font-sans font-normal text-lg ml-2">(<?= count($comments) ?>)</span>
    </h2>

    <?php if ($commentSuccess): ?>
      <div class="bg-primary/8 border border-primary/20 text-primary px-5 py-4 rounded-xl mb-6 text-sm flex items-center gap-2">
        <i class="fa-solid fa-circle-check"></i> <?= $commentSuccess ?>
      </div>
    <?php endif; ?>
    <?php if ($commentError): ?>
      <div class="bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-xl mb-6 text-sm flex items-center gap-2">
        <i class="fa-solid fa-circle-exclamation"></i> <?= $commentError ?>
      </div>
    <?php endif; ?>

    <?php if (isLoggedIn()): ?>
    <form method="POST" class="mb-12 bg-mist rounded-2xl p-6 border border-gray-100">
      <p class="text-sm font-semibold text-ink mb-3">Leave a comment</p>
      <textarea name="comment" rows="4" placeholder="Share your thoughts on this article..."
        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 resize-none bg-white"></textarea>
      <div class="flex items-center justify-between mt-3">
        <p class="text-xs text-gray-400">Comments are reviewed before publishing.</p>
        <button class="bg-primary text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:bg-primary/85 transition">
          Post Comment
        </button>
      </div>
    </form>
    <?php else: ?>
    <div class="bg-mist border border-gray-200 rounded-2xl p-6 mb-12 text-center">
      <p class="text-gray-500 text-sm">
        <a href="<?= SITE_URL ?>/auth/login" class="text-primary font-semibold hover:underline">Sign in</a> to join the discussion.
      </p>
    </div>
    <?php endif; ?>

    <?php if (empty($comments)): ?>
      <div class="text-center py-10">
        <i class="fa-regular fa-comment text-3xl text-gray-200 mb-3 block"></i>
        <p class="text-gray-400 text-sm">No comments yet. Start the conversation.</p>
      </div>
    <?php else: ?>
    <div class="space-y-4">
      <?php foreach ($comments as $c): ?>
      <div class="flex gap-4">
        <div class="w-10 h-10 rounded-full bg-ink text-white flex items-center justify-center font-semibold text-sm flex-shrink-0 mt-0.5">
          <?= strtoupper(substr($c['username'], 0, 1)) ?>
        </div>
        <div class="flex-1 bg-mist rounded-2xl px-5 py-4 border border-gray-100">
          <div class="flex flex-wrap items-center gap-3 mb-2">
            <p class="font-semibold text-ink text-sm"><?= sanitize($c['username']) ?></p>
            <p class="text-gray-400 text-xs"><?= date('M d, Y · H:i', $c['created_at']->toDateTime()->getTimestamp()) ?></p>
          </div>
          <p class="text-gray-600 text-sm leading-relaxed"><?= $c['body'] ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
