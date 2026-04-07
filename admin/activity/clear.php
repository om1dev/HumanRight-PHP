<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db->activity->deleteMany([]);
    flash('success', 'Activity log cleared.');
}
header('Location: ' . SITE_URL . '/admin/activity/');
exit;
