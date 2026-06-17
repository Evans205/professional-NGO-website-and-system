<?php
session_start();
require_once 'db.php';

// ========== ADMIN CREDENTIALS ==========
define('ADMIN_USER', 'admin');
// CHANGE THIS PASSWORD IMMEDIATELY!
define('ADMIN_PASS_HASH', password_hash('your_secure_password_here', PASSWORD_DEFAULT));

// ========== HANDLE ACTIONS ==========
$action = $_GET['action'] ?? '';

// --- LOGIN ---
if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    if ($username === ADMIN_USER && password_verify($password, ADMIN_PASS_HASH)) {
        $_SESSION['logged_in'] = true;
        session_regenerate_id(true);
        header('Location: admin.php?login=success');
        exit;
    } else {
        $login_error = "Invalid username or password.";
    }
}

// --- LOGOUT ---
if ($action === 'logout') {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// --- SAVE (CREATE/UPDATE) ---
if (isAdmin() && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_post'])) {
    $title = sanitize($_POST['title']);
    $category = sanitize($_POST['category']);
    $content = sanitize($_POST['content']);
    $edit_id = intval($_POST['edit_id'] ?? 0);

    $media_paths = [];
    if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION));
                // Allowed: images + videos
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'webm', 'ogg', 'mov', 'avi'];
                if (in_array($ext, $allowed)) {
                    $new_name = uniqid() . '.' . $ext;
                    $destination = $upload_dir . $new_name;
                    if (move_uploaded_file($tmp_name, $destination)) {
                        $media_paths[] = $destination;
                    }
                }
            }
        }
    }

    // If updating and no new media, keep old ones
    if ($edit_id > 0 && empty($media_paths)) {
        $old_post = getPost($conn, $edit_id);
        if ($old_post) {
            $media_paths = json_decode($old_post['images'], true);
            if (!is_array($media_paths)) $media_paths = [];
        }
    }

    $images_json = json_encode($media_paths);
    savePost($conn, $title, $category, $content, $images_json, $edit_id);
    header('Location: admin.php');
    exit;
}

// --- DELETE ---
if (isAdmin() && isset($_GET['delete_id'])) {
    deletePost($conn, intval($_GET['delete_id']));
    header('Location: admin.php');
    exit;
}

// ========== HELPER TO CHECK LOGIN ==========
function isAdmin() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Fetch all posts for the admin list
$posts = getPosts($conn);

// If editing, fetch the post data
$edit_post = null;
if (isAdmin() && isset($_GET['edit_id'])) {
    $edit_post = getPost($conn, intval($_GET['edit_id']));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - NGO Stories</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f7f6;
            color: #1e2b37;
            padding: 30px;
        }
        .container { max-width: 1100px; margin: 0 auto; }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 18px 30px;
            border-radius: 60px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
            margin-bottom: 30px;
        }
        .admin-header h1 { font-size: 1.5rem; color: #0f3b2c; }
        .admin-header .btn {
            background: #c62828;
            color: white;
            padding: 8px 20px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .admin-header .btn-success { background: #0f3b2c; }

        /* Login Box */
        .login-box {
            max-width: 400px;
            margin: 80px auto;
            background: white;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 20px 40px -12px rgba(0,0,0,0.15);
        }
        .login-box h2 { margin-bottom: 10px; }
        .login-box .error { color: #c62828; background: #ffebee; padding: 10px; border-radius: 8px; margin-bottom: 15px; }
        .login-box input {
            width: 100%;
            padding: 14px 16px;
            margin-bottom: 15px;
            border: 1px solid #dce1e6;
            border-radius: 12px;
            font-size: 1rem;
        }
        .login-box button {
            width: 100%;
            background: #0f3b2c;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 40px;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
        }

        /* Admin Dashboard */
        .admin-panel {
            background: white;
            padding: 30px 35px;
            border-radius: 28px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.08);
            margin-bottom: 40px;
        }
        .admin-panel h2 { font-size: 1.6rem; margin-bottom: 5px; }
        .admin-panel .sub { color: #5a6a7a; margin-bottom: 25px; }
        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #dce1e6;
            border-radius: 14px;
            font-size: 1rem;
            font-family: inherit;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none;
            border-color: #0f3b2c;
            box-shadow: 0 0 0 4px rgba(15, 59, 44, 0.1);
        }
        .form-group textarea { min-height: 100px; resize: vertical; }
        .form-group .hint { font-size: 0.8rem; color: #6a7a8a; margin-top: 4px; }
        .btn-primary {
            background: #0f3b2c;
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 40px;
            font-weight: 700;
            cursor: pointer;
        }
        .btn-primary:hover { background: #1a5c3e; }

        /* Post List */
        .post-list { margin-top: 20px; }
        .post-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            background: #f8fafc;
            border-radius: 16px;
            margin-bottom: 12px;
            border-left: 5px solid #2e7d32;
        }
        .post-item .info { flex: 1; }
        .post-item .info .title { font-weight: 600; }
        .post-item .info .meta { font-size: 0.8rem; color: #6a7a8a; }
        .post-item .actions a {
            margin-left: 12px;
            padding: 6px 14px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .post-item .actions .edit { background: #e3f0fa; color: #0a58ca; }
        .post-item .actions .delete { background: #fce4e4; color: #b71c1c; }

        @media (max-width: 700px) {
            .admin-header { flex-direction: column; gap: 15px; text-align: center; }
            .admin-panel { padding: 20px; }
            .post-item { flex-direction: column; align-items: stretch; gap: 10px; }
        }
    </style>
</head>
<body>
<div class="container">

    <?php if (!isAdmin()): ?>
        <!-- ========== LOGIN FORM ========== -->
        <div class="login-box">
            <h2><i class="fas fa-lock"></i> Admin Login</h2>
            <?php if (isset($login_error)) echo '<div class="error">' . $login_error . '</div>'; ?>
            <form method="POST" action="?action=login">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit"><i class="fas fa-arrow-right-to-bracket"></i> Login</button>
            </form>
            <p style="text-align: center; margin-top: 20px; font-size: 0.9rem; color: #6a7a8a;">
                <a href="index.php" style="color: #0f3b2c;">← Back to website</a>
            </p>
        </div>
    <?php else: ?>
        <!-- ========== ADMIN DASHBOARD ========== -->
        <div class="admin-header">
            <h1><i class="fas fa-seedling" style="color: #2e7d32;"></i> Admin Dashboard</h1>
            <div>
                <a href="index.php" target="_blank" class="btn btn-success" style="margin-right:10px; background:#2e7d32;"><i class="fas fa-eye"></i> View Site</a>
                <a href="?action=logout" class="btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>

        <!-- CREATE / EDIT FORM -->
        <div class="admin-panel" id="admin-form">
            <h2><?= $edit_post ? '<i class="fas fa-pen"></i> Edit Post' : '<i class="fas fa-plus-circle"></i> Create New Post'; ?></h2>
            <p class="sub"><?= $edit_post ? 'Update the details below.' : 'Share a news, success story, or event.'; ?></p>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="edit_id" value="<?= $edit_post['id'] ?? 0; ?>">
                <div class="form-group">
                    <label>Title *</label>
                    <input type="text" name="title" value="<?= $edit_post['title'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Category *</label>
                    <select name="category" required>
                        <option value="news" <?= ($edit_post['category'] ?? '') == 'news' ? 'selected' : ''; ?>>📰 News</option>
                        <option value="story" <?= ($edit_post['category'] ?? '') == 'story' ? 'selected' : ''; ?>>🌟 Success Story</option>
                        <option value="event" <?= ($edit_post['category'] ?? '') == 'event' ? 'selected' : ''; ?>>📅 Event</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Content *</label>
                    <textarea name="content" required><?= $edit_post['content'] ?? ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Upload Images or Videos</label>
                    <input type="file" name="images[]" accept="image/*,video/*" multiple <?= $edit_post ? '' : 'required'; ?>>
                    <?php if ($edit_post): 
                        $imgs = json_decode($edit_post['images'], true);
                        if (!empty($imgs)) echo '<div class="hint">✅ Currently ' . count($imgs) . ' media file(s). Upload new ones to replace them.</div>';
                    endif; ?>
                    <div class="hint">Allowed: JPG, PNG, GIF, WEBP, MP4, WEBM, MOV. Hold Ctrl/Cmd to select multiple.</div>
                </div>
                <button type="submit" name="save_post" class="btn-primary"><i class="fas fa-save"></i> <?= $edit_post ? 'Update Post' : 'Publish Post'; ?></button>
                <?php if ($edit_post): ?>
                    <a href="admin.php" style="margin-left: 15px; color: #5a6a7a;">Cancel</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- POST LIST -->
        <div class="admin-panel">
            <h2><i class="fas fa-list"></i> All Posts</h2>
            <div class="post-list">
                <?php if (empty($posts)): ?>
                    <p style="color: #6a7a8a;">No posts created yet.</p>
                <?php else: ?>
                    <?php foreach ($posts as $p): ?>
                        <div class="post-item">
                            <div class="info">
                                <div class="title"><?= sanitize($p['title']); ?></div>
                                <div class="meta">
                                    <?= ucfirst($p['category']); ?> &bull; 
                                    <?= date('M d, Y', strtotime($p['created_at'])); ?>
                                </div>
                            </div>
                            <div class="actions">
                                <a href="?edit_id=<?= $p['id']; ?>#admin-form" class="edit"><i class="fas fa-edit"></i> Edit</a>
                                <a href="?delete_id=<?= $p['id']; ?>" class="delete" onclick="return confirm('Delete this post permanently?');"><i class="fas fa-trash-can"></i> Delete</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
<?php $conn->close(); ?>