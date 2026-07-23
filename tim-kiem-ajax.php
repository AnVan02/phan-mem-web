<?php
require_once 'admin/config/config.php';
header('Content-Type: application/json; charset=utf-8');

function format_gia_tim_kiem($gia_ban, $giam_gia) {
    $gia_ban  = (int) $gia_ban;
    $giam_gia = (int) $giam_gia;
    if ($gia_ban <= 0) {
        return 'Liên hệ';
    }
    $gia_sau_giam = $giam_gia > 0 ? (int) round($gia_ban * (100 - $giam_gia) / 100) : $gia_ban;
    return number_format($gia_sau_giam, 0, ',', '.') . '₫';
}

function first_image_tim_kiem($hinh_anh) {
    $images = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', (string) $hinh_anh))));
    return !empty($images) ? $images[0] : 'assets/image/pc.webp';
}

$action = $_GET['action'] ?? '';

if ($action === 'suggest') {
    $q = trim($_GET['q'] ?? '');

    if (mb_strlen($q) < 2) {
        echo json_encode([]);
        exit;
    }

    $stmt = $pdo->prepare("SELECT sp.ma_san_pham, sp.ten_san_pham, sp.hinh_anh, sp.gia_ban, sp.giam_gia, th.ten_thuong_hieu
        FROM san_pham sp
        LEFT JOIN thuong_hieu th ON sp.ma_thuong_hieu = th.ma_thuong_hieu
        LEFT JOIN danh_muc dm ON sp.ma_danh_muc = dm.ma_danh_muc
        WHERE sp.trang_thai = 1
          AND (sp.ten_san_pham LIKE :q1
               OR sp.mo_ta LIKE :q2
               OR th.ten_thuong_hieu LIKE :q3
               OR dm.ten_danh_muc LIKE :q4
               OR sp.ma_san_pham = :id)
        ORDER BY (sp.ten_san_pham LIKE :q5) DESC, sp.da_ban DESC, sp.ma_san_pham DESC
        LIMIT 8");
    $like = '%' . $q . '%';
    $stmt->execute([
        ':q1' => $like,
        ':q2' => $like,
        ':q3' => $like,
        ':q4' => $like,
        ':q5' => $like,
        ':id' => ctype_digit($q) ? (int) $q : 0,
    ]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = array_map(function ($r) {
        return [
            'id'              => (int) $r['ma_san_pham'],
            'ten_san_pham'    => $r['ten_san_pham'],
            'hinh_anh'        => first_image_tim_kiem($r['hinh_anh']),
            'gia_display'     => format_gia_tim_kiem($r['gia_ban'], $r['giam_gia']),
            'ten_thuong_hieu' => $r['ten_thuong_hieu'],
            'url'             => tao_url_san_pham($r['ma_san_pham'], $r['ten_san_pham']),
        ];
    }, $rows);

    echo json_encode($result);
    exit;
}

echo json_encode(['error' => 'invalid_action']);
