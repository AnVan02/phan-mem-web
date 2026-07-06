<?php
require_once 'admin/config/config.php';

// Nhận danh mục / dòng qua tên (slug) thay vì mã số, vd: ?dm=ram&dl=agi-ssd
$dm_slug      = isset($_GET['dm']) ? trim($_GET['dm']) : '';
$ma_dm_filter = 0;
if ($dm_slug !== '') {
    foreach ($pdo->query("SELECT ma_danh_muc, ten_danh_muc FROM danh_muc")->fetchAll(PDO::FETCH_ASSOC) as $dm_row) {
        if (tao_slug($dm_row['ten_danh_muc']) === $dm_slug) {
            $ma_dm_filter = (int) $dm_row['ma_danh_muc'];
            break;
        }
    }
}

if ($ma_dm_filter <= 0) {
    header('Location: san-pham.php');
    exit;
}

$dl_slug      = isset($_GET['dl']) ? trim($_GET['dl']) : '';
$ma_dl_filter = 0;
if ($dl_slug !== '') {
    foreach ($pdo->query("SELECT ma_dung_luong, ten_dung_luong FROM dung_luong")->fetchAll(PDO::FETCH_ASSOC) as $dl_row) {
        if (tao_slug($dl_row['ten_dung_luong']) === $dl_slug) {
            $ma_dl_filter = (int) $dl_row['ma_dung_luong'];
            break;
        }
    }
}
    $ma_th_filter = isset($_GET['th']) ? (int) $_GET['th'] : 0;
    $gia_filter   = isset($_GET['gia']) ? trim($_GET['gia']) : '';
    $sort         = isset($_GET['sort']) ? trim($_GET['sort']) : '';
    $keyword      = isset($_GET['q']) ? trim($_GET['q']) : '';

    // Các khoảng giá dùng cho bộ lọc sidebar
    $gia_buckets_dinh_nghia = [
        'duoi-2'  => ['label' => 'Dưới 2 triệu',  'min' => 0,          'max' => 2000000],
        '2-5'     => ['label' => '2 - 5 triệu',    'min' => 2000000,    'max' => 5000000],
        '5-10'    => ['label' => '5 - 10 triệu',   'min' => 5000000,    'max' => 10000000],
        '10-20'   => ['label' => '10 - 20 triệu',  'min' => 10000000,   'max' => 20000000],
        'tren-20' => ['label' => 'Trên 20 triệu',  'min' => 20000000,   'max' => PHP_INT_MAX],
    ];

    // Xây lại URL trang danh mục, giữ nguyên các bộ lọc hiện tại trừ phần được ghi đè
    function xay_dung_url_sp($overrides, $hien_tai)
    {
        $params = array_merge($hien_tai, $overrides);
        foreach ($params as $k => $v) {
            if ($k !== 'dm' && ($v === '' || $v === 0)) {
                unset($params[$k]);
            }
        }
        return 'mo-ta-linh-kien.php?' . http_build_query($params);
    }

    // Render 1 thẻ sản phẩm
    function render_the_card($sp)
    {
        $gia_ban      = (int) $sp['gia_ban'];
        $giam_gia     = (int) $sp['giam_gia'];
        $gia_sau_giam = $giam_gia > 0 ? (int) round($gia_ban * (100 - $giam_gia) / 100) : $gia_ban;
        $anh_list_sp  = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', $sp['hinh_anh']))));
        $hinh_anh     = !empty($anh_list_sp) ? $anh_list_sp[0] : 'assets/image/pc.webp';
        $slug         = tao_slug($sp['ten_san_pham']);
        $tra_truoc    = $gia_ban > 0 ? (int) round($gia_sau_giam * 0.3 / 100000) * 100000 : 0;
        ?>
        <a class="product-card"
            href="chi-tiet-san-pham.php?id=<?php echo (int)$sp['ma_san_pham']; ?>&ten-san-pham=<?php echo $slug; ?>">
            <?php if ($giam_gia > 0): ?><span class="product-badge">-<?php echo $giam_gia; ?>%</span><?php endif; ?>
            <span class="product-badge-official"><i class="fa-solid fa-circle-check"></i> Chính hãng</span>
            <div class="product-media">
                <img src="<?php echo htmlspecialchars($hinh_anh); ?>" alt="<?php echo htmlspecialchars($sp['ten_san_pham']); ?>" loading="lazy" onerror="this.onerror=null;this.src='assets/image/pc.webp';">
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
                <div class="product-rating">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </div>
            </div>
        </a>
        <?php
    }

    // Lấy sản phẩm của riêng danh mục này
    $sql = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu,
                   dl.ten_dung_luong, dl.hinh_anh AS dung_luong_hinh_anh
            FROM san_pham sp
            LEFT JOIN danh_muc dm ON sp.ma_danh_muc = dm.ma_danh_muc
            LEFT JOIN thuong_hieu th ON sp.ma_thuong_hieu = th.ma_thuong_hieu
            LEFT JOIN dung_luong dl ON sp.ma_dung_luong = dl.ma_dung_luong
            WHERE sp.trang_thai = 1 AND sp.ma_danh_muc = :ma_dm";

    $params = [':ma_dm' => $ma_dm_filter];

    if ($keyword !== '') {
        $sql .= " AND (sp.ten_san_pham LIKE :keyword OR sp.ma_san_pham = :ma_san_pham)";
        $params[':keyword']    = '%' . $keyword . '%';
        $params[':ma_san_pham'] = ctype_digit($keyword) ? (int) $keyword : 0;
    }

    $sql .= " ORDER BY sp.ma_san_pham DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $san_pham_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($san_pham_list)) {
        header('Location: san-pham.php');
        exit;
    }

    $ten_danh_muc = $san_pham_list[0]['ten_danh_muc'] ?? 'Danh mục';

    // Xây danh sách "dòng" (dung lượng), thương hiệu và khoảng giá để làm bộ lọc
    $dong_groups          = [];
    $thuong_hieu_options  = [];
    $gia_bucket_counts    = [];

    foreach ($san_pham_list as $sp) {
        $ma_dl    = (int) ($sp['ma_dung_luong'] ?? 0);
        $ten_dong = trim($sp['ten_dung_luong'] ?? '');

        if ($ma_dl > 0 && $ten_dong !== '' && !isset($dong_groups[$ma_dl])) {
            $dong_groups[$ma_dl] = [
                'ten'      => $ten_dong,
                'hinh_anh' => trim($sp['dung_luong_hinh_anh'] ?? ''),
            ];
        }

        $ma_th  = (int) ($sp['ma_thuong_hieu'] ?? 0);
        $ten_th = trim($sp['ten_thuong_hieu'] ?? '');

        if ($ma_th > 0 && $ten_th !== '') {
            if (!isset($thuong_hieu_options[$ma_th])) {
                $thuong_hieu_options[$ma_th] = ['ten' => $ten_th, 'so_luong' => 0];
            }
            $thuong_hieu_options[$ma_th]['so_luong']++;
        }

        $gia_ban_sp = (int) $sp['gia_ban'];
        foreach ($gia_buckets_dinh_nghia as $key => $bucket) {
            if ($gia_ban_sp >= $bucket['min'] && $gia_ban_sp < $bucket['max']) {
                $gia_bucket_counts[$key] = ($gia_bucket_counts[$key] ?? 0) + 1;
                break;
            }
        }
    }

    $ten_dong_hien_tai = ($ma_dl_filter > 0 && isset($dong_groups[$ma_dl_filter]))
        ? $dong_groups[$ma_dl_filter]['ten']
        : '';

    // Query string hiện tại, dùng để build lại link khi đổi 1 bộ lọc mà giữ các bộ lọc khác
    // dm/dl dùng tên (slug) thay vì mã số để URL hiển thị tên linh kiện
    $hien_tai = [
        'dm'   => tao_slug($ten_danh_muc),
        'dl'   => $ten_dong_hien_tai !== '' ? tao_slug($ten_dong_hien_tai) : '',
        'th'   => $ma_th_filter,
        'gia'  => $gia_filter,
        'sort' => $sort,
        'q'    => $keyword,
    ];

    // Nhãn của bộ lọc đang áp dụng (ưu tiên dòng, rồi tới thương hiệu, rồi tới khoảng giá)
    $nhan_loc_hien_tai = '';
    if ($ten_dong_hien_tai !== '') {
        $nhan_loc_hien_tai = $ten_dong_hien_tai;
    } elseif ($ma_th_filter > 0 && isset($thuong_hieu_options[$ma_th_filter])) {
        $nhan_loc_hien_tai = $thuong_hieu_options[$ma_th_filter]['ten'];
    } elseif ($gia_filter !== '' && isset($gia_buckets_dinh_nghia[$gia_filter])) {
        $nhan_loc_hien_tai = $gia_buckets_dinh_nghia[$gia_filter]['label'];
    }

    // Áp dụng bộ lọc dòng / thương hiệu / khoảng giá / sắp xếp lên danh sách hiển thị
    $danh_sach_hien_thi = $san_pham_list;

    if ($ma_dl_filter > 0) {
        $danh_sach_hien_thi = array_filter($danh_sach_hien_thi, function ($sp) use ($ma_dl_filter) {
            return (int) ($sp['ma_dung_luong'] ?? 0) === $ma_dl_filter;
        });
    }

    if ($ma_th_filter > 0) {
        $danh_sach_hien_thi = array_filter($danh_sach_hien_thi, function ($sp) use ($ma_th_filter) {
            return (int) ($sp['ma_thuong_hieu'] ?? 0) === $ma_th_filter;
        });
    }

    if ($gia_filter !== '' && isset($gia_buckets_dinh_nghia[$gia_filter])) {
        $bucket_dang_loc    = $gia_buckets_dinh_nghia[$gia_filter];
        $danh_sach_hien_thi = array_filter($danh_sach_hien_thi, function ($sp) use ($bucket_dang_loc) {
            $g = (int) $sp['gia_ban'];
            return $g >= $bucket_dang_loc['min'] && $g < $bucket_dang_loc['max'];
        });
    }

    $danh_sach_hien_thi = array_values($danh_sach_hien_thi);

    if ($sort === 'gia-tang') {
        usort($danh_sach_hien_thi, function ($a, $b) {
            return (int) $a['gia_ban'] <=> (int) $b['gia_ban'];
        });
    } elseif ($sort === 'gia-giam') {
        usort($danh_sach_hien_thi, function ($a, $b) {
            return (int) $b['gia_ban'] <=> (int) $a['gia_ban'];
        });
    }
   
    // Chỉ lấy banner của các thương hiệu thực sự có sản phẩm trong danh mục đang xem
    $ma_th_trong_dm = array_keys($thuong_hieu_options);
    if (!empty($ma_th_trong_dm)) {
        $placeholders = implode(',', array_fill(0, count($ma_th_trong_dm), '?'));
        $stmt_banner = $pdo->prepare("
            SELECT ma_thuong_hieu, ten_thuong_hieu, banner
            FROM thuong_hieu
            WHERE banner IS NOT NULL AND banner != '' AND ma_thuong_hieu IN ($placeholders)
            ORDER BY ma_thuong_hieu
        ");
        $stmt_banner->execute($ma_th_trong_dm);
        $banner_list = $stmt_banner->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $banner_list = [];
    }

    $related_articles_stmt = $pdo->query("SELECT * FROM article WHERE article_status = 1 ORDER BY article_date DESC, article_id DESC LIMIT 6");
    $related_articles = $related_articles_stmt->fetchAll(PDO::FETCH_ASSOC);


    
    ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh mục sản phẩm - Viết Sơn Achieva</title>
    <link rel="shortcut icon" href="assets/images/icon/logo VS_icon.jpg" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="assets/js/header.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/mo-ta-linh-kien.css">
    <script src="assets/js/san-pham.js" defer></script>
</head>

<body>
    <?php include 'header.php'; ?>

    <section class="product-page">
        <div class="container">
            <div class="category-hero">
                <div class="category-breadcrumb">
                    <a href="index.php">Trang chủ</a>
                    <span class="crumb-sep">/</span>
                    <a href="san-pham.php">Sản phẩm</a>
                    <span class="crumb-sep">/</span>
                    <?php if ($nhan_loc_hien_tai !== ''): ?>
                        <a href="<?php echo xay_dung_url_sp(['dl' => '', 'th' => '', 'gia' => '', 'sort' => ''], $hien_tai); ?>"><?php echo htmlspecialchars($ten_danh_muc); ?></a>
                        <span class="crumb-sep">/</span>
                        <span class="crumb-current"><?php echo htmlspecialchars($nhan_loc_hien_tai); ?></span>
                    <?php else: ?>
                        <span class="crumb-current"><?php echo htmlspecialchars($ten_danh_muc); ?></span>
                    <?php endif; ?>
                </div>

                <!-- <span class="product-eyebrow">— Cửa hàng Viết Sơn</span>
                <h1 class="product-title">
                    <?php echo htmlspecialchars($ten_danh_muc); ?><?php echo $nhan_loc_hien_tai !== '' ? ' · ' . htmlspecialchars($nhan_loc_hien_tai) : ''; ?>
                </h1> -->
                <!-- <p class="category-hero-count"><?php echo count($san_pham_list); ?> sản phẩm</p> -->
            </div>

            <section class="hero-banner">
                <div class="container hero-slider">
                    <button class="hero-arrow hero-arrow-prev" type="button" aria-label="Slide trước">
                        <i class="fas fa-chevron-left"></i>
                    </button>

                    <div class="hero-slides-wrapper">
                        <?php if (count($banner_list) > 0): ?>
                            <?php foreach ($banner_list as $index => $b): ?>
                                <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>" 
                                    style="background-image: url('<?php echo htmlspecialchars($b['banner']); ?>');">
                                    <div class="hero-overlay"></div>
                                    <div class="hero-slide-content">
                                        <h2 class="hero-title"><?php echo htmlspecialchars(trim($b['ten_thuong_hieu'])); ?></h2>
                                        <p class="hero-desc">Khám phá sản phẩm chính hãng đến từ <?php echo htmlspecialchars(trim($b['ten_thuong_hieu'])); ?></p>
                                          <!-- <p class="hero-desc"><?php echo htmlspecialchars(trim($b['noi-dung-banner'])); ?></p> -->
                                        <!-- <div class="hero-actions">
                                            <a href="danh-sach-san-pham.php?thuong_hieu=<?php echo $b['ma_thuong_hieu']; ?>" class="btn btn-purple">
                                                Mua ngay <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div> -->
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Slide mặc định nếu chưa có banner nào trong DB -->
                            <div class="hero-slide active" style="background-image: url('assets/image/banner1.png');">
                                <div class="hero-overlay"></div>
                                <div class="hero-slide-content">
                                    <!-- <h2 class="hero-title">Chào mừng đến với cửa hàng</h2> -->
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <button class="hero-arrow hero-arrow-next" type="button" aria-label="Slide sau">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </section>

            <!-- <div class="category-layout">
                <aside class="category-sidebar">
                    <?php if (!empty($thuong_hieu_options)): ?>
                        <div class="sidebar-filter-group">
                            <h3 class="sidebar-filter-title">Thương hiệu</h3>
                            <ul class="sidebar-filter-list">
                                <li>
                                    <a class="<?php echo $ma_th_filter === 0 ? 'active' : ''; ?>" href="<?php echo xay_dung_url_sp(['th' => ''], $hien_tai); ?>">Tất cả</a>
                                </li>
                                <?php foreach ($thuong_hieu_options as $ma_th => $th): ?>
                                    <li>
                                        <a class="<?php echo $ma_th_filter === $ma_th ? 'active' : ''; ?>" href="<?php echo xay_dung_url_sp(['th' => $ma_th], $hien_tai); ?>">
                                            <?php echo htmlspecialchars($th['ten']); ?>
                                            <span class="sidebar-filter-count">(<?php echo $th['so_luong']; ?>)</span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($gia_bucket_counts)): ?>
                        <div class="sidebar-filter-group">
                            <h3 class="sidebar-filter-title">Khoảng giá</h3>
                            <ul class="sidebar-filter-list">
                                <li>
                                    <a class="<?php echo $gia_filter === '' ? 'active' : ''; ?>" href="<?php echo xay_dung_url_sp(['gia' => ''], $hien_tai); ?>">Tất cả</a>
                                </li>
                                <?php foreach ($gia_buckets_dinh_nghia as $key => $bucket): ?>
                                    <?php if (!empty($gia_bucket_counts[$key])): ?>
                                        <li>
                                            <a class="<?php echo $gia_filter === $key ? 'active' : ''; ?>" href="<?php echo xay_dung_url_sp(['gia' => $key], $hien_tai); ?>">
                                                <?php echo htmlspecialchars($bucket['label']); ?>
                                                <span class="sidebar-filter-count">(<?php echo $gia_bucket_counts[$key]; ?>)</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </aside> -->

                <div class="category-main">
                    <h3 class="category-strip-title">Chọn sản phẩm theo nhu cầu</h3>
                    <?php if (!empty($dong_groups)): ?>
                        <div class="category-strip">
                            <!-- <a class="category-circle <?php echo $ma_dl_filter === 0 ? 'active' : ''; ?>" href="<?php echo xay_dung_url_sp(['dl' => ''], $hien_tai); ?>">
                                <span class="category-circle-name">Tất cả</span>
                            </a> -->
                            <?php foreach ($dong_groups as $ma_dl => $dong): ?>
                                <a class="category-circle <?php echo $ma_dl_filter === $ma_dl ? 'active' : ''; ?>" href="<?php echo xay_dung_url_sp(['dl' => tao_slug($dong['ten'])], $hien_tai); ?>">
                                    <span class="category-circle-img">
                                        <img src="<?php echo !empty($dong['hinh_anh']) ? htmlspecialchars($dong['hinh_anh']) : 'assets/image/pc.webp'; ?>"
                                            loading="lazy"
                                            onerror="this.onerror=null;this.src='assets/image/pc.webp';">
                                    </span>
                                    <span class="category-circle-name"><?php echo htmlspecialchars($dong['ten']); ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- <div class="sort-bar">
                        <span class="sort-bar-count"><?php echo count($danh_sach_hien_thi); ?> sản phẩm</span>
                        <label class="sort-bar-label">
                            Sắp xếp
                            <select onchange="window.location.href=this.value">
                                <option value="<?php echo xay_dung_url_sp(['sort' => ''], $hien_tai); ?>" <?php echo $sort === '' ? 'selected' : ''; ?>>Mặc định</option>
                                <option value="<?php echo xay_dung_url_sp(['sort' => 'gia-tang'], $hien_tai); ?>" <?php echo $sort === 'gia-tang' ? 'selected' : ''; ?>>Giá tăng dần</option>
                                <option value="<?php echo xay_dung_url_sp(['sort' => 'gia-giam'], $hien_tai); ?>" <?php echo $sort === 'gia-giam' ? 'selected' : ''; ?>>Giá giảm dần</option>
                            </select>
                        </label>
                    </div> -->

                    <?php if (count($danh_sach_hien_thi) === 0): ?>
                        <div class="product-empty">
                            <i class="fa-solid fa-box-open"></i>
                            <p>Không có sản phẩm nào phù hợp với bộ lọc đã chọn.</p>
                        </div>
                    <?php else: ?>
                        <div class="product-grid">
                            <?php foreach ($danh_sach_hien_thi as $sp) render_the_card($sp); ?>
                        </div>
                    <?php endif; ?>

                        <?php if (count($related_articles) > 1): ?>
                        <div class="product-related-articles">
                            <h2>Bài viết liên quan</h2>
                            <div class="article-list">
                               <?php foreach ($related_articles as $a):
                                    $art_anh    = trim($a['article_image']) !== '' ? $a['article_image'] : 'assets/image/pc.webp';
                                    $art_ngay   = date('d/m/Y', strtotime($a['article_date']));
                                    $art_slug   = tao_slug($a['article_title']);

                                    $mo_ta_goc  = trim(strip_tags($a['article_content'] ?? ''));
                                    if (mb_strlen($mo_ta_goc) > 150) {
                                        $mo_ta_ngan = mb_substr($mo_ta_goc, 0, 150);
                                        $mo_ta_ngan = mb_substr($mo_ta_ngan, 0, mb_strrpos($mo_ta_ngan, ' ')) . '...';
                                    } else {
                                        $mo_ta_ngan = $mo_ta_goc;
                                    }
                                ?>
                                    <a class="article-item" href="chi-tiet-tin-tuc.php?ten-bai-viet=<?php echo $art_slug; ?>">
                                        <div class="article-thumb">
                                            <img src="<?php echo htmlspecialchars($art_anh); ?>" alt="<?php echo htmlspecialchars($a['article_title']); ?>" loading="lazy">
                                        </div>
                                        <div class="article-body">
                                            <h3 class="article_title"><?php echo htmlspecialchars($a['article_title']); ?></h3>
                                            <span class="article-date"><i class="fa-regular fa-clock"></i> <?php echo $art_ngay; ?></span>
                                            <span class="article_author"><i class="fa-solid fa-circle-user"></i> <?php echo htmlspecialchars($a['article_author']); ?></span>
                                            <?php if ($mo_ta_ngan !== ''): ?>
                                                <span class="article_content"></i> <?php echo htmlspecialchars($mo_ta_ngan); ?></span>
                                            <?php endif; ?>
                                            
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    

                </div>
            </div>
        </div>
    </section>
    <?php include 'footer.php'; ?>

</body>

</html>
