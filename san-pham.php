<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm - Viết Sơn Achieva</title>
    <link rel="shortcut icon" href="assets/images/icon/logo VS_icon.jpg" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="assets/js/header.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/san-pham.css">
    <script src="assets/js/san-pham.js" defer></script>
</head>

<body>
    <?php
    require_once 'admin/config/config.php';
    include 'header.php';

    $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';

    // Query string dùng chung để build lại link (giữ nguyên từ khóa tìm kiếm)
    $qs_giu_keyword = $keyword !== '' ? '&q=' . urlencode($keyword) : '';

    // Danh sách thương hiệu để làm bộ lọc, kèm số lượng sản phẩm đang active của mỗi thương hiệu
    $thuong_hieu_stmt = $pdo->query("SELECT th.ma_thuong_hieu, th.ten_thuong_hieu, COUNT(*) AS so_luong
        FROM san_pham sp
        JOIN thuong_hieu th ON sp.ma_thuong_hieu = th.ma_thuong_hieu
        WHERE sp.trang_thai = 1
        GROUP BY th.ma_thuong_hieu, th.ten_thuong_hieu
        ORDER BY th.ten_thuong_hieu ASC");
    $thuong_hieu_options = $thuong_hieu_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Nhận thương hiệu lọc qua tên (slug) thay vì mã số, vd: ?th=agi
    $th_slug      = isset($_GET['th']) ? trim($_GET['th']) : '';
    $ma_th_filter = 0;
    if ($th_slug !== '') {
        foreach ($thuong_hieu_options as $th_row) {
            if (tao_slug($th_row['ten_thuong_hieu']) === $th_slug) {
                $ma_th_filter = (int) $th_row['ma_thuong_hieu'];
                break;
            }
        }
    }

    // Render 1 thẻ sản phẩm
    function render_the_card($sp)
    {
        $gia_ban      = (int) $sp['gia_ban'];
        $giam_gia     = (int) $sp['giam_gia'];
        $gia_sau_giam = $giam_gia > 0 ? (int) round($gia_ban * (100 - $giam_gia) / 100) : $gia_ban;
        $anh_list_sp  = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', $sp['hinh_anh']))));
        $hinh_anh     = !empty($anh_list_sp) ? $anh_list_sp[0] : 'assets/image/pc.webp';
        $hinh_anh_hover = !empty($anh_list_sp[1]) ? $anh_list_sp[1] : '';
        $slug         = tao_slug($sp['ten_san_pham']);
        $tra_truoc    = $gia_ban > 0 ? (int) round($gia_sau_giam * 0.3 / 100000) * 100000 : 0;
        ?>
        <a class="product-card<?php echo $hinh_anh_hover !== '' ? ' has-hover-image' : ''; ?>"
            href="chi-tiet-san-pham.php?id=<?php echo (int)$sp['ma_san_pham']; ?>&ten-san-pham=<?php echo $slug; ?>">
            <?php if ($giam_gia > 0): ?><span class="product-badge">-<?php echo $giam_gia; ?>%</span><?php endif; ?>
            <span class="product-badge-official"><i class="fa-solid fa-circle-check"></i> Chính hãng</span>
            <div class="product-media">
                <img class="product-media-img is-primary" src="<?php echo htmlspecialchars($hinh_anh); ?>" alt="<?php echo htmlspecialchars($sp['ten_san_pham']); ?>" loading="lazy" onerror="this.onerror=null;this.src='assets/image/pc.webp';">
                <?php if ($hinh_anh_hover !== ''): ?>
                    <img class="product-media-img is-secondary" src="<?php echo htmlspecialchars($hinh_anh_hover); ?>" alt="<?php echo htmlspecialchars($sp['ten_san_pham']); ?>" loading="lazy" onerror="this.onerror=null;this.src='assets/image/pc.webp';">
                <?php endif; ?>
            </div>
            <div class="product-body">
                <?php if ($giam_gia > 0): ?>
                    <div class="product-flash-banner">
                        <i class="fa-solid fa-bolt"></i>
                        <span class="flash-countdown" data-countdown-endofday>--:--:--</span>
                    </div>
                <?php endif; ?>

                <div class="product-tags-row">
                    <?php if (!empty($sp['ten_thuong_hieu'])): ?>
                        <span class="product-brand"><?php echo htmlspecialchars($sp['ten_thuong_hieu']); ?></span>
                    <?php endif; ?>
                    <?php if (!empty($sp['ten_dung_luong'])): ?>
                        <span class="product-spec"><?php echo htmlspecialchars($sp['ten_dung_luong']); ?></span>
                    <?php endif; ?>
                </div>
                <h3 class="product-name"><?php echo htmlspecialchars($sp['ten_san_pham']); ?></h3>

                <?php
                $mo_ta_ngan = trim(strip_tags($sp['mo_ta']));
                if (mb_strlen($mo_ta_ngan) > 150) {
                    $mo_ta_ngan = mb_substr($mo_ta_ngan, 0, 150) . '...';
                }
                ?>
                <?php if ($mo_ta_ngan !== ''): ?>
                    <p class="product-desc"><?php echo htmlspecialchars($mo_ta_ngan); ?></p>
                <?php endif; ?>
                <div class="product-price-row">
                    <?php if ($gia_ban <= 0): ?>
                        <span class="product-price product-price-contact">Liên hệ</span>
                    <?php else: ?>
                        <span class="product-price"><?php echo number_format($gia_sau_giam, 0, ',', '.'); ?>₫</span>
                        <?php if ($giam_gia > 0): ?>
                            <span class="product-price-old"><?php echo number_format($gia_ban, 0, ',', '.'); ?>₫</span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <?php if ($gia_ban > 0): ?>
                    <div class="product-prepay">Hoặc trả trước <b><?php echo number_format($tra_truoc, 0, ',', '.'); ?>đ</b></div>
                <?php endif; ?>

                <!-- <div class="product-rating">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </div> -->
            </div>
        </a>
        <?php
    }

    // Lấy tất cả sản phẩm active, sắp xếp theo danh mục rồi theo id giảm dần
    $sql = "SELECT sp.*, dm.ten_danh_muc, dm.ma_danh_muc AS dm_id, th.ten_thuong_hieu,
                   dl.ten_dung_luong, dl.hinh_anh AS dung_luong_hinh_anh
            FROM san_pham sp
            LEFT JOIN danh_muc dm ON sp.ma_danh_muc = dm.ma_danh_muc
            LEFT JOIN thuong_hieu th ON sp.ma_thuong_hieu = th.ma_thuong_hieu
            LEFT JOIN dung_luong dl ON sp.ma_dung_luong = dl.ma_dung_luong
            WHERE sp.trang_thai = 1";

    $params = [];
    if ($keyword !== '') {
        $sql .= " AND (sp.ten_san_pham LIKE :keyword OR sp.ma_san_pham = :ma_san_pham)";
        $params[':keyword']    = '%' . $keyword . '%';
        $params[':ma_san_pham'] = ctype_digit($keyword) ? (int) $keyword : 0;
    }

    if ($ma_th_filter > 0) {
        $sql .= " AND sp.ma_thuong_hieu = :ma_th";
        $params[':ma_th'] = $ma_th_filter;
    }

    $sql .= " ORDER BY dm.ten_danh_muc ASC, sp.ma_san_pham DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $san_pham_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Nhóm sản phẩm theo danh mục
    // $nhom_danh_muc[ma_dm] = ['ten' => ..., 'san_pham' => [...], 'dong_groups' => [ma_dl => ['ten'=>, 'hinh_anh'=>]]]
    $nhom_danh_muc = [];
    foreach ($san_pham_list as $sp) {
        $ma_dm  = (int) $sp['dm_id'];
        $ten_dm = $sp['ten_danh_muc'] ?? 'Khác';

        if (!isset($nhom_danh_muc[$ma_dm])) {
            $nhom_danh_muc[$ma_dm] = [
                'ten'         => $ten_dm,
                'san_pham'    => [],
                'dong_groups' => [],
            ];
        }

        // Thêm sản phẩm vào nhóm
        $nhom_danh_muc[$ma_dm]['san_pham'][] = $sp;

        // Xây dựng danh sách "dòng" (dung lượng) để hiện vòng tròn lọc
        // Gom theo mã dung lượng (ma_dung_luong) để tránh trùng/lệch tên
        $ma_dl    = (int) ($sp['ma_dung_luong'] ?? 0);
        $ten_dong = trim($sp['ten_dung_luong'] ?? '');

        if ($ma_dl > 0 && $ten_dong !== '' && !isset($nhom_danh_muc[$ma_dm]['dong_groups'][$ma_dl])) {
            $nhom_danh_muc[$ma_dm]['dong_groups'][$ma_dl] = [
                'ten'      => $ten_dong,
                'hinh_anh' => trim($sp['dung_luong_hinh_anh'] ?? ''),
            ];
        }
    }
    ?>

    <section class="product-page">
        <div class="container">
            <div class="product-page-header">
                <span class="product-eyebrow">— Cửa hàng Viết Sơn</span>
                <h1 class="product-title">Sản phẩm</h1>
            </div>

            <?php if (!empty($thuong_hieu_options)): ?>
                <div class="brand-filter-bar">
                    <span class="brand-filter-label"><i class="fa-solid fa-filter"></i> Thương hiệu</span>
                    <div class="brand-filter-list">
                        <a class="brand-filter-pill <?php echo $ma_th_filter === 0 ? 'active' : ''; ?>"
                            href="san-pham.php?<?php echo ltrim($qs_giu_keyword, '&') ?: ''; ?>">
                            Tất cả
                        </a>
                        <?php foreach ($thuong_hieu_options as $th): ?>
                            <a class="brand-filter-pill <?php echo $ma_th_filter === (int) $th['ma_thuong_hieu'] ? 'active' : ''; ?>"
                                href="san-pham.php?th=<?php echo tao_slug($th['ten_thuong_hieu']) . $qs_giu_keyword; ?>">
                                <?php echo htmlspecialchars(trim($th['ten_thuong_hieu'])); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (count($san_pham_list) === 0): ?>
                <div class="product-empty">
                    <div class="empty-illustration">
                        <svg viewBox="0 0 520 320" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <rect x="130" y="60" width="220" height="155" rx="14" fill="#f5f5f5" stroke="#e0e0e0" stroke-width="2"/>
                            <rect x="145" y="75" width="190" height="125" rx="8" fill="#fff" stroke="#eee" stroke-width="1"/>
                            <rect x="220" y="215" width="40" height="22" rx="4" fill="#e0e0e0"/>
                            <rect x="195" y="234" width="90" height="10" rx="5" fill="#d0d0d0"/>
                            <text x="240" y="155" text-anchor="middle" font-family="Arial Black, sans-serif" font-size="52" font-weight="900" fill="#e53935" opacity="0.9">404</text>
                            <polygon points="248,85 238,118 248,118 232,148 252,108 240,108" fill="#FFD600" opacity="0.85"/>
                            <circle cx="348" cy="58" r="16" fill="#fff5f5" stroke="#e53935" stroke-width="2"/>
                            <text x="348" y="64" text-anchor="middle" font-family="Arial" font-size="16" fill="#e53935">!</text>
                            <ellipse cx="118" cy="230" rx="18" ry="8" fill="#ffcdd2" opacity="0.5"/>
                            <rect x="114" y="195" width="8" height="38" rx="4" fill="#ef9a9a"/>
                            <ellipse cx="118" cy="195" rx="16" ry="22" fill="#ef9a9a" opacity="0.5"/>
                            <ellipse cx="105" cy="200" rx="12" ry="16" fill="#e57373" opacity="0.4"/>
                            <ellipse cx="131" cy="198" rx="11" ry="15" fill="#e57373" opacity="0.4"/>
                            <circle cx="390" cy="130" r="38" fill="#f5f5f5" stroke="#e0e0e0" stroke-width="2"/>
                            <circle cx="378" cy="122" r="9" fill="#fff" stroke="#333" stroke-width="1.5"/>
                            <circle cx="400" cy="118" r="9" fill="#fff" stroke="#333" stroke-width="1.5"/>
                            <circle cx="381" cy="124" r="4" fill="#333"/>
                            <circle cx="403" cy="120" r="4" fill="#333"/>
                            <ellipse cx="390" cy="140" rx="7" ry="5" fill="#333"/>
                            <ellipse cx="390" cy="185" rx="24" ry="30" fill="#e53935"/>
                            <line x1="366" y1="175" x2="345" y2="155" stroke="#e53935" stroke-width="8" stroke-linecap="round"/>
                            <line x1="414" y1="172" x2="435" y2="152" stroke="#e53935" stroke-width="8" stroke-linecap="round"/>
                            <line x1="380" y1="213" x2="365" y2="245" stroke="#e53935" stroke-width="9" stroke-linecap="round"/>
                            <line x1="400" y1="213" x2="418" y2="240" stroke="#e53935" stroke-width="9" stroke-linecap="round"/>
                            <ellipse cx="358" cy="250" rx="16" ry="9" fill="#c62828"/>
                            <ellipse cx="424" cy="246" rx="16" ry="9" fill="#c62828"/>
                            <line x1="320" y1="178" x2="345" y2="178" stroke="#e53935" stroke-width="3" stroke-linecap="round" opacity="0.4"/>
                            <line x1="314" y1="192" x2="342" y2="192" stroke="#e53935" stroke-width="2" stroke-linecap="round" opacity="0.3"/>
                            <line x1="320" y1="206" x2="344" y2="206" stroke="#e53935" stroke-width="2" stroke-linecap="round" opacity="0.25"/>
                        </svg>
                    </div>
                    <h2 class="empty-title">Oops! Không tìm thấy sản phẩm</h2>
                    <p class="empty-subtitle">Rất tiếc, Việt Sơn Achieva chưa tìm thấy sản phẩm bạn đang cần<?php echo $keyword !== '' ? ' với từ khóa "' . htmlspecialchars($keyword) . '"' : ''; ?>.</p>
                    <p class="empty-desc">Sản phẩm có thể chưa được cập nhật hoặc từ khóa chưa chính xác. Đừng lo, chúng tôi luôn sẵn sàng hỗ trợ bạn!</p>
                    <div class="empty-actions">
                        <a href="index.php" class="empty-btn empty-btn-primary">
                            <i class="fa-solid fa-house"></i> Quay về trang chủ
                        </a>
                        <a href="san-pham.php" class="empty-btn empty-btn-secondary">
                            <i class="fa-solid fa-th-large"></i> Xem tất cả sản phẩm
                        </a>
                        <a href="lien-he.php" class="empty-btn empty-btn-outline">
                            <i class="fa-solid fa-phone"></i> Cần tư vấn? Liên hệ ngay
                        </a>
                    </div>
                </div>
            <?php else: ?>

                <?php foreach ($nhom_danh_muc as $ma_dm => $nhom): ?>
                    <div class="product-section">
                        <!-- Tiêu đề danh mục -->
                        <div class="product-section-header">
                            <h2 class="product-section-title">Sản Phẩm Nổi Bật <?php echo htmlspecialchars($nhom['ten']); ?></h2>
                            <div class="product-section-line"></div>
                        </div>
                        <?php if (!empty($nhom['dong_groups'])): ?>
                            <div class="category-strip" id="strip-<?php echo $ma_dm; ?>">
                                <!-- <a class="category-circle active"
                                    href="mo-ta-linh-kien.php?dm=<?php echo $ma_dm . $qs_giu_keyword; ?>"
                                    target="_blank" rel="noopener">
                                    <span class="category-circle-name">Tất cả</span>
                                </a> -->
                                <?php foreach ($nhom['dong_groups'] as $ma_dl => $dong): ?>
                                    <a class="category-circle"
                                        href="mo-ta-linh-kien.php?dm=<?php echo tao_slug($nhom['ten']); ?>&dl=<?php echo tao_slug($dong['ten']) . $qs_giu_keyword; ?>">
                                        <span class="category-circle-img">
                                            <img src="<?php echo !empty($dong['hinh_anh']) ? htmlspecialchars($dong['hinh_anh']) : 'assets/image/pc.webp'; ?>"
                                                loading="lazy"
                                                onerror="this.onerror=null;this.src='assets/image/pc.webp';">
                                        </span>
                                        <span class="category-circle-name">
                                            <?php echo htmlspecialchars($dong['ten']); ?>
                                        </span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Grid sản phẩm của danh mục này -->
                        <div class="product-grid" id="section-<?php echo $ma_dm; ?>">
                            <?php foreach (array_slice($nhom['san_pham'], 0, 10) as $sp): ?>
                                <?php render_the_card($sp); ?>
                            <?php endforeach; ?>
                        </div>

                    </div><!-- /.product-section -->
                <?php endforeach; ?>

            <?php endif; ?>
        </div>
    </section>

        <?php include 'footer.php'; ?>

</body>

</html>
