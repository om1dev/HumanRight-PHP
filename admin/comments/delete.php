<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = sanitize($_POST['id'] ?? '');
    try {
        $db->comments->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
        flash('success', 'Comment deleted.');
    } catch (Exception $e) {
        flash('error', 'Could not delete comment.');
    }
}
header('Location: ' . SITE_URL . '/admin/comments/index.php');
exit;
