<?php
require_once 'admin/config/config.php';

$files = [
    'database/vietson-achieva.sql',
    'database/tai-khoan-khach-hang.sql',
    'database/gio-hang-don-hang.sql'
];

foreach ($files as $f) {
    if (file_exists($f)) {
        try {
            $sql = file_get_contents($f);
            $pdo->exec($sql);
            echo "Imported $f\n";
        } catch (Exception $e) {
            echo "Error importing $f: " . $e->getMessage() . "\n";
        }
    }
}
