<?php
require_once 'admin/config/config.php';

// Thêm cột cho phép hiển thị trang chính sách lên menu header (mục "Về công ty",
// "Cộng đồng", "Mô tả sản phẩm") ngay từ trang quản trị, không cần sửa code.
try {
    $pdo->exec("ALTER TABLE `policy_page`
        ADD COLUMN IF NOT EXISTS `policy_show_menu` TINYINT(1) NOT NULL DEFAULT 0 AFTER `policy_status`,
        ADD COLUMN IF NOT EXISTS `policy_menu_group` VARCHAR(50) NOT NULL DEFAULT '' AFTER `policy_show_menu`");
    echo "Cập nhật bảng policy_page thành công!\n";
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
}
