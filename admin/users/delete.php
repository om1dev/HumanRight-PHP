<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = sanitize($_POST['id'] ?? '');
    try {
        $oid = new MongoDB\BSON\ObjectId($id);
        $db->users->deleteOne(['_id' => $oid]);
        $db->comments->deleteMany(['user_id' => $id]);
        flash('success', 'User and their comments have been permanently deleted.');
    } catch (Exception $e) {
        flash('error', 'Could not delete user.');
    }
}

header('Location: ' . SITE_URL . '/admin/users/index.php');
exit;
