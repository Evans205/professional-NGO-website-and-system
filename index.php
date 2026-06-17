<?php
require_once 'db.php';
// Fetch latest 3 published posts (news or stories)
$posts = getPosts($conn, 3); // assuming getPosts accepts a limit
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Grow Africa Futures | Cultivating Prosperity, Growing Futures</title>
  <!-- Tailwind CSS + Font Awesome + Google Fonts -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
  <style>
    * { font-family: 'Inter', sans-serif; }
    html { scroll-behavior: smooth; }
    body { background-color: #fefcf5; overflow-x: hidden; }
    /* animations */
    @keyframes fadeInUp { from { opacity:0; transform:translateY(40px); } to { opacity:1; transform:translateY(0); } }
    @keyframes scaleBtn { 0% { transform:scale(0.9); opacity:0; } 80% { transform:scale(1.05); } 100% { transform:scale(1); opacity:1; } }
    .animate-fade-up { animation: fadeInUp 0.8s ease forwards; }
    .animate-btn { animation: scaleBtn 0.5s ease forwards; }
    .delay-100 { animation-delay:0.1s; }
    .delay-200 { animation-delay:0.2s; }
    .delay-300 { animation-delay:0.3s; }
    .delay-400 { animation-delay:0.4s; }
    .hover-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .hover-lift:hover { transform: translateY(-8px); box-shadow: 0 20px 30px -12px rgba(0,0,0,0.2); }
    .pillar-icon { background: #f5e7d9; transition: 0.2s; }
    .group:hover .pillar-icon { background: #e67e22; color: white; }
    .hero-slide { transition: opacity 1.2s ease-in-out; }
    .value-card { transition: all 0.4s cubic-bezier(0.2,0.9,0.4,1.1); }
    .value-card:hover { transform: translateY(-12px) scale(1.02); box-shadow: 0 25px 35px -12px rgba(0,0,0,0.25); }
    .reveal { opacity:0; transform:translateY(30px); transition: opacity 0.9s ease, transform 0.9s ease; }
    .reveal.visible { opacity:1; transform:translateY(0); }
    .bg-soft-leaf { background: linear-gradient(120deg, #f8f4ea 0%, #f0ede3 100%); }
    .hero-bg-overlay { background: linear-gradient(120deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 100%); }
    .btn-donate { background: #e67e22; transition: all 0.2s; }
    .btn-donate:hover { background: #d35400; transform: scale(1.05); box-shadow: 0 4px 12px rgba(230,126,34,0.4); }
    /* News card styles (matching whasnew.php) */
    .news-card { transition: transform 0.25s ease, box-shadow 0.25s ease; }
    .news-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px -12px rgba(0,0,0,0.1); }
    .category-badge { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; padding: 0.25rem 0.75rem; border-radius: 40px; }
    .category-badge.news { background: #dbeafe; color: #1e40af; }
    .category-badge.story { background: #d1fae5; color: #065f46; }
    .category-badge.event { background: #fef3c7; color: #92400e; }
    .read-more-btn { background: #d97706; color: white; padding: 0.5rem 1.25rem; border-radius: 0.5rem; font-weight: 600; transition: background 0.2s; display: inline-block; text-align: center; border: none; cursor: pointer; }
    .read-more-btn:hover { background: #b45309; }
    .read-more-btn i { margin-left: 0.5rem; }
    /* Carousel track */
    .carousel-track {
      display: flex;
      height: 100%;
      transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
      will-change: transform;
    }
    .carousel-track img,
    .carousel-track video {
      width: 100%;
      height: 100%;
      object-fit: cover;
      flex-shrink: 0;
      background: #1a1a1a;
    }
    .no-img {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100%;
      background: #e5e7eb;
      color: #6b7280;
    }
    .share-btn { transition: color 0.2s; }
    .share-btn.whatsapp:hover { color: #25D366; }
    .share-btn.facebook:hover { color: #1877F2; }
  </style>
</head>
<body class="antialiased">

<!-- ==================== NAVBAR ==================== -->
<nav class="bg-white/90 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-gray-100">
  <div class="container mx-auto px-5 lg:px-8 py-3 flex flex-wrap items-center justify-between">
    <div class="flex items-center gap-3">
      <img src="./images/logo.png" alt="Grow Africa Futures Logo" class="h-12 w-auto object-contain" onerror="this.src='https://placehold.co/400x200?text=Grow+Africa+Futures'">
      <span class="font-bold text-2xl tracking-tight text-gray-800">Grow Africa<span class="text-amber-700"> Futures</span></span>
    </div>
    <div class="hidden md:flex items-center space-x-8 text-gray-700 font-medium">
      <a href="index.php" class="hover:text-amber-700 transition">Home</a>
      <a href="about.php" class="hover:text-amber-700 transition">About</a>
      <a href="#focus" class="hover:text-amber-700 transition">Our Focus</a>
      <a href="team.php" class="hover:text-amber-700 transition">Team</a>
      <a href="whatsnew.php" class="hover:text-amber-700 transition">News</a>
      <a href="donate.php" class="hover:text-amber-700 transition">Donate</a>
    </div>
    <div class="flex md:hidden items-center gap-3">
      <a href="donate.php" class="btn-donate text-white px-4 py-1.5 rounded-full text-sm font-semibold"><i class="fas fa-heart mr-1"></i>Donate</a>
      <button id="mobile-menu-btn" class="text-2xl text-gray-700 focus:outline-none"><i class="fas fa-bars"></i></button>
    </div>
  </div>
  <div id="mobile-menu" class="hidden md:hidden bg-white px-5 pb-4 flex flex-col space-y-3 text-gray-700 font-medium border-t">
    <a href="index.php" class="py-2 hover:text-amber-700">Home</a>
    <a href="about.php" class="py-2 hover:text-amber-700">About</a>
    <a href="#focus" class="py-2 hover:text-amber-700">Our Focus</a>
    <a href="team.php" class="py-2 hover:text-amber-700">Team</a>
    <a href="whasnew.php" class="py-2 hover:text-amber-700">News</a>
    <a href="donate.php" class="py-2 hover:text-amber-700">Donate</a>
  </div>
</nav>

<main>

  <!-- ==================== HERO SECTION ==================== -->
  <section id="home" class="relative min-h-[90vh] flex items-center overflow-hidden">
    <div class="absolute inset-0 w-full h-full">
      <div id="hero-slide-1" class="hero-slide absolute inset-0 w-full h-full bg-cover bg-center" style="background-image: url('./images/homepg1-2-1024x1536.jpg'); background-size: cover; opacity: 1;"></div>
      <div id="hero-slide-2" class="hero-slide absolute inset-0 w-full h-full bg-cover bg-center" style="background-image: url('./images/homepg1-9-1024x683.jpg'); background-size: cover; opacity: 0;"></div>
      <div id="hero-slide-3" class="hero-slide absolute inset-0 w-full h-full bg-cover bg-center" style="background-image: url('./images/homepg1-16-768x1152.jpg'); background-size: cover; opacity: 0;"></div>
      <div class="absolute inset-0 hero-bg-overlay"></div>
    </div>
    <div class="relative container mx-auto px-6 lg:px-12 text-white z-10 py-20">
      <div class="max-w-3xl">
        <h1 class="text-4xl md:text-6xl font-extrabold leading-tight animate-fade-up">Welcome to <span class="text-amber-300">Grow Africa Futures</span></h1>
        <p class="text-lg md:text-xl mt-6 opacity-95 leading-relaxed animate-fade-up delay-100">“Grow Africa Futures ignites possibility where it matters most — empowering communities through sustainable agriculture, innovation and inclusive economic growth. We don't just grow food, we grow Futures.”</p>
        <div class="mt-8 flex flex-wrap gap-5">
          <a href="https://docs.google.com/forms/d/e/1FAIpQLSeKTvbcvB8RIrB8VhrR-mwNBfavsUzIEsgy96pUV-DdXx2Kkg/viewform" class="bg-amber-600 hover:bg-amber-700 text-white px-8 py-3 rounded-full font-semibold transition shadow-lg flex items-center gap-2 animate-btn"><i class="fas fa-hand-holding-heart"></i> Join Us</a>
          <a href="#pillars" class="border-2 border-white/80 hover:bg-white hover:text-gray-900 text-white px-8 py-3 rounded-full font-semibold transition animate-btn delay-200"><i class="fas fa-seedling mr-2"></i> Our Pillars</a>
          <a href="donate.php" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-3 rounded-full font-semibold transition shadow-lg animate-btn delay-300"><i class="fas fa-heart mr-2"></i> Donate Now</a>
          <!-- ===== NEW 4th BUTTON ===== -->
          <a href="whatsnew.php" class="border-2 border-white/80 hover:bg-white hover:text-gray-900 text-white px-8 py-3 rounded-full font-semibold transition animate-btn delay-400"><i class="fas fa-newspaper mr-2"></i> What's New</a>
        </div>
      </div>
    </div>
    <div class="absolute bottom-6 left-0 right-0 z-20 flex justify-center gap-3">
      <div class="hero-dot w-2.5 h-2.5 rounded-full bg-white/50 cursor-pointer transition-all duration-300" data-slide="0"></div>
      <div class="hero-dot w-2.5 h-2.5 rounded-full bg-white/50 cursor-pointer transition-all duration-300" data-slide="1"></div>
      <div class="hero-dot w-2.5 h-2.5 rounded-full bg-white/50 cursor-pointer transition-all duration-300" data-slide="2"></div>
    </div>
  </section>

  <!-- Intro statement -->
  <section class="py-16 bg-white border-b reveal">
    <div class="container mx-auto px-6 text-center max-w-4xl">
      <span class="text-amber-600 font-semibold tracking-wide">OUR MISSION IN ACTION</span>
      <p class="text-xl md:text-2xl text-gray-700 mt-3 font-medium leading-relaxed">We catalyze rural transformation in Africa by empowering young entrepreneurs in agribusiness, addressing sector challenges, and unlocking untapped potential for sustainable economic growth.</p>
    </div>
  </section>

  <!-- ==================== 3 MAIN PILLARS ==================== -->
  <section id="pillars" class="py-20 bg-soft-leaf">
    <div class="container mx-auto px-6">
      <div class="text-center mb-14 reveal">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-800">Our Pillars of Impact</h2>
        <p class="text-gray-600 mt-3 max-w-2xl mx-auto">Driving real change through sustainable agriculture, economic empowerment, and community resilience.</p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 text-center transition-all hover-lift group reveal delay-100">
          <div class="pillar-icon w-16 h-16 mx-auto rounded-full flex items-center justify-center text-2xl text-amber-700 bg-amber-50 group-hover:bg-amber-600 group-hover:text-white transition"><i class="fas fa-leaf"></i></div>
          <h3 class="text-2xl font-bold mt-5">Grow Sustainably</h3>
          <p class="text-gray-600 mt-3">Boosting farms and food security with smart, eco-friendly practices. We implement regenerative agriculture, water conservation, and climate-smart techniques to protect our planet while increasing yields.</p>
          <a href="#contact" class="inline-block mt-6 text-amber-700 font-semibold hover:underline">Find Out More →</a>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6 text-center transition-all hover-lift group reveal delay-200">
          <div class="pillar-icon w-16 h-16 mx-auto rounded-full flex items-center justify-center text-2xl text-amber-700 bg-amber-50 group-hover:bg-amber-600 group-hover:text-white transition"><i class="fas fa-chart-line"></i></div>
          <h3 class="text-2xl font-bold mt-5">Empower Economically</h3>
          <p class="text-gray-600 mt-3">Opening doors to income and opportunity for all. Through micro-financing, market linkages, and business training, we turn smallholder farms into profitable enterprises.</p>
          <a href="#contact" class="inline-block mt-6 text-amber-700 font-semibold hover:underline">Find Out More →</a>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6 text-center transition-all hover-lift group reveal delay-300">
          <div class="pillar-icon w-16 h-16 mx-auto rounded-full flex items-center justify-center text-2xl text-amber-700 bg-amber-50 group-hover:bg-amber-600 group-hover:text-white transition"><i class="fas fa-graduation-cap"></i></div>
          <h3 class="text-2xl font-bold mt-5">Build Skills</h3>
          <p class="text-gray-600 mt-3">Small Gardens, Great Futures — equipping communities to thrive and be self-reliant with hands-on workshops, digital tools, and mentorship from agronomists.</p>
          <a href="#contact" class="inline-block mt-6 text-amber-700 font-semibold hover:underline">Find Out More →</a>
        </div>
      </div>
    </div>
  </section>

  <!-- ==================== OUR VALUES ==================== -->
  <section class="py-20 bg-white">
    <div class="container mx-auto px-6">
      <div class="text-center mb-12 reveal">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-800">Our Core Values</h2>
        <p class="text-gray-600 max-w-2xl mx-auto mt-2">At Grow Africa Futures, our values serve as the foundation of our actions and decisions — each one visualized through the spirit of our work.</p>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Value cards (unchanged) -->
        <div class="value-card bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 reveal">
          <img src="./images/innovation.jpg" alt="Innovation" class="w-full h-40 object-cover" onerror="this.src='https://placehold.co/400x300?text=Innovation'">
          <div class="p-5 text-center"><i class="fas fa-lightbulb text-3xl text-amber-600 mb-2"></i><h3 class="font-bold text-xl">Innovation</h3><p class="text-gray-600 text-sm mt-2">We embrace creativity & forward-thinking to unlock agricultural potential.</p></div>
        </div>
        <div class="value-card bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 reveal delay-100">
          <img src="./images/empowerment.jpg" alt="Empowerment" class="w-full h-40 object-cover" onerror="this.src='https://placehold.co/400x300?text=Empowerment'">
          <div class="p-5 text-center"><i class="fas fa-hand-fist text-3xl text-amber-600 mb-2"></i><h3 class="font-bold text-xl">Empowerment</h3><p class="text-gray-600 text-sm mt-2">Youth become architects of their own success within a sustainable ecosystem.</p></div>
        </div>
        <div class="value-card bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 reveal delay-200">
          <img src="./images/collaboration.jpg" alt="Collaboration" class="w-full h-40 object-cover" onerror="this.src='https://placehold.co/400x300?text=Collaboration'">
          <div class="p-5 text-center"><i class="fas fa-people-arrows text-3xl text-amber-600 mb-2"></i><h3 class="font-bold text-xl">Collaboration</h3><p class="text-gray-600 text-sm mt-2">Strong partnerships & ecosystems for sustainable growth.</p></div>
        </div>
        <div class="value-card bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 reveal delay-300">
          <img src="./images/sustainability.jpg" alt="Sustainability" class="w-full h-40 object-cover" onerror="this.src='https://placehold.co/400x300?text=Sustainability'">
          <div class="p-5 text-center"><i class="fas fa-globe text-3xl text-amber-600 mb-2"></i><h3 class="font-bold text-xl">Sustainability</h3><p class="text-gray-600 text-sm mt-2">Eco-friendly, responsible practices safeguarding future agriculture.</p></div>
        </div>
        <div class="value-card bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 reveal delay-400">
          <img src="./images/inclusivity.jpg" alt="Inclusivity" class="w-full h-40 object-cover" onerror="this.src='https://placehold.co/400x300?text=Inclusivity'">
          <div class="p-5 text-center"><i class="fas fa-hand-peace text-3xl text-amber-600 mb-2"></i><h3 class="font-bold text-xl">Inclusivity</h3><p class="text-gray-600 text-sm mt-2">Equitable access & opportunities for every individual.</p></div>
        </div>
      </div>
      <div class="text-center mt-10 reveal"><a href="about.php" class="text-amber-700 font-semibold border-b border-amber-300 hover:border-amber-700 transition">Find Out More →</a></div>
    </div>
  </section>

  <!-- ==================== MISSION & SDG ==================== -->
  <section id="about" class="py-20 bg-soft-leaf">
    <div class="container mx-auto px-6">
      <div class="grid md:grid-cols-2 gap-12 items-start">
        <div class="reveal">
          <h2 class="text-3xl font-bold text-gray-800">Our Mission</h2>
          <p class="text-gray-700 mt-4 leading-relaxed">To empower rural Africa through innovative agribusiness, our mission at Grow Africa Futures is to catalyze sustainable economic growth by unlocking untapped agribusiness potential. We inspire a new generation of young entrepreneurs, transforming local communities and fostering resilience. Through hands-on training, access to finance, and market integration, we create lasting prosperity.</p>
          <div class="mt-6 bg-white p-5 rounded-xl shadow-sm"><i class="fas fa-seedling text-amber-600 text-xl mr-2"></i><span class="font-semibold">Flourishing Farm Testimonial:</span> <em class="text-gray-600">“Thanks to their training and support, my little farm is now feeding my family and my dreams!”</em> — Emmy Kemunto, Poultry farmer in Narok.</div>
        </div>
        <div class="reveal delay-100">
          <h3 class="text-2xl font-semibold mb-4 flex items-center gap-2"><i class="fas fa-bullseye text-amber-700"></i> Aligned with SDGs</h3>
          <div class="grid grid-cols-2 gap-3">
            <div class="bg-white/70 p-3 rounded-lg flex items-center gap-2"><i class="fas fa-hand-holding-heart text-amber-600"></i> <span class="text-sm">SDG 1: No Poverty</span></div>
            <div class="bg-white/70 p-3 rounded-lg flex items-center gap-2"><i class="fas fa-utensils text-amber-600"></i> <span class="text-sm">SDG 2: Zero Hunger</span></div>
            <div class="bg-white/70 p-3 rounded-lg flex items-center gap-2"><i class="fas fa-briefcase text-amber-600"></i> <span class="text-sm">SDG 8: Decent Work</span></div>
            <div class="bg-white/70 p-3 rounded-lg flex items-center gap-2"><i class="fas fa-microchip text-amber-600"></i> <span class="text-sm">SDG 9: Innovation</span></div>
            <div class="bg-white/70 p-3 rounded-lg flex items-center gap-2"><i class="fas fa-scale-balanced text-amber-600"></i> <span class="text-sm">SDG 10: Reduced Inequalities</span></div>
            <div class="bg-white/70 p-3 rounded-lg flex items-center gap-2"><i class="fas fa-handshake text-amber-600"></i> <span class="text-sm">SDG 17: Partnerships</span></div>
          </div>
          <p class="text-sm text-gray-600 mt-4 italic">Our initiatives directly contribute to these global goals, creating measurable impact across rural Africa.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Key Activities + Founding Story -->
  <section class="py-16 bg-white">
    <div class="container mx-auto px-6 grid md:grid-cols-2 gap-12">
      <div class="reveal">
        <h3 class="text-2xl font-bold mb-4"><i class="fas fa-chalkboard-user text-amber-700 mr-2"></i> Key Activities</h3>
        <ul class="space-y-3 text-gray-700">
          <li class="flex gap-3"><i class="fas fa-check-circle text-amber-600 mt-1"></i> <span><strong>Training & Capacity Building:</strong> Equip youth with AgriTech skills, drone farming, and sustainable agriculture practices.</span></li>
          <li class="flex gap-3"><i class="fas fa-users text-amber-600 mt-1"></i> <span><strong>Networking & Community Building:</strong> Monthly innovation hubs, agribusiness expos, and peer-learning circles.</span></li>
          <li class="flex gap-3"><i class="fas fa-tractor text-amber-600 mt-1"></i> <span><strong>Resource Management:</strong> Distribution of drought-resistant seeds, organic fertilizers, and solar irrigation kits.</span></li>
          <li class="flex gap-3"><i class="fas fa-chalkboard text-amber-600 mt-1"></i> <span><strong>Mentoring & Knowledge Transfer:</strong> 1-on-1 mentorship linking experienced farmers with young agripreneurs.</span></li>
        </ul>
      </div>
      <div class="reveal delay-100">
        <h3 class="text-2xl font-bold mb-4"><i class="fas fa-book-open text-amber-700 mr-2"></i> Founding Story</h3>
        <p class="text-gray-700 leading-relaxed">Born from a collective vision in July 2024, a group of visionary individuals with backgrounds in agriculture, entrepreneurship, and community development came together. Witnessing the struggles of rural youth and the untapped abundance of Africa's soil, they founded Grow Africa Futures. Today, we've impacted over 5,000 farmers and launched 200+ agribusiness startups, creating a ripple effect of prosperity and food security across Kenya and beyond.</p>
      </div>
    </div>
  </section>

  <!-- ==================== CORE FOCUS AREAS ==================== -->
  <section class="py-16 bg-amber-50/40">
    <div class="container mx-auto px-6">
      <h2 class="text-3xl font-bold text-center mb-10 reveal">Core Focus Areas</h2>
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="flex items-start gap-3 bg-white p-5 rounded-xl shadow hover:shadow-md transition reveal"><i class="fas fa-lightbulb text-2xl text-amber-600"></i><div><h4 class="font-bold">Innovation & Entrepreneurship</h4><p class="text-sm">Incubating agritech startups, hackathons, and innovation grants.</p></div></div>
        <div class="flex items-start gap-3 bg-white p-5 rounded-xl shadow hover:shadow-md transition reveal delay-100"><i class="fas fa-user-graduate text-2xl text-amber-600"></i><div><h4 class="font-bold">Youth Empowerment</h4><p class="text-sm">Leadership camps, digital literacy, and vocational training in agribusiness.</p></div></div>
        <div class="flex items-start gap-3 bg-white p-5 rounded-xl shadow hover:shadow-md transition reveal delay-200"><i class="fas fa-handshake text-2xl text-amber-600"></i><div><h4 class="font-bold">Collaborative Ecosystem</h4><p class="text-sm">Partnerships with universities, government bodies, and NGOs.</p></div></div>
        <div class="flex items-start gap-3 bg-white p-5 rounded-xl shadow hover:shadow-md transition reveal delay-300"><i class="fas fa-tree text-2xl text-amber-600"></i><div><h4 class="font-bold">Environmental Sustainability</h4><p class="text-sm">Tree planting, regenerative agriculture, and carbon-smart techniques.</p></div></div>
        <div class="flex items-start gap-3 bg-white p-5 rounded-xl shadow hover:shadow-md transition reveal delay-400"><i class="fas fa-chart-simple text-2xl text-amber-600"></i><div><h4 class="font-bold">Access to Resources & Markets</h4><p class="text-sm">Linking farmers to fair-trade markets, micro-loans, and land access programs.</p></div></div>
      </div>
    </div>
  </section>

  <!-- ==================== STRATEGIC FOCUS AREAS ==================== -->
  <section id="focus" class="py-20 bg-white">
    <div class="container mx-auto px-6">
      <div class="text-center max-w-3xl mx-auto mb-12 reveal">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-800">Cultivating Africa's Future</h2>
        <p class="text-gray-600 mt-3">Everything we focus on is designed to create dignity, opportunity and lasting transformation.</p>
      </div>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-7">
        <div class="border-l-4 border-amber-500 bg-gray-50 p-5 rounded-r-xl shadow-sm hover:shadow-md transition reveal"><i class="fas fa-seedling text-amber-600 text-xl mr-2"></i><h3 class="font-bold text-xl">Environmental Sustainability</h3><p class="text-gray-600">Climate-resilient farming, water harvesting, and agroforestry.</p></div>
        <div class="border-l-4 border-amber-500 bg-gray-50 p-5 rounded-r-xl shadow-sm hover:shadow-md transition reveal delay-100"><i class="fas fa-brain text-amber-600 text-xl mr-2"></i><h3 class="font-bold text-xl">Capacity Building</h3><p class="text-gray-600">Digital agronomy courses, field schools, and certified trainings.</p></div>
        <div class="border-l-4 border-amber-500 bg-gray-50 p-5 rounded-r-xl shadow-sm hover:shadow-md transition reveal delay-200"><i class="fas fa-rocket text-amber-600 text-xl mr-2"></i><h3 class="font-bold text-xl">Innovation Hubs</h3><p class="text-gray-600">Co-working spaces, prototyping labs, and agritech accelerators.</p></div>
        <div class="border-l-4 border-amber-500 bg-gray-50 p-5 rounded-r-xl shadow-sm hover:shadow-md transition reveal delay-300"><i class="fas fa-share-alt text-amber-600 text-xl mr-2"></i><h3 class="font-bold text-xl">Networking</h3><p class="text-gray-600">Annual agri-summit, cross-border learning tours, and digital community.</p></div>
        <div class="border-l-4 border-amber-500 bg-gray-50 p-5 rounded-r-xl shadow-sm hover:shadow-md transition reveal delay-400"><i class="fas fa-store text-amber-600 text-xl mr-2"></i><h3 class="font-bold text-xl">Market Access</h3><p class="text-gray-600">Digital marketplace, contract farming, and export readiness programs.</p></div>
        <div class="border-l-4 border-amber-500 bg-gray-50 p-5 rounded-r-xl shadow-sm hover:shadow-md transition reveal delay-500"><i class="fas fa-wrench text-amber-600 text-xl mr-2"></i><h3 class="font-bold text-xl">Skill Development</h3><p class="text-gray-600">Drone piloting, precision agriculture, and financial literacy.</p></div>
      </div>
    </div>
  </section>

  <!-- ==================== TEAM SECTION ==================== -->
  <section id="team" class="py-20 bg-soft-leaf">
    <div class="container mx-auto px-6 text-center">
      <div class="reveal">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-800">Our Expert Team</h2>
        <p class="text-gray-600 max-w-2xl mx-auto mt-3">Behind Grow Africa Futures is a passionate group of agribusiness specialists, technology innovators, and community development experts. Together we bring decades of experience in sustainable farming, youth empowerment, and rural transformation.</p>
        <div class="mt-8 inline-flex items-center gap-2 bg-amber-100 hover:bg-amber-200 text-amber-800 px-6 py-3 rounded-full font-semibold transition shadow-md">
          <a href="team.php" class="flex items-center gap-2">Meet the Full Team <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-6 max-w-3xl mx-auto">
          <div class="bg-white p-4 rounded-xl shadow-sm"><i class="fas fa-user-tie text-amber-600 text-2xl"></i><p class="font-bold mt-2">Board of Directors</p><p class="text-xs text-gray-500">Strategic leadership</p></div>
          <div class="bg-white p-4 rounded-xl shadow-sm"><i class="fas fa-chalkboard-user text-amber-600 text-2xl"></i><p class="font-bold mt-2">Technical Advisors</p><p class="text-xs text-gray-500">Agronomy & research</p></div>
          <div class="bg-white p-4 rounded-xl shadow-sm"><i class="fas fa-laptop-code text-amber-600 text-2xl"></i><p class="font-bold mt-2">Innovation Hub</p><p class="text-xs text-gray-500">Digital solutions</p></div>
          <div class="bg-white p-4 rounded-xl shadow-sm"><i class="fas fa-hand-holding-heart text-amber-600 text-2xl"></i><p class="font-bold mt-2">Field Mentors</p><p class="text-xs text-gray-500">On-ground impact</p></div>
        </div>
      </div>
    </div>
  </section>

  <!-- ==================== TESTIMONIALS ==================== -->
  <section class="py-20 bg-white">
    <div class="container mx-auto px-6">
      <h2 class="text-3xl font-bold text-center mb-12 reveal">Voices of Impact</h2>
      <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-amber-50 p-6 rounded-2xl shadow-lg hover-lift reveal"><i class="fas fa-star text-amber-400 text-lg"></i><i class="fas fa-star text-amber-400"></i><i class="fas fa-star text-amber-400"></i><i class="fas fa-star text-amber-400"></i><i class="fas fa-star text-amber-400"></i><p class="italic mt-3">“Before Grow Africa Futures, farming felt like survival. Today it’s a business with a future. I’ve increased yields, improved income, and gained confidence.”</p><p class="font-bold mt-4">— Youth Farmer, Kitui County</p></div>
        <div class="bg-amber-50 p-6 rounded-2xl shadow-lg hover-lift reveal delay-100"><i class="fas fa-star text-amber-400"></i><i class="fas fa-star text-amber-400"></i><i class="fas fa-star text-amber-400"></i><i class="fas fa-star text-amber-400"></i><i class="fas fa-star text-amber-400"></i><p class="italic mt-3">“Grow Africa Futures stands out with clarity and commitment to measurable impact. Their work is not charity — it's transformation. Partnering with them means lasting change.”</p><p class="font-bold mt-4">— FSK, New York</p></div>
        <div class="bg-amber-50 p-6 rounded-2xl shadow-lg hover-lift reveal delay-200"><i class="fas fa-star text-amber-400"></i><i class="fas fa-star text-amber-400"></i><i class="fas fa-star text-amber-400"></i><i class="fas fa-star text-amber-400"></i><i class="fas fa-star text-amber-400"></i><p class="italic mt-3">“Thanks to their training and support, my little farm is now feeding my family and my dreams!”</p><p class="font-bold mt-4">— Emmy Kemunto, Poultry farmer, Narok</p></div>
      </div>
    </div>
  </section>

  <!-- ==================== WHAT'S NEW / LATEST UPDATES (with carousel per post) ==================== -->
  <section class="py-20 bg-soft-leaf">
    <div class="container mx-auto px-6">
      <div class="text-center mb-12 reveal">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-800">Latest Updates</h2>
        <p class="text-gray-600 mt-2">Stories of change, news, and upcoming events from our community</p>
      </div>
      <?php if (empty($posts)): ?>
        <div class="text-center py-12 bg-white rounded-2xl shadow-sm">
          <p class="text-gray-500">No updates yet. Check back soon!</p>
        </div>
      <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <?php foreach ($posts as $post): 
            $media = json_decode($post['images'], true);
            if (!is_array($media) || empty($media)) $media = [];
            $categoryClass = $post['category'];
            $categoryIcon = $post['category'] == 'news' ? '📰' : ($post['category'] == 'story' ? '🌟' : '📅');
            $shareText = urlencode($post['title'] . ' - ' . substr(strip_tags($post['content']), 0, 80) . '...');
            $shareUrl = urlencode('https://' . $_SERVER['HTTP_HOST'] . '/post.php?id=' . $post['id']);
          ?>
          <div class="bg-white rounded-2xl shadow-md overflow-hidden news-card flex flex-col">
            <!-- Carousel -->
            <div class="relative w-full h-56 overflow-hidden bg-gray-100 flex-shrink-0">
              <div class="carousel-track" data-slide-interval="7000">
                <?php if (empty($media)): ?>
                  <div class="no-img"><i class="fas fa-image mr-2"></i> No media</div>
                <?php else: ?>
                  <?php foreach ($media as $file):
                    if (isVideo($file)): ?>
                      <video muted loop playsinline>
                        <source src="<?= $file; ?>">
                        Your browser does not support video.
                      </video>
                    <?php else: ?>
                      <img src="<?= $file; ?>" alt="<?= $post['title']; ?>" loading="lazy">
                    <?php endif; ?>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>
            <!-- Card Body -->
            <div class="p-5 flex flex-col flex-1">
              <div class="flex justify-between items-start mb-2">
                <span class="category-badge <?= $categoryClass; ?>"><?= $categoryIcon . ' ' . ucfirst($post['category']); ?></span>
                <span class="text-sm text-gray-500"><i class="far fa-calendar-alt mr-1"></i> <?= date('M d, Y', strtotime($post['created_at'])); ?></span>
              </div>
              <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2"><?= sanitize($post['title']); ?></h3>
              <div class="text-gray-600 text-sm flex-1 line-clamp-3 mb-4"><?= nl2br(sanitize(substr(strip_tags($post['content']), 0, 160))); ?>...</div>
              <div class="flex items-center justify-between border-t border-gray-100 pt-3 mt-auto">
                <div class="flex gap-3">
                  <a href="https://api.whatsapp.com/send?text=<?= $shareText; ?>%20<?= $shareUrl; ?>" target="_blank" class="share-btn whatsapp" title="Share on WhatsApp"><i class="fab fa-whatsapp text-lg"></i></a>
                  <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrl; ?>&quote=<?= $shareText; ?>" target="_blank" class="share-btn facebook" title="Share on Facebook"><i class="fab fa-facebook text-lg"></i></a>
                </div>
                <a href="post.php?id=<?= $post['id']; ?>" class="read-more-btn">Read Full Story <i class="fas fa-arrow-right"></i></a>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <div class="text-center mt-10">
          <a href="whatsnew.php" class="inline-block bg-amber-600 hover:bg-amber-700 text-white font-semibold px-6 py-3 rounded-full transition">View All News <i class="fas fa-arrow-right ml-2"></i></a>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ==================== CONTACT SECTION ==================== -->
  <section id="contact" class="py-20 bg-gray-900 text-white">
    <div class="container mx-auto px-6 grid md:grid-cols-2 gap-12">
      <div class="reveal">
        <h2 class="text-3xl font-bold">Talk to us.</h2>
        <p class="text-gray-300 mt-2 mb-6">Have a question, idea, or partnership in mind? We’d love to hear from you.</p>
        <div class="space-y-5">
          <div class="flex items-center gap-4"><i class="fas fa-phone-alt text-amber-400 text-2xl"></i><div><p class="font-semibold">Phone</p><p>+254700536808</p></div></div>
          <div class="flex items-center gap-4"><i class="fas fa-envelope text-amber-400 text-2xl"></i><div><p class="font-semibold">Email</p><p>info@growafricafutures.org</p></div></div>
          <div class="flex items-center gap-4"><i class="fas fa-map-marker-alt text-amber-400 text-2xl"></i><div><p class="font-semibold">Address</p><p>P.O Box 67509, 00200, Nairobi, Kenya</p></div></div>
        </div>
      </div>
      <div class="bg-white/10 backdrop-blur-sm p-6 rounded-2xl reveal delay-100">
        <h3 class="text-2xl font-semibold mb-4">Send us a message</h3>
        <form id="contactForm" class="space-y-4">
          <input type="text" placeholder="Full Name" class="w-full p-3 rounded-lg bg-white/20 text-white placeholder-gray-300 border border-gray-500 focus:outline-none focus:border-amber-400">
          <input type="email" placeholder="Email Address" class="w-full p-3 rounded-lg bg-white/20 text-white placeholder-gray-300 border border-gray-500 focus:outline-none">
          <textarea rows="4" placeholder="Comment or Message" class="w-full p-3 rounded-lg bg-white/20 text-white placeholder-gray-300 border border-gray-500"></textarea>
          <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-gray-900 font-bold py-3 px-6 rounded-lg transition w-full">Submit Message</button>
        </form>
        <p id="form-feedback" class="text-sm mt-2 text-green-300 hidden"></p>
      </div>
    </div>
  </section>
</main>

<!-- ==================== FOOTER ==================== -->
<footer class="bg-gray-800 text-gray-300 border-t border-gray-700">
  <div class="container mx-auto px-6 py-10">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
      <div>
        <div class="flex items-center gap-3 mb-4">
          <img src="./images/logo.png" alt="Grow Africa Futures" class="h-10 w-auto" onerror="this.src='https://placehold.co/200x200?text=GAF'">
          <span class="font-bold text-xl text-white">Grow Africa<span class="text-amber-400"> Futures</span></span>
        </div>
        <p class="text-sm text-gray-400 leading-relaxed">Empowering communities through sustainable agriculture, innovation, and inclusive economic growth.</p>
      </div>
      <div>
        <h4 class="text-white font-semibold text-lg mb-4">Quick Links</h4>
        <ul class="space-y-2 text-sm">
          <li><a href="index.php" class="hover:text-amber-400 transition">Home</a></li>
          <li><a href="about.php" class="hover:text-amber-400 transition">About Us</a></li>
          <li><a href="#focus" class="hover:text-amber-400 transition">Our Focus</a></li>
          <li><a href="team.php" class="hover:text-amber-400 transition">Team</a></li>
          <li><a href="whasnew.php" class="hover:text-amber-400 transition">News</a></li>
          <li><a href="donate.php" class="hover:text-amber-400 transition">Donate</a></li>
          <li><a href="#contact" class="hover:text-amber-400 transition">Contact</a></li>
        </ul>
      </div>
      <div>
        <h4 class="text-white font-semibold text-lg mb-4">Get In Touch</h4>
        <ul class="space-y-3 text-sm">
          <li class="flex items-start gap-3"><i class="fas fa-map-marker-alt text-amber-400 mt-1"></i> P.O Box 67509, 00200, Nairobi, Kenya</li>
          <li class="flex items-center gap-3"><i class="fas fa-phone-alt text-amber-400"></i> +254700536808</li>
          <li class="flex items-center gap-3"><i class="fas fa-envelope text-amber-400"></i> info@growafricafutures.org</li>
        </ul>
      </div>
      <div>
        <h4 class="text-white font-semibold text-lg mb-4">Follow Us</h4>
        <div class="flex gap-4 mb-5">
          <a href="#" class="bg-gray-700 hover:bg-amber-500 text-gray-300 hover:text-white w-10 h-10 rounded-full flex items-center justify-center transition"><i class="fab fa-twitter"></i></a>
          <a href="#" class="bg-gray-700 hover:bg-amber-500 text-gray-300 hover:text-white w-10 h-10 rounded-full flex items-center justify-center transition"><i class="fab fa-linkedin-in"></i></a>
          <a href="#" class="bg-gray-700 hover:bg-amber-500 text-gray-300 hover:text-white w-10 h-10 rounded-full flex items-center justify-center transition"><i class="fab fa-instagram"></i></a>
          <a href="#" class="bg-gray-700 hover:bg-amber-500 text-gray-300 hover:text-white w-10 h-10 rounded-full flex items-center justify-center transition"><i class="fab fa-facebook-f"></i></a>
        </div>
        <p class="text-sm text-gray-400">Join our mission — partner with us to transform African agriculture.</p>
      </div>
    </div>
    <div class="border-t border-gray-700 mt-8 pt-6 text-center text-sm text-gray-500">
      © <?= date('Y'); ?> Grow Africa Futures — Cultivating Prosperity, Growing Futures. All rights reserved.
    </div>
  </div>
</footer>

<script>
  // Mobile menu toggle
  const menuBtn = document.getElementById('mobile-menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');
  if(menuBtn) menuBtn.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));

  // Hero slider
  const heroSlides = [document.getElementById('hero-slide-1'), document.getElementById('hero-slide-2'), document.getElementById('hero-slide-3')];
  let heroIndex = 0;
  const heroDots = document.querySelectorAll('.hero-dot');
  function showHeroSlide(index) {
    heroSlides.forEach((slide, i) => { if(slide) slide.style.opacity = i === index ? '1' : '0'; });
    heroDots.forEach((dot, i) => { if(i === index) { dot.classList.add('bg-white'); dot.classList.remove('bg-white/50'); } else { dot.classList.remove('bg-white'); dot.classList.add('bg-white/50'); } });
    heroIndex = index;
  }
  function nextHeroSlide() { showHeroSlide((heroIndex + 1) % heroSlides.length); }
  let heroInterval = setInterval(nextHeroSlide, 6000);
  heroDots.forEach((dot, idx) => dot.addEventListener('click', () => { clearInterval(heroInterval); showHeroSlide(idx); heroInterval = setInterval(nextHeroSlide, 6000); }));
  showHeroSlide(0);

  // Contact form
  const form = document.getElementById('contactForm');
  const feedback = document.getElementById('form-feedback');
  if(form) form.addEventListener('submit', (e) => { e.preventDefault(); feedback.textContent = "Thank you! Your message has been received."; feedback.classList.remove('hidden'); form.reset(); setTimeout(() => feedback.classList.add('hidden'), 4000); });

  // Scroll reveal
  const revealElements = document.querySelectorAll('.reveal');
  const observer = new IntersectionObserver((entries) => { entries.forEach(entry => { if(entry.isIntersecting) entry.target.classList.add('visible'); }); }, { threshold: 0.1 });
  revealElements.forEach(el => observer.observe(el));

  // ===== CAROUSEL SCRIPT (exactly like whasnew.php) =====
  (function() {
    const tracks = document.querySelectorAll('.carousel-track');
    tracks.forEach(track => {
      const slides = track.querySelectorAll('img, video');
      if (slides.length <= 1) return;
      let currentIndex = 0;
      const total = slides.length;
      const interval = parseInt(track.dataset.slideInterval) || 7000;

      function goTo(index) {
        if (index >= total) index = 0;
        if (index < 0) index = total - 1;
        currentIndex = index;
        track.style.transform = `translateX(-${currentIndex * 100}%)`;
        slides.forEach((el, i) => {
          if (el.tagName === 'VIDEO') {
            if (i === currentIndex) {
              el.play().catch(() => {});
            } else {
              el.pause();
            }
          }
        });
      }

      let slideInterval = setInterval(() => goTo(currentIndex + 1), interval);
      const carousel = track.parentElement;
      carousel.addEventListener('mouseenter', () => clearInterval(slideInterval));
      carousel.addEventListener('mouseleave', () => {
        slideInterval = setInterval(() => goTo(currentIndex + 1), interval);
      });

      let startX = 0, isDragging = false;
      carousel.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
        isDragging = true;
      });
      carousel.addEventListener('touchmove', (e) => {
        if (!isDragging) return;
        const diff = startX - e.touches[0].clientX;
        if (Math.abs(diff) > 40) {
          if (diff > 0) goTo(currentIndex + 1);
          else goTo(currentIndex - 1);
          isDragging = false;
        }
      });
      carousel.addEventListener('touchend', () => { isDragging = false; });

      slides.forEach((el, i) => {
        if (el.tagName === 'VIDEO' && i === 0) {
          el.play().catch(() => {});
        }
      });
    });
  })();
</script>
</body>
</html>