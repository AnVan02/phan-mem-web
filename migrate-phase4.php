<?php
require_once 'admin/config/config.php';

try {
    $sql = "
    CREATE TABLE IF NOT EXISTS `ma_giam_gia` (
        `ma_giam` int(11) NOT NULL AUTO_INCREMENT,
        `code` varchar(50) NOT NULL,
        `phan_tram_giam` int(11) NOT NULL,
        `trang_thai` tinyint(4) NOT NULL DEFAULT 1,
        PRIMARY KEY (`ma_giam`),
        UNIQUE KEY `uniq_code` (`code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    CREATE TABLE IF NOT EXISTS `ho_tro_khach_hang` (
        `ma_ho_tro` int(11) NOT NULL AUTO_INCREMENT,
        `ma_khach_hang` int(11) NOT NULL,
        `chu_de` varchar(255) NOT NULL,
        `noi_dung` text NOT NULL,
        `trang_thai` tinyint(4) NOT NULL DEFAULT 0,
        `ngay_gui` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`ma_ho_tro`),
        KEY `fk_ht_khach_hang` (`ma_khach_hang`),
        CONSTRAINT `fk_ht_khach_hang` FOREIGN KEY (`ma_khach_hang`) REFERENCES `khach_hang_lien_he` (`ma_lien_he`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";
    
    $pdo->exec($sql);
    
    // Thêm thử 1 mã giảm giá
    $pdo->exec("INSERT IGNORE INTO `ma_giam_gia` (`code`, `phan_tram_giam`) VALUES ('WELCOME10', 10)");
    
    echo "Tạo bảng Phase 4 thành công!\n";
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
}
