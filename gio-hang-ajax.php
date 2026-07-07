<?php
require_once 'admin/config/config.php';
header('Content-Type: application/json; charset=utf-8');

$session_id = session_id();

function dem_gio_hang(PDO $pdo, string $session_id): int
{
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(so_luong), 0) FROM gio_hang WHERE session_id = :sid");
    $stmt->execute([':sid' => $session_id]);
    return (int) $stmt->fetchColumn();
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'dem') {
    echo json_encode(['success' => true, 'cart_count' => dem_gio_hang($pdo, $session_id)]);
    exit;
}

if ($action === 'them') {
    $ma_san_pham = (int) ($_POST['ma_san_pham'] ?? 0);
    $so_luong_them = max(1, (int) ($_POST['so_luong'] ?? 1));

    $stmt = $pdo->prepare("SELECT ma_san_pham, so_luong, gia_ban FROM san_pham WHERE ma_san_pham = :id AND trang_thai = 1 LIMIT 1");
    $stmt->execute([':id' => $ma_san_pham]);
    $sp = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sp) {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại.']);
        exit;
    }

    if ((int) $sp['gia_ban'] <= 0) {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm này chưa có giá bán online, vui lòng liên hệ để đặt hàng.']);
        exit;
    }

    $ton_kho = (int) $sp['so_luong'];
    if ($ton_kho <= 0) {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm đã hết hàng.']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT ma_gio_hang, so_luong FROM gio_hang WHERE session_id = :sid AND ma_san_pham = :sp LIMIT 1");
    $stmt->execute([':sid' => $session_id, ':sp' => $ma_san_pham]);
    $dong_hien_co = $stmt->fetch(PDO::FETCH_ASSOC);

    $so_luong_moi = min($ton_kho, ($dong_hien_co ? (int) $dong_hien_co['so_luong'] : 0) + $so_luong_them);

    if ($dong_hien_co) {
        $upd = $pdo->prepare("UPDATE gio_hang SET so_luong = :sl WHERE ma_gio_hang = :id");
        $upd->execute([':sl' => $so_luong_moi, ':id' => $dong_hien_co['ma_gio_hang']]);
    } else {
        $ins = $pdo->prepare("INSERT INTO gio_hang (session_id, ma_san_pham, so_luong) VALUES (:sid, :sp, :sl)");
        $ins->execute([':sid' => $session_id, ':sp' => $ma_san_pham, ':sl' => $so_luong_moi]);
    }

    echo json_encode(['success' => true, 'message' => 'Đã thêm vào giỏ hàng.', 'cart_count' => dem_gio_hang($pdo, $session_id)]);
    exit;
}

if ($action === 'cap_nhat') {
    $ma_gio_hang = (int) ($_POST['ma_gio_hang'] ?? 0);
    $so_luong = max(1, (int) ($_POST['so_luong'] ?? 1));

    $stmt = $pdo->prepare("SELECT gh.ma_gio_hang, sp.so_luong AS ton_kho
        FROM gio_hang gh
        JOIN san_pham sp ON sp.ma_san_pham = gh.ma_san_pham
        WHERE gh.ma_gio_hang = :id AND gh.session_id = :sid LIMIT 1");
    $stmt->execute([':id' => $ma_gio_hang, ':sid' => $session_id]);
    $dong = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dong) {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm trong giỏ hàng.']);
        exit;
    }

    $so_luong = min($so_luong, max(1, (int) $dong['ton_kho']));

    $upd = $pdo->prepare("UPDATE gio_hang SET so_luong = :sl WHERE ma_gio_hang = :id");
    $upd->execute([':sl' => $so_luong, ':id' => $ma_gio_hang]);

    echo json_encode(['success' => true, 'so_luong' => $so_luong, 'cart_count' => dem_gio_hang($pdo, $session_id)]);
    exit;
}

if ($action === 'xoa') {
    $ma_gio_hang = (int) ($_POST['ma_gio_hang'] ?? 0);

    $del = $pdo->prepare("DELETE FROM gio_hang WHERE ma_gio_hang = :id AND session_id = :sid");
    $del->execute([':id' => $ma_gio_hang, ':sid' => $session_id]);

    echo json_encode(['success' => true, 'cart_count' => dem_gio_hang($pdo, $session_id)]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ.']);
