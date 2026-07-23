<?php
require_once 'config/config.php';

if (isset($_SESSION['account_id_admin'])) {
    ghi_nhat_ky($pdo, 'dang_xuat', 'tai_khoan', (int) $_SESSION['account_id_admin'], 'Đăng xuất khỏi hệ thống.');
}

$_SESSION = [];
session_destroy();

header('Location: dang-nhap.php');
exit;
