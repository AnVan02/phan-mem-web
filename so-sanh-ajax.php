<?php
require_once 'admin/config/config.php';
header('Content-Type: application/json; charset=utf-8');

function format_gia($gia_ban, $giam_gia) {
    $gia_ban  = (int) $gia_ban;
    $giam_gia = (int) $giam_gia;
    if ($gia_ban <= 0) {
        return 'Liên hệ';
    }
    $gia_sau_giam = $giam_gia > 0 ? (int) round($gia_ban * (100 - $giam_gia) / 100) : $gia_ban;
    return number_format($gia_sau_giam, 0, ',', '.') . '₫';
}

function first_image($hinh_anh) {
    $images = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', (string) $hinh_anh))));
    return !empty($images) ? $images[0] : 'assets/image/pc.webp';
}

$action = $_GET['action'] ?? '';

if ($action === 'search') {
    $q = trim($_GET['q'] ?? '');
    $exclude = (int) ($_GET['exclude'] ?? 0);
    $ma_danh_muc = (int) ($_GET['danh_muc'] ?? 0);

    if (mb_strlen($q) < 2) {
        echo json_encode([]);
        exit;
    }

    $stmt = $pdo->prepare("SELECT sp.ma_san_pham, sp.ten_san_pham, sp.hinh_anh, sp.gia_ban, sp.giam_gia, sp.ma_danh_muc, th.ten_thuong_hieu
        FROM san_pham sp
        LEFT JOIN thuong_hieu th ON sp.ma_thuong_hieu = th.ma_thuong_hieu
        WHERE sp.trang_thai = 1 AND sp.ma_san_pham != :exclude AND sp.ten_san_pham LIKE :q
        ORDER BY (sp.ma_danh_muc = :danh_muc) DESC, sp.ma_san_pham DESC
        LIMIT 8");
    $stmt->execute([
        ':exclude'    => $exclude,
        ':q'          => '%' . $q . '%',
        ':danh_muc'   => $ma_danh_muc,
    ]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = array_map(function ($r) {
        return [
            'id'             => (int) $r['ma_san_pham'],
            'ten_san_pham'   => $r['ten_san_pham'],
            'hinh_anh'       => first_image($r['hinh_anh']),
            'gia_display'    => format_gia($r['gia_ban'], $r['giam_gia']),
            'ten_thuong_hieu'=> $r['ten_thuong_hieu'],
        ];
    }, $rows);

    echo json_encode($result);
    exit;
}

if ($action === 'detail') {
    $id = (int) ($_GET['id'] ?? 0);

    $stmt = $pdo->prepare("SELECT sp.*, th.ten_thuong_hieu, dl.ten_dung_luong
        FROM san_pham sp
        LEFT JOIN thuong_hieu th ON sp.ma_thuong_hieu = th.ma_thuong_hieu
        LEFT JOIN dung_luong dl ON sp.ma_dung_luong = dl.ma_dung_luong
        WHERE sp.ma_san_pham = :id AND sp.trang_thai = 1
        LIMIT 1");
    $stmt->execute([':id' => $id]);
    $sp = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sp) {
        echo json_encode(['error' => 'not_found']);
        exit;
    }

    echo json_encode([
        'id'              => (int) $sp['ma_san_pham'],
        'ten_san_pham'    => $sp['ten_san_pham'],
        'hinh_anh'        => first_image($sp['hinh_anh']),
        'gia_display'     => format_gia($sp['gia_ban'], $sp['giam_gia']),
        'ten_thuong_hieu' => $sp['ten_thuong_hieu'] ?? '',
        'ten_dung_luong'  => $sp['ten_dung_luong'] ?? '',
        'thong_so'        => $sp['thong-so'] ?? '',
        'url'             => tao_url_san_pham($sp['ma_san_pham'], $sp['ten_san_pham']),
    ]);
    exit;
}

echo json_encode(['error' => 'invalid_action']);
