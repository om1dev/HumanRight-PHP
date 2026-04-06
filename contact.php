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
        $success = 'Your message has been sent. We\'ll get back to you soon!';
    }
}

include __DIR__ . '/includes/header.php';
?>

<section class="bg-blue-900 text-white py-14 text-center px-4">
  <h1 class="text-4xl font-extrabold mb-2">Contact Us</h1>
  <p class="text-blue-200">We'd love to hear from you. Reach out anytime.</p>
</section>

<section class="max-w-5xl mx-auto px-4 py-16 grid md:grid-cols-2 gap-12">
  <!-- Info -->
  <div>
    <h2 class="text-2xl font-bold text-blue-900 mb-6">Get In Touch</h2>
    <?php foreach ([
      ['fa-envelope','Email', ADMIN_EMAIL],
      ['fa-phone','Phone','+1 (555) 000-0000'],
      ['fa-location-dot','Address','123 Justice Ave, Human Rights City'],
    ] as [$icon, $label, $val]): ?>
    <div class="flex items-start gap-4 mb-5">
      <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
        <i class="fa-solid <?= $icon ?> text-blue-700"></i>
      </div>
      <div>
        <p class="font-semibold text-sm"><?= $label ?></p>
        <p class="text-gray-500 text-sm"><?= $val ?></p>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Form -->
  <div class="bg-white rounded-2xl shadow p-8">
    <?php if ($success): ?>
      <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-5"><?= $success ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-5"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST" class="space-y-4">
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
          <input type="text" name="name" value="<?= sanitize($_POST['name'] ?? '') ?>"
            class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
          <input type="email" name="email" value="<?= sanitize($_POST['email'] ?? '') ?>"
            class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
      </div>
      <div>
        <label class="text-sm font-medium text-gray-700">Subject</label>
        <input type="text" name="subject" value="<?= sanitize($_POST['subject'] ?? '') ?>"
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
      <div>
        <label class="text-sm font-medium text-gray-700">Message <span class="text-red-500">*</span></label>
        <textarea name="message" rows="5"
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"><?= sanitize($_POST['message'] ?? '') ?></textarea>
      </div>
      <button class="w-full bg-blue-800 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
        Send Message
      </button>
    </form>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
