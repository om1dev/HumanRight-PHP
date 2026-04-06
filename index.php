<?php
require_once __DIR__ . '/includes/init.php';
$pageTitle = 'Home — ' . SITE_NAME;

// Fetch latest 3 blogs
$blogs = $db->blogs->find(
    ['published' => true],
    ['sort' => ['created_at' => -1], 'limit' => 3]
)->toArray();

include __DIR__ . '/includes/header.php';
?>

<!-- Hero -->
<section class="bg-gradient-to-br from-blue-900 to-blue-700 text-white py-24 px-4 text-center">
  <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Standing Up for Human Rights</h1>
  <p class="text-lg md:text-xl text-blue-200 max-w-2xl mx-auto mb-8">
    Empowering communities, advocating for justice, and building a more equitable world — one voice at a time.
  </p>
  <a href="<?= SITE_URL ?>/blog.php" class="bg-yellow-400 text-blue-900 font-bold px-8 py-3 rounded-full hover:bg-yellow-300 transition">
    Read Our Blog
  </a>
</section>

<!-- Mission Cards -->
<section class="max-w-6xl mx-auto px-4 py-16 grid md:grid-cols-3 gap-8">
  <?php foreach ([
    ['fa-gavel','Justice','Advocating for fair legal processes and equal rights for all individuals.'],
    ['fa-hands-holding-child','Community','Supporting vulnerable communities through outreach and social programs.'],
    ['fa-earth-americas','Equality','Promoting gender equality, racial justice, and inclusive policies.'],
  ] as [$icon, $title, $desc]): ?>
  <div class="bg-white rounded-2xl shadow p-8 text-center hover:shadow-lg transition">
    <i class="fa-solid <?= $icon ?> text-4xl text-blue-700 mb-4"></i>
    <h3 class="text-xl font-bold mb-2"><?= $title ?></h3>
    <p class="text-gray-500 text-sm"><?= $desc ?></p>
  </div>
  <?php endforeach; ?>
</section>

<!-- Latest Blogs -->
<?php if ($blogs): ?>
<section class="bg-gray-100 py-16 px-4">
  <div class="max-w-6xl mx-auto">
    <h2 class="text-3xl font-bold text-center mb-10 text-blue-900">Latest Articles</h2>
    <div class="grid md:grid-cols-3 gap-8">
      <?php foreach ($blogs as $blog): ?>
      <div class="bg-white rounded-2xl shadow overflow-hidden hover:shadow-lg transition">
        <?php if (!empty($blog['image'])): ?>
          <img src="<?= SITE_URL ?>/assets/images/<?= sanitize($blog['image']) ?>" class="w-full h-48 object-cover" alt="">
        <?php else: ?>
          <div class="w-full h-48 bg-blue-100 flex items-center justify-center">
            <i class="fa-solid fa-newspaper text-5xl text-blue-300"></i>
          </div>
        <?php endif; ?>
        <div class="p-6">
          <span class="text-xs text-blue-600 font-semibold uppercase"><?= sanitize($blog['category'] ?? 'General') ?></span>
          <h3 class="font-bold text-lg mt-1 mb-2"><?= sanitize($blog['title']) ?></h3>
          <p class="text-gray-500 text-sm mb-4"><?= sanitize(substr($blog['excerpt'] ?? '', 0, 100)) ?>...</p>
          <a href="<?= SITE_URL ?>/single-blog.php?slug=<?= sanitize($blog['slug']) ?>" class="text-blue-700 font-semibold text-sm hover:underline">Read More →</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-10">
      <a href="<?= SITE_URL ?>/blog.php" class="bg-blue-800 text-white px-8 py-3 rounded-full hover:bg-blue-700 transition">View All Articles</a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- CTA -->
<section class="py-16 px-4 text-center">
  <h2 class="text-3xl font-bold text-blue-900 mb-4">Have a Question or Need Help?</h2>
  <p class="text-gray-500 mb-6">Reach out to us. We're here to listen and support.</p>
  <a href="<?= SITE_URL ?>/contact.php" class="bg-yellow-400 text-blue-900 font-bold px-8 py-3 rounded-full hover:bg-yellow-300 transition">Contact Us</a>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
