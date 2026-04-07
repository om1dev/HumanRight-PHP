<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = sanitize($_POST['id'] ?? '');

    // Prevent self-deletion
    if ($id === $_SESSION['user_id']) {
        flash('error', 'You cannot remove your own admin account.');
        header('Location: ' . SITE_URL . '/admin/admins/index.php');
        exit;
    }

    try {
        $db->admins->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
        flash('success', 'Admin removed successfully.');
    } catch (Exception $e) {
        flash('error', 'Could not remove admin.');
    }
}

header('Location: ' . SITE_URL . '/admin/admins/index.php');
exit;
