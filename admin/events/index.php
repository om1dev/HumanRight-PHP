<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();
$pageTitle = 'Manage Events';

$success = flash('success');
$error   = flash('error');
$editing = null;

// Upload thumbnail helper
function uploadEventThumbnail(): string {
    if (empty($_FILES['thumbnail']['name']) || $_FILES['thumbnail']['error'] !== 0) return '';
    $ext     = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','webp'];
    if (!in_array($ext, $allowed)) throw new Exception('Invalid image format.');
    if ($_FILES['thumbnail']['size'] > 5 * 1024 * 1024) throw new Exception('Image must be under 5MB.');
    return uploadToCloudinary($_FILES['thumbnail']['tmp_name'], 'humanrights/events');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action      = $_POST['action'] ?? '';
    $title       = sanitize($_POST['title'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $event_date  = sanitize($_POST['event_date'] ?? '');
    $button_text = sanitize($_POST['button_text'] ?? 'Learn More');
    $button_url  = sanitize($_POST['button_url'] ?? '');
    $active      = isset($_POST['active']);

    if ($action === 'create') {
        if (!$title || !$event_date) {
            $error = 'Title and event date are required.';
        } else {
            try {
                $thumbnail = uploadEventThumbnail();
                $db->events->insertOne([
                    'title'       => $title,
                    'description' => $description,
                    'event_date'  => new MongoDB\BSON\UTCDateTime(strtotime($event_date) * 1000),
                    'button_text' => $button_text,
                    'button_url'  => $button_url,
                    'thumbnail'   => $thumbnail,
                    'active'      => $active,
                    'created_at'  => new MongoDB\BSON\UTCDateTime(),
                ]);
                flash('success', 'Event created successfully.');
                header('Location: ' . SITE_URL . '/admin/events/');
                exit;
            } catch (Exception $e) {
                $error = 'Error: ' . $e->getMessage();
            }
        }

    } elseif ($action === 'update') {
        $id = sanitize($_POST['id'] ?? '');
        if (!$title || !$event_date) {
            $error = 'Title and event date are required.';
        } else {
            try {
                $update = [
                    'title'       => $title,
                    'description' => $description,
                    'event_date'  => new MongoDB\BSON\UTCDateTime(strtotime($event_date) * 1000),
                    'button_text' => $button_text,
                    'button_url'  => $button_url,
                    'active'      => $active,
                    'updated_at'  => new MongoDB\BSON\UTCDateTime(),
                ];
                $newThumb = uploadEventThumbnail();
                if ($newThumb) $update['thumbnail'] = $newThumb;

                $db->events->updateOne(
                    ['_id' => new MongoDB\BSON\ObjectId($id)],
                    ['$set' => $update]
                );
                flash('success', 'Event updated successfully.');
                header('Location: ' . SITE_URL . '/admin/events/');
                exit;
            } catch (Exception $e) {
                $error = 'Error: ' . $e->getMessage();
            }
        }

    } elseif ($action === 'delete') {
        $id = sanitize($_POST['id'] ?? '');
        try {
            $db->events->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
            flash('success', 'Event deleted.');
        } catch (Exception $e) {
            flash('error', 'Could not delete event.');
        }
        header('Location: ' . SITE_URL . '/admin/events/');
        exit;

    } elseif ($action === 'toggle') {
        $id     = sanitize($_POST['id'] ?? '');
        $status = (bool)(int)($_POST['status'] ?? 0);
        try {
            $db->events->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($id)],
                ['$set' => ['active' => $status]]
            );
        } catch (Exception $e) {}
        header('Location: ' . SITE_URL . '/admin/events/');
        exit;
    }
}

if (isset($_GET['edit'])) {
    try {
        $editing = $db->events->findOne(['_id' => new MongoDB\BSON\ObjectId(sanitize($_GET['edit']))]);
    } catch (Exception $e) {}
}

$events = $db->events->find([], ['sort' => ['created_at' => -1]])->toArray();
include __DIR__ . '/../partials/header.php';
?>

<?php if ($success): ?><div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm"><?= $success ?></div><?php endif; ?>
<?php if ($error):   ?><div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm"><?= $error ?></div><?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

  <!-- Form -->
  <div class="bg-white rounded-2xl shadow p-6">
    <h2 class="font-bold text-gray-700 mb-5 flex items-center gap-2">
      <i class="fa-solid <?= $editing ? 'fa-pen' : 'fa-plus' ?> text-blue-600"></i>
      <?= $editing ? 'Edit Event' : 'Create New Event' ?>
    </h2>
    <form method="POST" enctype="multipart/form-data" class="space-y-4">
      <input type="hidden" name="action" value="<?= $editing ? 'update' : 'create' ?>">
      <?php if ($editing): ?>
        <input type="hidden" name="id" value="<?= (string)$editing['_id'] ?>">
      <?php endif; ?>

      <div>
        <label class="text-sm font-medium text-gray-700">Event Title <span class="text-red-500">*</span></label>
        <input type="text" name="title" value="<?= sanitize($editing['title'] ?? '') ?>" required
          placeholder="e.g. Human Rights Conference 2025"
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
      </div>

      <div>
        <label class="text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" rows="3" placeholder="Brief description of the event..."
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none"><?= sanitize($editing['description'] ?? '') ?></textarea>
      </div>

      <!-- Thumbnail Upload -->
      <div>
        <label class="text-sm font-medium text-gray-700">Event Thumbnail</label>
        <div class="mt-1 flex items-center gap-4">
          <!-- Preview -->
          <div id="thumbPreviewWrap" class="flex-shrink-0">
            <?php if (!empty($editing['thumbnail'])): ?>
              <img id="thumbPreview" src="<?= sanitize($editing['thumbnail']) ?>"
                class="w-20 h-20 rounded-xl object-cover border border-gray-200">
            <?php else: ?>
              <div id="thumbPlaceholder" class="w-20 h-20 rounded-xl bg-gray-100 flex items-center justify-center border border-dashed border-gray-300">
                <i class="fa-solid fa-image text-gray-300 text-2xl"></i>
              </div>
              <img id="thumbPreview" src="" class="w-20 h-20 rounded-xl object-cover border border-gray-200 hidden">
            <?php endif; ?>
          </div>
          <div class="flex-1">
            <input type="file" name="thumbnail" id="thumbInput" accept="image/*"
              class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-semibold hover:file:bg-blue-100 cursor-pointer">
            <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP up to 5MB. Uploaded to Cloudinary.</p>
            <?php if (!empty($editing['thumbnail'])): ?>
              <p class="text-xs text-green-600 mt-1"><i class="fa-solid fa-check mr-1"></i>Current image uploaded. Upload new to replace.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div>
        <label class="text-sm font-medium text-gray-700">Event Date & Time <span class="text-red-500">*</span></label>
        <input type="datetime-local" name="event_date" required
          value="<?= $editing ? date('Y-m-d\TH:i', $editing['event_date']->toDateTime()->getTimestamp()) : '' ?>"
          class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="text-sm font-medium text-gray-700">Button Text</label>
          <input type="text" name="button_text" value="<?= sanitize($editing['button_text'] ?? 'Learn More') ?>"
            class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700">Button URL</label>
          <input type="text" name="button_url" value="<?= sanitize($editing['button_url'] ?? '') ?>"
            placeholder="https://... or /contact"
            class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
        </div>
      </div>

      <div class="flex items-center gap-2">
        <input type="checkbox" name="active" id="active" value="1"
          <?= ($editing ? $editing['active'] : true) ? 'checked' : '' ?> class="w-4 h-4 accent-blue-600">
        <label for="active" class="text-sm font-medium text-gray-700">Show popup on website</label>
      </div>

      <div class="flex gap-3">
        <button class="bg-blue-700 text-white px-6 py-2 rounded-lg hover:bg-blue-600 font-semibold text-sm">
          <?= $editing ? 'Update Event' : 'Create Event' ?>
        </button>
        <?php if ($editing): ?>
          <a href="<?= SITE_URL ?>/admin/events/" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 text-sm">Cancel</a>
        <?php endif; ?>
      </div>
    </form>
  </div>

  <!-- Events List -->
  <div class="space-y-4">
    <h2 class="font-bold text-gray-700">All Events (<?= count($events) ?>)</h2>
    <?php if (empty($events)): ?>
      <div class="bg-white rounded-2xl shadow p-8 text-center text-gray-400">
        <i class="fa-solid fa-calendar-xmark text-4xl mb-3"></i>
        <p>No events created yet.</p>
      </div>
    <?php endif; ?>
    <?php foreach ($events as $ev): ?>
    <?php
      $evDate    = $ev['event_date']->toDateTime()->getTimestamp();
      $isExpired = $evDate < time();
    ?>
    <div class="bg-white rounded-2xl shadow overflow-hidden <?= $isExpired ? 'opacity-60' : '' ?>">
      <!-- Thumbnail -->
      <?php if (!empty($ev['thumbnail'])): ?>
        <img src="<?= sanitize($ev['thumbnail']) ?>" class="w-full h-32 object-cover" alt="">
      <?php else: ?>
        <div class="w-full h-20 bg-gradient-to-r from-blue-900 to-blue-700 flex items-center justify-center">
          <i class="fa-solid fa-calendar-star text-white text-3xl opacity-50"></i>
        </div>
      <?php endif; ?>

      <div class="p-5">
        <div class="flex items-start justify-between gap-3">
          <div class="flex-1">
            <div class="flex items-center gap-2 flex-wrap mb-1">
              <h3 class="font-bold text-gray-800"><?= sanitize($ev['title']) ?></h3>
              <?php if ($ev['active'] && !$isExpired): ?>
                <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full font-semibold">Live</span>
              <?php elseif ($isExpired): ?>
                <span class="bg-gray-100 text-gray-500 text-xs px-2 py-0.5 rounded-full">Expired</span>
              <?php else: ?>
                <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded-full">Inactive</span>
              <?php endif; ?>
            </div>
            <?php if (!empty($ev['description'])): ?>
              <p class="text-gray-500 text-xs mb-2"><?= sanitize(substr($ev['description'], 0, 70)) ?>...</p>
            <?php endif; ?>
            <p class="text-xs text-blue-600 font-semibold">
              <i class="fa-solid fa-calendar mr-1"></i><?= date('M d, Y — h:i A', $evDate) ?>
            </p>
          </div>
          <div class="flex flex-col gap-1.5 flex-shrink-0">
            <a href="?edit=<?= (string)$ev['_id'] ?>"
              class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg text-xs hover:bg-blue-100 font-semibold text-center">
              <i class="fa-solid fa-pen mr-1"></i>Edit
            </a>
            <form method="POST">
              <input type="hidden" name="action" value="toggle">
              <input type="hidden" name="id" value="<?= (string)$ev['_id'] ?>">
              <input type="hidden" name="status" value="<?= $ev['active'] ? '0' : '1' ?>">
              <button class="w-full <?= $ev['active'] ? 'bg-yellow-50 text-yellow-600 hover:bg-yellow-100' : 'bg-green-50 text-green-600 hover:bg-green-100' ?> px-3 py-1 rounded-lg text-xs font-semibold">
                <?= $ev['active'] ? 'Deactivate' : 'Activate' ?>
              </button>
            </form>
            <form method="POST" onsubmit="return confirm('Delete this event?')">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= (string)$ev['_id'] ?>">
              <button class="w-full bg-red-50 text-red-500 px-3 py-1 rounded-lg text-xs hover:bg-red-100 font-semibold">
                <i class="fa-solid fa-trash mr-1"></i>Delete
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

</div>

<script>
document.getElementById('thumbInput').addEventListener('change', function () {
  const file = this.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    const preview     = document.getElementById('thumbPreview');
    const placeholder = document.getElementById('thumbPlaceholder');
    preview.src = e.target.result;
    preview.classList.remove('hidden');
    if (placeholder) placeholder.classList.add('hidden');
  };
  reader.readAsDataURL(file);
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>
