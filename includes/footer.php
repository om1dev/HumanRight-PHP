</main>
<?php include __DIR__ . '/event-popup.php'; ?>

<footer class="relative overflow-hidden bg-ink text-white">
  <div class="absolute inset-0 opacity-25 pointer-events-none" style="background:radial-gradient(circle at top right, rgba(48,68,200,.22), transparent 32%), radial-gradient(circle at left bottom, rgba(209,73,91,.18), transparent 28%);"></div>
  <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/15 to-transparent"></div>
  <div class="relative max-w-7xl mx-auto px-5 sm:px-8 pt-16 pb-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">

    <!-- Brand -->
    <div class="sm:col-span-2 lg:col-span-2">
      <a href="<?= SITE_URL ?>/" class="flex items-center gap-3 mb-5">
        <div class="relative w-9 h-9 flex-shrink-0">
          <svg viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
            <rect width="36" height="36" rx="10" fill="#2D3A8C"/>
            <path d="M18 7 L18 29" stroke="white" stroke-width="2" stroke-linecap="round"/>
            <path d="M10 13 L18 7 L26 13" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M10 13 L10 22 Q10 25 13 25 L23 25 Q26 25 26 22 L26 13" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
            <circle cx="18" cy="29" r="1.5" fill="#C0392B"/>
          </svg>
        </div>
        <div>
          <p class="font-serif text-white text-lg leading-none"><?= SITE_NAME ?></p>
          <p class="text-primary text-[9px] font-semibold tracking-[0.18em] uppercase mt-0.5">Justice · Equity · Rights</p>
        </div>
      </a>
      <p class="text-white/45 text-sm leading-relaxed max-w-xs mb-6">
        A global platform advancing human rights, social justice, and equitable futures through research, advocacy, and community-led action.
      </p>
      <div class="flex gap-2.5">
        <?php foreach ([['fa-twitter','#'],['fa-linkedin','#'],['fa-facebook','#'],['fa-instagram','#']] as [$icon,$url]): ?>
        <a href="<?= $url ?>"
          class="w-9 h-9 rounded-full border border-white/15 flex items-center justify-center text-white/50 hover:bg-primary hover:border-primary hover:text-white transition-all text-sm">
          <i class="fa-brands <?= $icon ?>"></i>
        </a>
        <?php endforeach; ?>
      </div>
    </div>

    <div>
      <p class="text-white/30 text-xs font-semibold uppercase tracking-widest mb-5">Navigate</p>
      <ul class="space-y-3 text-sm text-white/55">
        <?php foreach ([['Home',SITE_URL.'/'],['About',SITE_URL.'/about'],['Insights',SITE_URL.'/blog'],['Contact',SITE_URL.'/contact']] as [$l,$u]): ?>
        <li><a href="<?= $u ?>" class="hover:text-white transition-colors"><?= $l ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div>
      <p class="text-white/30 text-xs font-semibold uppercase tracking-widest mb-5">Focus Areas</p>
      <ul class="space-y-3 text-sm text-white/55">
        <?php foreach (['Gender Justice','Governance & Accountability','Economic Justice','Decolonial Development','Global South Voices'] as $a): ?>
        <li class="flex items-start gap-2">
          <span class="w-1 h-1 rounded-full bg-primary mt-2 flex-shrink-0"></span><?= $a ?>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>

  <div class="relative border-t border-white/8 max-w-7xl mx-auto px-5 sm:px-8 py-5 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-white/25">
    <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved.</p>
    <p>Advocating for justice, equality &amp; human dignity worldwide.</p>
  </div>
</footer>

</body>
</html>
