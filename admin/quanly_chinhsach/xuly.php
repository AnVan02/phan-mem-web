<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_NOI_DUNG], '../dang-nhap.php');

    function upload_anh_chinh_sach($file) {
        if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $duoi_hop_le = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $duoi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($duoi, $duoi_hop_le, true)) {
            return false;
        }

        $thu_muc = '../../assets/uploads/chinh-sach/';
        if (!is_dir($thu_muc)) {
            mkdir($thu_muc, 0755, true);
        }

        $ten_file = uniqid('chinh-sach-', true) . '.' . $duoi;
        if (!move_uploaded_file($file['tmp_name'], $thu_muc . $ten_file)) {
            return false;
        }

        return 'assets/uploads/chinh-sach/' . $ten_file;
    }

    $action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');
    $slug_co_san = ['bao-hanh', 've-chung-toi'];

    if ($action === 'them' || $action === 'sua') {
        $ma_chinh_sach = (int) ($_POST['policy_id'] ?? 0);
        $trang_loi = $action === 'sua' ? 'sua.php?id=' . $ma_chinh_sach . '&' : 'them.php?';

        $tieu_de = trim($_POST['policy_title'] ?? '');
        $slug_nhap = trim($_POST['policy_slug'] ?? '');
        $slug = tao_slug($slug_nhap !== '' ? $slug_nhap : $tieu_de);
        $mo_ta = trim($_POST['policy_subtitle'] ?? '');
        $noi_dung = $_POST['policy_content'] ?? '';
        $trang_thai = isset($_POST['policy_status']) ? 1 : 0;
        $anh_cu = trim($_POST['anh_hien_tai'] ?? '');

        if ($tieu_de === '' || $slug === '' || ($action === 'sua' && $ma_chinh_sach <= 0)) {
            header('Location: ' . $trang_loi . 'msg=loi_thieu_du_lieu');
            exit;
        }

        $anh_upload = upload_anh_chinh_sach($_FILES['policy_image_file'] ?? null);
        if ($anh_upload === false) {
            header('Location: ' . $trang_loi . 'msg=loi_anh');
            exit;
        }
        $anh = $anh_upload !== null ? $anh_upload : ($_POST['policy_image'] ?? $anh_cu);
        $anh = trim($anh);

        try {
            if ($action === 'them') {
                $stmt = $pdo->prepare("INSERT INTO policy_page
                    (policy_slug, policy_title, policy_subtitle, policy_content, policy_image, policy_status)
                    VALUES (:slug, :title, :subtitle, :content, :image, :status)");
                $stmt->execute([
                    ':slug'     => $slug,
                    ':title'    => $tieu_de,
                    ':subtitle' => $mo_ta,
                    ':content'  => $noi_dung,
                    ':image'    => $anh,
                    ':status'   => $trang_thai,
                ]);
                ghi_nhat_ky($pdo, 'them', 'chinh_sach', (int) $pdo->lastInsertId(), "Thêm trang chính sách \"$tieu_de\"");
                header('Location: danh-sach.php?msg=da_them');
                exit;
            }

            $stmt = $pdo->prepare("UPDATE policy_page SET
                    policy_slug = :slug,
                    policy_title = :title,
                    policy_subtitle = :subtitle,
                    policy_content = :content,
                    policy_image = :image,
                    policy_status = :status
                WHERE policy_id = :id");
            $stmt->execute([
                ':slug'     => $slug,
                ':title'    => $tieu_de,
                ':subtitle' => $mo_ta,
                ':content'  => $noi_dung,
                ':image'    => $anh,
                ':status'   => $trang_thai,
                ':id'       => $ma_chinh_sach,
            ]);
            ghi_nhat_ky($pdo, 'sua', 'chinh_sach', $ma_chinh_sach, "Sửa trang chính sách \"$tieu_de\"");
            header('Location: danh-sach.php?msg=da_sua');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                header('Location: ' . $trang_loi . 'msg=loi_trung_slug');
                exit;
            }
            throw $e;
        }
    }

    if ($action === 'xoa') {
        $ma_chinh_sach = (int) ($_GET['id'] ?? 0);
        if ($ma_chinh_sach > 0) {
            $stmt = $pdo->prepare("SELECT policy_slug, policy_title FROM policy_page WHERE policy_id = :id");
            $stmt->execute([':id' => $ma_chinh_sach]);
            $trang_can_xoa = $stmt->fetch(PDO::FETCH_ASSOC);
            $slug_can_xoa = $trang_can_xoa['policy_slug'] ?? false;

            if ($slug_can_xoa !== false && !in_array($slug_can_xoa, $slug_co_san, true)) {
                $stmt = $pdo->prepare("DELETE FROM policy_page WHERE policy_id = :id");
                $stmt->execute([':id' => $ma_chinh_sach]);
                ghi_nhat_ky($pdo, 'xoa', 'chinh_sach', $ma_chinh_sach, "Xoá trang chính sách \"{$trang_can_xoa['policy_title']}\"");
            }
        }
        header('Location: danh-sach.php?msg=da_xoa');
        exit;
    }

    header('Location: danh-sach.php');
    exit;
