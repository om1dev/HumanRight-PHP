<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = sanitize($_POST['id'] ?? '');
    try {
        $oid = new MongoDB\BSON\ObjectId($id);
        $db->blogs->deleteOne(['_id' => $oid]);
        $db->comments->deleteMany(['blog_id' => $id]);
        flash('success', 'Blog deleted.');
    } catch (Exception $e) {
        flash('error', 'Could not delete blog.');
    }
}
header('Location: ' . SITE_URL . '/admin/blogs/index.php');
exit;
