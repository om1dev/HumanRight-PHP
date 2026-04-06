<?php
require_once __DIR__ . '/includes/init.php';

$slug = sanitize($_GET['slug'] ?? '');
if (!$slug) { header('Location: ' . SITE_URL . '/blog.php'); exit; }

$blog = $db->blogs->findOne(['slug' => $slug, 'published' => true]);
if (!$blog) { header('Location: ' . SITE_URL . '/blog.php'); exit; }

$blogId = (string)$blog['_id'];
$pageTitle = sanitize($blog['title']) . ' — ' . SITE_NAME;

// Handle comment submission
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
            'created_at' => new MongoDB\BSON\UTCDateTime(),
        ]);
        $commentSuccess = 'Comment posted!';
    }
}

$comments = $db->comments->find(
    ['blog_id' => $blogId],
    ['sort' => ['created_at' => -1]]
)->toArray();

include __DIR__ . '/includes/header.php';
?>

<article class="max-w-3xl mx-auto px-4 py-12">
  <!-- Category & Date -->
  <div class="flex items-center gap-3 text-sm text-gray-400 mb-3">
    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-semibold"><?= sanitize($blog['category'] ?? 'General') ?></span>
    <span><i class="fa-regular fa-calendar mr-1"></i><?= date('F d, Y', $blog['created_at']->toDateTime()->getTimestamp()) ?></span>
  </div>

  <h1 class="text-3xl md:text-4xl font-extrabold text-blue-900 mb-6"><?= sanitize($blog['title']) ?></h1>

  <?php if (!empty($blog['image'])): ?>
    <img src="<?= SITE_URL ?>/assets/images/<?= sanitize($blog['image']) ?>" class="w-full rounded-2xl mb-8 object-cover max-h-96" alt="">
  <?php endif; ?>

  <div class="prose max-w-none text-gray-700 leading-relaxed text-base">
    <?= $blog['content'] /* stored as HTML from admin */ ?>
  </div>
</article>

<!-- Comments -->
<section class="max-w-3xl mx-auto px-4 pb-16">
  <hr class="mb-8">
  <h2 class="text-2xl font-bold text-blue-900 mb-6">Comments (<?= count($comments) ?>)</h2>

  <?php if ($commentSuccess): ?>
    <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4"><?= $commentSuccess ?></div>
  <?php endif; ?>
  <?php if ($commentError): ?>
    <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4"><?= $commentError ?></div>
  <?php endif; ?>

  <?php if (isLoggedIn()): ?>
  <form method="POST" class="mb-8">
    <textarea name="comment" rows="4" placeholder="Write your comment..."
      class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
    <button class="mt-3 bg-blue-800 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Post Comment</button>
  </form>
  <?php else: ?>
  <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-8 text-center">
    <p class="text-gray-600">Please <a href="<?= SITE_URL ?>/auth/login.php" class="text-blue-700 font-semibold hover:underline">login</a> to leave a comment.</p>
  </div>
  <?php endif; ?>

  <?php if (empty($comments)): ?>
    <p class="text-gray-400 text-center py-6">No comments yet. Be the first!</p>
  <?php else: ?>
    <div class="space-y-5">
      <?php foreach ($comments as $c): ?>
      <div class="bg-white border rounded-xl p-5 shadow-sm">
        <div class="flex items-center gap-3 mb-2">
          <div class="w-9 h-9 rounded-full bg-blue-700 text-white flex items-center justify-center font-bold text-sm">
            <?= strtoupper(substr($c['username'], 0, 1)) ?>
          </div>
          <div>
            <p class="font-semibold text-sm"><?= sanitize($c['username']) ?></p>
            <p class="text-xs text-gray-400"><?= date('M d, Y H:i', $c['created_at']->toDateTime()->getTimestamp()) ?></p>
          </div>
        </div>
        <p class="text-gray-700 text-sm"><?= $c['body'] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
