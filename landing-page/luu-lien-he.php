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

$refererPage = basename(parse_url($_SERVER['HTTP_REFERER'] ?? '', PHP_URL_PATH) ?? '');
$sourceMap = [
    'landing.php' => 'landing',
    'ROSA-AI-CONNECT.php' => 'ai-connect',
    'ROSA-AI-WORKSPACE.php' => 'ai-workspace',
];
$source = $sourceMap[$refererPage] ?? 'khac';

mysqli_report(MYSQLI_REPORT_OFF);

$fail = function ($debugReason) {
    file_put_contents(__DIR__ . '/data/db-error.log', '[' . date('Y-m-d H:i:s') . '] ' . $debugReason . PHP_EOL, FILE_APPEND | LOCK_EX);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Không thể lưu thông tin, vui lòng thử lại sau.']);
    exit;
};

if (!is_dir(__DIR__ . '/data')) {
    mkdir(__DIR__ . '/data', 0755, true);
}

$mysqli = @new mysqli("localhost", "root", "", "vietson-achieva");

if ($mysqli->connect_errno) {
    $fail('connect: ' . $mysqli->connect_error);
}

$mysqli->set_charset('utf8mb4');

$stmt = $mysqli->prepare(
    'INSERT INTO dang_ky_tu_van (ho_ten, so_dien_thoai, email, ten_cong_ty, nhu_cau, trang_nguon)
     VALUES (?, ?, ?, ?, ?, ?)'
);

if ($stmt === false) {
    $fail('prepare: ' . $mysqli->error);
}

$companyValue = $company !== '' ? $company : null;
$messageValue = $message !== '' ? $message : null;
$stmt->bind_param('ssssss', $fullname, $phone, $email, $companyValue, $messageValue, $source);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    $fail('execute: ' . $stmt->error);
}

$stmt->close();
$mysqli->close();
