<?php
    require_once 'config/config.php';
    yeu_cau_dang_nhap();

    $tong_san_pham   = (int) $pdo->query("SELECT COUNT(*) FROM san_pham")->fetchColumn();
    $tong_bai_viet   = (int) $pdo->query("SELECT COUNT(*) FROM article")->fetchColumn();
    $tong_danh_muc   = (int) $pdo->query("SELECT COUNT(*) FROM danh_muc")->fetchColumn();
    $tong_thuong_hieu = (int) $pdo->query("SELECT COUNT(*) FROM thuong_hieu")->fetchColumn();

    // Số bài viết đăng theo từng tháng, 6 tháng gần nhất
    $thang_nhan = [];
    $so_luong_theo_thang = [];
    for ($i = 5; $i >= 0; $i--) {
        $moc_thang = date('Y-m', strtotime("-$i months"));
        $thang_nhan[] = 'Th ' . date('n/Y', strtotime("-$i months"));
        $so_luong_theo_thang[$moc_thang] = 0;
    }
    $rows = $pdo->query("SELECT DATE_FORMAT(article_date, '%Y-%m') AS moc_thang, COUNT(*) AS so_luong
        FROM article
        WHERE article_date >= DATE_SUB(CURDATE(), INTERVAL 5 MONTH)
        GROUP BY moc_thang")->fetchAll(PDO::FETCH_KEY_PAIR);
    foreach ($rows as $moc_thang => $so_luong) {
        if (isset($so_luong_theo_thang[$moc_thang])) {
            $so_luong_theo_thang[$moc_thang] = (int) $so_luong;
        }
    }

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
</head>

<body>
    <div class="admin-shell">
        <?php include 'includes/sidebar.php'; ?>

        <main class="admin-main">
            <div class="admin-main-header">
                <div>
                    <h1><img width="30" height="30" src="https://img.icons8.com/color/48/dashboard-layout.png" alt="dashboard-layout"/>Dashboard</h1>
                    <div class="subtitle"><?php echo date('d/m/Y'); ?></div>
                </div>
                <a href="article/them.php" class="link-out"><i class="fa-solid fa-plus"></i> Đăng bài viết mới</a>
            </div>

            <div class="stat-grid">
                <div class="stat-card">
                    <div class="icon red"><i class="fa-solid fa-box"></i></div>
                    <div>
                        <div class="value"><?php echo $tong_san_pham; ?></div>
                        <div class="label">Sản phẩm</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon blue"><i class="fa-solid fa-newspaper"></i></div>
                    <div>
                        <div class="value"><?php echo $tong_bai_viet; ?></div>
                        <div class="label">Bài viết</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon green"><i class="fa-solid fa-layer-group"></i></div>
                    <div>
                        <div class="value"><?php echo $tong_danh_muc; ?></div>
                        <div class="label">Danh mục</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon orange"><i class="fa-solid fa-tags"></i></div>
                    <div>
                        <div class="value"><?php echo $tong_thuong_hieu; ?></div>
                        <div class="label">Thương hiệu</div>
                    </div>
                </div>
            </div>

            <div class="dash-grid">
                <div class="dash-panel">
                    <h2>Bài viết đăng theo tháng</h2>
                    <canvas id="bieuDoThang" height="110"></canvas>
                </div>
                <div class="dash-panel">
                    <h2>Bài viết theo chuyên mục</h2>
                    <?php if (empty($nhan_chuyen_muc)): ?>
                        <div class="chart-empty">Chưa có bài viết nào gắn chuyên mục.</div>
                    <?php else: ?>
                        <canvas id="bieuDoChuyenMuc" height="220"></canvas>
                    <?php endif; ?>
                </div>
            </div>

            <div class="dash-panel dash-table-panel">
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
        new Chart(document.getElementById('bieuDoThang'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($thang_nhan); ?>,
                datasets: [{
                    label: 'Bài viết',
                    data: <?php echo json_encode(array_values($so_luong_theo_thang)); ?>,
                    backgroundColor: '#1e3c72',
                    borderRadius: 6,
                    maxBarThickness: 40
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
            
        });

        <?php if (!empty($nhan_chuyen_muc)): ?>
        new Chart(document.getElementById('bieuDoChuyenMuc'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($nhan_chuyen_muc); ?>,
                datasets: [{
                    data: <?php echo json_encode($so_luong_chuyen_muc); ?>,
                    backgroundColor: ['#DC2626', '#2563eb', '#059669', '#d97706', '#7c3aed', '#0891b2', '#db2777']
                }]
            },
            options: {
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
            }
        });
        <?php endif; ?>
    </script>
</body>

</html>
