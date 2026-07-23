<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_DON_HANG], '../dang-nhap.php');

    $action = $_GET['action'] ?? '';

    if ($action === 'xoa') {
        $ma_don_hang = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($ma_don_hang > 0) {
            $del = $pdo->prepare("DELETE FROM don_hang WHERE ma_don_hang = :id");
            $del->execute([':id' => $ma_don_hang]);
            ghi_nhat_ky($pdo, 'xoa', 'don_hang', $ma_don_hang, "Xoá đơn hàng #$ma_don_hang");
        }
        header('Location: don-hang.php?msg=da_xoa');
        exit;
    }

    $ma_don_hang = isset($_POST['ma_don_hang']) ? (int) $_POST['ma_don_hang'] : 0;
    $trang_thai  = isset($_POST['trang_thai']) ? (int) $_POST['trang_thai'] : 0;

    if ($ma_don_hang > 0 && $trang_thai >= 0 && $trang_thai <= 4) {
        $upd = $pdo->prepare("UPDATE don_hang SET trang_thai = :tt WHERE ma_don_hang = :id");
        $upd->execute([':tt' => $trang_thai, ':id' => $ma_don_hang]);
        ghi_nhat_ky($pdo, 'sua', 'don_hang', $ma_don_hang, "Cập nhật trạng thái đơn hàng #$ma_don_hang thành mã trạng thái $trang_thai");
        header('Location: chi-tiet-don-hang.php?id=' . $ma_don_hang . '&msg=da_cap_nhat');
        exit;
    }

    header('Location: don-hang.php');
    exit;
