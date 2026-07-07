<?php
require_once 'admin/config/config.php';

try {
    $pdo->exec("ALTER TABLE don_hang ADD COLUMN ma_khach_hang int(11) NULL AFTER session_id");
    
    // Check if we need foreign key
    $pdo->exec("ALTER TABLE don_hang ADD CONSTRAINT fk_don_hang_khach_hang FOREIGN KEY (ma_khach_hang) REFERENCES khach_hang_lien_he(ma_lien_he) ON DELETE SET NULL");
    
    echo "Thêm cột ma_khach_hang thành công!\n";
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
}
