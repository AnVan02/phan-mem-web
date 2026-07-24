<?php
// image-proxy.php
$src = $_GET['src'] ?? '';
if (!$src || !filter_var($src, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    exit;
}

// Thư mục cache local
$cacheDir = __DIR__ . '/cache/images/';
if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0755, true);
}

$cacheFile = $cacheDir . md5($src) . '.img';
$cacheMetaFile = $cacheFile . '.meta';

// Nếu đã cache thì trả luôn từ local, khỏi gọi lại link gốc
if (file_exists($cacheFile) && file_exists($cacheMetaFile)) {
    $mime = file_get_contents($cacheMetaFile);
    header('Content-Type: ' . $mime);
    header('Cache-Control: public, max-age=86400');
    readfile($cacheFile);
    exit;
}

// Tải ảnh từ link gốc (chỉ server gọi, trình duyệt không thấy link này)
$ch = curl_init($src);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$imageData = curl_exec($ch);
$mime = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200 || !$imageData || strpos($mime, 'image/') !== 0) {
    http_response_code(404);
    exit;
}

file_put_contents($cacheFile, $imageData);
file_put_contents($cacheMetaFile, $mime);

header('Content-Type: ' . $mime);
header('Cache-Control: public, max-age=86400');
echo $imageData;