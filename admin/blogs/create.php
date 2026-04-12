<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();
$pageTitle = 'Create Blog';
$error = '';

$categories = $db->blogs->distinct('category', []);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title     = sanitize($_POST['title'] ?? '');
    $category  = sanitize($_POST['category'] ?? '');
    $excerpt   = sanitize($_POST['excerpt'] ?? '');
    $content   = $_POST['content'] ?? '';
    $published = isset($_POST['published']);

    if (!$title || !$content) {
        $error = 'Title and content are required.';
    } else {
        $image = '';
        if (!empty($_FILES['image']['name'])) {
            $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp'];
            if (in_array($ext, $allowed)) {
                $image = uniqid('blog_') . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../../assets/images/' . $image);
            }
        }
        $db->blogs->insertOne([
            'title'      => $title,
            'slug'       => slug($title) . '-' . time(),
            'category'   => $category,
            'excerpt'    => $excerpt,
            'content'    => $content,
            'image'      => $image,
            'published'  => $published,
            'created_at' => new MongoDB\BSON\UTCDateTime(),
            'updated_at' => new MongoDB\BSON\UTCDateTime(),
        ]);
        // Log activity
        $db->activity->insertOne([
            'message'    => 'Blog "' . $title . '" was created by ' . ($_SESSION['admin_username'] ?? 'Admin'),
            'icon'       => 'fa-newspaper',
            'created_at' => new MongoDB\BSON\UTCDateTime(),
        ]);
        flash('success', 'Blog created successfully.');
        header('Location: ' . SITE_URL . '/admin/blogs/');
        exit;
    }
}

include __DIR__ . '/../partials/header.php';
?>

<div class="max-w-4xl">
  <?php if ($error): ?><div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-5 text-sm"><?= $error ?></div><?php endif; ?>
  <form method="POST" enctype="multipart/form-data" class="space-y-5">
    <div class="bg-white rounded-2xl shadow p-6 space-y-5">
      <div>
        <label class="text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
        <input type="text" name="title" value="<?= sanitize($_POST['title'] ?? '') ?>" required
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg font-semibold">
      </div>
      <div>
        <label class="text-sm font-medium text-gray-700">Content <span class="text-red-500">*</span></label>
        <textarea name="content" id="content" rows="16" placeholder="Write your blog content here..."
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-y font-mono text-sm"><?= htmlspecialchars($_POST['content'] ?? '', ENT_QUOTES) ?></textarea>
        <p class="text-xs text-gray-400 mt-1">Basic HTML is supported. e.g. &lt;b&gt;bold&lt;/b&gt;, &lt;p&gt;paragraph&lt;/p&gt;, &lt;ul&gt;&lt;li&gt;list&lt;/li&gt;&lt;/ul&gt;</p>
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 space-y-4">
      <h3 class="font-semibold text-gray-700">Blog Settings</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="text-sm font-medium text-gray-700">Category</label>
          <input type="text" name="category" value="<?= sanitize($_POST['category'] ?? '') ?>"
            list="cat-list"
            class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
          <datalist id="cat-list">
            <?php foreach ($categories as $cat): ?>
              <option value="<?= sanitize($cat) ?>">
            <?php endforeach; ?>
          </datalist>
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700">Featured Image</label>
          <input type="file" name="image" accept="image/*" id="imgInput"
            class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 text-sm">
          <img id="imgPreview" src="" class="mt-2 rounded-lg max-h-32 hidden" alt="Preview">
        </div>
      </div>
      <div>
        <label class="text-sm font-medium text-gray-700">Excerpt</label>
        <textarea name="excerpt" rows="2"
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"><?= sanitize($_POST['excerpt'] ?? '') ?></textarea>
      </div>
      <div class="flex items-center gap-2">
        <input type="checkbox" name="published" id="published" value="1" <?= isset($_POST['published']) ? 'checked' : '' ?> class="w-4 h-4 accent-blue-600">
        <label for="published" class="text-sm font-medium text-gray-700">Publish immediately</label>
      </div>
    </div>

    <div class="flex gap-3">
      <button class="bg-blue-700 text-white px-8 py-2.5 rounded-lg hover:bg-blue-600 font-semibold">Create Blog</button>
      <a href="<?= SITE_URL ?>/admin/blogs/" class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-200">Cancel</a>
    </div>
  </form>
</div>

<script>
document.getElementById('imgInput').addEventListener('change', function() {
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = e => {
      const img = document.getElementById('imgPreview');
      img.src = e.target.result;
      img.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
  }
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>
