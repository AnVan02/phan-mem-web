<?php
require_once 'admin/config/config.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS `danh_gia_san_pham` (
        `ma_danh_gia` int(11) NOT NULL AUTO_INCREMENT,
        `ma_san_pham` int(11) NOT NULL,
        `ma_khach_hang` int(11) NOT NULL,
        `so_sao` tinyint(4) NOT NULL DEFAULT 5,
        `noi_dung` text NOT NULL,
        `ngay_danh_gia` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`ma_danh_gia`),
        KEY `fk_dg_san_pham` (`ma_san_pham`),
        KEY `fk_dg_khach_hang` (`ma_khach_hang`),
        CONSTRAINT `fk_dg_san_pham` FOREIGN KEY (`ma_san_pham`) REFERENCES `san_pham` (`ma_san_pham`) ON DELETE CASCADE,
        CONSTRAINT `fk_dg_khach_hang` FOREIGN KEY (`ma_khach_hang`) REFERENCES `khach_hang_lien_he` (`ma_lien_he`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
    
    $pdo->exec($sql);
    echo "Tạo bảng danh_gia_san_pham thành công!\n";
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
}
