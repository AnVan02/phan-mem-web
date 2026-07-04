<?php
    require_once '../config/config.php';

    function upload_anh_san_pham($files) {
        if (!isset($files) || !isset($files['name']) || !is_array($files['name'])) {
            return [];
        }

        $duoi_hop_le = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $thu_muc = '../../assets/uploads/san-pham/';
        if (!is_dir($thu_muc)) {
            mkdir($thu_muc, 0755, true);
        }

        $duong_dan = [];
        foreach ($files['name'] as $i => $ten_goc) {
            if ($ten_goc === '' || $files['error'][$i] === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                return false;
            }

            $duoi = strtolower(pathinfo($ten_goc, PATHINFO_EXTENSION));
            if (!in_array($duoi, $duoi_hop_le, true)) {
                return false;
            }

            $ten_file = uniqid('san-pham-', true) . '.' . $duoi;
            if (!move_uploaded_file($files['tmp_name'][$i], $thu_muc . $ten_file)) {
                return false;
            }

            $duong_dan[] = 'assets/uploads/san-pham/' . $ten_file;
        }

        return $duong_dan;
    }

    $action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

    if ($action === 'them' || $action === 'sua') {
        $ma_san_pham = (int) ($_POST['ma_san_pham'] ?? 0);
        $trang_loi = $action === 'sua' ? 'sua-san-pham.php?id=' . $ma_san_pham . '&' : 'them-san-pham.php?';

        $ten_san_pham = trim($_POST['ten_san_pham'] ?? '');
        $ma_danh_muc = (int) ($_POST['ma_danh_muc'] ?? 0);
        $ma_thuong_hieu = (int) ($_POST['ma_thuong_hieu'] ?? 0);
        $ma_dung_luong = (int) ($_POST['ma_dung_luong'] ?? 0);
        $so_luong = max(0, (int) ($_POST['so_luong'] ?? 0));
        $da_ban = max(0, (int) ($_POST['da_ban'] ?? 0));
        $gia_nhap = max(0, (int) ($_POST['gia_nhap'] ?? 0));
        $gia_ban = max(0, (int) ($_POST['gia_ban'] ?? 0));
        $giam_gia = min(100, max(0, (int) ($_POST['giam_gia'] ?? 0)));
        $mo_ta = $_POST['mo_ta'] ?? '';
        $thong_so = $_POST['thong_so'] ?? '';
        $anh_cu = trim($_POST['anh_hien_tai'] ?? '');

        if ($ten_san_pham === '' || $ma_danh_muc <= 0 || ($action === 'sua' && $ma_san_pham <= 0)) {
            header('Location: ' . $trang_loi . 'msg=loi_thieu_du_lieu');
            exit;
        }

        $anh_upload = upload_anh_san_pham($_FILES['hinh_anh_files'] ?? null);
        if ($anh_upload === false) {
            header('Location: ' . $trang_loi . 'msg=loi_anh');
            exit;
        }

        $anh_nhap_tay = trim($_POST['hinh_anh'] ?? $anh_cu);
        $danh_sach_anh = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', $anh_nhap_tay))));
        $danh_sach_anh = array_merge($danh_sach_anh, $anh_upload);
        $anh = implode(',', $danh_sach_anh);

        if ($action === 'them') {
            $stmt = $pdo->prepare("INSERT INTO san_pham
                (ten_san_pham, ma_danh_muc, ma_thuong_hieu, ma_dung_luong, so_luong, da_ban, gia_nhap, gia_ban, giam_gia, mo_ta, `thong-so`, hinh_anh, trang_thai)
                VALUES (:ten, :danh_muc, :thuong_hieu, :dung_luong, :so_luong, 0, :gia_nhap, :gia_ban, :giam_gia, :mo_ta, :thong_so, :hinh_anh, :trang_thai)");
            $stmt->execute([
                ':ten'          => $ten_san_pham,
                ':danh_muc'     => $ma_danh_muc,
                ':thuong_hieu'  => $ma_thuong_hieu,
                ':dung_luong'   => $ma_dung_luong,
                ':so_luong'     => $so_luong,
                ':gia_nhap'     => $gia_nhap,
                ':gia_ban'      => $gia_ban,
                ':giam_gia'     => $giam_gia,
                ':mo_ta'        => $mo_ta,
                ':thong_so'     => $thong_so,
                ':hinh_anh'     => $anh,
                ':trang_thai'   => isset($_POST['trang_thai']) ? 1 : 0,
            ]);
            header('Location: danh-sach-san-pham.php?msg=da_them');
            exit;
        }

        $stmt = $pdo->prepare("UPDATE san_pham SET
                ten_san_pham = :ten,
                ma_danh_muc = :danh_muc,
                ma_thuong_hieu = :thuong_hieu,
                ma_dung_luong = :dung_luong,
                so_luong = :so_luong,
                da_ban = :da_ban,
                gia_nhap = :gia_nhap,
                gia_ban = :gia_ban,
                giam_gia = :giam_gia,
                mo_ta = :mo_ta,
                `thong-so` = :thong_so,
                hinh_anh = :hinh_anh,
                trang_thai = :trang_thai
            WHERE ma_san_pham = :id");
        $stmt->execute([
            ':ten'          => $ten_san_pham,
            ':danh_muc'     => $ma_danh_muc,
            ':thuong_hieu'  => $ma_thuong_hieu,
            ':dung_luong'   => $ma_dung_luong,
            ':so_luong'     => $so_luong,
            ':da_ban'       => $da_ban,
            ':gia_nhap'     => $gia_nhap,
            ':gia_ban'      => $gia_ban,
            ':giam_gia'     => $giam_gia,
            ':mo_ta'        => $mo_ta,
            ':thong_so'     => $thong_so,
            ':hinh_anh'     => $anh,
            ':trang_thai'   => isset($_POST['trang_thai']) ? 1 : 0,
            ':id'           => $ma_san_pham,
        ]);
        header('Location: danh-sach-san-pham.php?msg=da_sua');
        exit;
    }

    header('Location: danh-sach-san-pham.php');
    exit;
