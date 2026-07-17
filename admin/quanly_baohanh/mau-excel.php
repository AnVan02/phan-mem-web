<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_DON_HANG], '../dang-nhap.php');

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="mau-import-bao-hanh.csv"');

    echo "\xEF\xBB\xBF"; // BOM để Excel hiển thị đúng tiếng Việt

    $out = fopen('php://output', 'w');
    fputcsv($out, ['MAHANG', 'TENHANG', 'SOSERIAL', 'NGAYXUAT', 'THOIHANBH']);
    fputcsv($out, ['SP001', 'Ổ cứng SSD 512GB', 'SN123456789', '2026-07-13', '24']);
    fclose($out);
    exit;
