<?php
require_once 'admin/config/config.php';

$action = $_POST['action'] ?? '';

if ($action === 'dang_ky') {
    $ten     = trim($_POST['customer_name'] ?? '');
    $email   = trim($_POST['customer_email'] ?? '');
    $sdt     = trim($_POST['customer_phone'] ?? '');
    $dia_chi = trim($_POST['customer_address'] ?? '');
    $mat_khau       = (string) ($_POST['mat_khau'] ?? '');
    $mat_khau_nhac_lai = (string) ($_POST['mat_khau_nhac_lai'] ?? '');

    if ($ten === '' || $email === '' || $sdt === '' || $dia_chi === '' || $mat_khau === '') {
        header('Location: tai-khoan.php?msg=loi_thieu_du_lieu&tab=dang-ky');
        exit;
    }
    if (mb_strlen($mat_khau) < 6) {
        header('Location: tai-khoan.php?msg=loi_mat_khau_ngan&tab=dang-ky');
        exit;
    }
    if ($mat_khau !== $mat_khau_nhac_lai) {
        header('Location: tai-khoan.php?msg=loi_mat_khau_khong_khop&tab=dang-ky');
        exit;
    }

    $check = $pdo->prepare("SELECT ma_lien_he FROM khach_hang_lien_he WHERE customer_email = :email LIMIT 1");
    $check->execute([':email' => $email]);
    if ($check->fetch()) {
        header('Location: tai-khoan.php?msg=loi_email_ton_tai&tab=dang-ky');
        exit;
    }

    $mat_khau_ma_hoa = password_hash($mat_khau, PASSWORD_DEFAULT);
    $ins = $pdo->prepare("INSERT INTO khach_hang_lien_he (customer_name, customer_email, customer_phone, customer_address, mat_khau, trang_thai)
        VALUES (:ten, :email, :sdt, :dia_chi, :mk, 1)");
    $ins->execute([
        ':ten'   => $ten,
        ':email' => $email,
        ':sdt'   => $sdt,
        ':dia_chi' => $dia_chi,
        ':mk'    => $mat_khau_ma_hoa,
    ]);

    $_SESSION['khach_hang_id']  = (int) $pdo->lastInsertId();
    $_SESSION['khach_hang_ten'] = $ten;

    header('Location: tai-khoan.php?msg=dang_ky_thanh_cong');
    exit;
}

if ($action === 'dang_nhap') {
    $email    = trim($_POST['customer_email'] ?? '');
    $mat_khau = (string) ($_POST['mat_khau'] ?? '');

    $stmt = $pdo->prepare("SELECT * FROM khach_hang_lien_he WHERE customer_email = :email AND mat_khau IS NOT NULL LIMIT 1");
    $stmt->execute([':email' => $email]);
    $kh = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$kh || !password_verify($mat_khau, $kh['mat_khau'])) {
        header('Location: tai-khoan.php?msg=loi_dang_nhap&tab=dang-nhap');
        exit;
    }

    $_SESSION['khach_hang_id']  = (int) $kh['ma_lien_he'];
    $_SESSION['khach_hang_ten'] = $kh['customer_name'];

    header('Location: tai-khoan.php?msg=dang_nhap_thanh_cong');
    exit;
}

if ($action === 'dang_xuat') {
    unset($_SESSION['khach_hang_id'], $_SESSION['khach_hang_ten']);
    header('Location: tai-khoan.php');
    exit;
}

header('Location: tai-khoan.php');
exit;
