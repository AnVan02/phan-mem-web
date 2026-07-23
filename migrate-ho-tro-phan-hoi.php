<?php
require_once 'admin/config/config.php';

try {
    $pdo->exec("ALTER TABLE `ho_tro_khach_hang`
        ADD COLUMN IF NOT EXISTS `phan_hoi` TEXT NULL AFTER `trang_thai`,
        ADD COLUMN IF NOT EXISTS `ngay_phan_hoi` TIMESTAMP NULL DEFAULT NULL AFTER `phan_hoi`");
    echo "Thêm cột phản hồi cho bảng ho_tro_khach_hang thành công!\n";
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
}
