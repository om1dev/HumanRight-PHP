<?php
require_once __DIR__ . '/includes/init.php';
$pageTitle = 'About — ' . SITE_NAME;
include __DIR__ . '/includes/header.php';
?>

<section class="bg-blue-900 text-white py-16 text-center px-4">
  <h1 class="text-4xl font-extrabold mb-3">About Us</h1>
  <p class="text-blue-200 max-w-xl mx-auto">Dedicated to protecting human rights and empowering communities since 2010.</p>
</section>

<section class="max-w-5xl mx-auto px-4 py-16 grid md:grid-cols-2 gap-12 items-center">
  <div>
    <h2 class="text-2xl font-bold text-blue-900 mb-4">Our Mission</h2>
    <p class="text-gray-600 mb-4">We are a team of passionate social workers, lawyers, and activists committed to defending the rights of every individual regardless of race, gender, religion, or socioeconomic status.</p>
    <p class="text-gray-600">Through education, advocacy, and community outreach, we strive to create lasting change and ensure that justice is accessible to all.</p>
  </div>
  <div class="bg-blue-50 rounded-2xl p-8 text-center">
    <i class="fa-solid fa-scale-balanced text-6xl text-blue-700 mb-4"></i>
    <h3 class="text-xl font-bold text-blue-900">Justice for All</h3>
    <p class="text-gray-500 mt-2 text-sm">Every person deserves dignity, equality, and the full protection of their rights.</p>
  </div>
</section>

<section class="bg-gray-100 py-16 px-4">
  <div class="max-w-5xl mx-auto">
    <h2 class="text-2xl font-bold text-blue-900 text-center mb-10">Our Core Values</h2>
    <div class="grid md:grid-cols-4 gap-6 text-center">
      <?php foreach ([
        ['fa-heart','Compassion'],
        ['fa-shield-halved','Integrity'],
        ['fa-people-group','Inclusion'],
        ['fa-bullhorn','Advocacy'],
      ] as [$icon, $val]): ?>
      <div class="bg-white rounded-xl shadow p-6">
        <i class="fa-solid <?= $icon ?> text-3xl text-blue-700 mb-3"></i>
        <p class="font-semibold"><?= $val ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
