<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_DON_HANG], '../dang-nhap.php');

    $action = $_POST['action'] ?? $_GET['action'] ?? '';
    $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);

    if ($id > 0 && $action === 'xu_ly') {
        $phan_hoi = trim($_POST['phan_hoi'] ?? '');
        $stmt = $pdo->prepare("UPDATE ho_tro_khach_hang SET trang_thai = 1, phan_hoi = :ph, ngay_phan_hoi = NOW() WHERE ma_ho_tro = :id");
        $stmt->execute([':ph' => $phan_hoi !== '' ? $phan_hoi : null, ':id' => $id]);
        ghi_nhat_ky($pdo, 'sua', 'ho_tro_khach_hang', $id, 'Phản hồi và đánh dấu yêu cầu hỗ trợ đã xử lý');

        header('Location: ho-tro.php?msg=da_xu_ly');
        exit;
    }

    if ($id > 0 && $action === 'chua_xu_ly') {
        $stmt = $pdo->prepare("UPDATE ho_tro_khach_hang SET trang_thai = 0 WHERE ma_ho_tro = :id");
        $stmt->execute([':id' => $id]);
        ghi_nhat_ky($pdo, 'sua', 'ho_tro_khach_hang', $id, 'Chuyển yêu cầu hỗ trợ về chưa xử lý');

        header('Location: ho-tro.php?msg=chua_xu_ly');
        exit;
    }

    if ($id > 0 && $action === 'xoa') {
        $stmt = $pdo->prepare("DELETE FROM ho_tro_khach_hang WHERE ma_ho_tro = :id");
        $stmt->execute([':id' => $id]);
        ghi_nhat_ky($pdo, 'xoa', 'ho_tro_khach_hang', $id, 'Xoá yêu cầu hỗ trợ khách hàng');

        header('Location: ho-tro.php?msg=da_xoa');
        exit;
    }

    header('Location: ho-tro.php');
    exit;
