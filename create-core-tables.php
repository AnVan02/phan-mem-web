<?php
require_once 'admin/config/config.php';

try {
    $sql = "
    CREATE TABLE IF NOT EXISTS `khach_hang_lien_he` (
        `ma_lien_he` int(11) NOT NULL AUTO_INCREMENT,
        `customer_name` varchar(100) NOT NULL,
        `customer_email` varchar(100) NOT NULL,
        `customer_phone` varchar(20) NOT NULL,
        `customer_address` varchar(255) NOT NULL,
        `mat_khau` varchar(255) NULL,
        `trang_thai` tinyint(4) NOT NULL DEFAULT 1,
        `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`ma_lien_he`),
        UNIQUE KEY `uniq_customer_email` (`customer_email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    CREATE TABLE IF NOT EXISTS `don_hang` (
        `ma_don_hang` int(11) NOT NULL AUTO_INCREMENT,
        `session_id` varchar(100) NOT NULL,
        `ma_khach_hang` int(11) NULL,
        `ten_khach_hang` varchar(100) NOT NULL,
        `so_dien_thoai` varchar(20) NOT NULL,
        `dia_chi` varchar(255) NOT NULL,
        `ghi_chu` text DEFAULT NULL,
        `tong_tien` bigint(20) NOT NULL DEFAULT 0,
        `trang_thai` tinyint(4) NOT NULL DEFAULT 0,
        `ngay_dat` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`ma_don_hang`),
        KEY `fk_don_hang_khach_hang` (`ma_khach_hang`),
        CONSTRAINT `fk_don_hang_khach_hang` FOREIGN KEY (`ma_khach_hang`) REFERENCES `khach_hang_lien_he` (`ma_lien_he`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    CREATE TABLE IF NOT EXISTS `don_hang_chi_tiet` (
        `ma_chi_tiet` int(11) NOT NULL AUTO_INCREMENT,
        `ma_don_hang` int(11) NOT NULL,
        `ma_san_pham` int(11) NOT NULL,
        `ten_san_pham` varchar(100) NOT NULL,
        `so_luong` int(11) NOT NULL,
        `don_gia` int(11) NOT NULL,
        PRIMARY KEY (`ma_chi_tiet`),
        KEY `fk_dhct_don_hang` (`ma_don_hang`),
        KEY `fk_dhct_san_pham` (`ma_san_pham`),
        CONSTRAINT `fk_dhct_don_hang` FOREIGN KEY (`ma_don_hang`) REFERENCES `don_hang` (`ma_don_hang`) ON DELETE CASCADE,
        CONSTRAINT `fk_dhct_san_pham` FOREIGN KEY (`ma_san_pham`) REFERENCES `san_pham` (`ma_san_pham`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    CREATE TABLE IF NOT EXISTS `gio_hang` (
        `ma_gio_hang` int(11) NOT NULL AUTO_INCREMENT,
        `session_id` varchar(100) NOT NULL,
        `ma_san_pham` int(11) NOT NULL,
        `so_luong` int(11) NOT NULL DEFAULT 1,
        `ngay_them` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`ma_gio_hang`),
        UNIQUE KEY `uniq_session_sp` (`session_id`, `ma_san_pham`),
        KEY `fk_gh_san_pham` (`ma_san_pham`),
        CONSTRAINT `fk_gh_san_pham` FOREIGN KEY (`ma_san_pham`) REFERENCES `san_pham` (`ma_san_pham`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";
    
    $pdo->exec($sql);
    echo "Tạo các bảng cơ sở thành công!\n";
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
}
