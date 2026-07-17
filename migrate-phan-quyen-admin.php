<?php
require_once 'admin/config/config.php';

// Mã hoá lại các mật khẩu tài khoản admin còn đang lưu dạng plain text (bcrypt hoá bằng password_hash).
// An toàn để chạy lại nhiều lần: các mật khẩu đã là hash bcrypt (bắt đầu $2y$/$2a$/$2b$) sẽ được bỏ qua.
try {
    $rows = $pdo->query("SELECT account_id, account_password FROM account")->fetchAll(PDO::FETCH_ASSOC);

    $so_da_cap_nhat = 0;
    foreach ($rows as $r) {
        $mat_khau_hien_tai = $r['account_password'];
        if (preg_match('/^\$2[aby]\$/', $mat_khau_hien_tai)) {
            continue; // đã là hash bcrypt rồi
        }

        $mat_khau_hash = password_hash($mat_khau_hien_tai, PASSWORD_DEFAULT);
        $up = $pdo->prepare("UPDATE account SET account_password = :mk WHERE account_id = :id");
        $up->execute([':mk' => $mat_khau_hash, ':id' => $r['account_id']]);
        $so_da_cap_nhat++;
    }

    echo "Đã mã hoá $so_da_cap_nhat mật khẩu tài khoản admin (bcrypt).\n";
    echo "Tổng số tài khoản admin trong hệ thống: " . count($rows) . "\n";
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
}
