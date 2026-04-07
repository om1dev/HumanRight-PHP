<?php
// Fetch active event that hasn't expired yet
$activeEvent = $db->events->findOne([
    'active'      => true,
    'event_date'  => ['$gt' => new MongoDB\BSON\UTCDateTime(time() * 1000)],
]);
if (!$activeEvent) return;

$eventTimestamp = $activeEvent['event_date']->toDateTime()->getTimestamp() * 1000;
$eventId        = (string)$activeEvent['_id'];
?>

<!-- Event Popup -->
<div id="eventPopup"
  class="fixed inset-0 z-50 flex items-center justify-center px-4"
  style="background:rgba(0,0,0,0.6); backdrop-filter:blur(4px);">

  <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden animate-fadeIn">

    <!-- Header Banner -->
    <div class="relative">
      <?php if (!empty($activeEvent['thumbnail'])): ?>
        <img src="<?= sanitize($activeEvent['thumbnail']) ?>" class="w-full h-40 object-cover" alt="">
        <div class="absolute inset-0 bg-gradient-to-t from-blue-900 to-transparent"></div>
        <button onclick="closeEventPopup()" class="absolute top-3 right-3 bg-black bg-opacity-40 text-white hover:bg-opacity-70 w-8 h-8 rounded-full flex items-center justify-center text-lg leading-none">&times;</button>
        <div class="absolute bottom-0 left-0 px-6 pb-4 text-white">
          <p class="text-blue-200 text-xs uppercase tracking-widest font-semibold">Upcoming Event</p>
          <h2 class="text-xl font-extrabold"><?= sanitize($activeEvent['title']) ?></h2>
        </div>
      <?php else: ?>
        <div class="bg-gradient-to-r from-blue-900 to-blue-700 px-8 py-6 text-white text-center relative">
          <button onclick="closeEventPopup()" class="absolute top-3 right-4 text-blue-200 hover:text-white text-2xl leading-none">&times;</button>
          <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
            <i class="fa-solid fa-calendar-star text-2xl text-yellow-300"></i>
          </div>
          <p class="text-blue-200 text-xs uppercase tracking-widest font-semibold mb-1">Upcoming Event</p>
          <h2 class="text-xl font-extrabold"><?= sanitize($activeEvent['title']) ?></h2>
        </div>
      <?php endif; ?>
    </div>

    <!-- Body -->
    <div class="px-8 py-6 text-center">
      <?php if (!empty($activeEvent['description'])): ?>
        <p class="text-gray-600 text-sm mb-5 leading-relaxed"><?= sanitize($activeEvent['description']) ?></p>
      <?php endif; ?>

      <!-- Event Date -->
      <p class="text-blue-700 font-semibold text-sm mb-4">
        <i class="fa-solid fa-calendar-days mr-1"></i>
        <?= date('l, F d, Y — h:i A', $activeEvent['event_date']->toDateTime()->getTimestamp()) ?>
      </p>

      <!-- Countdown Timer -->
      <div class="bg-blue-50 rounded-2xl px-4 py-4 mb-6">
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-3">Event starts in</p>
        <div class="grid grid-cols-4 gap-2" id="countdown">
          <?php foreach (['days' => 'Days', 'hours' => 'Hours', 'mins' => 'Mins', 'secs' => 'Secs'] as $id => $label): ?>
          <div class="bg-white rounded-xl shadow-sm py-3">
            <p class="text-2xl font-extrabold text-blue-900" id="cd-<?= $id ?>">00</p>
            <p class="text-xs text-gray-400 mt-0.5"><?= $label ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Buttons -->
      <div class="flex gap-3">
        <?php if (!empty($activeEvent['button_url'])): ?>
          <a href="<?= sanitize($activeEvent['button_url']) ?>"
            class="flex-1 bg-blue-700 text-white py-2.5 rounded-xl font-semibold text-sm hover:bg-blue-600 transition">
            <?= sanitize($activeEvent['button_text'] ?: 'Learn More') ?>
          </a>
        <?php endif; ?>
        <button onclick="closeEventPopup()"
          class="flex-1 bg-gray-100 text-gray-600 py-2.5 rounded-xl font-semibold text-sm hover:bg-gray-200 transition">
          Close
        </button>
      </div>

      <!-- Don't show again -->
      <button onclick="dontShowAgain()" class="mt-3 text-xs text-gray-400 hover:text-gray-600 underline">
        Don't show again
      </button>
    </div>
  </div>
</div>

<style>
@keyframes fadeIn {
  from { opacity:0; transform:scale(0.95); }
  to   { opacity:1; transform:scale(1); }
}
.animate-fadeIn { animation: fadeIn 0.3s ease; }
</style>

<script>
const eventId        = '<?= $eventId ?>';
const eventTimestamp = <?= $eventTimestamp ?>;
const cookieKey      = 'event_closed_' + eventId;

// Check cookie — if dismissed don't show
function getCookie(name) {
  return document.cookie.split(';').some(c => c.trim().startsWith(name + '='));
}

if (getCookie(cookieKey)) {
  document.getElementById('eventPopup').style.display = 'none';
}

// Countdown
function updateCountdown() {
  const now  = new Date().getTime();
  const diff = eventTimestamp - now;

  if (diff <= 0) {
    document.getElementById('eventPopup').style.display = 'none';
    return;
  }

  const days  = Math.floor(diff / (1000 * 60 * 60 * 24));
  const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  const mins  = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
  const secs  = Math.floor((diff % (1000 * 60)) / 1000);

  document.getElementById('cd-days').textContent  = String(days).padStart(2,'0');
  document.getElementById('cd-hours').textContent = String(hours).padStart(2,'0');
  document.getElementById('cd-mins').textContent  = String(mins).padStart(2,'0');
  document.getElementById('cd-secs').textContent  = String(secs).padStart(2,'0');
}

updateCountdown();
setInterval(updateCountdown, 1000);

function closeEventPopup() {
  document.getElementById('eventPopup').style.display = 'none';
}

function dontShowAgain() {
  // Set cookie for 30 days
  const expires = new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toUTCString();
  document.cookie = cookieKey + '=1; expires=' + expires + '; path=/';
  closeEventPopup();
}

// Close on backdrop click
document.getElementById('eventPopup').addEventListener('click', function(e) {
  if (e.target === this) closeEventPopup();
});
</script>
