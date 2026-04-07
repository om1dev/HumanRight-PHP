<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if (preg_match('/\.(css|js|png|jpg|jpeg|gif|webp|ico|svg|woff|woff2|ttf)$/', $uri)) {
    return false;
}

$routes = [
    // Public
    '/'                            => '/index.php',
    '/about'                       => '/about.php',
    '/blog'                        => '/blog.php',
    '/contact'                     => '/contact.php',
    '/single-blog'                 => '/single-blog.php',
    // User Profile
    '/user/profile'                => '/user/profile.php',
    '/user/edit-profile'           => '/user/edit-profile.php',
    // Auth
    '/auth/login'                  => '/auth/login.php',
    '/auth/signup'                 => '/auth/signup.php',
    '/auth/logout'                 => '/auth/logout.php',
    // Admin core
    '/admin'                       => '/admin/index.php',
    '/admin/'                      => '/admin/index.php',
    '/admin/profile'               => '/admin/profile.php',
    '/admin/login'                 => '/admin/login.php',
    '/admin/logout'                => '/admin/logout.php',
    // Blogs
    '/admin/blogs'                 => '/admin/blogs/index.php',
    '/admin/blogs/'                => '/admin/blogs/index.php',
    '/admin/blogs/create'          => '/admin/blogs/create.php',
    '/admin/blogs/edit'            => '/admin/blogs/edit.php',
    '/admin/blogs/delete'          => '/admin/blogs/delete.php',
    // Events
    '/admin/events'                => '/admin/events/index.php',
    '/admin/events/'               => '/admin/events/index.php',
    // Categories
    '/admin/categories'            => '/admin/categories/index.php',
    '/admin/categories/'           => '/admin/categories/index.php',
    // Users
    '/admin/users'                 => '/admin/users/index.php',
    '/admin/users/'                => '/admin/users/index.php',
    '/admin/users/view'           => '/admin/users/view.php',
    '/admin/users/suspend'         => '/admin/users/suspend.php',
    '/admin/users/delete'          => '/admin/users/delete.php',
    // Comments
    '/admin/comments'              => '/admin/comments/index.php',
    '/admin/comments/'             => '/admin/comments/index.php',
    '/admin/comments/approve'      => '/admin/comments/approve.php',
    '/admin/comments/delete'       => '/admin/comments/delete.php',
    // Messages
    '/admin/messages'              => '/admin/messages/index.php',
    '/admin/messages/'             => '/admin/messages/index.php',
    '/admin/messages/toggle-read'  => '/admin/messages/toggle-read.php',
    '/admin/messages/delete'       => '/admin/messages/delete.php',
    // Admins
    '/admin/admins'                => '/admin/admins/index.php',
    '/admin/admins/'               => '/admin/admins/index.php',
    '/admin/admins/create'         => '/admin/admins/create.php',
    '/admin/admins/delete'         => '/admin/admins/delete.php',
    // Activity
    '/admin/activity'              => '/admin/activity/index.php',
    '/admin/activity/'             => '/admin/activity/index.php',
    '/admin/activity/clear'        => '/admin/activity/clear.php',
    // Settings
    '/admin/settings'              => '/admin/settings/index.php',
    '/admin/settings/'             => '/admin/settings/index.php',
    '/admin/settings/export'       => '/admin/settings/export.php',
];

$path = strtok($uri, '?');

if (isset($routes[$path])) {
    require __DIR__ . $routes[$path];
} elseif (file_exists(__DIR__ . $path . '.php')) {
    require __DIR__ . $path . '.php';
} elseif (file_exists(__DIR__ . $path)) {
    require __DIR__ . $path;
} else {
    http_response_code(404);
    include __DIR__ . '/includes/header.php';
    echo '<div class="max-w-xl mx-auto text-center py-32"><h1 class="text-6xl font-extrabold text-blue-900">404</h1><p class="text-gray-500 mt-4 text-lg">Page not found.</p><a href="/" class="mt-6 inline-block bg-blue-700 text-white px-6 py-3 rounded-full hover:bg-blue-600">Go Home</a></div>';
    include __DIR__ . '/includes/footer.php';
}
