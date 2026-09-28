<?php
    require_once 'config/config.php';
    yeu_cau_dang_nhap();

    $tong_san_pham    = (int) $pdo->query("SELECT COUNT(*) FROM san_pham")->fetchColumn();
    $tong_bai_viet    = (int) $pdo->query("SELECT COUNT(*) FROM article")->fetchColumn();
    $tong_danh_muc    = (int) $pdo->query("SELECT COUNT(*) FROM danh_muc")->fetchColumn();
    $tong_thuong_hieu = (int) $pdo->query("SELECT COUNT(*) FROM thuong_hieu")->fetchColumn();

    // Thống kê đơn hàng theo trạng thái
    $tong_don_hang  = (int) $pdo->query("SELECT COUNT(*) FROM don_hang")->fetchColumn();
    $don_hoan_thanh = (int) $pdo->query("SELECT COUNT(*) FROM don_hang WHERE trang_thai = 3")->fetchColumn();
    $don_dang_xl    = (int) $pdo->query("SELECT COUNT(*) FROM don_hang WHERE trang_thai IN (1,2)")->fetchColumn();
    $don_cho_xl     = (int) $pdo->query("SELECT COUNT(*) FROM don_hang WHERE trang_thai = 0")->fetchColumn();
    $ty_le_hoan_thanh = $tong_don_hang > 0 ? round($don_hoan_thanh / $tong_don_hang * 100) : 0;

    // Doanh thu từ đơn đã hoàn thành
    $tong_doanh_thu = (int) $pdo->query("SELECT COALESCE(SUM(tong_tien), 0) FROM don_hang WHERE trang_thai = 3")->fetchColumn();

    // Đơn hàng mới nhất
    $don_hang_moi = $pdo->query("SELECT * FROM don_hang ORDER BY ngay_dat DESC, ma_don_hang DESC LIMIT 6")->fetchAll(PDO::FETCH_ASSOC);

    $nhan_trang_thai_dh = [
        0 => ['text' => 'Chờ xử lý',   'class' => 'off'],
        1 => ['text' => 'Đã xác nhận', 'class' => 'on'],
        2 => ['text' => 'Đang giao',   'class' => 'on'],
        3 => ['text' => 'Hoàn thành',  'class' => 'on'],
        4 => ['text' => 'Đã huỷ',      'class' => 'off'],
    ];

    // Số bài viết theo chuyên mục
    $nhan_chuyen_muc = [];
    $so_luong_chuyen_muc = [];
    foreach ($article_categories as $cm) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM article WHERE article_linh = :linh");
        $stmt->execute([':linh' => $cm]);
        $so_luong = (int) $stmt->fetchColumn();
        if ($so_luong > 0) {
            $nhan_chuyen_muc[] = trim($cm);
            $so_luong_chuyen_muc[] = $so_luong;
        }
    }

    // Bài viết mới nhất
    $bai_viet_moi = $pdo->query("SELECT * FROM article ORDER BY article_date DESC, article_id DESC LIMIT 6")->fetchAll(PDO::FETCH_ASSOC);

    $ADMIN_ROOT = '';
    $active_page = 'dashboard';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
    <link rel="shortcut icon" href="<?php echo htmlspecialchars(asset_url('../assets/images/icon/logo VS_icon.jpg')); ?>"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(asset_url('assets/css/admin-layout.css')); ?>">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(asset_url('assets/css/article.css')); ?>">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(asset_url('assets/css/dashboard.css')); ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <style>
        /* ── Dashboard Premium Redesign ── */
        :root {
            --db-bg: #f0f2f8;
            --db-primary: #1e3c72;
            --db-accent: #ffd700;
            --db-surface: #ffffff;
            --db-ink: #1a202c;
            --db-sub: #64748b;
            --db-border: #e2e8f0;
            --db-radius: 18px;
            --db-radius-sm: 12px;
            --db-shadow: 0 1px 3px rgba(0,0,0,.05), 0 4px 16px rgba(0,0,0,.06);
            --db-shadow-hover: 0 8px 32px rgba(30,60,114,.14);
        }
        .db-wrap { font-family:'Inter','Montserrat',sans-serif; color:var(--db-ink); }
        .db-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
        .db-header-left h1 { font-size:22px; font-weight:800; color:var(--db-primary); margin:0 0 2px; display:flex; align-items:center; gap:10px; }
        .db-h1-icon { width:38px; height:38px; background:linear-gradient(135deg,#1e3c72,#2a5298); border-radius:10px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:16px; }
        .db-header-date { font-size:13px; color:var(--db-sub); margin-left:48px; margin-top:3px; }
        .db-btn-new { display:inline-flex; align-items:center; gap:8px; background:linear-gradient(135deg,#1e3c72,#2a5298); color:#fff; border-radius:10px; padding:10px 20px; font-size:14px; font-weight:600; text-decoration:none; transition:transform .15s ease,box-shadow .15s ease; box-shadow:0 4px 12px rgba(30,60,114,.25); }
        .db-btn-new:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(30,60,114,.35); }
        /* stat cards */
        .db-statgrid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:20px; }
        .db-stat { background:var(--db-surface); border-radius:var(--db-radius); padding:20px 22px; box-shadow:var(--db-shadow); text-decoration:none; color:var(--db-ink); display:flex; align-items:center; gap:16px; transition:transform .18s ease,box-shadow .18s ease; position:relative; overflow:hidden; }
        .db-stat::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; border-radius:var(--db-radius) var(--db-radius) 0 0; }
        .db-stat.s1::before { background:linear-gradient(90deg,#1e3c72,#2a5298); }
        .db-stat.s2::before { background:linear-gradient(90deg,#16a34a,#22c55e); }
        .db-stat.s3::before { background:linear-gradient(90deg,#d97706,#f59e0b); }
        .db-stat.s4::before { background:linear-gradient(90deg,#dc2626,#ef4444); }
        .db-stat:hover { transform:translateY(-3px); box-shadow:var(--db-shadow-hover); }
        .db-stat-icon { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:20px; flex:none; }
        .db-stat.s1 .db-stat-icon { background:#eff6ff; color:#1e3c72; }
        .db-stat.s2 .db-stat-icon { background:#f0fdf4; color:#16a34a; }
        .db-stat.s3 .db-stat-icon { background:#fffbeb; color:#d97706; }
        .db-stat.s4 .db-stat-icon { background:#fef2f2; color:#dc2626; }
        .db-stat-info { flex:1; min-width:0; }
        .db-stat-label { font-size:12px; font-weight:600; color:var(--db-sub); text-transform:uppercase; letter-spacing:.04em; margin-bottom:4px; }
        .db-stat-value { font-size:30px; font-weight:800; color:var(--db-primary); line-height:1; }
        .db-stat-arrow { font-size:14px; color:var(--db-border); }
        /* grid */
        .db-row { display:grid; grid-template-columns:1.4fr 1fr; gap:16px; margin-bottom:16px; }
        .db-card { background:var(--db-surface); border-radius:var(--db-radius); padding:22px 24px; box-shadow:var(--db-shadow); }
        .db-card-title { font-size:15px; font-weight:700; color:var(--db-primary); margin:0 0 18px; display:flex; align-items:center; gap:8px; }
        .db-card-title i { width:28px; height:28px; background:linear-gradient(135deg,#1e3c72,#2a5298); border-radius:8px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:12px; }
        /* overview */
        .db-overview-list { display:flex; flex-direction:column; }
        .db-overview-item { display:flex; align-items:center; gap:14px; padding:12px 0; border-bottom:1px solid var(--db-border); }
        .db-overview-item:last-child { border-bottom:none; }
        .db-ov-icon { width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:15px; flex:none; }
        .db-ov-icon.blue  { background:#eff6ff; color:#2563eb; }
        .db-ov-icon.green { background:#f0fdf4; color:#16a34a; }
        .db-ov-icon.amber { background:#fffbeb; color:#d97706; }
        .db-ov-icon.pink  { background:#fdf4ff; color:#9333ea; }
        .db-ov-text { flex:1; }
        .db-ov-text .name { font-size:14px; font-weight:600; color:var(--db-ink); }
        .db-ov-text .desc { font-size:12px; color:var(--db-sub); margin-top:1px; }
        .db-ov-count { font-size:22px; font-weight:800; color:var(--db-primary); }
        /* ring */
        .db-ring-wrap { display:flex; flex-direction:column; align-items:center; gap:10px; padding:8px 0; }
        .db-ring { width:150px; height:150px; border-radius:50%; background:conic-gradient(#1e3c72 calc(var(--pct)*3.6deg),#e2e8f0 0); display:flex; align-items:center; justify-content:center; }
        .db-ring-inner { width:112px; height:112px; border-radius:50%; background:#fff; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:2px; }
        .db-ring-pct { font-size:28px; font-weight:800; color:var(--db-primary); line-height:1; }
        .db-ring-lbl { font-size:11px; color:var(--db-sub); }
        .db-ring-caption { font-size:13px; color:var(--db-sub); text-align:center; }
        /* revenue */
        .db-revenue-card { background:linear-gradient(135deg,#1e3c72 0%,#2a5298 60%,#1a4ab8 100%); border-radius:var(--db-radius); padding:22px 24px; color:#fff; box-shadow:0 4px 20px rgba(30,60,114,.28); }
        .db-revenue-card .label { font-size:12px; opacity:.75; font-weight:600; text-transform:uppercase; letter-spacing:.05em; margin-bottom:8px; display:flex; align-items:center; gap:6px; }
        .db-revenue-card .amount { font-size:26px; font-weight:800; letter-spacing:-.01em; }
        .db-revenue-card .sublabel { font-size:12px; opacity:.6; margin-top:6px; }
        /* tables */
        .db-table-scroll { overflow-x:auto; }
        .db-table { width:100%; border-collapse:collapse; font-size:13.5px; }
        .db-table thead tr { border-bottom:2px solid var(--db-border); }
        .db-table thead th { padding:8px 12px; text-align:left; font-size:11px; font-weight:700; color:var(--db-sub); text-transform:uppercase; letter-spacing:.05em; white-space:nowrap; }
        .db-table tbody tr { border-bottom:1px solid var(--db-border); transition:background .12s; }
        .db-table tbody tr:last-child { border-bottom:none; }
        .db-table tbody tr:hover { background:#f8faff; }
        .db-table td { padding:11px 12px; color:var(--db-ink); vertical-align:middle; }
        .db-table td.mono { font-family:'SF Mono',monospace; font-size:13px; }
        .db-table .title-cell { max-width:260px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-weight:500; }
        .db-panel-footer { padding:12px 0 2px; text-align:right; }
        .db-panel-footer a { font-size:13px; font-weight:600; color:var(--db-primary); text-decoration:none; display:inline-flex; align-items:center; gap:5px; transition:gap .12s; }
        .db-panel-footer a:hover { gap:8px; }
        .db-badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:11.5px; font-weight:600; }
        .db-badge.on  { background:#dcfce7; color:#15803d; }
        .db-badge.off { background:#fef2f2; color:#b91c1c; }
        .db-badge.pending { background:#fef9c3; color:#92400e; }
        .chart-empty { font-size:13px; color:var(--db-sub); text-align:center; padding:32px 0; }
        @media(max-width:1024px){ .db-statgrid{ grid-template-columns:repeat(2,1fr); } }
        @media(max-width:768px){ .db-row{ grid-template-columns:1fr; } }
        @keyframes dbFadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
        .db-statgrid,.db-row,.db-card{ animation:dbFadeUp .3s ease both; }
    </style>
</head>

<body>
    <div class="admin-shell">
        <?php include 'includes/sidebar.php'; ?>

        <main class="admin-main db-wrap">

            <!-- Header -->
            <div class="db-header">
                <div class="db-header-left">
                    <h1>
                        <span class="db-h1-icon"><i class="fa-solid fa-gauge-high"></i></span>
                        Dashboard
                    </h1>
                    <div class="db-header-date">
                        <i class="fa-regular fa-calendar"></i>&nbsp;<?php echo date('l, d/m/Y'); ?>
                    </div>
                </div>
                <a href="article/them.php" class="db-btn-new">
                    <i class="fa-solid fa-plus"></i> Đăng bài viết mới
                </a>
            </div>

            <!-- Stat Cards -->
            <div class="db-statgrid">
                <a href="quanly/don-hang.php" class="db-stat s1">
                    <div class="db-stat-icon"><i class="fa-solid fa-bag-shopping"></i></div>
                    <div class="db-stat-info">
                        <div class="db-stat-label">Tổng đơn hàng</div>
                        <div class="db-stat-value"><?php echo $tong_don_hang; ?></div>
                    </div>
                    <i class="fa-solid fa-chevron-right db-stat-arrow"></i>
                </a>
                <a href="quanly/don-hang.php?trang_thai=3" class="db-stat s2">
                    <div class="db-stat-icon"><i class="fa-solid fa-circle-check"></i></div>
                    <div class="db-stat-info">
                        <div class="db-stat-label">Hoàn thành</div>
                        <div class="db-stat-value"><?php echo $don_hoan_thanh; ?></div>
                    </div>
                    <i class="fa-solid fa-chevron-right db-stat-arrow"></i>
                </a>
                <a href="quanly/don-hang.php?trang_thai=1,2" class="db-stat s3">
                    <div class="db-stat-icon"><i class="fa-solid fa-truck-fast"></i></div>
                    <div class="db-stat-info">
                        <div class="db-stat-label">Đang xử lý</div>
                        <div class="db-stat-value"><?php echo $don_dang_xl; ?></div>
                    </div>
                    <i class="fa-solid fa-chevron-right db-stat-arrow"></i>
                </a>
                <a href="quanly/don-hang.php?trang_thai=0" class="db-stat s4">
                    <div class="db-stat-icon"><i class="fa-solid fa-clock"></i></div>
                    <div class="db-stat-info">
                        <div class="db-stat-label">Chờ xử lý</div>
                        <div class="db-stat-value"><?php echo $don_cho_xl; ?></div>
                    </div>
                    <i class="fa-solid fa-chevron-right db-stat-arrow"></i>
                </a>
            </div>

            <!-- Row 1: Content Overview + Chart -->
            <div class="db-row">
                <div class="db-card">
                    <div class="db-card-title">
                        <i class="fa-solid fa-chart-pie"></i> Tổng quan nội dung
                    </div>
                    <div class="db-overview-list">
                        <div class="db-overview-item">
                            <div class="db-ov-icon blue"><i class="fa-solid fa-box"></i></div>
                            <div class="db-ov-text">
                                <div class="name">Sản phẩm</div>
                                <div class="desc">Đang bán trên hệ thống</div>
                            </div>
                            <div class="db-ov-count"><?php echo $tong_san_pham; ?></div>
                        </div>
                        <div class="db-overview-item">
                            <div class="db-ov-icon green"><i class="fa-solid fa-newspaper"></i></div>
                            <div class="db-ov-text">
                                <div class="name">Bài viết</div>
                                <div class="desc">Tin tức đã đăng</div>
                            </div>
                            <div class="db-ov-count"><?php echo $tong_bai_viet; ?></div>
                        </div>
                        <div class="db-overview-item">
                            <div class="db-ov-icon amber"><i class="fa-solid fa-layer-group"></i></div>
                            <div class="db-ov-text">
                                <div class="name">Danh mục</div>
                                <div class="desc">Nhóm sản phẩm</div>
                            </div>
                            <div class="db-ov-count"><?php echo $tong_danh_muc; ?></div>
                        </div>
                        <div class="db-overview-item">
                            <div class="db-ov-icon pink"><i class="fa-solid fa-tags"></i></div>
                            <div class="db-ov-text">
                                <div class="name">Thương hiệu</div>
                                <div class="desc">Đang hợp tác</div>
                            </div>
                            <div class="db-ov-count"><?php echo $tong_thuong_hieu; ?></div>
                        </div>
                    </div>
                </div>

                <div class="db-card">
                    <div class="db-card-title">
                        <i class="fa-solid fa-chart-bar"></i> Bài viết theo chuyên mục
                    </div>
                    <?php if (empty($nhan_chuyen_muc)): ?>
                        <div class="chart-empty">
                            <i class="fa-solid fa-inbox" style="font-size:28px;opacity:.3;display:block;margin-bottom:8px;"></i>
                            Chưa có bài viết nào gắn chuyên mục.
                        </div>
                    <?php else: ?>
                        <canvas id="bieuDoChuyenMuc" height="<?php echo max(140, count($nhan_chuyen_muc) * 38); ?>"></canvas>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Row 2: Recent Orders + Ring & Revenue -->
            <div class="db-row">
                <div class="db-card dash-table-panel">
                    <div class="db-card-title">
                        <i class="fa-solid fa-receipt"></i> Đơn hàng gần đây
                    </div>
                    <?php if (count($don_hang_moi) === 0): ?>
                        <div class="chart-empty">Chưa có đơn hàng nào.</div>
                    <?php else: ?>
                        <div class="db-table-scroll">
                            <table class="db-table">
                                <thead><tr>
                                    <th>Mã ĐH</th><th>Khách hàng</th><th>SĐT</th>
                                    <th>Tổng tiền</th><th>Ngày đặt</th><th>Trạng thái</th>
                                </tr></thead>
                                <tbody>
                                    <?php foreach ($don_hang_moi as $dh):
                                        $tt = $nhan_trang_thai_dh[(int) $dh['trang_thai']];
                                        $badge = 'off';
                                        if ((int)$dh['trang_thai'] === 3) $badge = 'on';
                                        elseif ((int)$dh['trang_thai'] === 0) $badge = 'pending';
                                        elseif (in_array((int)$dh['trang_thai'], [1,2])) $badge = 'on';
                                    ?>
                                    <tr>
                                        <td class="mono">#<?php echo (int) $dh['ma_don_hang']; ?></td>
                                        <td><?php echo htmlspecialchars($dh['ten_khach_hang']); ?></td>
                                        <td><?php echo htmlspecialchars($dh['so_dien_thoai']); ?></td>
                                        <td style="font-weight:600;"><?php echo number_format((int) $dh['tong_tien'], 0, ',', '.'); ?>đ</td>
                                        <td style="color:var(--db-sub);"><?php echo date('d/m/Y H:i', strtotime($dh['ngay_dat'])); ?></td>
                                        <td><span class="db-badge <?php echo $badge; ?>"><?php echo $tt['text']; ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="db-panel-footer">
                            <a href="quanly/don-hang.php">Xem tất cả đơn hàng <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    <?php endif; ?>
                </div>

                <div style="display:flex;flex-direction:column;gap:16px;">
                    <div class="db-card">
                        <div class="db-card-title">
                            <i class="fa-solid fa-circle-half-stroke"></i> Tỉ lệ hoàn thành
                        </div>
                        <div class="db-ring-wrap">
                            <div class="db-ring" style="--pct:<?php echo $ty_le_hoan_thanh; ?>">
                                <div class="db-ring-inner">
                                    <div class="db-ring-pct"><?php echo $ty_le_hoan_thanh; ?>%</div>
                                    <div class="db-ring-lbl">hoàn thành</div>
                                </div>
                            </div>
                            <div class="db-ring-caption">
                                <?php echo $don_hoan_thanh; ?> / <?php echo $tong_don_hang; ?> đơn hoàn thành
                            </div>
                        </div>
                    </div>
                    <div class="db-revenue-card">
                        <div class="label"><i class="fa-solid fa-sack-dollar"></i> Doanh thu hoàn thành</div>
                        <div class="amount"><?php echo number_format($tong_doanh_thu, 0, ',', '.'); ?>đ</div>
                        <div class="sublabel">Từ <?php echo $don_hoan_thanh; ?> đơn đã hoàn thành</div>
                    </div>
                </div>
            </div>

            <!-- Recent Articles -->
            <div class="db-card" style="margin-bottom:16px;">
                <div class="db-card-title">
                    <i class="fa-solid fa-newspaper"></i> Bài viết mới nhất
                </div>
                <?php if (count($bai_viet_moi) === 0): ?>
                    <div class="chart-empty">Chưa có bài viết nào.</div>
                <?php else: ?>
                    <div class="db-table-scroll">
                        <table class="db-table">
                            <thead><tr>
                                <th>Tiêu đề</th><th>Chuyên mục</th>
                                <th>Ngày đăng</th><th>Trạng thái</th><th>Thao tác</th>
                            </tr></thead>
                            <tbody>
                                <?php foreach ($bai_viet_moi as $a): ?>
                                <tr>
                                    <td class="title-cell"><?php echo htmlspecialchars($a['article_title']); ?></td>
                                    <td><?php echo htmlspecialchars(trim($a['article_linh'])); ?></td>
                                    <td style="color:var(--db-sub);"><?php echo date('d/m/Y', strtotime($a['article_date'])); ?></td>
                                    <td>
                                        <?php if ((int) $a['article_status'] === 1): ?>
                                            <span class="db-badge on">Hiển thị</span>
                                        <?php else: ?>
                                            <span class="db-badge off">Đã ẩn</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="article/sua.php?id=<?php echo (int) $a['article_id']; ?>"
                                           style="display:inline-flex;align-items:center;gap:5px;color:#1e3c72;font-size:13px;font-weight:600;text-decoration:none;padding:4px 10px;border-radius:7px;background:#eff6ff;transition:background .12s;">
                                            <i class="fa-solid fa-pen-to-square"></i> Sửa
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="db-panel-footer">
                        <a href="article/dah-sach-bai-viet.php">Xem tất cả bài viết <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                <?php endif; ?>
            </div>

        </main>
    </div>

    <script>
        <?php if (!empty($nhan_chuyen_muc)): ?>
        new Chart(document.getElementById('bieuDoChuyenMuc'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($nhan_chuyen_muc); ?>,
                datasets: [{
                    data: <?php echo json_encode($so_luong_chuyen_muc); ?>,
                    backgroundColor: [
                        'rgba(30,60,114,.85)','rgba(22,163,74,.85)',
                        'rgba(217,119,6,.85)','rgba(147,51,234,.85)',
                        'rgba(220,38,38,.85)','rgba(14,165,233,.85)'
                    ],
                    borderRadius: 8,
                    maxBarThickness: 24
                }]
            },
            options: {
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { precision: 0, color: '#64748b', font: { size: 11 } },
                        grid: { color: '#f1f5f9' }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { color: '#1a202c', font: { size: 12, weight: '600' } }
                    }
                }
            }
        });
        <?php endif; ?>
    </script>
</body>

</html>