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
    <link rel="shortcut icon" href="../assets/images/icon/logo VS_icon.jpg"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin-layout.css">
    <link rel="stylesheet" href="assets/css/article.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <style>
        :root{
            --db-green-dark:#1e3c72;
            --db-green:#ffd700;
            --db-green-soft:#fff8dc;
            --db-ink:#1b2420;
            --db-sub:#5c6b8a;
            --db-line:#eef2f0;
            --db-radius:20px;
        }
        .db2-wrap{ font-family:'Montserrat', sans-serif; color:var(--db-ink); }
        .db2-topgrid{
            display:grid;
            grid-template-columns:repeat(4,1fr);
            gap:16px;
            margin-bottom:18px;
        }
        .db2-stat{
            background:#fff;
            border-radius:var(--db-radius);
            padding:18px 20px;
            box-shadow:0 1px 2px rgba(20,30,25,.04);
            display:block;
            text-decoration:none;
            color:inherit;
            transition:transform .12s ease, box-shadow .12s ease;
            cursor:pointer;
        }
        .db2-stat:hover{
            transform:translateY(-2px);
            box-shadow:0 6px 16px rgba(20,30,25,.10);
        }
        .db2-stat.hi{
            background:#ffd700;
            color:#1e3c72;
        }
        .db2-stat .db2-label{ font-size:12px; color:var(--db-sub); margin-bottom:8px; }
        .db2-stat.hi .db2-label{ color:#1e3c72; opacity:.75; }
        .db2-stat .db2-value{ font-size:28px; font-weight:700; }

        .db2-row{ display:grid; grid-template-columns: 1.3fr 1fr; gap:16px; margin-bottom:16px; }
        .db2-card{
            background:#fff; border-radius:var(--db-radius); padding:20px 22px;
            box-shadow:0 1px 2px rgba(20,30,25,.04);
        }
        .db2-card h2{ font-size:14px; font-weight:700; margin:0 0 14px; }

        .db2-list{ display:flex; flex-direction:column; gap:12px; }
        .db2-list-item{ display:flex; align-items:center; gap:12px; }
        .db2-list-icon{
            width:38px; height:38px; border-radius:10px;
            display:flex; align-items:center; justify-content:center;
            background:#fff3b0; color:#1e3c72; font-size:15px; flex:none;
        }
        .db2-list-text{ flex:1; }
        .db2-list-text .t{ font-size:13px; font-weight:600; }
        .db2-list-text .s{ font-size:11px; color:var(--db-sub); }
        .db2-list-count{ font-size:16px; font-weight:700; color:var(--db-green-dark); }

        .db2-progress-wrap{ display:flex; flex-direction:column; align-items:center; gap:10px; }
        .db2-ring{
            width:140px; height:140px; border-radius:50%;
            background:conic-gradient(#1e3c72 calc(var(--pct) * 1%), #eef2f0 0);
            display:flex; align-items:center; justify-content:center;
        }
        .db2-ring-inner{
            width:108px; height:108px; border-radius:50%; background:#fff;
            display:flex; align-items:center; justify-content:center;
        }
        .db2-progress-num{ font-size:26px; font-weight:800; color:var(--db-green-dark); }
        .db2-progress-label{ font-size:12px; color:var(--db-sub); }

        .db2-revenue{
            background:#ffd700;
            color:#1e3c72; border-radius:var(--db-radius); padding:20px 22px;
            display:flex; flex-direction:column; justify-content:center; gap:6px;
        }
        .db2-revenue .l{ font-size:12px; color:#1e3c72; opacity:.75; }
        .db2-revenue .v{ font-size:24px; font-weight:800; }

        @media (max-width:900px){
            .db2-topgrid{ grid-template-columns:repeat(2,1fr); }
            .db2-row{ grid-template-columns:1fr; }
        }
    </style>
</head>

<body>
    <div class="admin-shell">
        <?php include 'includes/sidebar.php'; ?>

        <main class="admin-main db2-wrap">
            <div class="admin-main-header">
                <div>
                    <h1><img width="30" height="30" src="https://img.icons8.com/color/48/dashboard-layout.png" alt="dashboard-layout"/>Dashboard</h1>
                    <div class="subtitle"><?php echo date('d/m/Y'); ?></div>
                </div>
                <a href="article/them.php" class="link-out"><i class="fa-solid fa-plus"></i> Đăng bài viết mới</a>
            </div>

            <!-- Hàng thống kê đơn hàng theo trạng thái -->
            <div class="db2-topgrid">
                <a href="quanly/don-hang.php" class="db2-stat hi">
                    <div class="db2-label">Tổng đơn hàng</div>
                    <div class="db2-value"><?php echo $tong_don_hang; ?></div>
                </a>
                <a href="quanly/don-hang.php?trang_thai=3" class="db2-stat">
                    <div class="db2-label">Đơn hoàn thành</div>
                    <div class="db2-value"><?php echo $don_hoan_thanh; ?></div>
                </a>
                <a href="quanly/don-hang.php?trang_thai=1,2" class="db2-stat">
                    <div class="db2-label">Đang xử lý</div>
                    <div class="db2-value"><?php echo $don_dang_xl; ?></div>
                </a>
                <a href="quanly/don-hang.php?trang_thai=0" class="db2-stat">
                    <div class="db2-label">Chờ xử lý</div>
                    <div class="db2-value"><?php echo $don_cho_xl; ?></div>
                </a>
            </div>

            <!-- Hàng: Tổng quan nội dung + Bài viết theo chuyên mục -->
            <div class="db2-row">
                <div class="db2-card">
                    <h2>Tổng quan nội dung</h2>
                    <div class="db2-list">
                        <div class="db2-list-item">
                            <div class="db2-list-icon"><i class="fa-solid fa-box"></i></div>
                            <div class="db2-list-text">
                                <div class="t">Sản phẩm</div>
                                <div class="s">Đang bán trên hệ thống</div>
                            </div>
                            <div class="db2-list-count"><?php echo $tong_san_pham; ?></div>
                        </div>
                        <div class="db2-list-item">
                            <div class="db2-list-icon"><i class="fa-solid fa-newspaper"></i></div>
                            <div class="db2-list-text">
                                <div class="t">Bài viết</div>
                                <div class="s">Tin tức đã đăng</div>
                            </div>
                            <div class="db2-list-count"><?php echo $tong_bai_viet; ?></div>
                        </div>
                        <div class="db2-list-item">
                            <div class="db2-list-icon"><i class="fa-solid fa-layer-group"></i></div>
                            <div class="db2-list-text">
                                <div class="t">Danh mục</div>
                                <div class="s">Nhóm sản phẩm</div>
                            </div>
                            <div class="db2-list-count"><?php echo $tong_danh_muc; ?></div>
                        </div>
                        <div class="db2-list-item">
                            <div class="db2-list-icon"><i class="fa-solid fa-tags"></i></div>
                            <div class="db2-list-text">
                                <div class="t">Thương hiệu</div>
                                <div class="s">Đang hợp tác</div>
                            </div>
                            <div class="db2-list-count"><?php echo $tong_thuong_hieu; ?></div>
                        </div>
                    </div>
                </div>

                <div class="db2-card">
                    <h2>Bài viết theo chuyên mục</h2>
                    <?php if (empty($nhan_chuyen_muc)): ?>
                        <div class="chart-empty">Chưa có bài viết nào gắn chuyên mục.</div>
                    <?php else: ?>
                        <canvas id="bieuDoChuyenMuc" height="<?php echo max(140, count($nhan_chuyen_muc) * 34); ?>"></canvas>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Hàng: Đơn hàng gần đây + Tỉ lệ hoàn thành / Doanh thu -->
            <div class="db2-row">
                <div class="db2-card dash-table-panel" style="padding-bottom:8px;">
                    <h2>Đơn hàng gần đây</h2>
                    <?php if (count($don_hang_moi) === 0): ?>
                        <div class="admin-empty">Chưa có đơn hàng nào.</div>
                    <?php else: ?>
                        <div class="admin-table-wrap">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Mã ĐH</th>
                                        <th>Khách hàng</th>
                                        <th>SĐT</th>
                                        <th>Tổng tiền</th>
                                        <th>Ngày đặt</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($don_hang_moi as $dh): ?>
                                        <?php $tt = $nhan_trang_thai_dh[(int) $dh['trang_thai']]; ?>
                                        <tr>
                                            <td>#<?php echo (int) $dh['ma_don_hang']; ?></td>
                                            <td><?php echo htmlspecialchars($dh['ten_khach_hang']); ?></td>
                                            <td><?php echo htmlspecialchars($dh['so_dien_thoai']); ?></td>
                                            <td><?php echo number_format((int) $dh['tong_tien'], 0, ',', '.'); ?>đ</td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($dh['ngay_dat'])); ?></td>
                                            <td><span class="admin-badge <?php echo $tt['class']; ?>"><?php echo $tt['text']; ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="dash-panel-footer">
                            <a href="quanly/don-hang.php">Xem tất cả đơn hàng →</a>
                        </div>
                    <?php endif; ?>
                </div>

                <div style="display:flex; flex-direction:column; gap:16px;">
                    <div class="db2-card">
                        <h2>Tỉ lệ đơn hoàn thành</h2>
                        <div class="db2-progress-wrap">
                            <div class="db2-ring" style="--pct:<?php echo $ty_le_hoan_thanh; ?>;">
                                <div class="db2-ring-inner">
                                    <div class="db2-progress-num"><?php echo $ty_le_hoan_thanh; ?>%</div>
                                </div>
                            </div>
                            <div class="db2-progress-label"><?php echo $don_hoan_thanh; ?>/<?php echo $tong_don_hang; ?> đơn hoàn thành</div>
                        </div>
                    </div>
                    <div class="db2-revenue">
                        <div class="l">Doanh thu (đơn hoàn thành)</div>
                        <div class="v"><?php echo number_format($tong_doanh_thu, 0, ',', '.'); ?>đ</div>
                    </div>
                </div>
            </div>

            <div class="db2-card dash-table-panel">
                <h2>Bài viết mới nhất</h2>
                <?php if (count($bai_viet_moi) === 0): ?>
                    <div class="admin-empty">Chưa có bài viết nào.</div>
                <?php else: ?>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Tiêu đề</th>
                                    <th>Chuyên mục</th>
                                    <th>Ngày đăng</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bai_viet_moi as $a): ?>
                                    <tr>
                                        <td class="title-cell"><?php echo htmlspecialchars($a['article_title']); ?></td>
                                        <td><?php echo htmlspecialchars(trim($a['article_linh'])); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($a['article_date'])); ?></td>
                                        <td>
                                            <?php if ((int) $a['article_status'] === 1): ?>
                                                <span class="admin-badge on">Đang hiển thị</span>
                                            <?php else: ?>
                                                <span class="admin-badge off">Đã ẩn</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="admin-actions">
                                                <a class="edit" href="article/sua.php?id=<?php echo (int) $a['article_id']; ?>">Sửa</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="dash-panel-footer">
                        <a href="article/dah-sach-bai-viet.php">Xem tất cả bài viết →</a>
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
                    backgroundColor: '#1e3c72',
                    borderRadius: 6,
                    maxBarThickness: 22
                }]
            },
            options: {
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, ticks: { precision: 0 } },
                    y: { grid: { display: false } }
                }
            }
        });
        <?php endif; ?>

    </script>
</body>

</html>