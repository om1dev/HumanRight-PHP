<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = sanitize($_POST['id'] ?? '');
    $hours = (int)($_POST['hours'] ?? 0);

    try {
        $oid = new MongoDB\BSON\ObjectId($id);
        if ($hours === 0) {
            // Unsuspend
            $db->users->updateOne(
                ['_id' => $oid],
                ['$unset' => ['suspended_until' => '']]
            );
            flash('success', 'User has been unsuspended.');
        } else {
            $until = new MongoDB\BSON\UTCDateTime((time() + $hours * 3600) * 1000);
            $db->users->updateOne(
                ['_id' => $oid],
                ['$set' => ['suspended_until' => $until]]
            );
            $label = $hours < 24 ? "{$hours} hour(s)" : "1 day";
            flash('success', "User suspended for {$label}.");
        }
    } catch (Exception $e) {
        flash('error', 'Action failed. Please try again.');
    }
}

header('Location: ' . SITE_URL . '/admin/users/index.php');
exit;
