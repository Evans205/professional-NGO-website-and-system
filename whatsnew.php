<?php
require_once 'db.php';

// Fetch all posts
$posts = getPosts($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News & Stories | Grow Africa Futures</title>
    <!-- Tailwind CSS + Font Awesome + Google Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #fefcf5; }
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
        .news-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .news-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px -12px rgba(0,0,0,0.1);
        }
        .category-badge {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 0.25rem 0.75rem;
            border-radius: 40px;
        }
        .category-badge.news { background: #dbeafe; color: #1e40af; }
        .category-badge.story { background: #d1fae5; color: #065f46; }
        .category-badge.event { background: #fef3c7; color: #92400e; }
        .share-btn { transition: color 0.2s; }
        .share-btn.whatsapp:hover { color: #25D366; }
        .share-btn.facebook:hover { color: #1877F2; }
        /* Ensure footer links are visible */
        .footer-link { transition: color 0.2s; }
        .footer-link:hover { color: #f59e0b; }
        .social-icon { transition: background 0.2s, color 0.2s; }
        .social-icon:hover { background: #f59e0b; color: white; }
        /* Ensure the read-more button is visible */
        .read-more-btn {
            background: #d97706;
            color: white;
            padding: 0.5rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: background 0.2s;
            display: inline-block;
            text-align: center;
            border: none;
            cursor: pointer;
        }
        .read-more-btn:hover {
            background: #b45309;
        }
        .read-more-btn i {
            margin-left: 0.5rem;
        }
    </style>
</head>
<body>

    <!-- ==================== NAVBAR (matches main site) ==================== -->
    <nav class="bg-white/90 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-gray-100">
        <div class="container mx-auto px-5 lg:px-8 py-3 flex flex-wrap items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="./images/logo.png" alt="Grow Africa Futures Logo" class="h-12 w-auto object-contain" onerror="this.src='https://placehold.co/400x200?text=Grow+Africa+Futures'">
                <span class="font-bold text-2xl tracking-tight text-gray-800">Grow Africa<span class="text-amber-700"> Futures</span></span>
            </div>
            <div class="hidden md:flex items-center space-x-8 text-gray-700 font-medium">
                <a href="index.html" class="hover:text-amber-700 transition">Home</a>
                <a href="about.html" class="hover:text-amber-700 transition">About</a>
                <a href="index.html#focus" class="hover:text-amber-700 transition">Our Focus</a>
                <a href="team.html" class="hover:text-amber-700 transition">Team</a>
                <a href="whasnew.php" class="text-amber-700 font-semibold">News</a>
                <a href="donate.html" class="hover:text-amber-700 transition">Donate</a>
            </div>
            <div class="flex md:hidden">
                <button id="mobile-menu-btn" class="text-2xl text-gray-700 focus:outline-none"><i class="fas fa-bars"></i></button>
            </div>
        </div>
        <!-- mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white px-5 pb-4 flex flex-col space-y-3 text-gray-700 font-medium border-t">
            <a href="index.html" class="py-2 hover:text-amber-700">Home</a>
            <a href="about.html" class="py-2 hover:text-amber-700">About</a>
            <a href="index.html#focus" class="py-2 hover:text-amber-700">Our Focus</a>
            <a href="team.html" class="py-2 hover:text-amber-700">Team</a>
            <a href="whasnew.php" class="py-2 text-amber-700 font-semibold">News</a>
            <a href="donate.html" class="py-2 hover:text-amber-700">Donate</a>
        </div>
    </nav>

    <!-- ==================== MAIN CONTENT ==================== -->
    <div class="container mx-auto px-6 py-10" id="stories">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800"><i class="fas fa-seedling text-amber-600 mr-3"></i> What's New</h1>
            <p class="text-gray-600 max-w-2xl mx-auto mt-2">Stories of change, latest news, and upcoming events from our community</p>
        </div>

        <!-- Posts Grid -->
        <?php if (empty($posts)): ?>
            <div class="text-center py-20 bg-white rounded-2xl shadow-sm">
                <i class="fas fa-newspaper text-5xl text-gray-300"></i>
                <h3 class="text-xl font-semibold mt-4 text-gray-600">No stories yet</h3>
                <p class="text-gray-500">Check back soon for updates from our community.</p>
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
                            <!-- ====== VISIBLE READ MORE BUTTON ====== -->
                            <a href="post.php?id=<?= $post['id']; ?>" class="read-more-btn">
                                Read Full Story <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- ==================== FOOTER (matches main site) ==================== -->
    <footer class="bg-gray-900 text-gray-300 mt-16">
        <div class="container mx-auto px-6 py-12">
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
                        <li><a href="index.html" class="footer-link">Home</a></li>
                        <li><a href="about.html" class="footer-link">About Us</a></li>
                        <li><a href="index.html#focus" class="footer-link">Our Focus</a></li>
                        <li><a href="team.html" class="footer-link">Team</a></li>
                        <li><a href="whasnew.php" class="footer-link">News</a></li>
                        <li><a href="donate.html" class="footer-link">Donate</a></li>
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
                        <a href="#" class="bg-gray-800 social-icon w-10 h-10 rounded-full flex items-center justify-center transition"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="bg-gray-800 social-icon w-10 h-10 rounded-full flex items-center justify-center transition"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="bg-gray-800 social-icon w-10 h-10 rounded-full flex items-center justify-center transition"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="bg-gray-800 social-icon w-10 h-10 rounded-full flex items-center justify-center transition"><i class="fab fa-facebook-f"></i></a>
                    </div>
                    <p class="text-sm text-gray-400">Join our mission — partner with us to transform African agriculture.</p>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-10 pt-6 text-center text-sm text-gray-500">
                © <?= date('Y'); ?> Grow Africa Futures — Cultivating Prosperity, Growing Futures. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- ==================== CAROUSEL + MOBILE MENU SCRIPT ==================== -->
    <script>
        // Mobile menu toggle
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        if (menuBtn) {
            menuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Carousel auto-slide and touch support
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
<?php $conn->close(); ?>