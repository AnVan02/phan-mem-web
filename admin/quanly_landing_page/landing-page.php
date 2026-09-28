<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_NOI_DUNG], '../dang-nhap.php');

    $tu_khoa = isset($_GET['q']) ? trim($_GET['q']) : '';
    $sql_where = "WHERE 1=1";
    $tham_so = [];
    if ($tu_khoa !== '') {
        $sql_where .= " AND (policy_title LIKE :q OR policy_slug LIKE :q)";
        $tham_so[':q'] = '%' . $tu_khoa . '%';
    }

    $stats = $pdo->query("SELECT COUNT(*) AS tong, SUM(CASE WHEN policy_status = 1 THEN 1 ELSE 0 END) AS hien FROM policy_page")->fetch(PDO::FETCH_ASSOC);
    $tong = (int)$stats['tong'];
    $hien = (int)$stats['hien'];
    $an = $tong - $hien;

    $dem_stmt = $pdo->prepare("SELECT COUNT(*) FROM policy_page $sql_where");
    $dem_stmt->execute($tham_so);
    $tong_so_loc = (int) $dem_stmt->fetchColumn();

    $so_dong = 15;
    $tong_so_trang = max(1, (int) ceil($tong_so_loc / $so_dong));
    $trang_hien_tai = isset($_GET['trang']) ? (int) $_GET['trang'] : 1;
    if ($trang_hien_tai < 1) $trang_hien_tai = 1;
    if ($trang_hien_tai > $tong_so_trang) $trang_hien_tai = $tong_so_trang;
    $bat_dau = ($trang_hien_tai - 1) * $so_dong;

    $danh_sach_stmt = $pdo->prepare("SELECT * FROM policy_page $sql_where ORDER BY policy_updated DESC, policy_id DESC LIMIT :gioi_han OFFSET :bat_dau");
    foreach ($tham_so as $k => $v) {
        $danh_sach_stmt->bindValue($k, $v);
    }
    $danh_sach_stmt->bindValue(':gioi_han', $so_dong, PDO::PARAM_INT);
    $danh_sach_stmt->bindValue(':bat_dau', $bat_dau, PDO::PARAM_INT);
    $danh_sach_stmt->execute();
    $danh_sach = $danh_sach_stmt->fetchAll(PDO::FETCH_ASSOC);

    function xay_url_trang_lp($trang) {
        $params = $_GET;
        $params['trang'] = $trang;
        return 'landing-page.php?' . http_build_query($params);
    }

    $slug_co_san = ['bao-hanh', 've-chung-toi'];
    function duong_dan_xem_chinh_sach($slug) {
        if ($slug === 'bao-hanh') return '../../chinh-sach-bao-hanh.php';
        if ($slug === 've-chung-toi') return '../../ve-chung-toi.php';
        return '../../chinh-sach.php?slug=' . urlencode($slug);
    }

    $thong_bao = [
        'da_them'           => ['success', 'Đã thêm trang mới thành công.'],
        'da_sua'            => ['success', 'Đã cập nhật trang thành công.'],
        'da_xoa'            => ['success', 'Đã xoá trang.'],
        'loi_thieu_du_lieu' => ['error', 'Vui lòng nhập đầy đủ đường dẫn (slug) và tiêu đề.'],
        'loi_trung_slug'    => ['error', 'Đường dẫn (slug) này đã được sử dụng, vui lòng chọn đường dẫn khác.'],
        'loi_anh'           => ['error', 'Ảnh tải lên không hợp lệ (chỉ nhận jpg, jpeg, png, webp, gif).'],
    ];
    $msg = isset($_GET['msg']) && isset($thong_bao[$_GET['msg']]) ? $thong_bao[$_GET['msg']] : null;

    $ADMIN_ROOT = '../';
    $active_page = 'landing-page';
    $active_sub = 'landing-page';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Landing Page - Admin</title>
    <link rel="shortcut icon" href="../../assets/images/icon/logo VS_icon.jpg"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-layout.css">
    <link rel="stylesheet" href="../assets/css/landing-page.css">
</head>

<body>
    <div class="admin-shell">
        <?php include '../includes/sidebar.php'; ?>

        <main class="admin-main">

            <!-- Page Header -->
            <div class="lp-page-header">
                <div class="lp-page-header-left">
                    <div class="lp-page-icon">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <div>
                        <h1 class="lp-page-title">Quản lý Landing Page</h1>
                        <p class="lp-page-subtitle">Quản lý các trang nội dung tĩnh của website</p>
                    </div>
                </div>
                <a href="them.php" class="lp-add-btn">
                    <i class="fa-solid fa-plus"></i> Thêm trang mới
                </a>
            </div>

            <?php if ($msg): ?>
                <div class="lp-flash lp-flash-<?php echo $msg[0]; ?>">
                    <i class="fa-solid <?php echo $msg[0] === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'; ?>"></i>
                    <?php echo htmlspecialchars($msg[1]); ?>
                </div>
            <?php endif; ?>

            <!-- Stats cards -->
            <div class="lp-stats-row">
                <div class="lp-stat-card">
                    <div class="lp-stat-icon" style="background:#eef2ff;">
                        <i class="fa-solid fa-file-lines" style="color:#6366f1;"></i>
                    </div>
                    <div class="lp-stat-body">
                        <span class="lp-stat-num"><?php echo $tong; ?></span>
                        <span class="lp-stat-label">Tổng số trang</span>
                    </div>
                </div>
                <div class="lp-stat-card">
                    <div class="lp-stat-icon" style="background:#ecfdf5;">
                        <i class="fa-solid fa-eye" style="color:#10b981;"></i>
                    </div>
                    <div class="lp-stat-body">
                        <span class="lp-stat-num"><?php echo $hien; ?></span>
                        <span class="lp-stat-label">Đang hiển thị</span>
                    </div>
                </div>
                <div class="lp-stat-card">
                    <div class="lp-stat-icon" style="background:#fef3c7;">
                        <i class="fa-solid fa-eye-slash" style="color:#f59e0b;"></i>
                    </div>
                    <div class="lp-stat-body">
                        <span class="lp-stat-num"><?php echo $an; ?></span>
                        <span class="lp-stat-label">Đang ẩn</span>
                    </div>
                </div>
            </div>

            <!-- Main panel -->
            <div class="lp-panel">
                <div class="lp-panel-header">
                    <h2 class="lp-panel-title">
                        <i class="fa-solid fa-list"></i>
                        Tất cả trang <span class="lp-badge"><?php echo $tong; ?></span>
                    </h2>
                    <div class="lp-search-box">
                        <form action="" method="GET" style="display: flex; align-items: center; width: 100%;">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" name="q" id="lp-search" placeholder="Tìm kiếm trang..." value="<?php echo htmlspecialchars($tu_khoa); ?>">
                        </form>
                    </div>
                </div>

                <?php if ($tong_so_loc === 0): ?>
                    <div class="lp-empty">
                        <div class="lp-empty-icon">
                            <i class="fa-solid fa-file-circle-plus"></i>
                        </div>
                        <h3>Chưa có trang nào</h3>
                        <p>Hãy tạo trang đầu tiên cho website của bạn.</p>
                        <a href="them.php" class="lp-add-btn">
                            <i class="fa-solid fa-plus"></i> Thêm trang mới
                        </a>
                    </div>
                <?php else: ?>
                    <div class="lp-table-wrap">
                        <table class="lp-table" id="lp-table">
                            <thead>
                                <tr>
                                    <th style="width:50px;">STT</th>
                                    <th>TIÊU ĐỀ</th>
                                    <th>ĐƯỜNG DẪN (SLUG)</th>
                                    <th style="width:160px;">CẬP NHẬT LẦN CUỐI</th>
                                    <th style="width:130px;">TRẠNG THÁI</th>
                                    <th style="width:150px;">THAO TÁC</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($danh_sach as $i => $p): ?>
                                    <tr data-name="<?php echo htmlspecialchars(strtolower($p['policy_title'] . ' ' . $p['policy_slug'])); ?>">
                                        <td class="lp-td-num"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></td>
                                        <td>
                                            <div class="lp-title-cell">
                                                <div class="lp-title-icon">
                                                    <i class="fa-solid fa-file-alt"></i>
                                                </div>
                                                <div>
                                                    <span class="lp-title-text"><?php echo htmlspecialchars($p['policy_title']); ?></span>
                                                    <?php if (in_array($p['policy_slug'], $slug_co_san, true)): ?>
                                                        <span class="lp-built-in-badge"><i class="fa-solid fa-lock"></i> Hệ thống</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code class="lp-slug"><?php echo htmlspecialchars($p['policy_slug']); ?></code>
                                        </td>
                                        <td class="lp-td-date">
                                            <i class="fa-regular fa-clock" style="color:#9ca3af; margin-right:4px;"></i>
                                            <?php echo date('d/m/Y H:i', strtotime($p['policy_updated'])); ?>
                                        </td>
                                        <td>
                                            <?php if ((int) $p['policy_status'] === 1): ?>
                                                <span class="lp-status lp-status-on">
                                                    <span class="lp-status-dot"></span> Hiển thị
                                                </span>
                                            <?php else: ?>
                                                <span class="lp-status lp-status-off">
                                                    <span class="lp-status-dot"></span> Đã ẩn
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="lp-actions">
                                                <a class="lp-btn-edit" href="sua.php?id=<?php echo (int) $p['policy_id']; ?>" title="Sửa trang">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <a class="lp-btn-view" href="<?php echo htmlspecialchars(duong_dan_xem_chinh_sach($p['policy_slug'])); ?>" target="_blank" title="Xem trên website">
                                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                </a>
                                                <?php if (!in_array($p['policy_slug'], $slug_co_san, true)): ?>
                                                    <a class="lp-btn-delete" href="xuly.php?action=xoa&id=<?php echo (int) $p['policy_id']; ?>"
                                                       onclick="return confirm('Bạn có chắc muốn xoá trang \"<?php echo htmlspecialchars(addslashes($p['policy_title'])); ?>\"?');"
                                                       title="Xoá trang">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="lp-btn-locked" title="Không thể xoá trang hệ thống">
                                                        <i class="fa-solid fa-lock"></i>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if ($tong_so_trang > 1): ?>
                    <div class="lp-pagination">
                        <span class="lp-paging-info">Hiển thị <?php echo $bat_dau + 1; ?> đến <?php echo min($bat_dau + $so_dong, $tong_so_loc); ?> của <?php echo $tong_so_loc; ?> trang</span>
                        <div class="lp-paging-controls">
                            <a href="<?php echo xay_url_trang_lp(max(1, $trang_hien_tai - 1)); ?>" class="lp-page-btn <?php echo $trang_hien_tai <= 1 ? 'disabled' : ''; ?>"><i class="fa-solid fa-chevron-left"></i></a>
                            <div class="lp-page-numbers">
                                <?php for ($p = 1; $p <= $tong_so_trang; $p++): ?>
                                    <a href="<?php echo xay_url_trang_lp($p); ?>" class="lp-page-num <?php echo $p === $trang_hien_tai ? 'active' : ''; ?>"><?php echo $p; ?></a>
                                <?php endfor; ?>
                            </div>
                            <a href="<?php echo xay_url_trang_lp(min($tong_so_trang, $trang_hien_tai + 1)); ?>" class="lp-page-btn <?php echo $trang_hien_tai >= $tong_so_trang ? 'disabled' : ''; ?>"><i class="fa-solid fa-chevron-right"></i></a>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

        </main>
    </div>

    <script>
    </script>
</body>

</html>
