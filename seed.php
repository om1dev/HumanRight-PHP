<?php
/**
 * Run this ONCE to seed the admin account.
 * Usage: php seed.php
 * Then DELETE or move this file.
 */
require_once __DIR__ . '/includes/init.php';

// Create admin
$existing = $db->admins->findOne(['email' => 'admin@humanrights.org']);
if (!$existing) {
    $db->admins->insertOne([
        'username'   => 'Admin',
        'email'      => 'admin@humanrights.org',
        'password'   => password_hash('Admin@1234', PASSWORD_BCRYPT),
        'created_at' => new MongoDB\BSON\UTCDateTime(),
    ]);
    echo "Admin created.\n  Email: admin@humanrights.org\n  Password: Admin@1234\n";
} else {
    echo "Admin already exists.\n";
}

// Create MongoDB indexes
$db->users->createIndex(['email' => 1], ['unique' => true]);
$db->blogs->createIndex(['slug' => 1],  ['unique' => true]);
$db->comments->createIndex(['blog_id' => 1]);
$db->contacts->createIndex(['created_at' => -1]);

echo "Indexes created.\n";
echo "DONE. Delete seed.php now.\n";
