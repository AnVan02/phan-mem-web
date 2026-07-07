<?php
require_once 'admin/config/config.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS `san_pham_yeu_thich` (
        `ma_yeu_thich` int(11) NOT NULL AUTO_INCREMENT,
        `ma_khach_hang` int(11) NOT NULL,
        `ma_san_pham` int(11) NOT NULL,
        `ngay_them` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`ma_yeu_thich`),
        UNIQUE KEY `uniq_kh_sp` (`ma_khach_hang`, `ma_san_pham`),
        KEY `fk_yt_khach_hang` (`ma_khach_hang`),
        KEY `fk_yt_san_pham` (`ma_san_pham`),
        CONSTRAINT `fk_yt_khach_hang` FOREIGN KEY (`ma_khach_hang`) REFERENCES `khach_hang_lien_he` (`ma_lien_he`) ON DELETE CASCADE,
        CONSTRAINT `fk_yt_san_pham` FOREIGN KEY (`ma_san_pham`) REFERENCES `san_pham` (`ma_san_pham`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
    
    $pdo->exec($sql);
    echo "Tạo bảng san_pham_yeu_thich thành công!\n";
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
}
