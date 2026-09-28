<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_DON_HANG], '../dang-nhap.php');

    $trang_thai_nhan = [
        0 => ['Chờ xử lý', 'off'],
        1 => ['Đã xác nhận', 'on'],
        2 => ['Đang giao', 'on'],
        3 => ['Hoàn thành', 'on'],
        4 => ['Đã huỷ', 'off'],
    ];

    $trang_thai_loc = isset($_GET['trang_thai']) && $_GET['trang_thai'] !== '' ? (int) $_GET['trang_thai'] : -1;
    $tu_khoa_loc    = isset($_GET['q']) ? trim($_GET['q']) : '';

    $sql = "SELECT * FROM don_hang WHERE 1=1";
    $tham_so = [];

    if ($trang_thai_loc !== -1) {
        $sql .= " AND trang_thai = :trang_thai";
        $tham_so[':trang_thai'] = $trang_thai_loc;
    }
    if ($tu_khoa_loc !== '') {
        $sql .= " AND (ten_khach_hang LIKE :tu_khoa OR so_dien_thoai LIKE :tu_khoa OR ma_don_hang = :ma_don)";
        $tham_so[':tu_khoa'] = '%' . $tu_khoa_loc . '%';
        $tham_so[':ma_don']  = ctype_digit($tu_khoa_loc) ? (int) $tu_khoa_loc : 0;
    }

    $dem_stmt = $pdo->prepare(str_replace('SELECT *', 'SELECT COUNT(*)', $sql));
    $dem_stmt->execute($tham_so);
    $tong_so_don = (int) $dem_stmt->fetchColumn();

    $so_dong_moi_trang = 15;
    $tong_so_trang     = max(1, (int) ceil($tong_so_don / $so_dong_moi_trang));
    $trang_hien_tai    = isset($_GET['trang']) ? (int) $_GET['trang'] : 1;
    if ($trang_hien_tai < 1) $trang_hien_tai = 1;
    if ($trang_hien_tai > $tong_so_trang) $trang_hien_tai = $tong_so_trang;
    $bat_dau = ($trang_hien_tai - 1) * $so_dong_moi_trang;

    $sql .= " ORDER BY ma_don_hang DESC LIMIT :gioi_han OFFSET :bat_dau";

    $danh_sach_stmt = $pdo->prepare($sql);
    foreach ($tham_so as $key => $val) {
        $danh_sach_stmt->bindValue($key, $val);
    }
    $danh_sach_stmt->bindValue(':gioi_han', $so_dong_moi_trang, PDO::PARAM_INT);
    $danh_sach_stmt->bindValue(':bat_dau', $bat_dau, PDO::PARAM_INT);
    $danh_sach_stmt->execute();
    $danh_sach = $danh_sach_stmt->fetchAll(PDO::FETCH_ASSOC);

    function xay_url_trang_don($trang)
    {
        $params = $_GET;
        $params['trang'] = $trang;
        return 'don-hang.php?' . http_build_query($params);
    }

    $thong_bao = [
        'da_cap_nhat' => ['success', 'Đã cập nhật trạng thái đơn hàng.'],
        'da_xoa'      => ['success', 'Đã xoá đơn hàng.'],
    ];
    $msg = isset($_GET['msg']) && isset($thong_bao[$_GET['msg']]) ? $thong_bao[$_GET['msg']] : null;

    $ADMIN_ROOT = '../';
    $active_page = 'don-hang';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đơn hàng - Admin Viết Sơn Achieva</title>
    <link rel="shortcut icon" href="../../assets/images/icon/logo VS_icon.jpg"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-layout.css">
    <link rel="stylesheet" href="../assets/css/article.css">
    <style>
        /* ── Orders Page Premium Styles ── */
        :root {
            --dh-primary: #1e3c72;
            --dh-surface: #ffffff;
            --dh-bg: #f0f2f8;
            --dh-ink: #1a202c;
            --dh-sub: #64748b;
            --dh-border: #e2e8f0;
            --dh-radius: 16px;
            --dh-shadow: 0 1px 3px rgba(0,0,0,.05), 0 4px 16px rgba(0,0,0,.06);
            --dh-shadow-hover: 0 6px 24px rgba(30,60,114,.12);
        }

        /* Page header */
        .dh-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .dh-page-header h1 {
            font-family: 'Inter', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: var(--dh-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .dh-h1-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 15px;
        }
        .dh-header-sub { font-size: 13px; color: var(--dh-sub); margin-left: 48px; margin-top: 2px; }

        /* Filter bar */
        .dh-filter-bar {
            background: var(--dh-surface);
            border-radius: var(--dh-radius);
            padding: 16px 20px;
            box-shadow: var(--dh-shadow);
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }
        .dh-filter-bar select,
        .dh-filter-bar input[type="text"] {
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            border: 1.5px solid var(--dh-border);
            border-radius: 10px;
            padding: 9px 14px;
            background: #f8faff;
            color: var(--dh-ink);
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }
        .dh-filter-bar select { min-width: 180px; }
        .dh-filter-bar input[type="text"] { flex: 1; min-width: 200px; }
        .dh-filter-bar select:focus,
        .dh-filter-bar input[type="text"]:focus {
            border-color: #2a5298;
            box-shadow: 0 0 0 3px rgba(42,82,152,.12);
            background: #fff;
        }
        .dh-btn-filter {
            display: inline-flex; align-items: center; gap: 7px;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #fff; border: none; border-radius: 10px;
            padding: 9px 18px; font-size: 14px; font-weight: 600;
            cursor: pointer; font-family: 'Inter', sans-serif;
            transition: transform .15s, box-shadow .15s;
            box-shadow: 0 3px 10px rgba(30,60,114,.22);
        }
        .dh-btn-filter:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(30,60,114,.3); }
        .dh-btn-clear {
            display: inline-flex; align-items: center; gap: 6px;
            background: #f1f5f9; color: var(--dh-sub);
            border-radius: 10px; padding: 9px 16px;
            font-size: 14px; font-weight: 600; text-decoration: none;
            font-family: 'Inter', sans-serif;
            transition: background .12s;
        }
        .dh-btn-clear:hover { background: #e2e8f0; color: var(--dh-ink); }

        /* Table card */
        .dh-card {
            background: var(--dh-surface);
            border-radius: var(--dh-radius);
            box-shadow: var(--dh-shadow);
            overflow: hidden;
        }
        .dh-card-header {
            padding: 18px 22px 14px;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid var(--dh-border);
        }
        .dh-card-header h2 {
            font-size: 15px; font-weight: 700;
            color: var(--dh-primary); margin: 0;
            display: flex; align-items: center; gap: 8px;
        }
        .dh-card-count {
            font-size: 12px; font-weight: 700;
            background: #eff6ff; color: #2563eb;
            padding: 3px 10px; border-radius: 20px;
        }

        /* Table */
        .dh-table-wrap { overflow-x: auto; }
        .dh-table { width: 100%; border-collapse: collapse; font-size: 13.5px; font-family: 'Inter', sans-serif; }
        .dh-table thead th {
            padding: 11px 14px;
            text-align: left; font-size: 11px; font-weight: 700;
            color: var(--dh-sub); text-transform: uppercase;
            letter-spacing: .05em; white-space: nowrap;
            background: #f8faff;
            border-bottom: 1px solid var(--dh-border);
        }
        .dh-table tbody tr { border-bottom: 1px solid #f1f5f9; transition: background .1s; }
        .dh-table tbody tr:last-child { border-bottom: none; }
        .dh-table tbody tr:hover { background: #f5f8ff; }
        .dh-table td { padding: 12px 14px; color: var(--dh-ink); vertical-align: middle; }
        .dh-table td.mono { font-size: 13px; font-weight: 700; color: var(--dh-primary); letter-spacing: .01em; }
        .dh-table td.amount { font-weight: 700; color: #15803d; }
        .dh-table td.dt { font-size: 12.5px; color: var(--dh-sub); }
        .dh-table .name-cell { font-weight: 600; }

        /* Badges per status */
        .dh-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 11px; border-radius: 20px;
            font-size: 11.5px; font-weight: 700;
            white-space: nowrap;
        }
        .dh-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; display: inline-block; }
        .dh-badge.s0 { background: #fef9c3; color: #92400e; } /* Chờ xử lý */
        .dh-badge.s0::before { background: #d97706; }
        .dh-badge.s1 { background: #dbeafe; color: #1d4ed8; } /* Đã xác nhận */
        .dh-badge.s1::before { background: #2563eb; }
        .dh-badge.s2 { background: #e0f2fe; color: #0369a1; } /* Đang giao */
        .dh-badge.s2::before { background: #0ea5e9; }
        .dh-badge.s3 { background: #dcfce7; color: #15803d; } /* Hoàn thành */
        .dh-badge.s3::before { background: #16a34a; }
        .dh-badge.s4 { background: #fee2e2; color: #b91c1c; } /* Đã huỷ */
        .dh-badge.s4::before { background: #dc2626; }

        /* Action buttons */
        .dh-actions { display: flex; align-items: center; gap: 7px; }
        .dh-btn-view {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 5px 12px; border-radius: 8px;
            font-size: 12.5px; font-weight: 600; text-decoration: none;
            background: #eff6ff; color: #1e3c72;
            transition: background .12s, transform .12s;
        }
        .dh-btn-view:hover { background: #dbeafe; transform: translateY(-1px); }
        .dh-btn-del {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 5px 12px; border-radius: 8px;
            font-size: 12.5px; font-weight: 600; text-decoration: none;
            background: #fef2f2; color: #b91c1c;
            transition: background .12s, transform .12s;
        }
        .dh-btn-del:hover { background: #fee2e2; transform: translateY(-1px); }

        /* Empty */
        .dh-empty {
            text-align: center; padding: 48px 20px;
            color: var(--dh-sub); font-size: 14px;
        }
        .dh-empty i { font-size: 36px; opacity: .25; display: block; margin-bottom: 10px; }

        /* Pagination */
        .dh-pagination {
            display: flex; align-items: center; justify-content: center;
            gap: 6px; padding: 16px 22px;
            border-top: 1px solid var(--dh-border);
        }
        .dh-pagination a {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 34px; height: 34px; border-radius: 9px;
            font-size: 13px; font-weight: 600; text-decoration: none;
            color: var(--dh-sub); background: #f1f5f9;
            transition: background .12s, color .12s;
        }
        .dh-pagination a:hover { background: #dbeafe; color: #1e3c72; }
        .dh-pagination a.active { background: linear-gradient(135deg,#1e3c72,#2a5298); color: #fff; box-shadow: 0 2px 8px rgba(30,60,114,.22); }
        .dh-pagination a.disabled { opacity: .4; pointer-events: none; }

        /* Flash messages */
        .dh-flash {
            display: flex; align-items: center; gap: 10px;
            padding: 13px 18px; border-radius: 12px; margin-bottom: 16px;
            font-size: 14px; font-weight: 600;
        }
        .dh-flash.success { background: #dcfce7; color: #15803d; border-left: 4px solid #16a34a; }
        .dh-flash.error   { background: #fee2e2; color: #b91c1c; border-left: 4px solid #dc2626; }

        @keyframes dhFade { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
        .dh-card, .dh-filter-bar { animation: dhFade .28s ease both; }
        .dh-card { animation-delay: .05s; }
    </style>
</head>

<body>
    <div class="admin-shell">
        <?php include '../includes/sidebar.php'; ?>

        <main class="admin-main" style="font-family:'Inter',sans-serif;">

            <!-- Header -->
            <div class="dh-page-header">
                <div>
                    <h1>
                        <span class="dh-h1-icon"><i class="fa-solid fa-receipt"></i></span>
                        Quản lý Đơn hàng
                    </h1>
                    <div class="dh-header-sub">
                        <i class="fa-regular fa-calendar"></i>&nbsp;<?php echo date('d/m/Y'); ?> &nbsp;·&nbsp;
                        Tổng cộng <?php echo $tong_so_don; ?> đơn hàng
                    </div>
                </div>
            </div>

            <?php if ($msg): ?>
                <div class="dh-flash <?php echo $msg[0]; ?>">
                    <i class="fa-solid <?php echo $msg[0] === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'; ?>"></i>
                    <?php echo htmlspecialchars($msg[1]); ?>
                </div>
            <?php endif; ?>

            <!-- Filter Bar -->
            <form action="" method="GET" class="dh-filter-bar">
                <i class="fa-solid fa-sliders" style="color:#64748b;font-size:15px;"></i>
                <select name="trang_thai">
                    <option value="">Tất cả trạng thái</option>
                    <?php foreach ($trang_thai_nhan as $ma => $tt): ?>
                        <option value="<?php echo $ma; ?>" <?php echo $trang_thai_loc === $ma ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($tt[0]); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="q" placeholder="🔍  Tìm tên, SĐT hoặc mã đơn..." value="<?php echo htmlspecialchars($tu_khoa_loc); ?>">
                <button type="submit" class="dh-btn-filter"><i class="fa-solid fa-filter"></i> Lọc</button>
                <?php if ($trang_thai_loc !== -1 || $tu_khoa_loc !== ''): ?>
                    <a href="don-hang.php" class="dh-btn-clear"><i class="fa-solid fa-xmark"></i> Xoá lọc</a>
                <?php endif; ?>
            </form>

            <!-- Table Card -->
            <div class="dh-card">
                <div class="dh-card-header">
                    <h2><i class="fa-solid fa-list-ul" style="color:#2563eb;font-size:14px;"></i> Danh sách đơn hàng</h2>
                    <span class="dh-card-count"><?php echo $tong_so_don; ?> đơn</span>
                </div>

                <?php if (count($danh_sach) === 0): ?>
                    <div class="dh-empty">
                        <i class="fa-solid fa-box-open"></i>
                        Chưa có đơn hàng nào phù hợp.
                    </div>
                <?php else: ?>
                    <div class="dh-table-wrap">
                        <table class="dh-table">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Khách hàng</th>
                                    <th>Số điện thoại</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày đặt</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($danh_sach as $dh):
                                    $tt_key = (int) $dh['trang_thai'];
                                ?>
                                    <tr>
                                        <td class="mono">#<?php echo (int) $dh['ma_don_hang']; ?></td>
                                        <td class="name-cell"><?php echo htmlspecialchars($dh['ten_khach_hang']); ?></td>
                                        <td><?php echo htmlspecialchars($dh['so_dien_thoai']); ?></td>
                                        <td class="amount"><?php echo number_format((int) $dh['tong_tien'], 0, ',', '.'); ?>₫</td>
                                        <td>
                                            <span class="dh-badge s<?php echo $tt_key; ?>">
                                                <?php echo htmlspecialchars($trang_thai_nhan[$tt_key][0]); ?>
                                            </span>
                                        </td>
                                        <td class="dt"><?php echo date('d/m/Y H:i', strtotime($dh['ngay_dat'])); ?></td>
                                        <td>
                                            <div class="dh-actions">
                                                <a class="dh-btn-view" href="chi-tiet-don-hang.php?id=<?php echo (int) $dh['ma_don_hang']; ?>">
                                                    <i class="fa-solid fa-eye"></i> Xem
                                                </a>
                                                <a class="dh-btn-del" href="xuly-don-hang.php?action=xoa&id=<?php echo (int) $dh['ma_don_hang']; ?>"
                                                    onclick="return confirm('Xoá đơn hàng #<?php echo (int) $dh['ma_don_hang']; ?>? Hành động không thể hoàn tác.');">
                                                    <i class="fa-solid fa-trash-can"></i> Xoá
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ($tong_so_trang > 1): ?>
                        <div class="dh-pagination">
                            <a class="<?php echo $trang_hien_tai <= 1 ? 'disabled' : ''; ?>"
                               href="<?php echo xay_url_trang_don(max(1, $trang_hien_tai - 1)); ?>">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>
                            <?php for ($i = 1; $i <= $tong_so_trang; $i++): ?>
                                <a class="<?php echo $i === $trang_hien_tai ? 'active' : ''; ?>"
                                   href="<?php echo xay_url_trang_don($i); ?>"><?php echo $i; ?></a>
                            <?php endfor; ?>
                            <a class="<?php echo $trang_hien_tai >= $tong_so_trang ? 'disabled' : ''; ?>"
                               href="<?php echo xay_url_trang_don(min($tong_so_trang, $trang_hien_tai + 1)); ?>">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

        </main>
    </div>
</body>

</html>
