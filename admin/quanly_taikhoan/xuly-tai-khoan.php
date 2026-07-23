<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI], '../dang-nhap.php');

    $action = $_POST['action'] ?? $_GET['action'] ?? '';

    // Kiểm tra email đã dùng cho tài khoản khác chưa (loại trừ chính tài khoản đang sửa)
    function email_da_ton_tai($pdo, $email, $bo_qua_id = 0)
    {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM account WHERE account_email = :email AND account_id != :id");
        $stmt->execute([':email' => $email, ':id' => $bo_qua_id]);
        return (int) $stmt->fetchColumn() > 0;
    }

    if ($action === 'them') {
        $ten   = trim($_POST['account_name'] ?? '');
        $email = trim($_POST['account_email'] ?? '');
        $mk    = trim($_POST['account_password'] ?? '');
        $vt    = (int) ($_POST['account_type'] ?? -1);

        if ($ten === '' || $email === '' || $mk === '' || !in_array($vt, [VAI_TRO_QUAN_TRI, VAI_TRO_NOI_DUNG, VAI_TRO_DON_HANG], true)) {
            header('Location: tai-khoan.php?msg=loi_thieu_du_lieu');
            exit;
        }
        if (mb_strlen($mk) < 6) {
            header('Location: tai-khoan.php?msg=loi_mat_khau_ngan');
            exit;
        }
        if (email_da_ton_tai($pdo, $email)) {
            header('Location: tai-khoan.php?msg=loi_email_ton_tai');
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO account (account_name, account_email, account_password, account_type) VALUES (:ten, :email, :mk, :vt)");
        $stmt->execute([
            ':ten'   => $ten,
            ':email' => $email,
            ':mk'    => password_hash($mk, PASSWORD_DEFAULT),
            ':vt'    => $vt,
        ]);
        $id_moi = (int) $pdo->lastInsertId();
        ghi_nhat_ky($pdo, 'them', 'tai_khoan', $id_moi, "Thêm tài khoản \"$ten\" ($email) - vai trò: " . ($DS_VAI_TRO[$vt] ?? $vt));

        header('Location: tai-khoan.php?msg=da_them');
        exit;
    }

    if ($action === 'sua') {
        $id    = (int) ($_POST['id'] ?? 0);
        $ten   = trim($_POST['account_name'] ?? '');
        $email = trim($_POST['account_email'] ?? '');
        $mk    = trim($_POST['account_password'] ?? '');
        $vt    = (int) ($_POST['account_type'] ?? -1);

        if ($id <= 0 || $ten === '' || $email === '' || !in_array($vt, [VAI_TRO_QUAN_TRI, VAI_TRO_NOI_DUNG, VAI_TRO_DON_HANG], true)) {
            header('Location: tai-khoan.php?msg=loi_thieu_du_lieu');
            exit;
        }
        if ($mk !== '' && mb_strlen($mk) < 6) {
            header('Location: tai-khoan.php?msg=loi_mat_khau_ngan');
            exit;
        }
        if (email_da_ton_tai($pdo, $email, $id)) {
            header('Location: tai-khoan.php?msg=loi_email_ton_tai');
            exit;
        }

        // Không cho tự hạ quyền/đổi vai trò của chính mình nếu đó là Quản trị viên duy nhất còn lại
        if ($vt !== VAI_TRO_QUAN_TRI) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM account WHERE account_type = :vt AND account_id != :id");
            $stmt->execute([':vt' => VAI_TRO_QUAN_TRI, ':id' => $id]);
            $con_lai = (int) $stmt->fetchColumn();
            $stmt2 = $pdo->prepare("SELECT account_type FROM account WHERE account_id = :id");
            $stmt2->execute([':id' => $id]);
            $vt_cu = (int) $stmt2->fetchColumn();
            if ($vt_cu === VAI_TRO_QUAN_TRI && $con_lai === 0) {
                header('Location: tai-khoan.php?msg=loi_xoa_quan_tri_cuoi');
                exit;
            }
        }

        if ($mk !== '') {
            $stmt = $pdo->prepare("UPDATE account SET account_name = :ten, account_email = :email, account_password = :mk, account_type = :vt WHERE account_id = :id");
            $stmt->execute([
                ':ten'   => $ten,
                ':email' => $email,
                ':mk'    => password_hash($mk, PASSWORD_DEFAULT),
                ':vt'    => $vt,
                ':id'    => $id,
            ]);
        } else {
            $stmt = $pdo->prepare("UPDATE account SET account_name = :ten, account_email = :email, account_type = :vt WHERE account_id = :id");
            $stmt->execute([
                ':ten'   => $ten,
                ':email' => $email,
                ':vt'    => $vt,
                ':id'    => $id,
            ]);
        }

        ghi_nhat_ky($pdo, 'sua', 'tai_khoan', $id, "Sửa tài khoản \"$ten\" ($email) - vai trò: " . ($DS_VAI_TRO[$vt] ?? $vt) . ($mk !== '' ? ' (đã đổi mật khẩu)' : ''));

        // Nếu vừa tự sửa tài khoản đang đăng nhập, cập nhật lại session cho khớp
        if (isset($_SESSION['account_id_admin']) && (int) $_SESSION['account_id_admin'] === $id) {
            $_SESSION['account_name'] = $ten;
            $_SESSION['login'] = $email;
            $_SESSION['account_type'] = $vt;
        }

        header('Location: tai-khoan.php?msg=da_sua');
        exit;
    }

    if ($action === 'xoa') {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id > 0) {
            if (isset($_SESSION['account_id_admin']) && (int) $_SESSION['account_id_admin'] === $id) {
                header('Location: tai-khoan.php?msg=loi_tu_xoa');
                exit;
            }

            $stmt = $pdo->prepare("SELECT account_name, account_email, account_type FROM account WHERE account_id = :id");
            $stmt->execute([':id' => $id]);
            $tk_can_xoa = $stmt->fetch(PDO::FETCH_ASSOC);
            $vt_can_xoa = $tk_can_xoa['account_type'] ?? null;

            if ((int) $vt_can_xoa === VAI_TRO_QUAN_TRI) {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM account WHERE account_type = :vt AND account_id != :id");
                $stmt->execute([':vt' => VAI_TRO_QUAN_TRI, ':id' => $id]);
                if ((int) $stmt->fetchColumn() === 0) {
                    header('Location: tai-khoan.php?msg=loi_xoa_quan_tri_cuoi');
                    exit;
                }
            }

            $stmt = $pdo->prepare("DELETE FROM account WHERE account_id = :id");
            $stmt->execute([':id' => $id]);

            if ($tk_can_xoa) {
                ghi_nhat_ky($pdo, 'xoa', 'tai_khoan', $id, "Xoá tài khoản \"{$tk_can_xoa['account_name']}\" ({$tk_can_xoa['account_email']})");
            }
        }
        header('Location: tai-khoan.php?msg=da_xoa');
        exit;
    }

    header('Location: tai-khoan.php');
    exit;
