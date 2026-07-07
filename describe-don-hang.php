<?php
require_once 'admin/config/config.php';

try {
    $stmt = $pdo->query("DESCRIBE don_hang");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $col) {
        echo $col['Field'] . " - " . $col['Type'] . "\n";
    }
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
}
