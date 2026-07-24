<?php
require_once 'admin/config/config.php';

// Bảng lưu token đặt lại mật khẩu cho khách hàng (chức năng "Quên mật khẩu").
// Token thật gửi qua email, chỉ lưu bản băm SHA-256 trong CSDL để tránh lộ token nếu CSDL bị truy cập trái phép.
try {
    $sql = "CREATE TABLE IF NOT EXISTS `khach_hang_reset_mat_khau` (
        `id` INT NOT NULL AUTO_INCREMENT,
        `ma_khach_hang` INT NOT NULL,
        `token_hash` VARCHAR(64) NOT NULL,
        `ngay_tao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `het_han` DATETIME NOT NULL,
        `da_su_dung` TINYINT(1) NOT NULL DEFAULT 0,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_token_hash` (`token_hash`),
        KEY `fk_reset_khach_hang` (`ma_khach_hang`),
        CONSTRAINT `fk_reset_khach_hang` FOREIGN KEY (`ma_khach_hang`) REFERENCES `khach_hang_lien_he` (`ma_lien_he`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

    $pdo->exec($sql);
    echo "Tạo bảng khach_hang_reset_mat_khau thành công!\n";
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
}
