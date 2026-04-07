<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = sanitize($_POST['id'] ?? '');
    try {
        $db->contacts->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
        flash('success', 'Message deleted.');
    } catch (Exception $e) {
        flash('error', 'Could not delete message.');
    }
}
header('Location: ' . SITE_URL . '/admin/messages/');
exit;
