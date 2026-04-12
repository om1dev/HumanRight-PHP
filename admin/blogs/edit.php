<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();

$id = sanitize($_GET['id'] ?? '');
if (!$id) { header('Location: ' . SITE_URL . '/admin/blogs/'); exit; }

try {
    $blog = $db->blogs->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
} catch (Exception $e) { $blog = null; }
if (!$blog) { header('Location: ' . SITE_URL . '/admin/blogs/'); exit; }

$pageTitle = 'Edit Blog';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title     = sanitize($_POST['title'] ?? '');
    $category  = sanitize($_POST['category'] ?? '');
    $excerpt   = sanitize($_POST['excerpt'] ?? '');
    $content   = $_POST['content'] ?? '';
    $published = isset($_POST['published']);

    if (!$title || !$content) {
        $error = 'Title and content are required.';
    } else {
        $update = [
            'title'      => $title,
            'slug'       => slug($title) . '-' . (string)$blog['_id'],
            'category'   => $category,
            'excerpt'    => $excerpt,
            'content'    => $content,
            'published'  => $published,
            'updated_at' => new MongoDB\BSON\UTCDateTime(),
        ];

        if (!empty($_FILES['image']['name'])) {
            $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp'];
            if (in_array($ext, $allowed)) {
                $image = uniqid('blog_') . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../../assets/images/' . $image);
                $update['image'] = $image;
            }
        }

        $db->blogs->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($id)],
            ['$set' => $update]
        );
        flash('success', 'Blog updated successfully.');
        header('Location: ' . SITE_URL . '/admin/blogs/');
        exit;
    }
}

include __DIR__ . '/../partials/header.php';
?>

<div class="max-w-3xl">
  <?php if ($error): ?><div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-5 text-sm"><?= $error ?></div><?php endif; ?>
  <form method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow p-8 space-y-5">
    <div>
      <label class="text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
      <input type="text" name="title" value="<?= sanitize($_POST['title'] ?? $blog['title']) ?>" required
        class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div>
        <label class="text-sm font-medium text-gray-700">Category</label>
        <input type="text" name="category" value="<?= sanitize($_POST['category'] ?? $blog['category'] ?? '') ?>"
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
      <div>
        <label class="text-sm font-medium text-gray-700">Replace Image</label>
        <input type="file" name="image" accept="image/*"
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 text-sm">
      </div>
    </div>
    <div>
      <label class="text-sm font-medium text-gray-700">Excerpt</label>
      <textarea name="excerpt" rows="2"
        class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"><?= sanitize($_POST['excerpt'] ?? $blog['excerpt'] ?? '') ?></textarea>
    </div>
    <div>
      <label class="text-sm font-medium text-gray-700">Content <span class="text-red-500">*</span></label>
      <textarea name="content" rows="12"
        class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none font-mono text-sm"><?= htmlspecialchars($_POST['content'] ?? $blog['content'], ENT_QUOTES) ?></textarea>
    </div>
    <div class="flex items-center gap-2">
      <input type="checkbox" name="published" id="published" value="1"
        <?= (isset($_POST['published']) || (!isset($_POST['title']) && $blog['published'])) ? 'checked' : '' ?> class="w-4 h-4">
      <label for="published" class="text-sm font-medium text-gray-700">Published</label>
    </div>
    <div class="flex gap-3">
      <button class="bg-blue-700 text-white px-6 py-2 rounded-lg hover:bg-blue-600 font-semibold">Update Blog</button>
      <a href="<?= SITE_URL ?>/admin/blogs/index.php" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200">Cancel</a>
    </div>
  </form>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
