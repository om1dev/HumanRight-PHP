<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id   = sanitize($_POST['id'] ?? '');
    $read = (bool)(int)($_POST['read'] ?? 1);
    try {
        $db->contacts->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($id)],
            ['$set' => ['read' => $read]]
        );
    } catch (Exception $e) {}
}
header('Location: ' . SITE_URL . '/admin/messages/');
exit;
