<?php
// ========== DATABASE CONFIGURATION ==========
$DB_HOST = 'localhost';
$DB_NAME = 'growafrica';
$DB_USER = 'evans';
$DB_PASS = 'YES'; // <-- CHANGE THIS

// ========== CONNECTION ==========
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// ========== HELPER FUNCTIONS ==========
function sanitize($input) {
    return htmlspecialchars(strip_tags($input), ENT_QUOTES, 'UTF-8');
}

// ====== NEW: Check if a file is a video ======
function isVideo($filepath) {
    $ext = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
    return in_array($ext, ['mp4', 'webm', 'ogg', 'mov', 'avi']);
}

// ---------- FETCH ALL POSTS (Newest first) ----------
function getPosts($conn) {
    $posts = [];
    $result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
    }
    return $posts;
}

// ---------- FETCH SINGLE POST ----------
function getPost($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    $stmt->close();
    return $post;
}

// ---------- CREATE / UPDATE POST ----------
function savePost($conn, $title, $category, $content, $images_json, $edit_id = 0) {
    $title = sanitize($title);
    $category = sanitize($category);
    $content = sanitize($content);

    if ($edit_id > 0) {
        $stmt = $conn->prepare("UPDATE posts SET title=?, category=?, content=?, images=? WHERE id=?");
        $stmt->bind_param("ssssi", $title, $category, $content, $images_json, $edit_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    } else {
        $stmt = $conn->prepare("INSERT INTO posts (title, category, content, images) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $category, $content, $images_json);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}

// ---------- DELETE POST ----------
function deletePost($conn, $id) {
    // First, fetch images to delete files from server
    $stmt = $conn->prepare("SELECT images FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $images = json_decode($row['images'], true);
        if (is_array($images)) {
            foreach ($images as $img) {
                if (file_exists($img)) unlink($img);
            }
        }
    }
    $stmt->close();

    // Now delete the record
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
?>