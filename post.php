<?php
require_once 'db.php';

$post = null;
$error = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $post = getPost($conn, intval($_GET['id']));
    if (!$post) {
        $error = "Story not found.";
    }
} else {
    $error = "Invalid request.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $post ? sanitize($post['title']) : 'Story'; ?> | Grow Africa Futures</title>
    <!-- Tailwind CSS + Font Awesome + Google Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #fefcf5; }
        .post-gallery img,
        .post-gallery video {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 0.75rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            transition: transform 0.2s;
            background: #1a1a1a;
        }
        .post-gallery img:hover,
        .post-gallery video:hover {
            transform: scale(1.02);
        }
        .post-content p { margin-bottom: 1rem; }
        .share-btn { transition: color 0.2s; }
        .share-btn.whatsapp:hover { color: #25D366; }
        .share-btn.facebook:hover { color: #1877F2; }
        .share-btn.twitter:hover { color: #1DA1F2; }
        .footer-link { transition: color 0.2s; }
        .footer-link:hover { color: #f59e0b; }
        .social-icon { transition: background 0.2s, color 0.2s; }
        .social-icon:hover { background: #f59e0b; color: white; }
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
                <a href="index.php" class="hover:text-amber-700 transition">Home</a>
                <a href="about.html" class="hover:text-amber-700 transition">About</a>
                <a href="index.html#focus" class="hover:text-amber-700 transition">Our Focus</a>
                <a href="team.html" class="hover:text-amber-700 transition">Team</a>
                <a href="whatsnew.php" class="hover:text-amber-700 transition">News</a>
                <a href="donate.html" class="hover:text-amber-700 transition">Donate</a>
            </div>
            <div class="flex md:hidden">
                <button id="mobile-menu-btn" class="text-2xl text-gray-700 focus:outline-none"><i class="fas fa-bars"></i></button>
            </div>
        </div>
        <!-- mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white px-5 pb-4 flex flex-col space-y-3 text-gray-700 font-medium border-t">
            <a href="index.php" class="py-2 hover:text-amber-700">Home</a>
            <a href="about.html" class="py-2 hover:text-amber-700">About</a>
            <a href="index.php#focus" class="py-2 hover:text-amber-700">Our Focus</a>
            <a href="team.html" class="py-2 hover:text-amber-700">Team</a>
            <a href="whasnew.php" class="py-2 hover:text-amber-700">News</a>
            <a href="donate.html" class="py-2 hover:text-amber-700">Donate</a>
        </div>
    </nav>

    <!-- ==================== MAIN CONTENT ==================== -->
    <div class="container mx-auto px-6 py-10 max-w-4xl">
        <a href="whatsnew.php" class="inline-block mb-6 text-amber-700 font-semibold hover:underline"><i class="fas fa-arrow-left mr-2"></i> Back to all stories</a>

        <?php if ($error || !$post): ?>
            <div class="text-center py-16 bg-white rounded-2xl shadow-sm">
                <i class="fas fa-circle-exclamation text-5xl text-gray-300"></i>
                <h2 class="text-2xl font-bold text-gray-700 mt-4"><?= $error ?? 'Something went wrong.'; ?></h2>
                <p class="text-gray-500 mt-2">The story you are looking for might have been removed.</p>
            </div>
        <?php else: 
            $media = json_decode($post['images'], true);
            if (!is_array($media) || empty($media)) $media = [];
            $categoryClass = $post['category'];
            $categoryIcon = $post['category'] == 'news' ? '📰' : ($post['category'] == 'story' ? '🌟' : '📅');
            $shareText = urlencode($post['title'] . ' - ' . substr(strip_tags($post['content']), 0, 100) . '...');
            $shareUrl = urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        ?>
            <div class="bg-white rounded-2xl shadow-md p-6 md:p-8">
                <div class="mb-4">
                    <span class="category-badge <?= $categoryClass; ?>"><?= $categoryIcon . ' ' . ucfirst($post['category']); ?></span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 leading-tight"><?= sanitize($post['title']); ?></h1>
                <div class="text-gray-500 text-sm mt-2"><i class="far fa-calendar-alt mr-1"></i> Published on <?= date('F d, Y', strtotime($post['created_at'])); ?></div>

                <?php if (!empty($media)): ?>
                    <div class="post-gallery grid grid-cols-1 sm:grid-cols-2 gap-4 my-6">
                        <?php foreach ($media as $file):
                            if (isVideo($file)): ?>
                                <video controls muted playsinline class="rounded-xl shadow">
                                    <source src="<?= $file; ?>">
                                    Your browser does not support video.
                                </video>
                            <?php else: ?>
                                <img src="<?= $file; ?>" alt="<?= sanitize($post['title']); ?>" loading="lazy" class="rounded-xl shadow">
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="post-content text-gray-700 text-base md:text-lg leading-relaxed">
                    <?= nl2br(sanitize($post['content'])); ?>
                </div>

                <div class="border-t border-gray-100 pt-6 mt-6 flex flex-wrap items-center gap-4">
                    <span class="font-semibold text-gray-700"><i class="fas fa-share-nodes mr-2"></i> Share:</span>
                    <a href="https://api.whatsapp.com/send?text=<?= $shareText; ?>%20<?= $shareUrl; ?>" target="_blank" class="share-btn whatsapp text-2xl text-gray-500" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrl; ?>&quote=<?= $shareText; ?>" target="_blank" class="share-btn facebook text-2xl text-gray-500" title="Facebook"><i class="fab fa-facebook"></i></a>
                    <a href="https://twitter.com/intent/tweet?text=<?= $shareText; ?>&url=<?= $shareUrl; ?>" target="_blank" class="share-btn twitter text-2xl text-gray-500" title="Twitter"><i class="fab fa-twitter"></i></a>
                </div>
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
                        <li><a href="index.php" class="footer-link">Home</a></li>
                        <li><a href="about.html" class="footer-link">About Us</a></li>
                        <li><a href="index.php#focus" class="footer-link">Our Focus</a></li>
                        <li><a href="team.html" class="footer-link">Team</a></li>
                        <li><a href="whatsnew.php" class="footer-link">News</a></li>
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

    <!-- ==================== MOBILE MENU SCRIPT ==================== -->
    <script>
        // Mobile menu toggle
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        if (menuBtn) {
            menuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>