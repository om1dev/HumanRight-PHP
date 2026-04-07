<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = sanitize($_POST['id'] ?? '');
    try {
        $db->comments->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($id)],
            ['$set' => ['approved' => true]]
        );
        flash('success', 'Comment approved.');
    } catch (Exception $e) {
        flash('error', 'Could not approve comment.');
    }
}
header('Location: ' . SITE_URL . '/admin/comments/');
exit;
