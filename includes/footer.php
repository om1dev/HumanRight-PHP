</main>
<!-- Event Popup -->
<?php include __DIR__ . '/event-popup.php'; ?>
<!-- Footer -->
<footer class="bg-blue-900 text-white text-center py-5 text-sm mt-8">
  <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved.</p>
  <p class="text-blue-300 mt-1">Advocating for justice, equality &amp; human dignity.</p>
</footer>
<script>
  document.getElementById('menuBtn').addEventListener('click', () => {
    document.getElementById('mobileMenu').classList.toggle('hidden');
  });
</script>
</body>
</html>
