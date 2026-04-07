<?php
require_once __DIR__ . '/../../includes/init.php';
requireAdmin();

$type = sanitize($_GET['type'] ?? '');

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $type . '_export_' . date('Y-m-d') . '.csv"');

$out = fopen('php://output', 'w');

if ($type === 'users') {
    fputcsv($out, ['Username', 'Email', 'Joined', 'Status']);
    $users = $db->users->find([], ['sort' => ['created_at' => -1]])->toArray();
    foreach ($users as $u) {
        $suspended = !empty($u['suspended_until']) && $u['suspended_until']->toDateTime()->getTimestamp() > time();
        fputcsv($out, [
            $u['username'],
            $u['email'],
            date('Y-m-d', $u['created_at']->toDateTime()->getTimestamp()),
            $suspended ? 'Suspended' : 'Active',
        ]);
    }
} elseif ($type === 'blogs') {
    fputcsv($out, ['Title', 'Category', 'Status', 'Created']);
    $blogs = $db->blogs->find([], ['sort' => ['created_at' => -1]])->toArray();
    foreach ($blogs as $b) {
        fputcsv($out, [
            $b['title'],
            $b['category'] ?? '',
            $b['published'] ? 'Published' : 'Draft',
            date('Y-m-d', $b['created_at']->toDateTime()->getTimestamp()),
        ]);
    }
} elseif ($type === 'messages') {
    fputcsv($out, ['Name', 'Email', 'Subject', 'Message', 'Date', 'Read']);
    $msgs = $db->contacts->find([], ['sort' => ['created_at' => -1]])->toArray();
    foreach ($msgs as $m) {
        fputcsv($out, [
            $m['name'],
            $m['email'],
            $m['subject'] ?? '',
            $m['message'],
            date('Y-m-d H:i', $m['created_at']->toDateTime()->getTimestamp()),
            $m['read'] ? 'Yes' : 'No',
        ]);
    }
} else {
    fputcsv($out, ['Error']);
    fputcsv($out, ['Invalid export type.']);
}

fclose($out);
exit;
