<?php
require_once __DIR__ . '/includes/init.php';
$pageTitle = 'Home — ' . SITE_NAME;
$blogs = $db->blogs->find(['published'=>true],['sort'=>['created_at'=>-1],'limit'=>3])->toArray();
include __DIR__ . '/includes/header.php';
?>

<!-- ══════════════════════════════════════════
     HERO
══════════════════════════════════════════ -->
<section class="relative min-h-[82vh] flex items-center overflow-hidden bg-[#EEEEF8]">
  <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(48,68,200,.14),transparent_28%),radial-gradient(circle_at_80%_18%,rgba(209,73,91,.11),transparent_22%),radial-gradient(circle_at_65%_75%,rgba(48,68,200,.08),transparent_24%)]"></div>
  <div class="absolute inset-0 pointer-events-none opacity-[0.32]" style="background-image:radial-gradient(rgba(48,68,200,.12) 1px, transparent 1px);background-size:26px 26px;"></div>
  <div class="absolute top-0 right-0 w-[520px] h-[520px] bg-primary/12 rounded-full blur-[130px] pointer-events-none"></div>
  <div class="absolute -bottom-20 left-[10%] w-[320px] h-[320px] bg-accent/12 rounded-full blur-[110px] pointer-events-none"></div>

  <div class="relative max-w-7xl mx-auto px-5 sm:px-8 pt-6 pb-12 sm:py-16 w-full">
    <div class="grid xl:grid-cols-12 gap-8 xl:gap-10 items-center">

      <div class="xl:col-span-7 space-y-7 sm:space-y-8">
        <div class="inline-flex items-center gap-2 bg-white/95 border border-primary/20 text-primary text-[11px] font-semibold px-3.5 py-1.5 rounded-full shadow-sm">
          <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
          Human Rights. Human Dignity. Shared Future.
        </div>

        <h1 class="font-serif text-ink leading-[1.04] text-[2.2rem] sm:text-[2.9rem] lg:text-[3.5rem] xl:text-[4rem]">
          Turning Rights Into<br>
          <span class="text-primary">Real-World Protection</span><br>
          for Every Community.
        </h1>

        <p class="text-gray-600 text-base sm:text-[1.04rem] leading-relaxed max-w-xl">
          We collaborate with communities, researchers, and institutions to confront inequality, defend freedoms, and build systems where justice is accessible to all.
        </p>

        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
          <a href="<?= SITE_URL ?>/blog"
            class="group inline-flex items-center justify-center gap-2 bg-primary text-white font-semibold px-7 py-3.5 rounded-full hover:bg-primary/85 transition-all text-sm shadow-lg shadow-primary/25">
            Explore Research & Insights
            <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
          </a>
          <a href="<?= SITE_URL ?>/contact"
            class="inline-flex items-center justify-center gap-2 bg-white border border-gray-200 text-ink font-semibold px-7 py-3.5 rounded-full hover:border-primary hover:text-primary transition-all text-sm shadow-sm">
            <i class="fa-solid fa-handshake text-xs"></i>
            Partner With Us
          </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 pt-3">
          <?php foreach ([['15+','Years'],['40+','Countries'],['200+','Programs'],['50K+','Lives']] as $i=>[$n,$l]): ?>
          <div class="stat-num bg-white/90 border border-gray-100 rounded-2xl px-4 py-3 shadow-sm" style="animation-delay:<?= $i*.12 ?>s">
            <p class="font-serif text-2xl sm:text-[1.75rem] font-semibold text-ink leading-none"><?= $n ?></p>
            <p class="text-gray-500 text-[11px] uppercase tracking-[0.14em] mt-1"><?= $l ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="xl:col-span-5">
        <div class="relative">
          <div class="bg-white/92 border border-white/70 rounded-[1.8rem] shadow-[0_30px_70px_rgba(15,23,42,.12)] p-4 sm:p-5">
            <div class="rounded-[1.35rem] overflow-hidden aspect-[4/3] mb-4 border border-gray-100">
              <img
                src="https://images.unsplash.com/photo-1541872703-74c5e44368f9?w=1300&q=80&auto=format&fit=crop"
                alt="Human rights advocates marching for justice"
                class="w-full h-full object-cover object-center"
                loading="eager">
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div class="rounded-xl border border-gray-100 bg-mist px-3.5 py-3">
                <p class="text-[10px] uppercase tracking-[0.16em] text-gray-400 mb-1">Current Priority</p>
                <p class="text-sm font-semibold text-ink">Civic Freedoms & Safety</p>
              </div>
              <div class="rounded-xl border border-gray-100 bg-white px-3.5 py-3">
                <p class="text-[10px] uppercase tracking-[0.16em] text-gray-400 mb-1">Active Region</p>
                <p class="text-sm font-semibold text-ink">Global South Networks</p>
              </div>
            </div>
          </div>

          <div class="hidden sm:flex absolute -left-10 top-8 bg-white border border-gray-100 rounded-2xl shadow-xl px-4 py-3 items-center gap-2.5">
            <span class="w-2 h-2 rounded-full bg-accent animate-pulse"></span>
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-[0.14em]">Community-Led Action</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-white to-transparent"></div>
</section>


<!-- ══════════════════════════════════════════
     TICKER / MARQUEE
══════════════════════════════════════════ -->
<div class="bg-primary text-white py-3 overflow-hidden">
  <div class="flex gap-12 animate-[marquee_25s_linear_infinite] whitespace-nowrap" style="animation:marquee 25s linear infinite">
    <?php foreach (array_fill(0,4,['Gender Justice','·','Governance & Accountability','·','Economic Justice','·','Decolonial Development','·','Global South Voices','·','Human Dignity','·']) as $set): foreach($set as $item): ?>
    <span class="text-xs font-semibold tracking-widest uppercase"><?= $item ?></span>
    <?php endforeach; endforeach; ?>
  </div>
</div>
<style>@keyframes marquee{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}</style>


<!-- ══════════════════════════════════════════
     MISSION
══════════════════════════════════════════ -->
<section class="py-24 px-5 sm:px-8 bg-white">
  <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

    <!-- Left: image composition -->
    <div class="relative sr-l">
      <div class="absolute -top-8 -left-8 w-40 h-40 bg-primary/10 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-10 right-0 w-48 h-48 bg-accent/10 rounded-full blur-3xl pointer-events-none"></div>

      <div class="relative grid grid-cols-1 sm:grid-cols-6 gap-3 sm:gap-4">
        <div class="sm:col-span-4 rounded-3xl overflow-hidden shadow-[0_20px_60px_rgba(15,23,42,.14)] aspect-[4/5] sm:aspect-[4/4.7] border border-white/70">
          <img src="https://images.unsplash.com/photo-1531206715517-5c0ba140b2b8?w=900&q=80&auto=format&fit=crop"
            alt="Human rights march" class="w-full h-full object-cover">
        </div>

        <div class="sm:col-span-2 grid grid-cols-2 sm:grid-cols-1 gap-3 sm:gap-4">
          <div class="rounded-2xl overflow-hidden shadow-lg aspect-[4/4.2] sm:aspect-[4/4.8] border border-white/70">
            <img src="https://images.unsplash.com/photo-1607748862156-7c548e7e98f4?w=700&q=80&auto=format&fit=crop"
              alt="Human rights advocacy gathering" class="w-full h-full object-cover">
          </div>
          <div class="rounded-2xl overflow-hidden shadow-lg aspect-[4/4.2] sm:aspect-[4/4.8] border border-white/70">
            <img src="https://images.unsplash.com/photo-1509099836639-18ba1795216d?w=700&q=80&auto=format&fit=crop"
              alt="Community solidarity for human rights" class="w-full h-full object-cover">
          </div>
        </div>
      </div>

      <div class="absolute -bottom-5 left-4 sm:left-6 bg-white border border-gray-100 rounded-2xl px-4 sm:px-5 py-3 shadow-xl">
        <p class="font-serif text-2xl sm:text-3xl font-semibold text-primary leading-none">15+</p>
        <p class="text-gray-500 text-[11px] uppercase tracking-[0.14em] mt-1">Years of Impact</p>
      </div>

      <div class="absolute top-4 right-3 sm:right-4 bg-ink text-white rounded-xl px-3.5 py-2.5 shadow-xl hidden sm:block">
        <p class="text-[10px] uppercase tracking-[0.14em] text-white/60">Field Reach</p>
        <p class="font-semibold text-sm mt-0.5">40+ Countries</p>
      </div>
    </div>

    <!-- Right: text -->
    <div class="sr-r space-y-6">
      <div>
        <span class="inline-flex items-center gap-2 bg-primary/10 text-primary text-xs font-semibold px-3 py-1.5 rounded-full mb-4">
          <i class="fa-solid fa-circle-dot text-[8px]"></i> Our Mission
        </span>
        <h2 class="font-serif text-4xl md:text-5xl text-ink leading-tight">
          Justice is not a destination — it is a practice.
        </h2>
      </div>
      <p class="text-gray-500 leading-relaxed text-base">
        Our work sits at the intersection of policy, community, and power. We partner with grassroots movements, governments, and international bodies to dismantle systemic inequalities and build institutions that serve everyone — especially those historically left behind.
      </p>
      <div class="space-y-3">
        <?php foreach ([
          ['fa-check-circle','Community-led solutions co-created with affected populations'],
          ['fa-check-circle','Evidence-based research informing global policy'],
          ['fa-check-circle','Intersectional approach to justice and equity'],
        ] as [$icon,$text]): ?>
        <div class="flex items-center gap-3 text-sm text-gray-600">
          <i class="fa-solid <?= $icon ?> text-primary flex-shrink-0"></i>
          <?= $text ?>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="flex flex-wrap gap-3 pt-2">
        <a href="<?= SITE_URL ?>/about" class="inline-flex items-center gap-2 bg-ink text-white font-semibold px-7 py-3.5 rounded-full hover:bg-primary transition-colors text-sm">
          Our Story <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
        <a href="<?= SITE_URL ?>/blog" class="inline-flex items-center gap-2 border border-gray-200 text-ink font-semibold px-7 py-3.5 rounded-full hover:border-ink transition-colors text-sm">
          Read Insights
        </a>
      </div>
    </div>
  </div>
</section>


<!-- ══════════════════════════════════════════
     FOCUS AREAS
══════════════════════════════════════════ -->
<section class="py-24 px-5 sm:px-8 bg-mist">
  <div class="max-w-7xl mx-auto">
    <div class="text-center max-w-3xl mx-auto mb-12 sr">
      <span class="inline-flex items-center gap-2 bg-primary/10 text-primary text-xs font-semibold px-3 py-1.5 rounded-full mb-4">
        <i class="fa-solid fa-circle-dot text-[8px]"></i> What We Do
      </span>
      <h2 class="font-serif text-4xl md:text-5xl text-ink">Our Focus Areas</h2>
      <p class="text-gray-500 text-sm sm:text-base mt-4">We use evidence, advocacy, and community partnership to protect rights and drive systemic change.</p>
    </div>

    <div class="bg-white border border-gray-100 rounded-3xl p-5 sm:p-7 lg:p-8 shadow-[0_20px_60px_rgba(13,27,42,.08)] sr">
      <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5">
        <?php foreach ([
          ['01','fa-venus',        '#2D3A8C','bg-primary/10', 'Gender Justice',              'Ending gender-based violence and advancing women\'s leadership.', SITE_URL.'/blog?search=gender'],
          ['02','fa-landmark',     '#C0392B','bg-accent/10',  'Governance & Accountability', 'Strengthening democratic institutions and transparent governance.', SITE_URL.'/blog?search=governance'],
          ['03','fa-coins',        '#2D3A8C','bg-primary/10', 'Economic Justice',            'Advocating for fair wages, land rights, and resource equity.', SITE_URL.'/blog?search=economic%20justice'],
          ['04','fa-globe-africa', '#C0392B','bg-accent/10',  'Decolonial Development',      'Centering Global South leadership in development priorities.', SITE_URL.'/blog?search=decolonial'],
        ] as $i=>[$num,$icon,$color,$bg,$title,$desc,$url]): ?>
        <article class="group rounded-2xl border border-gray-100 bg-mist/55 p-5 hover:bg-white hover:shadow-lg transition-all">
          <div class="flex items-center justify-between mb-4">
            <span class="text-[11px] font-semibold tracking-[0.14em] text-gray-400"><?= $num ?></span>
            <div class="w-10 h-10 rounded-xl <?= $bg ?> flex items-center justify-center group-hover:scale-105 transition-transform">
              <i class="fa-solid <?= $icon ?> text-sm" style="color:<?= $color ?>"></i>
            </div>
          </div>
          <h3 class="font-serif text-[1.35rem] text-ink leading-snug mb-2"><?= $title ?></h3>
          <p class="text-gray-500 text-sm leading-relaxed mb-4"><?= $desc ?></p>
          <a href="<?= $url ?>" class="inline-flex items-center gap-1.5 text-xs font-semibold" style="color:<?= $color ?>">
            Explore work <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
          </a>
        </article>
        <?php endforeach; ?>
      </div>

      <div class="mt-6 sm:mt-8 pt-5 border-t border-gray-100 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
        <p class="text-gray-500 text-sm">Want the full methodology behind these pillars?</p>
        <div class="flex flex-col sm:flex-row gap-3">
          <a href="<?= SITE_URL ?>/about" class="inline-flex items-center justify-center gap-2 bg-ink text-white font-semibold px-6 py-3 rounded-full hover:bg-primary transition-colors text-sm">
            Our Approach <i class="fa-solid fa-arrow-right text-xs"></i>
          </a>
          <a href="<?= SITE_URL ?>/blog" class="inline-flex items-center justify-center gap-2 bg-white border border-gray-200 text-ink font-semibold px-6 py-3 rounded-full hover:border-ink transition-colors text-sm">
            Read Insights
          </a>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ══════════════════════════════════════════
     IMPACT — dark accent section
══════════════════════════════════════════ -->
<section class="py-24 px-5 sm:px-8 bg-ink text-white relative overflow-hidden">
  <!-- Decorative -->
  <div class="absolute top-0 right-0 w-96 h-96 bg-primary/8 rounded-full blur-[100px] pointer-events-none"></div>
  <div class="absolute bottom-0 left-0 w-64 h-64 bg-accent/8 rounded-full blur-[80px] pointer-events-none"></div>

  <div class="relative max-w-7xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

      <div class="sr-l space-y-6">
        <span class="inline-flex items-center gap-2 bg-primary/20 text-primary text-xs font-semibold px-3 py-1.5 rounded-full">
          <i class="fa-solid fa-circle-dot text-[8px]"></i> Our Impact
        </span>
        <h2 class="font-serif text-4xl md:text-5xl leading-tight">
          Decades of work.<br><span class="text-primary">Measurable change.</span>
        </h2>
        <p class="text-white/55 leading-relaxed max-w-md">
          From grassroots campaigns to international policy reform, our impact is felt across communities, institutions, and borders.
        </p>
        <a href="<?= SITE_URL ?>/contact"
          class="inline-flex items-center gap-2 bg-primary text-white font-semibold px-7 py-3.5 rounded-full hover:bg-primary/85 transition-colors text-sm shadow-lg shadow-primary/20">
          Partner With Us <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
      </div>

      <div class="grid grid-cols-2 gap-4 sr-r">
        <?php foreach ([
          ['15+',  'Years of Advocacy',  'fa-clock',        'border-primary/30'],
          ['40+',  'Countries Impacted', 'fa-globe',        'border-accent/30'],
          ['200+', 'Programs Led',       'fa-chart-line',   'border-primary/30'],
          ['50K+', 'Lives Reached',      'fa-people-group', 'border-accent/30'],
        ] as [$n,$l,$icon,$border]): ?>
        <div class="bg-white/5 border <?= $border ?> rounded-2xl p-6 hover:bg-white/8 transition-colors">
          <i class="fa-solid <?= $icon ?> text-primary text-xl mb-4 block"></i>
          <p class="font-serif text-4xl font-semibold text-white"><?= $n ?></p>
          <p class="text-white/45 text-sm mt-1"><?= $l ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>


<!-- ══════════════════════════════════════════
     FEATURED INSIGHTS
══════════════════════════════════════════ -->
<?php if ($blogs): ?>
<section class="py-24 px-5 sm:px-8 bg-white">
  <div class="max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-14 sr">
      <div>
        <span class="inline-flex items-center gap-2 bg-primary/10 text-primary text-xs font-semibold px-3 py-1.5 rounded-full mb-4">
          <i class="fa-solid fa-circle-dot text-[8px]"></i> Latest Insights
        </span>
        <h2 class="font-serif text-4xl md:text-5xl text-ink">Research & Perspectives</h2>
      </div>
      <a href="<?= SITE_URL ?>/blog"
        class="inline-flex items-center gap-2 border border-gray-200 text-ink font-semibold px-5 py-2.5 rounded-full hover:border-ink transition-colors text-sm self-end whitespace-nowrap">
        View All <i class="fa-solid fa-arrow-right text-xs"></i>
      </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php foreach ($blogs as $i=>$blog): ?>
      <article class="group flex flex-col lift bg-white border border-gray-100 rounded-2xl overflow-hidden sr" style="transition-delay:<?= $i*100 ?>ms">
        <a href="<?= SITE_URL ?>/single-blog?slug=<?= sanitize($blog['slug']) ?>" class="block aspect-[16/10] bg-mist overflow-hidden flex-shrink-0">
          <?php if (!empty($blog['image'])): ?>
            <img src="<?= SITE_URL ?>/assets/images/<?= sanitize($blog['image']) ?>"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
              alt="<?= sanitize($blog['title']) ?>" loading="lazy">
          <?php else: ?>
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary/5 to-accent/5">
              <i class="fa-solid fa-newspaper text-4xl text-gray-200"></i>
            </div>
          <?php endif; ?>
        </a>
        <div class="p-6 flex flex-col flex-1">
          <div class="flex items-center gap-2 mb-3">
            <span class="bg-primary/10 text-primary text-xs font-semibold px-3 py-1 rounded-full"><?= sanitize($blog['category'] ?? 'General') ?></span>
            <span class="text-gray-400 text-xs"><?= date('M d, Y', $blog['created_at']->toDateTime()->getTimestamp()) ?></span>
          </div>
          <h3 class="font-serif text-xl text-ink mb-3 leading-snug group-hover:text-primary transition-colors flex-1">
            <a href="<?= SITE_URL ?>/single-blog?slug=<?= sanitize($blog['slug']) ?>"><?= sanitize($blog['title']) ?></a>
          </h3>
          <p class="text-gray-500 text-sm leading-relaxed mb-5"><?= sanitize(substr($blog['excerpt']??'',0,100)) ?>...</p>
          <a href="<?= SITE_URL ?>/single-blog?slug=<?= sanitize($blog['slug']) ?>"
            class="inline-flex items-center gap-1.5 text-primary font-semibold text-sm hover:gap-2.5 transition-all mt-auto">
            Read Article <i class="fa-solid fa-arrow-right text-xs"></i>
          </a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- ══════════════════════════════════════════
     PULL QUOTE
══════════════════════════════════════════ -->
<section class="relative py-28 px-5 sm:px-8 overflow-hidden">
  <!-- Background image -->
  <div class="absolute inset-0">
    <img src="https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?w=1800&q=80&auto=format&fit=crop"
      alt="Human rights demonstration background" class="w-full h-full object-cover object-center">
    <!-- Dark overlay so text is readable -->
    <div class="absolute inset-0 bg-ink/75"></div>
    <!-- Subtle vignette -->
    <div class="absolute inset-0 bg-gradient-to-b from-ink/30 via-transparent to-ink/30"></div>
  </div>

  <div class="relative max-w-4xl mx-auto text-center sr">
    <div class="w-14 h-14 rounded-full bg-white/15 backdrop-blur-sm border border-white/20 flex items-center justify-center mx-auto mb-8">
      <i class="fa-solid fa-quote-left text-white text-xl"></i>
    </div>
    <blockquote class="font-serif text-3xl md:text-4xl text-white leading-tight italic">
      "Justice is not charity. It is the recognition of every person's inherent worth and the structural commitment to honour it."
    </blockquote>
    <div class="flex items-center justify-center gap-3 mt-8">
      <div class="h-px w-12 bg-white/30"></div>
      <p class="text-white/70 font-semibold text-xs tracking-widest uppercase"><?= SITE_NAME ?> Manifesto</p>
      <div class="h-px w-12 bg-white/30"></div>
    </div>
  </div>
</section>


<!-- ══════════════════════════════════════════
     NEWSLETTER
══════════════════════════════════════════ -->
<section class="py-24 px-5 sm:px-8 bg-white">
  <div class="max-w-2xl mx-auto text-center sr">
    <span class="inline-flex items-center gap-2 bg-primary/10 text-primary text-xs font-semibold px-3 py-1.5 rounded-full mb-5">
      <i class="fa-solid fa-circle-dot text-[8px]"></i> Stay Informed
    </span>
    <h2 class="font-serif text-4xl md:text-5xl text-ink mb-5">Join the Movement</h2>
    <p class="text-gray-500 leading-relaxed mb-10">
      Get our latest research, policy insights, and advocacy updates. No spam — only substance.
    </p>
    <form class="flex flex-col sm:flex-row gap-3" onsubmit="return false;">
      <input type="email" placeholder="Your email address"
        class="flex-1 border border-gray-200 rounded-full px-6 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-primary/25 bg-mist">
      <button class="bg-primary text-white font-semibold px-8 py-4 rounded-full hover:bg-primary/85 transition-colors text-sm whitespace-nowrap shadow-md shadow-primary/20">
        Subscribe
      </button>
    </form>
    <p class="text-gray-400 text-xs mt-4">We respect your privacy. Unsubscribe at any time.</p>
  </div>
</section>


<!-- ══════════════════════════════════════════
     CTA BANNER
══════════════════════════════════════════ -->
<section class="py-20 px-5 sm:px-8 bg-ink relative overflow-hidden">
  <div class="absolute inset-0 opacity-[0.03]"
    style="background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:28px 28px"></div>
  <div class="relative max-w-5xl mx-auto flex flex-col md:flex-row items-center justify-between gap-8 sr">
    <div>
      <h2 class="font-serif text-3xl md:text-4xl text-white mb-2">
        Ready to build equitable futures together?
      </h2>
      <p class="text-white/50">Partner with us on research, advocacy, or community programs.</p>
    </div>
    <a href="<?= SITE_URL ?>/contact"
      class="flex-shrink-0 bg-primary text-white font-semibold px-9 py-4 rounded-full hover:bg-primary/85 transition-colors text-sm shadow-lg shadow-primary/30">
      Get In Touch
    </a>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
