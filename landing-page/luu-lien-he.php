<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    exit;
}

$fullname = trim($_POST['fullname'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$email    = trim($_POST['email'] ?? '');
$company  = trim($_POST['company'] ?? '');
$message  = trim($_POST['message'] ?? '');

if ($fullname === '' || $phone === '' || $email === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin bắt buộc']);
    exit;
}

$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

$line = sprintf(
    "[%s] Họ tên: %s | SĐT: %s | Email: %s | Công ty: %s | Nhu cầu: %s%s",
    date('Y-m-d H:i:s'),
    str_replace(["\r", "\n"], ' ', $fullname),
    str_replace(["\r", "\n"], ' ', $phone),
    str_replace(["\r", "\n"], ' ', $email),
    str_replace(["\r", "\n"], ' ', $company !== '' ? $company : '-'),
    str_replace(["\r", "\n"], ' ', $message !== '' ? $message : '-'),
    PHP_EOL
);

file_put_contents($dataDir . '/lien-he.txt', $line, FILE_APPEND | LOCK_EX);

echo json_encode(['success' => true]);
