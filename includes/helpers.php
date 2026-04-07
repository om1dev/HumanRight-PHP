<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function isAdmin(): bool {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: ' . SITE_URL . '/auth/login');
        exit;
    }
}

function requireAdmin(): void {
    if (!isAdmin()) {
        header('Location: ' . SITE_URL . '/admin/login');
        exit;
    }
}

function flash(string $key, string $msg = ''): string {
    if ($msg) {
        $_SESSION['flash'][$key] = $msg;
        return '';
    }
    $out = $_SESSION['flash'][$key] ?? '';
    unset($_SESSION['flash'][$key]);
    return $out;
}

function sanitize(?string $val): string {
    if ($val === null) return '';
    return htmlspecialchars(trim($val), ENT_QUOTES, 'UTF-8');
}

function slug(string $text): string {
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text), '-'));
}
