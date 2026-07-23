<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_DON_HANG], '../dang-nhap.php');

    $action = $_GET['action'] ?? '';

    if ($action === 'xoa') {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("SELECT customer_name, customer_email FROM khach_hang_lien_he WHERE ma_lien_he = :id");
            $stmt->execute([':id' => $id]);
            $kh_can_xoa = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $pdo->prepare("DELETE FROM khach_hang_lien_he WHERE ma_lien_he = :id");
            $stmt->execute([':id' => $id]);

            if ($kh_can_xoa) {
                ghi_nhat_ky($pdo, 'xoa', 'khach_hang', $id, "Xoá tài khoản khách hàng \"{$kh_can_xoa['customer_name']}\" ({$kh_can_xoa['customer_email']})");
            }
        }
        header('Location: danh-sach.php?msg=da_xoa');
        exit;
    }

    header('Location: danh-sach.php');
    exit;
