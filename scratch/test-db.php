<?php
require_once '../admin/config/config.php';

echo "--- DANH MUC ---\n";
$stmt = $pdo->query("SELECT * FROM danh_muc");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print_r($row);
}

echo "\n--- DUNG LUONG ---\n";
$stmt = $pdo->query("SELECT * FROM dung_luong");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print_r($row);
}

echo "\n--- THUONG HIEU ---\n";
$stmt = $pdo->query("SELECT * FROM thuong_hieu");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print_r($row);
}

echo "\n--- UNIQUE LOAI_SAN_PHAM ---\n";
$stmt = $pdo->query("SELECT DISTINCT loai_san_pham FROM san_pham");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print_r($row);
}

echo "\n--- UNIQUE CHUAN_KET_NOI ---\n";
$stmt = $pdo->query("SELECT DISTINCT chuan_ket_noi FROM san_pham");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print_r($row);
}
