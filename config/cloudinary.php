<?php
define('CLOUDINARY_CLOUD_NAME', 'deq5qb9vv');
define('CLOUDINARY_API_KEY',    '498657542632958');
define('CLOUDINARY_API_SECRET', 'OnD-3ARGFevxoFO7WKwJUHFo1-Q');

function uploadToCloudinary(string $filePath, string $folder = 'humanrights/users'): string {
    $timestamp = time();

    // Build signature string manually (no URL encoding of values)
    $params = [
        'folder'    => $folder,
        'timestamp' => $timestamp,
    ];
    ksort($params);

    // Cloudinary signature: key=value pairs joined by & then append secret
    $sigParts = [];
    foreach ($params as $k => $v) {
        $sigParts[] = $k . '=' . $v;
    }
    $sigString = implode('&', $sigParts) . CLOUDINARY_API_SECRET;
    $signature = sha1($sigString);

    // Build multipart POST
    $postFields = [
        'file'      => new CURLFile($filePath),
        'folder'    => $folder,
        'timestamp' => $timestamp,
        'api_key'   => CLOUDINARY_API_KEY,
        'signature' => $signature,
    ];

    $url = 'https://api.cloudinary.com/v1_1/' . CLOUDINARY_CLOUD_NAME . '/image/upload';

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $postFields,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT        => 60,
    ]);

    $response = curl_exec($ch);
    $curlErr  = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($curlErr) {
        throw new Exception('cURL error: ' . $curlErr);
    }

    $data = json_decode($response, true);

    if (empty($data['secure_url'])) {
        $msg = $data['error']['message'] ?? ('HTTP ' . $httpCode . ' — ' . $response);
        throw new Exception('Cloudinary error: ' . $msg);
    }

    return $data['secure_url'];
}
