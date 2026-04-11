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

  <!-- Light background image, very subtle -->
  <div class="absolute inset-0">
    <img
      src="https://images.unsplash.com/photo-1591189863430-ab87e120f312?w=1800&q=80&auto=format&fit=crop"
      alt="Human rights advocates marching together"
      class="w-full h-full object-cover object-center opacity-[0.08]"
      loading="eager">
    <!-- Light gradient overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-[#EEEEF8]/95 via-[#EEEEF8]/80 to-[#EEEEF8]/50"></div>
  </div>

  <!-- Decorative primary line -->
  <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-transparent via-primary to-transparent opacity-40"></div>
  <!-- Soft primary blob top-right -->
  <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/10 rounded-full blur-[120px] pointer-events-none"></div>
  <div class="absolute bottom-0 left-1/3 w-[300px] h-[300px] bg-accent/8 rounded-full blur-[100px] pointer-events-none"></div>

  <div class="relative max-w-7xl mx-auto px-5 sm:px-8 py-14 w-full">
    <div class="grid lg:grid-cols-12 gap-8 items-center">

      <!-- Left content -->
      <div class="lg:col-span-7 space-y-8">

        <!-- Eyebrow -->
        <div class="inline-flex items-center gap-2 bg-white border border-primary/20 text-primary text-[11px] font-semibold px-3.5 py-1.5 rounded-full shadow-sm">
          <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
          Global Human Rights Platform
        </div>

        <!-- Headline -->
        <h1 class="font-serif text-ink leading-[1.1] text-[2.4rem] sm:text-[3rem] lg:text-[3.6rem] xl:text-[4rem]">
          Shifting Power,<br>
          <span class="text-primary italic">Advancing Justice</span> &amp;<br>
          Building Equitable Futures.
        </h1>

        <!-- Sub -->
        <p class="text-gray-500 text-base leading-relaxed max-w-md">
          Centering the voices of those most impacted by injustice — through research, advocacy, and community-led action.
        </p>

        <!-- CTAs -->
        <div class="flex flex-wrap gap-3">
          <a href="<?= SITE_URL ?>/blog"
            class="group inline-flex items-center gap-2 bg-primary text-white font-semibold px-7 py-3.5 rounded-full hover:bg-primary/85 transition-all text-sm shadow-lg shadow-primary/25">
            Explore Research
            <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
          </a>
          <a href="<?= SITE_URL ?>/contact"
            class="inline-flex items-center gap-2 bg-white border border-gray-200 text-ink font-semibold px-7 py-3.5 rounded-full hover:border-primary hover:text-primary transition-all text-sm shadow-sm">
            <i class="fa-solid fa-handshake text-xs"></i>
            Join the Movement
          </a>
        </div>

        <!-- Stats -->
        <div class="flex flex-wrap gap-8 pt-6 border-t border-gray-200">
          <?php foreach ([['15+','Years of Advocacy'],['40+','Countries Impacted'],['200+','Programs Led'],['50K+','Lives Reached']] as $i=>[$n,$l]): ?>
          <div class="stat-num" style="animation-delay:<?= $i*.12 ?>s">
            <p class="font-serif text-3xl font-semibold text-ink"><?= $n ?></p>
            <p class="text-gray-400 text-xs mt-0.5 tracking-wide"><?= $l ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Right: floating cards -->
      <div class="lg:col-span-5 hidden lg:flex flex-col gap-4 items-end">

        <!-- Quote card -->
        <div class="float bg-white rounded-2xl shadow-2xl p-6 max-w-[320px] w-full">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
              <i class="fa-solid fa-quote-left text-primary text-sm"></i>
            </div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Featured Quote</p>
          </div>
          <p class="font-serif text-ink text-[1.05rem] leading-snug italic">
            "Human rights are not a privilege conferred by government. They are every human being's entitlement."
          </p>
          <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-100">
            <div class="w-6 h-6 rounded-full bg-accent/20 flex items-center justify-center">
              <i class="fa-solid fa-user text-accent text-[9px]"></i>
            </div>
            <p class="text-accent text-xs font-semibold">Mother Teresa</p>
          </div>
        </div>

        <!-- Active campaigns badge -->
        <div class="float-d bg-primary text-white rounded-2xl shadow-xl px-5 py-4 flex items-center gap-3 self-start">
          <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-bullhorn text-sm"></i>
          </div>
          <div>
            <p class="font-semibold text-sm leading-none">Active Campaigns</p>
            <p class="text-white/70 text-xs mt-0.5">Ongoing globally</p>
          </div>
          <span class="ml-2 w-2 h-2 rounded-full bg-white animate-pulse"></span>
        </div>

        <!-- Focus tags -->
        <div class="flex flex-wrap gap-2 justify-end max-w-[320px]">
          <?php foreach (['Gender Justice','Governance','Economic Rights','Global South','Decolonial Dev'] as $tag): ?>
          <span class="bg-white border border-gray-200 text-gray-600 text-xs px-3 py-1.5 rounded-full shadow-sm"><?= $tag ?></span>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Bottom fade -->
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

    <!-- Left: image collage -->
    <div class="relative sr-l">
      <div class="grid grid-cols-2 gap-3">
        <div class="rounded-2xl overflow-hidden shadow-md aspect-[4/3]">
          <img src="https://images.unsplash.com/photo-1591189863430-ab87e120f312?w=600&q=80&auto=format&fit=crop"
            alt="Human rights march" class="w-full h-full object-cover">
        </div>
        <div class="rounded-2xl overflow-hidden shadow-md aspect-[4/3] mt-8">
          <img src="https://images.unsplash.com/photo-1607748862156-7c548e7e98f4?w=600&q=80&auto=format&fit=crop"
            alt="Women empowerment" class="w-full h-full object-cover">
        </div>
        <div class="rounded-2xl overflow-hidden shadow-md aspect-[4/3]">
          <img src="https://images.unsplash.com/photo-1509099836639-18ba1795216d?w=600&q=80&auto=format&fit=crop"
            alt="Community solidarity" class="w-full h-full object-cover">
        </div>
        <div class="rounded-2xl overflow-hidden shadow-md aspect-[4/3] -mt-8">
          <img src="https://images.unsplash.com/photo-1531206715517-5c0ba140b2b8?w=600&q=80&auto=format&fit=crop"
            alt="Advocacy and justice" class="w-full h-full object-cover">
        </div>
      </div>
      <!-- Overlay badge -->
      <div class="absolute -bottom-4 -right-4 bg-ink text-white rounded-2xl px-5 py-4 shadow-xl">
        <p class="font-serif text-3xl font-semibold text-primary">15+</p>
        <p class="text-white/60 text-xs mt-0.5">Years of Impact</p>
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
    <div class="text-center mb-16 sr">
      <span class="inline-flex items-center gap-2 bg-primary/10 text-primary text-xs font-semibold px-3 py-1.5 rounded-full mb-4">
        <i class="fa-solid fa-circle-dot text-[8px]"></i> What We Do
      </span>
      <h2 class="font-serif text-4xl md:text-5xl text-ink">Our Focus Areas</h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
      <?php foreach ([
        ['fa-venus',        '#2D3A8C','bg-primary/10',   'Gender Justice',              'Advancing feminist leadership, ending gender-based violence, and ensuring women\'s full participation in public life.'],
        ['fa-landmark',     '#C0392B','bg-accent/10',  'Governance & Accountability', 'Strengthening democratic institutions, fighting corruption, and ensuring transparent, inclusive governance.'],
        ['fa-coins',        '#2D3A8C','bg-primary/10',   'Economic Justice',            'Challenging extractive systems, advocating for fair wages, land rights, and equitable resource distribution.'],
        ['fa-globe-africa', '#C0392B','bg-accent/10',  'Decolonial Development',      'Centering Global South voices, dismantling colonial frameworks, and redefining what development truly means.'],
      ] as $i=>[$icon,$color,$bg,$title,$desc]): ?>
      <div class="group bg-white rounded-2xl p-7 lift sr border border-gray-100 cursor-default" style="transition-delay:<?= $i*80 ?>ms">
        <div class="w-12 h-12 rounded-xl <?= $bg ?> flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
          <i class="fa-solid <?= $icon ?> text-lg" style="color:<?= $color ?>"></i>
        </div>
        <h3 class="font-serif text-xl text-ink mb-3"><?= $title ?></h3>
        <p class="text-gray-500 text-sm leading-relaxed"><?= $desc ?></p>
        <div class="mt-5 flex items-center gap-1.5 text-xs font-semibold opacity-0 group-hover:opacity-100 transition-opacity" style="color:<?= $color ?>">
          Learn more <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </div>
      </div>
      <?php endforeach; ?>
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
    <img src="https://images.unsplash.com/photo-1464746133101-a2c3f88e0dd9?w=1800&q=80&auto=format&fit=crop"
      alt="Justice background" class="w-full h-full object-cover object-center">
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
