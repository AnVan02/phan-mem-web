<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_NOI_DUNG], '../dang-nhap.php');

    function upload_anh_bai_viet($file) {
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

        $thu_muc = '../../assets/uploads/tin-tuc/';
        if (!is_dir($thu_muc)) {
            mkdir($thu_muc, 0755, true);
        }

        $ten_file = uniqid('bai-viet-', true) . '.' . $duoi;
        if (!move_uploaded_file($file['tmp_name'], $thu_muc . $ten_file)) {
            return false;
        }

        return 'assets/uploads/tin-tuc/' . $ten_file;
    }

    $action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

    if ($action === 'them' || $action === 'sua') {
        $ma_bai_viet = (int) ($_POST['article_id'] ?? 0);
        $trang_loi = $action === 'sua' ? 'sua.php?id=' . $ma_bai_viet . '&' : 'them.php?';

        $tieu_de = trim($_POST['article_title'] ?? '');
        $tac_gia = trim($_POST['article_author'] ?? '');
        $linh = trim($_POST['article_linh'] ?? '');
        $mo_ta = trim($_POST['article_summary'] ?? '');
        $noi_dung = $_POST['article_content'] ?? '';
        $video = trim($_POST['article_video'] ?? '');
        $ngay = trim($_POST['article_date'] ?? '');
        $trang_thai = isset($_POST['article_status']) ? 1 : 0;
        $anh_cu = trim($_POST['anh_hien_tai'] ?? '');

        if ($tieu_de === '' || $tac_gia === '' || ($action === 'sua' && $ma_bai_viet <= 0)) {
            header('Location: ' . $trang_loi . 'msg=loi_thieu_du_lieu');
            exit;
        }

        if ($ngay === '') {
            $ngay = date('Y-m-d');
        }

        $anh_upload = upload_anh_bai_viet($_FILES['article_image_file'] ?? null);
        if ($anh_upload === false) {
            header('Location: ' . $trang_loi . 'msg=loi_anh');
            exit;
        }
        $anh = $anh_upload !== null ? $anh_upload : ($_POST['article_image'] ?? $anh_cu);
        $anh = trim($anh);

        if ($action === 'them') {
            $stmt = $pdo->prepare("INSERT INTO article
                (article_author, article_title, article_linh, article_summary, article_content, article_image, article_video, article_date, article_status)
                VALUES (:author, :title, :linh, :summary, :content, :image, :video, :date, :status)");
            $stmt->execute([
                ':author'  => $tac_gia,
                ':title'   => $tieu_de,
                ':linh'    => $linh,
                ':summary' => $mo_ta,
                ':content' => $noi_dung,
                ':image'   => $anh,
                ':video'   => $video,
                ':date'    => $ngay,
                ':status'  => $trang_thai,
            ]);
            header('Location: dah-sach-bai-viet.php?msg=da_them');
            exit;
        }

        $stmt = $pdo->prepare("UPDATE article SET
                article_author = :author,
                article_title = :title,
                article_linh = :linh,
                article_summary = :summary,
                article_content = :content,
                article_image = :image,
                article_video = :video,
                article_date = :date,
                article_status = :status
            WHERE article_id = :id");
        $stmt->execute([
            ':author'  => $tac_gia,
            ':title'   => $tieu_de,
            ':linh'    => $linh,
            ':summary' => $mo_ta,
            ':content' => $noi_dung,
            ':image'   => $anh,
            ':video'   => $video,
            ':date'    => $ngay,
            ':status'  => $trang_thai,
            ':id'      => $ma_bai_viet,
        ]);
        header('Location: dah-sach-bai-viet.php?msg=da_sua');
        exit;
    }

    if ($action === 'xoa') {
        $ma_bai_viet = (int) ($_GET['id'] ?? 0);
        if ($ma_bai_viet > 0) {
            $stmt = $pdo->prepare("DELETE FROM article WHERE article_id = :id");
            $stmt->execute([':id' => $ma_bai_viet]);
        }
        header('Location: dah-sach-bai-viet.php?msg=da_xoa');
        exit;
    }

    header('Location: dah-sach-bai-viet.php');
    exit;
