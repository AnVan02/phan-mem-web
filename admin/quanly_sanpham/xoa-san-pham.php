<?php
    require_once '../config/config.php';

    $ma_san_pham = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $tro_ve      = isset($_GET['tro_ve']) ? trim($_GET['tro_ve']) : '';
    if ($ma_san_pham > 0) {
        $stmt = $pdo->prepare("DELETE FROM san_pham WHERE ma_san_pham = :id");
        $stmt->execute([':id' => $ma_san_pham]);
    }

    header('Location: danh-sach-san-pham.php?' . ($tro_ve !== '' ? $tro_ve . '&' : '') . 'msg=da_xoa');
    exit;
