<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_NOI_DUNG], '../dang-nhap.php');

    // Whitelist bảng/cột theo loại — không lấy trực tiếp từ input người dùng.
    $cau_hinh = [
        'danh_muc'    => ['bang' => 'danh_muc',    'khoa' => 'ma_danh_muc',    'cot' => 'ten_danh_muc',    'co_anh' => false],
        'thuong_hieu' => ['bang' => 'thuong_hieu',  'khoa' => 'ma_thuong_hieu', 'cot' => 'ten_thuong_hieu', 'co_anh' => false],
        'dung_luong'  => ['bang' => 'dung_luong',   'khoa' => 'ma_dung_luong',  'cot' => 'ten_dung_luong',  'co_anh' => true],
    ];

    // Thư mục lưu ảnh (đường dẫn vật lý) và đường dẫn tương đối lưu vào DB
    define('THUMOC_LUU_ANH_VAT_LY', __DIR__ . '/../../assets/image/dung-luong/');
    define('THUMOC_LUU_ANH_DB', 'assets/image/dung-luong/');

    /**
     * Xử lý upload ảnh, trả về đường dẫn lưu DB.
     * null  = không có ảnh mới (giữ ảnh cũ)
     * false = có ảnh nhưng không hợp lệ
     */
    function xu_ly_upload_anh($file) {
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

        if (!is_dir(THUMOC_LUU_ANH_VAT_LY)) {
            mkdir(THUMOC_LUU_ANH_VAT_LY, 0755, true);
        }

        $ten_file = 'dungluong_' . uniqid() . '_' . time() . '.' . $ext;
        if (!move_uploaded_file($file['tmp_name'], THUMOC_LUU_ANH_VAT_LY . $ten_file)) {
            return false;
        }

        return THUMOC_LUU_ANH_DB . $ten_file;
    }

    function xoa_anh_cu($duong_dan_db) {
        if (empty($duong_dan_db)) {
            return;
        }
        $duong_dan_vat_ly = __DIR__ . '/../../' . $duong_dan_db;
        if (is_file($duong_dan_vat_ly)) {
            @unlink($duong_dan_vat_ly);
        }
    }

    $loai = $_POST['loai'] ?? $_GET['loai'] ?? '';
    if (!isset($cau_hinh[$loai])) {
        header('Location: danh-sach.php');
        exit;
    }
    $bang    = $cau_hinh[$loai]['bang'];
    $khoa    = $cau_hinh[$loai]['khoa'];
    $cot_ten = $cau_hinh[$loai]['cot'];
    $co_anh  = $cau_hinh[$loai]['co_anh'];
    $action  = $_POST['action'] ?? $_GET['action'] ?? '';

    if ($action === 'them') {
        $ten = trim($_POST['ten'] ?? '');
        if ($ten === '') {
            header('Location: danh-sach.php?msg=loi_thieu_ten#' . $loai);
            exit;
        }

        if ($co_anh) {
            $hinh_anh = null;
            if (!empty($_FILES['hinh_anh']['name'])) {
                $ket_qua = xu_ly_upload_anh($_FILES['hinh_anh']);
                if ($ket_qua === false) {
                    header('Location: danh-sach.php?msg=loi_anh#' . $loai);
                    exit;
                }
                $hinh_anh = $ket_qua;
            }
            $stmt = $pdo->prepare("INSERT INTO `$bang` (`$cot_ten`, `hinh_anh`) VALUES (:ten, :anh)");
            $stmt->execute([':ten' => $ten, ':anh' => $hinh_anh]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO `$bang` (`$cot_ten`) VALUES (:ten)");
            $stmt->execute([':ten' => $ten]);
        }
        ghi_nhat_ky($pdo, 'them', $loai, (int) $pdo->lastInsertId(), "Thêm \"$ten\" vào $loai");

        header('Location: danh-sach.php?msg=da_them#' . $loai);
        exit;
    }

    if ($action === 'sua') {
        $id  = (int) ($_POST['id'] ?? 0);
        $ten = trim($_POST['ten'] ?? '');
        if ($id <= 0 || $ten === '') {
            header('Location: danh-sach.php?msg=loi_thieu_ten#' . $loai);
            exit;
        }

        if ($co_anh) {
            $anh_moi = null;
            if (!empty($_FILES['hinh_anh']['name'])) {
                $ket_qua = xu_ly_upload_anh($_FILES['hinh_anh']);
                if ($ket_qua === false) {
                    header('Location: danh-sach.php?msg=loi_anh#' . $loai);
                    exit;
                }
                $anh_moi = $ket_qua;
            }

            if ($anh_moi !== null) {
                // Lấy ảnh cũ để xoá file vật lý, tránh rác trên server
                $stmt = $pdo->prepare("SELECT hinh_anh FROM `$bang` WHERE `$khoa` = :id");
                $stmt->execute([':id' => $id]);
                $anh_cu = $stmt->fetchColumn();

                $stmt = $pdo->prepare("UPDATE `$bang` SET `$cot_ten` = :ten, `hinh_anh` = :anh WHERE `$khoa` = :id");
                $stmt->execute([':ten' => $ten, ':anh' => $anh_moi, ':id' => $id]);

                xoa_anh_cu($anh_cu);
            } else {
                $stmt = $pdo->prepare("UPDATE `$bang` SET `$cot_ten` = :ten WHERE `$khoa` = :id");
                $stmt->execute([':ten' => $ten, ':id' => $id]);
            }
        } else {
            $stmt = $pdo->prepare("UPDATE `$bang` SET `$cot_ten` = :ten WHERE `$khoa` = :id");
            $stmt->execute([':ten' => $ten, ':id' => $id]);
        }
        ghi_nhat_ky($pdo, 'sua', $loai, $id, "Sửa \"$ten\" trong $loai");

        header('Location: danh-sach.php?msg=da_sua#' . $loai);
        exit;
    }

    if ($action === 'xoa') {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id > 0) {
            try {
                $stmt = $pdo->prepare("SELECT `$cot_ten`" . ($co_anh ? ", hinh_anh" : "") . " FROM `$bang` WHERE `$khoa` = :id");
                $stmt->execute([':id' => $id]);
                $ban_ghi_cu = $stmt->fetch(PDO::FETCH_ASSOC);
                $anh_cu = $co_anh ? ($ban_ghi_cu['hinh_anh'] ?? null) : null;

                $stmt = $pdo->prepare("DELETE FROM `$bang` WHERE `$khoa` = :id");
                $stmt->execute([':id' => $id]);

                if ($co_anh && !empty($anh_cu)) {
                    xoa_anh_cu($anh_cu);
                }

                if ($ban_ghi_cu) {
                    ghi_nhat_ky($pdo, 'xoa', $loai, $id, "Xoá \"{$ban_ghi_cu[$cot_ten]}\" trong $loai");
                }

                header('Location: danh-sach.php?msg=da_xoa#' . $loai);
                exit;
            } catch (PDOException $e) {
                header('Location: danh-sach.php?msg=loi_dang_su_dung#' . $loai);
                exit;
            }
        }
    }

    header('Location: danh-sach.php');
    exit;