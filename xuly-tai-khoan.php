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
    $ins = $pdo->prepare("INSERT INTO khach_hang_lien_he (customer_name, customer_email, customer_phone, customer_address, mat_khau)
        VALUES (:ten, :email, :sdt, :dia_chi, :mk)");
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

if ($action === 'cap_nhat_thong_tin') {
    if (!isset($_SESSION['khach_hang_id'])) {
        header('Location: tai-khoan.php');
        exit;
    }

    $ten = trim($_POST['customer_name'] ?? '');
    $sdt = trim($_POST['customer_phone'] ?? '');
    $dia_chi = trim($_POST['customer_address'] ?? '');
    $ma_kh = $_SESSION['khach_hang_id'];

    if ($ten === '' || $sdt === '' || $dia_chi === '') {
        header('Location: tai-khoan.php?msg=loi_thieu_du_lieu');
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE khach_hang_lien_he SET customer_name = :ten, customer_phone = :sdt, customer_address = :dc WHERE ma_lien_he = :id");
        $stmt->execute([
            ':ten' => $ten,
            ':sdt' => $sdt,
            ':dc' => $dia_chi,
            ':id' => $ma_kh
        ]);
        $_SESSION['khach_hang_ten'] = $ten;
        header('Location: tai-khoan.php?msg=cap_nhat_thanh_cong');
    } catch (PDOException $e) {
        header('Location: tai-khoan.php?msg=loi_cap_nhat');
    }
    exit;
}

if ($action === 'doi_mat_khau') {
    if (!isset($_SESSION['khach_hang_id'])) {
        header('Location: tai-khoan.php');
        exit;
    }
    $mk_cu = (string) ($_POST['mat_khau_cu'] ?? '');
    $mk_moi = (string) ($_POST['mat_khau_moi'] ?? '');
    $ma_kh = $_SESSION['khach_hang_id'];

    if ($mk_cu === '' || $mk_moi === '') {
        header('Location: tai-khoan.php?msg=loi_thieu_du_lieu');
        exit;
    }
    if (mb_strlen($mk_moi) < 6) {
        header('Location: tai-khoan.php?msg=loi_mat_khau_ngan');
        exit;
    }

    $stmt = $pdo->prepare("SELECT mat_khau FROM khach_hang_lien_he WHERE ma_lien_he = :id LIMIT 1");
    $stmt->execute([':id' => $ma_kh]);
    $kh = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$kh || !password_verify($mk_cu, $kh['mat_khau'])) {
        header('Location: tai-khoan.php?msg=loi_mk_cu_sai');
        exit;
    }

    $mk_ma_hoa = password_hash($mk_moi, PASSWORD_DEFAULT);
    $upd = $pdo->prepare("UPDATE khach_hang_lien_he SET mat_khau = :mk WHERE ma_lien_he = :id");
    $upd->execute([':mk' => $mk_ma_hoa, ':id' => $ma_kh]);

    header('Location: tai-khoan.php?msg=doi_mk_thanh_cong');
    exit;
}

if ($action === 'gui_ho_tro') {
    if (!isset($_SESSION['khach_hang_id'])) {
        header("Location: tai-khoan.php?tab=dang-nhap");
        exit;
    }

    $chu_de = trim($_POST['chu_de'] ?? '');
    $noi_dung = trim($_POST['noi_dung'] ?? '');

    if ($chu_de === '' || $noi_dung === '') {
        header("Location: tai-khoan.php?msg=loi_he_thong");
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO ho_tro_khach_hang (ma_khach_hang, chu_de, noi_dung) VALUES (:kh, :cd, :nd)");
        $stmt->execute([
            ':kh' => $_SESSION['khach_hang_id'],
            ':cd' => $chu_de,
            ':nd' => $noi_dung
        ]);
        header("Location: tai-khoan.php?msg=gui_ho_tro_thanh_cong");
    } catch (PDOException $e) {
        header("Location: tai-khoan.php?msg=loi_he_thong");
    }
    exit;
}

header("Location: index.php");
exit;
