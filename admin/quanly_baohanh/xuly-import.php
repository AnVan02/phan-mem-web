<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_DON_HANG], '../dang-nhap.php');

    function chuyenCotChuSangSo($chu)
    {
        $chu = strtoupper($chu);
        $ket_qua = 0;
        for ($i = 0; $i < strlen($chu); $i++) {
            $ket_qua = $ket_qua * 26 + (ord($chu[$i]) - ord('A') + 1);
        }
        return $ket_qua - 1;
    }

    function docFileXlsx($duong_dan)
    {
        $zip = new ZipArchive();
        if ($zip->open($duong_dan) !== true) {
            throw new Exception('Không thể mở file Excel.');
        }

        $chuoi_dung_chung = [];
        $shared_xml = $zip->getFromName('xl/sharedStrings.xml');
        if ($shared_xml !== false) {
            $sx = new SimpleXMLElement($shared_xml);
            foreach ($sx->si as $si) {
                if (isset($si->t)) {
                    $chuoi_dung_chung[] = (string) $si->t;
                } else {
                    $text = '';
                    foreach ($si->r as $r) {
                        $text .= (string) $r->t;
                    }
                    $chuoi_dung_chung[] = $text;
                }
            }
        }

        $sheet_xml = $zip->getFromName('xl/worksheets/sheet1.xml');
        if ($sheet_xml === false) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $ten = $zip->getNameIndex($i);
                if (preg_match('#^xl/worksheets/sheet\d+\.xml$#', $ten)) {
                    $sheet_xml = $zip->getFromName($ten);
                    break;
                }
            }
        }
        $zip->close();

        if ($sheet_xml === false) {
            throw new Exception('Không tìm thấy dữ liệu trong file Excel.');
        }

        $sheet = new SimpleXMLElement($sheet_xml);
        $rows = [];
        foreach ($sheet->sheetData->row as $row_xml) {
            $du_lieu_dong = [];
            $cot_toi_da = -1;
            foreach ($row_xml->c as $cell) {
                $tham_chieu = (string) $cell['r'];
                preg_match('/[A-Z]+/', $tham_chieu, $m);
                $chi_so_cot = isset($m[0]) ? chuyenCotChuSangSo($m[0]) : count($du_lieu_dong);

                $kieu = (string) $cell['t'];
                if ($kieu === 's') {
                    $idx = (int) $cell->v;
                    $gia_tri = $chuoi_dung_chung[$idx] ?? '';
                } elseif ($kieu === 'inlineStr') {
                    $gia_tri = isset($cell->is->t) ? (string) $cell->is->t : '';
                } else {
                    $gia_tri = isset($cell->v) ? (string) $cell->v : '';
                }

                $du_lieu_dong[$chi_so_cot] = trim($gia_tri);
                if ($chi_so_cot > $cot_toi_da) {
                    $cot_toi_da = $chi_so_cot;
                }
            }
            for ($i = 0; $i <= $cot_toi_da; $i++) {
                if (!isset($du_lieu_dong[$i])) {
                    $du_lieu_dong[$i] = '';
                }
            }
            ksort($du_lieu_dong);
            $rows[] = array_values($du_lieu_dong);
        }

        return $rows;
    }

    function docFileCsv($duong_dan)
    {
        $rows = [];
        $handle = fopen($duong_dan, 'r');
        if ($handle === false) {
            throw new Exception('Không thể đọc file CSV.');
        }

        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        while (($data = fgetcsv($handle)) !== false) {
            $rows[] = array_map('trim', $data);
        }
        fclose($handle);

        return $rows;
    }

    function xacDinhCotBaoHanh(array $hang_tieu_de)
    {
        $vi_tri = [];
        foreach ($hang_tieu_de as $i => $ten) {
            $vi_tri[mb_strtoupper(trim($ten), 'UTF-8')] = $i;
        }
        $cac_cot_can = ['MAHANG', 'TENHANG', 'SOSERIAL', 'NGAYXUAT', 'THOIHANBH'];
        $ket_qua = [];
        foreach ($cac_cot_can as $ten_cot) {
            $ket_qua[$ten_cot] = $vi_tri[$ten_cot] ?? null;
        }
        return $ket_qua;
    }

    function layGiaTriCot($row, $chi_so)
    {
        if ($chi_so === null || !isset($row[$chi_so])) {
            return '';
        }
        return trim((string) $row[$chi_so]);
    }

    // --- Kiểm tra file tải lên ---
    $file = $_FILES['excel_file'] ?? null;

    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        header('Location: bao-hanh.php?msg=loi_chua_chon');
        exit;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        header('Location: bao-hanh.php?msg=loi_dinh_dang');
        exit;
    }

    $duoi_hop_le = ['xlsx', 'csv'];
    $duoi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($duoi, $duoi_hop_le, true)) {
        header('Location: bao-hanh.php?msg=loi_dinh_dang');
        exit;
    }

    if ($file['size'] > 20 * 1024 * 1024) {
        header('Location: bao-hanh.php?msg=loi_kich_thuoc');
        exit;
    }

    $thu_muc = '../../assets/uploads/bao-hanh/';
    if (!is_dir($thu_muc)) {
        mkdir($thu_muc, 0755, true);
    }

    $ten_file = uniqid('bao-hanh-', true) . '.' . $duoi;
    $duong_dan_luu = $thu_muc . $ten_file;
    move_uploaded_file($file['tmp_name'], $duong_dan_luu);

    // --- Đọc và import dữ liệu vào bảng bao_hanh ---
    try {
        $rows = $duoi === 'csv' ? docFileCsv($duong_dan_luu) : docFileXlsx($duong_dan_luu);
    } catch (Exception $e) {
        header('Location: bao-hanh.php?msg=loi_dinh_dang');
        exit;
    }

    if (count($rows) < 2) {
        header('Location: bao-hanh.php?msg=loi_khong_co_du_lieu');
        exit;
    }

    $cot = xacDinhCotBaoHanh($rows[0]);
    if ($cot['MAHANG'] === null || $cot['SOSERIAL'] === null || $cot['THOIHANBH'] === null) {
        header('Location: bao-hanh.php?msg=loi_dinh_dang');
        exit;
    }

    $stmt = $pdo->prepare(
        "INSERT INTO bao_hanh (MAHANG, TENHANG, SOSERIAL, NGAYXUAT, THOIHANBH, hinh_anh)
         VALUES (:mahang, :tenhang, :soserial, :ngayxuat, :thoihanbh, '')
         ON DUPLICATE KEY UPDATE
            MAHANG = VALUES(MAHANG),
            TENHANG = VALUES(TENHANG),
            NGAYXUAT = VALUES(NGAYXUAT),
            THOIHANBH = VALUES(THOIHANBH)"
    );

    $tong = 0;
    $thanh_cong = 0;
    $that_bai = 0;

    for ($i = 1; $i < count($rows); $i++) {
        $row = $rows[$i];
        if (count(array_filter($row, function ($v) { return trim((string) $v) !== ''; })) === 0) {
            continue;
        }
        $tong++;

        $ma_hang = layGiaTriCot($row, $cot['MAHANG']);
        $so_serial = layGiaTriCot($row, $cot['SOSERIAL']);
        $ten_hang = layGiaTriCot($row, $cot['TENHANG']);
        $ngay_xuat_raw = layGiaTriCot($row, $cot['NGAYXUAT']);
        $thoi_han_raw = layGiaTriCot($row, $cot['THOIHANBH']);

        if ($ma_hang === '' || $so_serial === '' || $thoi_han_raw === '' || !is_numeric($thoi_han_raw)) {
            $that_bai++;
            continue;
        }

        $ngay_xuat = $ngay_xuat_raw;
        if ($ngay_xuat_raw !== '' && is_numeric($ngay_xuat_raw)) {
            // Excel lưu ngày dạng số ngày kể từ 1899-12-30
            $ngay_xuat = gmdate('Y-m-d', ((float) $ngay_xuat_raw - 25569) * 86400);
        }

        try {
            $stmt->execute([
                ':mahang'    => $ma_hang,
                ':tenhang'   => $ten_hang !== '' ? $ten_hang : null,
                ':soserial'  => $so_serial,
                ':ngayxuat'  => $ngay_xuat !== '' ? $ngay_xuat : null,
                ':thoihanbh' => (int) $thoi_han_raw,
            ]);
            $thanh_cong++;
        } catch (PDOException $e) {
            $that_bai++;
        }
    }

    if ($tong === 0) {
        header('Location: bao-hanh.php?msg=loi_khong_co_du_lieu');
        exit;
    }

    $_SESSION['bao_hanh_import'] = [
        'tong'       => $tong,
        'thanh_cong' => $thanh_cong,
        'that_bai'   => $that_bai,
        'thoi_gian'  => date('d/m/Y H:i'),
    ];

    header('Location: bao-hanh.php?msg=da_import');
    exit;
