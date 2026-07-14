<?php
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="mau-import-bao-hanh.csv"');

    echo "\xEF\xBB\xBF"; // BOM để Excel hiển thị đúng tiếng Việt

    $out = fopen('php://output', 'w');
    fputcsv($out, ['MAHANG', 'TENHANG', 'SOSERIAL', 'NGAYXUAT', 'THOIHANBH']);
    fputcsv($out, ['SP001', 'Ổ cứng SSD 512GB', 'SN123456789', '2026-07-13', '24']);
    fclose($out);
    exit;
