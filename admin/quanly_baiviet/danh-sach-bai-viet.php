<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_NOI_DUNG], '../dang-nhap.php');

    $tu_khoa = isset($_GET['q']) ? trim($_GET['q']) : '';
    $cm_loc = isset($_GET['cm']) ? trim($_GET['cm']) : 'all';
    $tg_loc = isset($_GET['tg']) ? trim($_GET['tg']) : 'all';
    $date_loc = isset($_GET['date']) ? trim($_GET['date']) : 'all';

    $sql_where = "WHERE 1=1";
    $tham_so = [];

    if ($tu_khoa !== '') {
        $sql_where .= " AND (article_title LIKE :q OR article_linh LIKE :q)";
        $tham_so[':q'] = '%' . $tu_khoa . '%';
    }
    if ($cm_loc !== 'all') {
        $sql_where .= " AND article_linh = :cm";
        $tham_so[':cm'] = $cm_loc;
    }
    if ($tg_loc !== 'all') {
        $sql_where .= " AND article_author = :tg";
        $tham_so[':tg'] = $tg_loc;
    }
    if ($date_loc === 'today') {
        $sql_where .= " AND DATE(article_date) = CURDATE()";
    } elseif ($date_loc === 'this_month') {
        $sql_where .= " AND MONTH(article_date) = MONTH(CURDATE()) AND YEAR(article_date) = YEAR(CURDATE())";
    }

    $dem_stmt = $pdo->prepare("SELECT COUNT(*) FROM article $sql_where");
    $dem_stmt->execute($tham_so);
    $tong_so = (int) $dem_stmt->fetchColumn();

    $tong_so_tat_ca = (int) $pdo->query("SELECT COUNT(*) FROM article")->fetchColumn();

    $so_dong = 10;
    $tong_so_trang = max(1, (int) ceil($tong_so / $so_dong));
    $trang_hien_tai = isset($_GET['trang']) ? (int) $_GET['trang'] : 1;
    if ($trang_hien_tai < 1) $trang_hien_tai = 1;
    if ($trang_hien_tai > $tong_so_trang) $trang_hien_tai = $tong_so_trang;
    $bat_dau = ($trang_hien_tai - 1) * $so_dong;

    $danh_sach_stmt = $pdo->prepare("SELECT * FROM article $sql_where ORDER BY article_date DESC, article_id DESC LIMIT :gioi_han OFFSET :bat_dau");
    foreach ($tham_so as $k => $v) {
        $danh_sach_stmt->bindValue($k, $v);
    }
    $danh_sach_stmt->bindValue(':gioi_han', $so_dong, PDO::PARAM_INT);
    $danh_sach_stmt->bindValue(':bat_dau', $bat_dau, PDO::PARAM_INT);
    $danh_sach_stmt->execute();
    $danh_sach = $danh_sach_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Lấy danh sách các chuyên mục (để filter)
    $chuyen_muc_list = $pdo->query("SELECT DISTINCT article_linh FROM article WHERE article_linh != '' ORDER BY article_linh ASC")->fetchAll(PDO::FETCH_COLUMN);

    // Lấy danh sách các tác giả (để filter)
    $tac_gia_list = $pdo->query("SELECT DISTINCT article_author FROM article WHERE article_author != '' ORDER BY article_author ASC")->fetchAll(PDO::FETCH_COLUMN);

    function xay_url_trang($trang) {
        $params = $_GET;
        $params['trang'] = $trang;
        return 'danh-sach-bai-viet.php?' . http_build_query($params);
    }

    $thong_bao = [
        'da_them'           => ['success', 'Đã thêm bài viết mới.'],
        'da_sua'            => ['success', 'Đã cập nhật bài viết.'],
        'da_xoa'            => ['success', 'Đã xoá bài viết.'],
        'loi_thieu_du_lieu' => ['error', 'Vui lòng nhập đầy đủ tiêu đề và tác giả.'],
        'loi_anh'           => ['error', 'Ảnh tải lên không hợp lệ (chỉ nhận jpg, jpeg, png, webp, gif).'],
    ];
    $msg = isset($_GET['msg']) && isset($thong_bao[$_GET['msg']]) ? $thong_bao[$_GET['msg']] : null;

    $ADMIN_ROOT = '../';
    $active_page = 'tin-tuc';
    $active_sub = 'danh-sach';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Tin tức - Admin</title>
    <link rel="shortcut icon" href="../../assets/images/icon/logo VS_icon.jpg"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-layout.css">
    <link rel="stylesheet" href="../assets/css/article.css">
</head>
<body>
<div class="admin-shell">
    <?php include '../includes/sidebar.php'; ?>

    <main class="admin-main">
        <!-- Top bar page header -->
        <div class="bv-header-section">
            <div>
                <h1 class="bv-page-title">Danh sách Tin tức</h1>
                <p class="bv-page-subtitle">Quản lý và theo dõi tất cả bài viết trên hệ thống</p>
            </div>
            <div class="bv-header-right">
                <form action="" method="GET" class="bv-search-box" style="margin: 0; display: flex; align-items: center;">
                    <input type="text" name="q" class="bv-search-input" placeholder="Tìm kiếm tiêu đề, chuyên mục..." value="<?php echo htmlspecialchars($tu_khoa); ?>">
                    <input type="hidden" name="cm" value="<?php echo htmlspecialchars($cm_loc); ?>">
                    <input type="hidden" name="tg" value="<?php echo htmlspecialchars($tg_loc); ?>">
                    <input type="hidden" name="date" value="<?php echo htmlspecialchars($date_loc); ?>">
                    <button type="submit" style="background:none; border:none; padding-left:10px;"><i class="fa-solid fa-magnifying-glass bv-search-icon"></i></button>
                </form>
                <a href="them.php" class="bv-btn-add">
                    <i class="fa-solid fa-plus"></i> Thêm bài viết mới
                </a>
            </div>
        </div>

        <?php if ($msg): ?>
            <div class="bv-flash bv-flash-<?php echo $msg[0]; ?>">
                <i class="fa-solid <?php echo $msg[0] === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'; ?>"></i>
                <?php echo htmlspecialchars($msg[1]); ?>
            </div>
        <?php endif; ?>

        <!-- Filters Section -->
        <div class="bv-filter-section">
            <div class="bv-filter-badge-card">
                <span class="bv-badge-card-title">Tất cả bài viết</span>
                <span class="bv-badge-card-count"><?php echo $tong_so_tat_ca; ?></span>
                <i class="fa-regular fa-file-lines bv-badge-card-icon"></i>
            </div>
            
            <form action="" method="GET" class="bv-filter-controls">
                <input type="hidden" name="q" value="<?php echo htmlspecialchars($tu_khoa); ?>">
                <select name="cm" class="bv-select">
                    <option value="all">-- Chọn chuyên mục --</option>
                    <?php foreach ($chuyen_muc_list as $cm): ?>
                        <option value="<?php echo htmlspecialchars($cm); ?>" <?php echo $cm_loc === $cm ? 'selected' : ''; ?>><?php echo htmlspecialchars($cm); ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="tg" class="bv-select">
                    <option value="all">-- Tác giả --</option>
                    <?php foreach ($tac_gia_list as $tg): ?>
                        <option value="<?php echo htmlspecialchars($tg); ?>" <?php echo $tg_loc === $tg ? 'selected' : ''; ?>><?php echo htmlspecialchars($tg); ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="date" class="bv-select">
                    <option value="all" <?php echo $date_loc === 'all' ? 'selected' : ''; ?>>Chọn ngày đăng</option>
                    <option value="today" <?php echo $date_loc === 'today' ? 'selected' : ''; ?>>Hôm nay</option>
                    <option value="this_month" <?php echo $date_loc === 'this_month' ? 'selected' : ''; ?>>Tháng này</option>
                </select>

                <button type="submit" class="bv-btn-filter">
                    <i class="fa-solid fa-sliders"></i> Bộ lọc
                </button>

                <a href="danh-sach-bai-viet.php" class="bv-btn-reset" title="Làm mới" style="text-decoration:none; display:flex; align-items:center; justify-content:center;">
                    <i class="fa-solid fa-rotate-right"></i>
                </a>
            </form>
        </div>

        <!-- Table List -->
        <div class="bv-card">
            <div class="bv-table-wrap">
                <table class="bv-table">
                    <thead>
                        <tr>
                            <th width="40"><input type="checkbox" id="bvSelectAll" class="bv-checkbox-all"></th>
                            <th width="50">#</th>
                            <th width="80">Ảnh</th>
                            <th>Tiêu đề</th>
                            <th>Chuyên mục</th>
                            <th>Tác giả</th>
                            <th>Ngày đăng</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="bvTbody">
                        <?php foreach ($danh_sach as $i => $a):
                            $anh = trim($a['article_image']) !== '' ? $a['article_image'] : '../../assets/image/pc.webp';
                            $anh_hien = (strpos($anh, 'http') === 0 || strpos($anh, '../') === 0) ? $anh : '../../' . $anh;
                            
                            // Tạo màu sắc ngẫu nhiên hoặc có quy luật cho chuyên mục để đẹp hơn
                            $cm_trimmed = trim($a['article_linh']);
                            $cm_class = 'cm-default';
                            if ($cm_trimmed === 'Công nghệ') $cm_class = 'cm-tech';
                            elseif ($cm_trimmed === 'AI') $cm_class = 'cm-ai';
                            elseif ($cm_trimmed === 'Linh kiện máy tính') $cm_class = 'cm-hardware';
                        ?>
                            <tr class="bv-row" 
                                data-title="<?php echo htmlspecialchars(mb_strtolower($a['article_title'], 'UTF-8')); ?>"
                                data-category="<?php echo htmlspecialchars($cm_trimmed); ?>"
                                data-author="<?php echo htmlspecialchars($a['article_author']); ?>"
                                data-date="<?php echo date('Y-m-d', strtotime($a['article_date'])); ?>">
                                <td><input type="checkbox" class="bv-row-checkbox"></td>
                                <td class="bv-td-num"><?php echo $bat_dau + $i + 1; ?></td>
                                <td>
                                    <div class="bv-thumb-wrap">
                                        <img src="<?php echo htmlspecialchars($anh_hien); ?>" class="bv-thumb" alt="thumb">
                                    </div>
                                </td>
                                <td class="bv-td-title">
                                    <div class="bv-title-text" title="<?php echo htmlspecialchars($a['article_title']); ?>">
                                        <?php echo htmlspecialchars($a['article_title']); ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="bv-category-badge <?php echo $cm_class; ?>">
                                        <span class="bv-cat-dot"></span>
                                        <?php echo htmlspecialchars($cm_trimmed !== '' ? $cm_trimmed : 'Chưa phân loại'); ?>
                                    </span>
                                </td>
                                <td class="bv-td-author"><?php echo htmlspecialchars($a['article_author']); ?></td>
                                <td class="bv-td-date"><?php echo date('d/m/Y', strtotime($a['article_date'])); ?></td>
                                <td>
                                    <?php if ((int)$a['article_status'] === 1): ?>
                                        <span class="bv-status-badge bv-status-active">Đang hiển thị</span>
                                    <?php else: ?>
                                        <span class="bv-status-badge bv-status-hidden">Đã ẩn</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="bv-actions">
                                        <a href="sua.php?id=<?php echo (int)$a['article_id']; ?>" class="bv-action-btn bv-action-edit" title="Sửa bài viết">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <a href="../../tin-tuc-chi-tiet.php?id=<?php echo (int)$a['article_id']; ?>" target="_blank" class="bv-action-btn bv-action-view" title="Xem chi tiết">
                                            <i class="fa-regular fa-eye"></i>
                                        </a>
                                        <a href="xuly.php?action=xoa&id=<?php echo (int)$a['article_id']; ?>" class="bv-action-btn bv-action-delete" title="Xóa bài viết"
                                           onclick="return confirm('Xoá bài viết «<?php echo htmlspecialchars($a['article_title']); ?>»?');">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </a>
                                        <button class="bv-action-more-btn" title="Thêm nữa">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php if ($tong_so === 0): ?>
                <div class="bv-empty" id="bvEmpty" style="display:flex;">
                    <i class="fa-regular fa-folder-open"></i>
                    <p>Không tìm thấy bài viết nào.</p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Table Footer / Pagination -->
            <?php if ($tong_so_trang > 1): ?>
            <div class="bv-pagination">
                <span class="bv-paging-info">Hiển thị <?php echo $bat_dau + 1; ?> đến <?php echo min($bat_dau + $so_dong, $tong_so); ?> của <?php echo $tong_so; ?> bài viết</span>
                <div class="bv-paging-controls">
                    <a href="<?php echo xay_url_trang(max(1, $trang_hien_tai - 1)); ?>" class="bv-page-btn <?php echo $trang_hien_tai <= 1 ? 'disabled' : ''; ?>" style="text-decoration:none; display:flex; align-items:center; justify-content:center;"><i class="fa-solid fa-chevron-left"></i></a>
                    <div class="bv-page-numbers">
                        <?php for ($p = 1; $p <= $tong_so_trang; $p++): ?>
                            <a href="<?php echo xay_url_trang($p); ?>" class="bv-page-num <?php echo $p === $trang_hien_tai ? 'active' : ''; ?>" style="text-decoration:none;"><?php echo $p; ?></a>
                        <?php endfor; ?>
                    </div>
                    <a href="<?php echo xay_url_trang(min($tong_so_trang, $trang_hien_tai + 1)); ?>" class="bv-page-btn <?php echo $trang_hien_tai >= $tong_so_trang ? 'disabled' : ''; ?>" style="text-decoration:none; display:flex; align-items:center; justify-content:center;"><i class="fa-solid fa-chevron-right"></i></a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
(function() {
    var selectAll = document.getElementById('bvSelectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            var checkboxes = document.querySelectorAll('.bv-row-checkbox');
            checkboxes.forEach(function(cb) {
                if (!cb.closest('.bv-row').style.display || cb.closest('.bv-row').style.display !== 'none') {
                    cb.checked = selectAll.checked;
                }
            });
        });
    }

    var selectAll = document.getElementById('bvSelectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            var checkboxes = document.querySelectorAll('.bv-row-checkbox');
            checkboxes.forEach(function(cb) {
                cb.checked = selectAll.checked;
            });
        });
    }
})();
</script>
</body>
</html>