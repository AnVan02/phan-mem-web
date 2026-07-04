<?php
    require_once '../config/config.php';

    // Whitelist bảng/cột theo loại — không lấy trực tiếp từ input người dùng.
    $cau_hinh = [
        'danh_muc'    => ['bang' => 'danh_muc',    'khoa' => 'ma_danh_muc',    'cot' => 'ten_danh_muc'],
        'thuong_hieu' => ['bang' => 'thuong_hieu',  'khoa' => 'ma_thuong_hieu', 'cot' => 'ten_thuong_hieu'],
        'dung_luong'  => ['bang' => 'dung_luong',   'khoa' => 'ma_dung_luong',  'cot' => 'ten_dung_luong'],
    ];

    $loai = $_POST['loai'] ?? $_GET['loai'] ?? '';
    if (!isset($cau_hinh[$loai])) {
        header('Location: danh-sach.php');
        exit;
    }
    $bang    = $cau_hinh[$loai]['bang'];
    $khoa    = $cau_hinh[$loai]['khoa'];
    $cot_ten = $cau_hinh[$loai]['cot'];
    $action  = $_POST['action'] ?? $_GET['action'] ?? '';

    if ($action === 'them') {
        $ten = trim($_POST['ten'] ?? '');
        if ($ten === '') {
            header('Location: danh-sach.php?msg=loi_thieu_ten#' . $loai);
            exit;
        }
        $stmt = $pdo->prepare("INSERT INTO `$bang` (`$cot_ten`) VALUES (:ten)");
        $stmt->execute([':ten' => $ten]);
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
        $stmt = $pdo->prepare("UPDATE `$bang` SET `$cot_ten` = :ten WHERE `$khoa` = :id");
        $stmt->execute([':ten' => $ten, ':id' => $id]);
        header('Location: danh-sach.php?msg=da_sua#' . $loai);
        exit;
    }

    if ($action === 'xoa') {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id > 0) {
            try {
                $stmt = $pdo->prepare("DELETE FROM `$bang` WHERE `$khoa` = :id");
                $stmt->execute([':id' => $id]);
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
