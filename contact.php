<?php
require_once __DIR__ . '/includes/init.php';
$pageTitle = 'Contact — ' . SITE_NAME;

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = sanitize($_POST['name'] ?? '');
    $email   = sanitize($_POST['email'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    if (!$name || !$email || !$message) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } else {
        $db->contacts->insertOne([
            'name'       => $name,
            'email'      => $email,
            'subject'    => $subject,
            'message'    => $message,
            'read'       => false,
            'created_at' => new MongoDB\BSON\UTCDateTime(),
        ]);
        $success = 'Your message has been sent. We\'ll be in touch soon.';
    }
}

include __DIR__ . '/includes/header.php';
?>

<!-- Hero -->
<section class="relative bg-ink text-white py-24 px-5 sm:px-8 overflow-hidden">
  <div class="absolute inset-0">
    <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?w=1600&q=80&auto=format&fit=crop"
      alt="" class="w-full h-full object-cover opacity-15">
    <div class="absolute inset-0 bg-gradient-to-r from-ink/95 to-ink/70"></div>
  </div>
  <div class="relative max-w-4xl mx-auto text-center">
    <span class="inline-flex items-center gap-2 bg-primary/20 text-primary text-xs font-semibold px-3 py-1.5 rounded-full mb-6">
      <i class="fa-solid fa-circle-dot text-[8px]"></i> Reach Out
    </span>
    <h1 class="font-serif text-5xl md:text-6xl leading-tight mb-5">
      Let's Build Something<br><span class="text-primary italic">Meaningful Together</span>
    </h1>
    <p class="text-white/60 text-lg max-w-xl mx-auto">
      Whether you're a researcher, activist, funder, or community leader — we want to hear from you.
    </p>
  </div>
</section>


<!-- Partnership types -->
<div class="bg-white border-b border-gray-100 py-8 px-5 sm:px-8">
  <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-5 sr">
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
        <i class="fa-solid fa-handshake text-primary"></i>
      </div>
      <div>
        <h3 class="font-serif text-xl text-ink">Partner With Us</h3>
        <p class="text-gray-500 text-sm">We actively seek partnerships aligned with our values.</p>
      </div>
    </div>
    <div class="flex flex-wrap gap-2">
      <?php foreach (['Research Collaboration','Funding Partnership','Community Programs','Speaking Engagements'] as $type): ?>
      <span class="tag-pill bg-mist border border-gray-200 text-ink text-xs font-semibold px-4 py-2 rounded-full cursor-default"><?= $type ?></span>
      <?php endforeach; ?>
    </div>
  </div>
</div>


<!-- Contact Content -->
<section class="py-20 px-5 sm:px-8 bg-white">
  <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-5 gap-14">

    <!-- Info -->
    <div class="lg:col-span-2 space-y-8 sr-l">
      <div>
        <span class="inline-flex items-center gap-2 bg-primary/10 text-primary text-xs font-semibold px-3 py-1.5 rounded-full mb-4">
          <i class="fa-solid fa-circle-dot text-[8px]"></i> Contact Information
        </span>
        <h2 class="font-serif text-3xl text-ink mb-5">We'd love to hear from you.</h2>
        <p class="text-gray-500 text-sm leading-relaxed">
          Use the form to send us a message, or reach out directly via email or LinkedIn. We aim to respond within 2–3 business days.
        </p>
      </div>

      <div class="space-y-4">
        <?php foreach ([
          ['fa-envelope',     'Email',    ADMIN_EMAIL,                    'mailto:'.ADMIN_EMAIL],
          ['fa-linkedin',     'LinkedIn', 'Connect on LinkedIn',           '#'],
          ['fa-location-dot', 'Location', 'Global — Remote & Field-Based', null],
        ] as [$icon,$label,$val,$url]): ?>
        <div class="flex items-start gap-4">
          <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid <?= $icon ?> text-primary text-sm"></i>
          </div>
          <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-0.5"><?= $label ?></p>
            <?php if ($url): ?>
              <a href="<?= $url ?>" class="text-ink font-semibold text-sm hover:text-primary transition-colors"><?= $val ?></a>
            <?php else: ?>
              <p class="text-ink text-sm font-semibold"><?= $val ?></p>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="bg-mist rounded-2xl border border-gray-100 p-6">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-4">We work on</p>
        <div class="flex flex-wrap gap-2">
          <?php foreach (['Gender Justice','Governance','Economic Rights','Global South','Policy Reform','Community Dev'] as $tag): ?>
          <span class="tag-pill bg-white border border-gray-200 text-ink text-xs font-semibold px-3 py-1.5 rounded-full cursor-default"><?= $tag ?></span>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Form -->
    <div class="lg:col-span-3 sr-r">
      <div class="bg-mist rounded-2xl border border-gray-100 p-8">

        <?php if ($success): ?>
          <div class="bg-primary/8 border border-primary/20 text-primary px-5 py-4 rounded-xl mb-6 text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> <?= $success ?>
          </div>
        <?php endif; ?>
        <?php if ($error): ?>
          <div class="bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-xl mb-6 text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i> <?= $error ?>
          </div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
              <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-2">
                Full Name <span class="text-red-400">*</span>
              </label>
              <input type="text" name="name" value="<?= sanitize($_POST['name'] ?? '') ?>" required
                class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 transition">
            </div>
            <div>
              <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-2">
                Email Address <span class="text-red-400">*</span>
              </label>
              <input type="email" name="email" value="<?= sanitize($_POST['email'] ?? '') ?>" required
                class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 transition">
            </div>
          </div>
          <div>
            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-2">Subject</label>
            <input type="text" name="subject" value="<?= sanitize($_POST['subject'] ?? '') ?>"
              placeholder="e.g. Research Collaboration, Partnership Inquiry..."
              class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 transition">
          </div>
          <div>
            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-2">
              Message <span class="text-red-400">*</span>
            </label>
            <textarea name="message" rows="6" required
              placeholder="Tell us about your inquiry, project, or how you'd like to collaborate..."
              class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 resize-none transition"><?= sanitize($_POST['message'] ?? '') ?></textarea>
          </div>
          <button type="submit"
            class="w-full bg-primary text-white font-semibold py-4 rounded-xl hover:bg-primary/85 transition text-sm flex items-center justify-center gap-2 shadow-md shadow-primary/20">
            Send Message <i class="fa-solid fa-paper-plane text-xs"></i>
          </button>
        </form>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
