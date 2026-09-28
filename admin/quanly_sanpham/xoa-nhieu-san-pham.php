<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_NOI_DUNG], '../dang-nhap.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids']) && is_array($_POST['ids'])) {
        $ids = array_map('intval', $_POST['ids']);
        $ids = array_filter($ids, function($id) { return $id > 0; });

        if (!empty($ids)) {
            $in_clause = implode(',', array_fill(0, count($ids), '?'));
            
            // Lấy tên để ghi nhật ký
            $stmt = $pdo->prepare("SELECT ma_san_pham, ten_san_pham FROM san_pham WHERE ma_san_pham IN ($in_clause)");
            $stmt->execute($ids);
            $names = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

            // Xoá sản phẩm
            $stmt = $pdo->prepare("DELETE FROM san_pham WHERE ma_san_pham IN ($in_clause)");
            $stmt->execute($ids);

            // Ghi nhật ký từng sản phẩm
            foreach ($names as $id => $ten) {
                ghi_nhat_ky($pdo, 'xoa', 'san_pham', $id, "Xoá sản phẩm \"$ten\" (từ chức năng xoá nhiều)");
            }
        }
    }

    header('Location: danh-sach-san-pham.php?msg=da_xoa');
    exit;
