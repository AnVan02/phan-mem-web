<?php
require_once 'admin/config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

if (!isset($_SESSION['khach_hang_id'])) {
    // If not logged in, just redirect back
    $ma_sp = (int) ($_POST['ma_san_pham'] ?? 0);
    if ($ma_sp > 0) {
        header("Location: chi-tiet-san-pham.php?id=$ma_sp&msg=loi_dang_nhap");
    } else {
        header("Location: index.php");
    }
    exit;
}

$ma_kh = $_SESSION['khach_hang_id'];
$ma_sp = (int) ($_POST['ma_san_pham'] ?? 0);
$so_sao = (int) ($_POST['so_sao'] ?? 5);
$noi_dung = trim($_POST['noi_dung'] ?? '');

if ($ma_sp <= 0 || $so_sao < 1 || $so_sao > 5 || $noi_dung === '') {
    header("Location: chi-tiet-san-pham.php?id=$ma_sp&msg=loi_thieu_thong_tin");
    exit;
}

// Optionally check if the user has bought the product
$dhct_stmt = $pdo->prepare("
    SELECT 1 FROM don_hang_chi_tiet dhct 
    JOIN don_hang dh ON dhct.ma_don_hang = dh.ma_don_hang 
    WHERE dh.ma_khach_hang = :kh AND dhct.ma_san_pham = :sp AND dh.trang_thai = 3 
    LIMIT 1
");
$dhct_stmt->execute([':kh' => $ma_kh, ':sp' => $ma_sp]);
$da_mua = $dhct_stmt->fetch();

if (!$da_mua) {
    header("Location: chi-tiet-san-pham.php?id=$ma_sp&msg=loi_chua_mua");
    exit;
}

try {
    $ins = $pdo->prepare("INSERT INTO danh_gia_san_pham (ma_san_pham, ma_khach_hang, so_sao, noi_dung) VALUES (:sp, :kh, :sao, :nd)");
    $ins->execute([
        ':sp' => $ma_sp,
        ':kh' => $ma_kh,
        ':sao' => $so_sao,
        ':nd' => $noi_dung
    ]);
    header("Location: chi-tiet-san-pham.php?id=$ma_sp&msg=danh_gia_thanh_cong");
} catch (PDOException $e) {
    header("Location: chi-tiet-san-pham.php?id=$ma_sp&msg=loi_he_thong");
}
exit;
