<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_NOI_DUNG], '../dang-nhap.php');

    $danh_sach_stmt = $pdo->query("SELECT * FROM article ORDER BY article_date DESC, article_id DESC");
    $danh_sach = $danh_sach_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Lấy danh sách các chuyên mục (để filter)
    $chuyen_muc_list = [];
    foreach ($danh_sach as $a) {
        $cm = trim($a['article_linh']);
        if ($cm !== '' && !in_array($cm, $chuyen_muc_list, true)) {
            $chuyen_muc_list[] = $cm;
        }
    }

    // Lấy danh sách các tác giả (để filter)
    $tac_gia_list = [];
    foreach ($danh_sach as $a) {
        $tg = trim($a['article_author']);
        if ($tg !== '' && !in_array($tg, $tac_gia_list, true)) {
            $tac_gia_list[] = $tg;
        }
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
                <div class="bv-search-box">
                    <input type="text" id="bvSearch" class="bv-search-input" placeholder="Tìm kiếm tiêu đề, chuyên mục...">
                    <i class="fa-solid fa-magnifying-glass bv-search-icon"></i>
                </div>
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
                <span class="bv-badge-card-count" id="bvTotalCount"><?php echo count($danh_sach); ?></span>
                <i class="fa-regular fa-file-lines bv-badge-card-icon"></i>
            </div>
            
            <div class="bv-filter-controls">
                <select id="bvFilterCategory" class="bv-select">
                    <option value="all">-- Chọn chuyên mục --</option>
                    <?php foreach ($chuyen_muc_list as $cm): ?>
                        <option value="<?php echo htmlspecialchars($cm); ?>"><?php echo htmlspecialchars($cm); ?></option>
                    <?php endforeach; ?>
                </select>

                <select id="bvFilterAuthor" class="bv-select">
                    <option value="all">-- Tác giả --</option>
                    <?php foreach ($tac_gia_list as $tg): ?>
                        <option value="<?php echo htmlspecialchars($tg); ?>"><?php echo htmlspecialchars($tg); ?></option>
                    <?php endforeach; ?>
                </select>

                <select id="bvFilterDate" class="bv-select">
                    <option value="all">Chọn ngày đăng</option>
                    <option value="today">Hôm nay</option>
                    <option value="this_month">Tháng này</option>
                </select>

                <button class="bv-btn-filter" id="bvFilterBtn">
                    <i class="fa-solid fa-sliders"></i> Bộ lọc
                </button>

                <button class="bv-btn-reset" id="bvResetBtn" title="Làm mới">
                    <i class="fa-solid fa-rotate-right"></i>
                </button>
            </div>
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
                                <td class="bv-td-num"><?php echo $i + 1; ?></td>
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

                <div class="bv-empty" id="bvEmpty" style="display:none;">
                    <i class="fa-regular fa-folder-open"></i>
                    <p>Không tìm thấy bài viết nào.</p>
                </div>
            </div>

            <!-- Table Footer / Pagination -->
            <div class="bv-pagination">
                <span class="bv-paging-info" id="bvPagingInfo"></span>
                <div class="bv-paging-controls">
                    <button class="bv-page-btn" id="bvPrevBtn"><i class="fa-solid fa-chevron-left"></i></button>
                    <div class="bv-page-numbers" id="bvPageNumbers"></div>
                    <button class="bv-page-btn" id="bvNextBtn"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>
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

    var rows = Array.from(document.querySelectorAll('.bv-row')),
        searchInp = document.getElementById('bvSearch'),
        catSel = document.getElementById('bvFilterCategory'),
        authSel = document.getElementById('bvFilterAuthor'),
        dateSel = document.getElementById('bvFilterDate'),
        emptyEl = document.getElementById('bvEmpty'),
        infoEl = document.getElementById('bvPagingInfo'),
        numsEl = document.getElementById('bvPageNumbers'),
        pb = document.getElementById('bvPrevBtn'),
        nb = document.getElementById('bvNextBtn'),
        totalEl = document.getElementById('bvTotalCount'),
        resetBtn = document.getElementById('bvResetBtn'),
        PP = 10, pg = 1, q = '', cat = 'all', auth = 'all', dateF = 'all';

    function checkDate(dateStr, filterVal) {
        if (filterVal === 'all') return true;
        var rDate = new Date(dateStr);
        var today = new Date();
        if (filterVal === 'today') {
            return rDate.toDateString() === today.toDateString();
        } else if (filterVal === 'this_month') {
            return rDate.getMonth() === today.getMonth() && rDate.getFullYear() === today.getFullYear();
        }
        return true;
    }

    function filtered() {
        return rows.filter(function(r) {
            var matchSearch = !q || r.dataset.title.includes(q) || r.dataset.category.toLowerCase().includes(q);
            var matchCat = cat === 'all' || r.dataset.category === cat;
            var matchAuth = auth === 'all' || r.dataset.author === auth;
            var matchDate = checkDate(r.dataset.date, dateF);
            return matchSearch && matchCat && matchAuth && matchDate;
        });
    }

    function render() {
        var list = filtered(), tot = list.length, pgs = Math.max(1, Math.ceil(tot / PP));
        if (pg > pgs) pg = pgs;
        var s = (pg - 1) * PP, e = Math.min(s + PP, tot);

        rows.forEach(function(r) { r.style.display = 'none'; });
        list.forEach(function(r, i) { r.style.display = (i >= s && i < e) ? '' : 'none'; });

        emptyEl.style.display = tot === 0 ? 'flex' : 'none';
        infoEl.textContent = tot > 0 ? 'Hiển thị ' + (s + 1) + ' đến ' + e + ' của ' + tot + ' kết quả' : '';
        totalEl.textContent = list.length;

        numsEl.innerHTML = '';
        for (let p = 1; p <= pgs; p++) {
            var b = document.createElement('button');
            b.className = 'bv-page-num' + (p === pg ? ' active' : '');
            b.textContent = p;
            (function(pp) {
                b.addEventListener('click', function() { pg = pp; render(); });
            })(p);
            numsEl.appendChild(b);
        }
        pb.disabled = pg <= 1;
        nb.disabled = pg >= pgs;
    }

    if (searchInp) searchInp.addEventListener('input', function() { q = searchInp.value.trim().toLowerCase(); pg = 1; render(); });
    if (catSel) catSel.addEventListener('change', function() { cat = catSel.value; pg = 1; render(); });
    if (authSel) authSel.addEventListener('change', function() { auth = authSel.value; pg = 1; render(); });
    if (dateSel) dateSel.addEventListener('change', function() { dateF = dateSel.value; pg = 1; render(); });

    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            searchInp.value = '';
            catSel.value = 'all';
            authSel.value = 'all';
            dateSel.value = 'all';
            q = ''; cat = 'all'; auth = 'all'; dateF = 'all';
            pg = 1;
            render();
        });
    }

    if (pb) pb.addEventListener('click', function() { if (pg > 1) { pg--; render(); } });
    if (nb) nb.addEventListener('click', function() { var p = Math.ceil(filtered().length / PP); if (pg < p) { pg++; render(); } });

    render();
})();
</script>
</body>
</html>