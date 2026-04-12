<?php
require_once __DIR__ . '/includes/init.php';
$pageTitle = 'About — ' . SITE_NAME;
include __DIR__ . '/includes/header.php';
?>

<!-- Hero -->
<section class="relative bg-ink text-white py-28 px-5 sm:px-8 overflow-hidden">
  <div class="absolute inset-0">
    <img src="https://images.unsplash.com/photo-1531206715517-5c0ba140b2b8?w=1600&q=80&auto=format&fit=crop"
      alt="Advocacy" class="w-full h-full object-cover opacity-20">
    <div class="absolute inset-0 bg-gradient-to-r from-ink/95 to-ink/60"></div>
  </div>
  <div class="relative max-w-4xl mx-auto text-center">
    <span class="inline-flex items-center gap-2 bg-primary/20 text-primary text-xs font-semibold px-3 py-1.5 rounded-full mb-6">
      <i class="fa-solid fa-circle-dot text-[8px]"></i> Who We Are
    </span>
    <h1 class="font-serif text-5xl md:text-6xl leading-tight mb-6">
      A Movement Built on<br><span class="text-primary italic">Principle & Purpose</span>
    </h1>
    <p class="text-white/60 text-lg leading-relaxed max-w-2xl mx-auto">
      We are researchers, advocates, and community builders united by a single conviction: that justice is possible, and that it requires all of us.
    </p>
  </div>
</section>


<!-- Mission & Vision -->
<section class="py-24 px-5 sm:px-8 bg-white">
  <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
    <div class="sr-l space-y-6">
      <span class="inline-flex items-center gap-2 bg-primary/10 text-primary text-xs font-semibold px-3 py-1.5 rounded-full">
        <i class="fa-solid fa-circle-dot text-[8px]"></i> Our Mission
      </span>
      <h2 class="font-serif text-4xl text-ink leading-tight">
        Centering the voices of those most impacted by injustice.
      </h2>
      <p class="text-gray-500 leading-relaxed">
        We are a global platform dedicated to protecting human rights, advancing social justice, and building equitable systems. Our work spans research, policy advocacy, community engagement, and international partnerships.
      </p>
      <p class="text-gray-500 leading-relaxed">
        We believe that lasting change comes from the ground up — from communities who understand their own needs and have the power to shape their own futures.
      </p>
    </div>
    <div class="sr-r space-y-4">
      <div class="bg-mist rounded-2xl border border-gray-100 p-8">
        <div class="w-11 h-11 rounded-xl bg-primary/10 flex items-center justify-center mb-5">
          <i class="fa-solid fa-eye text-primary"></i>
        </div>
        <h3 class="font-serif text-2xl text-ink mb-3">Our Vision</h3>
        <p class="text-gray-500 leading-relaxed">A world where every person — regardless of gender, race, nationality, or economic status — lives with full dignity, freedom, and access to justice.</p>
      </div>
      <div class="bg-blush rounded-2xl border border-accent/10 p-8">
        <div class="w-11 h-11 rounded-xl bg-accent/10 flex items-center justify-center mb-5">
          <i class="fa-solid fa-compass text-accent"></i>
        </div>
        <h3 class="font-serif text-2xl text-ink mb-3">Our Approach</h3>
        <p class="text-gray-500 leading-relaxed">Community-led. Evidence-based. Intersectional. We don't impose solutions — we co-create them with the people and communities we serve.</p>
      </div>
    </div>
  </div>
</section>


<!-- Core Values -->
<section class="py-24 px-5 sm:px-8 bg-mist">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-16 sr">
      <span class="inline-flex items-center gap-2 bg-primary/10 text-primary text-xs font-semibold px-3 py-1.5 rounded-full mb-4">
        <i class="fa-solid fa-circle-dot text-[8px]"></i> What Guides Us
      </span>
      <h2 class="font-serif text-4xl md:text-5xl text-ink">Core Values</h2>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
      <?php foreach ([
        ['fa-scale-balanced','#2D3A8C','bg-primary/10',  'Equity',         'We go beyond equality — addressing root causes and structural barriers to ensure everyone has what they need to thrive.'],
        ['fa-people-group',  '#C0392B','bg-accent/10', 'Inclusion',      'Every voice matters. We actively centre those most marginalised in our work, governance, and partnerships.'],
        ['fa-eye',           '#2D3A8C','bg-primary/10',  'Transparency',   'We hold ourselves accountable to the communities we serve through open reporting and ethical practice.'],
        ['fa-shield-halved', '#C0392B','bg-accent/10', 'Accountability', 'We take responsibility for our actions and their impact — and we demand the same from institutions of power.'],
      ] as $i=>[$icon,$color,$bg,$title,$desc]): ?>
      <div class="bg-white rounded-2xl border border-gray-100 p-7 lift sr" style="transition-delay:<?= $i*80 ?>ms">
        <div class="w-11 h-11 rounded-xl <?= $bg ?> flex items-center justify-center mb-5">
          <i class="fa-solid <?= $icon ?> text-base" style="color:<?= $color ?>"></i>
        </div>
        <h3 class="font-serif text-xl text-ink mb-3"><?= $title ?></h3>
        <p class="text-gray-500 text-sm leading-relaxed"><?= $desc ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- Experience -->
<section class="py-24 px-5 sm:px-8 bg-white">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-16 sr">
      <span class="inline-flex items-center gap-2 bg-primary/10 text-primary text-xs font-semibold px-3 py-1.5 rounded-full mb-4">
        <i class="fa-solid fa-circle-dot text-[8px]"></i> Global Leadership
      </span>
      <h2 class="font-serif text-4xl md:text-5xl text-ink">Experience & Reach</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php foreach ([
        ['fa-globe',     'International Policy',  'Engaged with UN bodies, regional human rights commissions, and multilateral development institutions to shape global policy frameworks.'],
        ['fa-users',     'Community Programs',    'Designed and led over 200 community-based programs across Africa, Asia, and Latin America, reaching 50,000+ individuals.'],
        ['fa-book-open', 'Research & Publishing', 'Produced peer-reviewed research, policy briefs, and advocacy reports that have influenced legislation in 15+ countries.'],
      ] as $i=>[$icon,$title,$desc]): ?>
      <div class="border border-gray-100 rounded-2xl p-8 lift sr" style="transition-delay:<?= $i*100 ?>ms">
        <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-6">
          <i class="fa-solid <?= $icon ?> text-primary text-lg"></i>
        </div>
        <h3 class="font-serif text-xl text-ink mb-3"><?= $title ?></h3>
        <p class="text-gray-500 text-sm leading-relaxed"><?= $desc ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- Philosophy — dark -->
<section class="py-24 px-5 sm:px-8 bg-ink text-white relative overflow-hidden">
  <div class="absolute top-0 right-0 w-80 h-80 bg-primary/8 rounded-full blur-[100px] pointer-events-none"></div>
  <div class="relative max-w-5xl mx-auto text-center sr">
    <span class="inline-flex items-center gap-2 bg-primary/20 text-primary text-xs font-semibold px-3 py-1.5 rounded-full mb-6">
      <i class="fa-solid fa-circle-dot text-[8px]"></i> Our Philosophy
    </span>
    <h2 class="font-serif text-4xl md:text-5xl mb-7 leading-tight">
      Development must be led by<br><span class="text-primary">the communities it serves.</span>
    </h2>
    <p class="text-white/55 text-lg leading-relaxed max-w-3xl mx-auto mb-14">
      We reject top-down models of development. True progress happens when power is redistributed, when local knowledge is valued, and when communities are architects of change.
    </p>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 text-left">
      <?php foreach ([
        ['fa-seedling',         'Community-Led',  'Solutions designed with and by the people most affected.'],
        ['fa-magnifying-glass', 'Evidence-Based', 'Grounded in rigorous research and lived experience.'],
        ['fa-link',             'Intersectional', 'Recognising that injustice is layered and interconnected.'],
      ] as [$icon,$title,$desc]): ?>
      <div class="bg-white/5 border border-white/10 rounded-2xl p-6 hover:bg-white/8 transition-colors">
        <i class="fa-solid <?= $icon ?> text-primary text-xl mb-4 block"></i>
        <h4 class="font-serif text-lg mb-2"><?= $title ?></h4>
        <p class="text-white/50 text-sm leading-relaxed"><?= $desc ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- CTA -->
<section class="py-20 px-5 sm:px-8 bg-white">
  <div class="max-w-3xl mx-auto text-center sr">
    <h2 class="font-serif text-4xl text-ink mb-5">Ready to collaborate?</h2>
    <p class="text-gray-500 mb-8">Whether you're a researcher, activist, funder, or community leader — there's a place for you in this movement.</p>
    <a href="<?= SITE_URL ?>/contact"
      class="inline-flex items-center gap-2 bg-primary text-white font-semibold px-9 py-4 rounded-full hover:bg-primary/85 transition-colors text-sm shadow-lg shadow-primary/20">
      Get In Touch <i class="fa-solid fa-arrow-right text-xs"></i>
    </a>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
