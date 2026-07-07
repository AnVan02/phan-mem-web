<?php
require_once 'admin/config/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['khach_hang_id'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để sử dụng tính năng này']);
    exit;
}

$ma_kh = $_SESSION['khach_hang_id'];
$action = $_POST['action'] ?? '';
$ma_sp = (int) ($_POST['ma_san_pham'] ?? 0);

if ($ma_sp <= 0) {
    echo json_encode(['success' => false, 'message' => 'Sản phẩm không hợp lệ']);
    exit;
}

if ($action === 'toggle') {
    $check = $pdo->prepare("SELECT ma_yeu_thich FROM san_pham_yeu_thich WHERE ma_khach_hang = :kh AND ma_san_pham = :sp LIMIT 1");
    $check->execute([':kh' => $ma_kh, ':sp' => $ma_sp]);
    $exists = $check->fetch();
    
    if ($exists) {
        $del = $pdo->prepare("DELETE FROM san_pham_yeu_thich WHERE ma_khach_hang = :kh AND ma_san_pham = :sp");
        $del->execute([':kh' => $ma_kh, ':sp' => $ma_sp]);
        echo json_encode(['success' => true, 'status' => 'removed', 'message' => 'Đã xoá khỏi danh sách yêu thích']);
    } else {
        $ins = $pdo->prepare("INSERT INTO san_pham_yeu_thich (ma_khach_hang, ma_san_pham) VALUES (:kh, :sp)");
        $ins->execute([':kh' => $ma_kh, ':sp' => $ma_sp]);
        echo json_encode(['success' => true, 'status' => 'added', 'message' => 'Đã thêm vào danh sách yêu thích']);
    }
    exit;
}

if ($action === 'remove') {
    $del = $pdo->prepare("DELETE FROM san_pham_yeu_thich WHERE ma_khach_hang = :kh AND ma_san_pham = :sp");
    $del->execute([':kh' => $ma_kh, ':sp' => $ma_sp]);
    echo json_encode(['success' => true, 'message' => 'Đã xoá khỏi danh sách yêu thích']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ']);
