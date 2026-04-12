<?php
require_once __DIR__ . '/env.php';

define('CLOUDINARY_CLOUD_NAME', getenv('CLOUDINARY_CLOUD_NAME') ?: '');
define('CLOUDINARY_API_KEY',    getenv('CLOUDINARY_API_KEY')    ?: '');
define('CLOUDINARY_API_SECRET', getenv('CLOUDINARY_API_SECRET') ?: '');

function uploadToCloudinary(string $filePath, string $folder = 'humanrights/users'): string {
    $timestamp = time();
    $params    = ['folder' => $folder, 'timestamp' => $timestamp];
    ksort($params);
    $sigParts  = [];
    foreach ($params as $k => $v) {
        $sigParts[] = $k . '=' . $v;
    }
    $signature = sha1(implode('&', $sigParts) . CLOUDINARY_API_SECRET);

    $postFields = [
        'file'      => new CURLFile($filePath),
        'folder'    => $folder,
        'timestamp' => $timestamp,
        'api_key'   => CLOUDINARY_API_KEY,
        'signature' => $signature,
    ];

    $ch = curl_init('https://api.cloudinary.com/v1_1/' . CLOUDINARY_CLOUD_NAME . '/image/upload');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $postFields,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT        => 60,
    ]);
    $response = curl_exec($ch);
    $curlErr  = curl_error($ch);
    curl_close($ch);

    if ($curlErr) throw new Exception('cURL error: ' . $curlErr);
    $data = json_decode($response, true);
    if (empty($data['secure_url'])) {
        throw new Exception('Cloudinary error: ' . ($data['error']['message'] ?? $response));
    }
    return $data['secure_url'];
}
