<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_NOI_DUNG], '../dang-nhap.php');

    // Thư mục lưu ảnh banner (đường dẫn vật lý) và đường dẫn tương đối lưu vào DB
    define('THUMOC_LUU_BANNER_VAT_LY', __DIR__ . '/../../assets/image/thuong-hieu/');
    define('THUMOC_LUU_BANNER_DB', 'assets/image/thuong-hieu/');

    /**
     * Xử lý upload ảnh banner, trả về đường dẫn lưu DB.
     * null  = không có ảnh mới tải lên
     * false = có ảnh nhưng không hợp lệ
     */
    function xu_ly_upload_banner($file) {
        if (empty($file) || !isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $ext_hop_le = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $ext_hop_le, true)) {
            return false;
        }

        if (@getimagesize($file['tmp_name']) === false) {
            return false; // không phải file ảnh thật
        }

        if (!is_dir(THUMOC_LUU_BANNER_VAT_LY)) {
            mkdir(THUMOC_LUU_BANNER_VAT_LY, 0755, true);
        }

        $ten_file = 'banner_' . uniqid() . '_' . time() . '.' . $ext;
        if (!move_uploaded_file($file['tmp_name'], THUMOC_LUU_BANNER_VAT_LY . $ten_file)) {
            return false;
        }

        return THUMOC_LUU_BANNER_DB . $ten_file;
    }

    // Chỉ xoá file vật lý cũ nếu nó là ảnh tự lưu trên server (không phải link ngoài http/https)
    function xoa_banner_cu($duong_dan_db) {
        if (empty($duong_dan_db) || preg_match('#^https?://#i', $duong_dan_db)) {
            return;
        }
        $duong_dan_vat_ly = __DIR__ . '/../../' . $duong_dan_db;
        if (is_file($duong_dan_vat_ly)) {
            @unlink($duong_dan_vat_ly);
        }
    }

    $action = $_POST['action'] ?? $_GET['action'] ?? '';

    if ($action === 'them') {
        $ten = trim($_POST['ten_thuong_hieu'] ?? '');
        if ($ten === '') {
            header('Location: banner.php?msg=loi_thieu_ten');
            exit;
        }

        $banner_url = trim($_POST['banner_url'] ?? '');
        $noi_dung   = trim($_POST['noi_dung_banner'] ?? '');

        $banner = $banner_url !== '' ? $banner_url : null;
        if (!empty($_FILES['banner_file']['name'])) {
            $ket_qua = xu_ly_upload_banner($_FILES['banner_file']);
            if ($ket_qua === false) {
                header('Location: banner.php?msg=loi_anh');
                exit;
            }
            $banner = $ket_qua;
        }

        $stmt = $pdo->prepare("INSERT INTO thuong_hieu (ten_thuong_hieu, banner, noi_dung_banner) VALUES (:ten, :banner, :noi_dung)");
        $stmt->execute([
            ':ten'      => $ten,
            ':banner'   => $banner,
            ':noi_dung' => $noi_dung !== '' ? $noi_dung : null,
        ]);
        ghi_nhat_ky($pdo, 'them', 'banner', (int) $pdo->lastInsertId(), "Thêm thương hiệu/banner \"$ten\"");

        header('Location: banner.php?msg=da_them');
        exit;
    }

    if ($action === 'sua') {
        $id  = (int) ($_POST['id'] ?? 0);
        $ten = trim($_POST['ten_thuong_hieu'] ?? '');
        if ($id <= 0 || $ten === '') {
            header('Location: banner.php?msg=loi_thieu_ten');
            exit;
        }

        $banner_url = trim($_POST['banner_url'] ?? '');
        $noi_dung   = trim($_POST['noi_dung_banner'] ?? '');
        $banner     = $banner_url !== '' ? $banner_url : null;

        if (!empty($_FILES['banner_file']['name'])) {
            $ket_qua = xu_ly_upload_banner($_FILES['banner_file']);
            if ($ket_qua === false) {
                header('Location: banner.php?msg=loi_anh');
                exit;
            }
            $banner = $ket_qua;
        }

        $stmt = $pdo->prepare("SELECT banner FROM thuong_hieu WHERE ma_thuong_hieu = :id");
        $stmt->execute([':id' => $id]);
        $banner_cu = $stmt->fetchColumn();

        $stmt = $pdo->prepare("UPDATE thuong_hieu SET ten_thuong_hieu = :ten, banner = :banner, noi_dung_banner = :noi_dung WHERE ma_thuong_hieu = :id");
        $stmt->execute([
            ':ten'      => $ten,
            ':banner'   => $banner,
            ':noi_dung' => $noi_dung !== '' ? $noi_dung : null,
            ':id'       => $id,
        ]);

        if ($banner_cu && $banner_cu !== $banner) {
            xoa_banner_cu($banner_cu);
        }

        ghi_nhat_ky($pdo, 'sua', 'banner', $id, "Sửa thương hiệu/banner \"$ten\"");
        header('Location: banner.php?msg=da_sua');
        exit;
    }

    if ($action === 'xoa_banner') {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("SELECT banner, ten_thuong_hieu FROM thuong_hieu WHERE ma_thuong_hieu = :id");
            $stmt->execute([':id' => $id]);
            $tk = $stmt->fetch(PDO::FETCH_ASSOC);
            $banner_cu = $tk['banner'] ?? null;

            $stmt = $pdo->prepare("UPDATE thuong_hieu SET banner = NULL, noi_dung_banner = NULL WHERE ma_thuong_hieu = :id");
            $stmt->execute([':id' => $id]);

            xoa_banner_cu($banner_cu);
            ghi_nhat_ky($pdo, 'sua', 'banner', $id, "Gỡ banner của thương hiệu \"" . ($tk['ten_thuong_hieu'] ?? '') . "\"");
        }
        header('Location: banner.php?msg=da_xoa_banner');
        exit;
    }

    if ($action === 'xoa') {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM san_pham WHERE ma_thuong_hieu = :id");
            $stmt->execute([':id' => $id]);
            $so_dung = (int) $stmt->fetchColumn();

            if ($so_dung > 0) {
                header('Location: banner.php?msg=loi_dang_su_dung');
                exit;
            }

            $stmt = $pdo->prepare("SELECT banner, ten_thuong_hieu FROM thuong_hieu WHERE ma_thuong_hieu = :id");
            $stmt->execute([':id' => $id]);
            $tk = $stmt->fetch(PDO::FETCH_ASSOC);
            $banner_cu = $tk['banner'] ?? null;

            $stmt = $pdo->prepare("DELETE FROM thuong_hieu WHERE ma_thuong_hieu = :id");
            $stmt->execute([':id' => $id]);

            xoa_banner_cu($banner_cu);
            if ($tk) {
                ghi_nhat_ky($pdo, 'xoa', 'banner', $id, "Xoá thương hiệu \"{$tk['ten_thuong_hieu']}\"");
            }
        }
        header('Location: banner.php?msg=da_xoa');
        exit;
    }

    header('Location: banner.php');
    exit;
