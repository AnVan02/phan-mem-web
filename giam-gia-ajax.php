<?php
require_once 'admin/config/config.php';
header('Content-Type: application/json');

$code = trim($_POST['code'] ?? '');

if (empty($code)) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập mã giảm giá']);
    exit;
}

$stmt = $pdo->prepare("SELECT phan_tram_giam FROM ma_giam_gia WHERE code = :code AND trang_thai = 1 LIMIT 1");
$stmt->execute([':code' => $code]);
$giam = $stmt->fetchColumn();

if ($giam !== false) {
    echo json_encode([
        'success' => true,
        'message' => "Áp dụng thành công! Giảm $giam%",
        'phan_tram_giam' => (int) $giam
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Mã giảm giá không tồn tại hoặc đã hết hạn']);
}
