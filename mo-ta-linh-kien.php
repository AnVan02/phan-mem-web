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
                <!-- <div class="product-rating">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </div> -->
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
   
    // Chỉ lấy banner của (các) thương hiệu thuộc danh mục đang xem, không lấy toàn bộ thương hiệu
    $ma_th_trong_dm = array_values(array_unique(array_filter(array_map(function ($sp) {
        return (int) ($sp['ma_thuong_hieu'] ?? 0);
    }, $san_pham_list))));

    $banner_list = [];
    if (!empty($ma_th_trong_dm)) {
        $placeholders = implode(',', array_fill(0, count($ma_th_trong_dm), '?'));
        $banner_stmt = $pdo->prepare("SELECT ma_thuong_hieu, ten_thuong_hieu, banner, noi_dung_banner
                                 FROM thuong_hieu
                                 WHERE banner IS NOT NULL AND banner != ''
                                 AND ma_thuong_hieu IN ($placeholders)");
        $banner_stmt->execute($ma_th_trong_dm);
        $banner_list = $banner_stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $related_articles_stmt = $pdo->query("SELECT * FROM article WHERE article_status = 1 ORDER BY article_date DESC, article_id DESC LIMIT 1");
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
                                        <p class="hero-desc"><?php echo htmlspecialchars(trim($b['noi_dung_banner'])); ?></p>
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

                    <?php if (!empty($dong_groups)): ?>
                    <div class="needs-card" id="chon-theo-nhu-cau">
                        <div class="needs-card-header">
                            <span class="needs-card-title"><i class="fa-solid fa-sliders"></i> Chọn sản phẩm theo nhu cầu</span>
                        </div>
                        <div class="category-pills">
                            <a class="category-pill <?php echo $ma_dl_filter === 0 ? 'active' : ''; ?>" href="<?php echo xay_dung_url_sp(['dl' => ''], $hien_tai); ?>#chon-theo-nhu-cau">
                                Tất cả
                            </a>
                            <?php foreach ($dong_groups as $ma_dl => $dong): ?>
                                <a class="category-pill <?php echo $ma_dl_filter === $ma_dl ? 'active' : ''; ?>" href="<?php echo xay_dung_url_sp(['dl' => tao_slug($dong['ten'])], $hien_tai); ?>#chon-theo-nhu-cau">
                                    <?php echo htmlspecialchars($dong['ten']); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
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
                            <div class="empty-illustration">
                                <svg viewBox="0 0 520 320" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <!-- Monitor -->
                                    <rect x="130" y="60" width="220" height="155" rx="14" fill="#f5f5f5" stroke="#e0e0e0" stroke-width="2"/>
                                    <rect x="145" y="75" width="190" height="125" rx="8" fill="#fff" stroke="#eee" stroke-width="1"/>
                                    <!-- Monitor stand -->
                                    <rect x="220" y="215" width="40" height="22" rx="4" fill="#e0e0e0"/>
                                    <rect x="195" y="234" width="90" height="10" rx="5" fill="#d0d0d0"/>
                                    <!-- 404 text on screen -->
                                    <text x="240" y="155" text-anchor="middle" font-family="Arial Black, sans-serif" font-size="52" font-weight="900" fill="#e53935" opacity="0.9">404</text>
                                    <!-- Lightning bolt on screen -->
                                    <polygon points="248,85 238,118 248,118 232,148 252,108 240,108" fill="#FFD600" opacity="0.85"/>
                                    <!-- Warning icon top-right of monitor -->
                                    <circle cx="348" cy="58" r="16" fill="#fff5f5" stroke="#e53935" stroke-width="2"/>
                                    <text x="348" y="64" text-anchor="middle" font-family="Arial" font-size="16" fill="#e53935">!</text>
                                    <!-- Plant / decoration left -->
                                    <ellipse cx="118" cy="230" rx="18" ry="8" fill="#ffcdd2" opacity="0.5"/>
                                    <rect x="114" y="195" width="8" height="38" rx="4" fill="#ef9a9a"/>
                                    <ellipse cx="118" cy="195" rx="16" ry="22" fill="#ef9a9a" opacity="0.5"/>
                                    <ellipse cx="105" cy="200" rx="12" ry="16" fill="#e57373" opacity="0.4"/>
                                    <ellipse cx="131" cy="198" rx="11" ry="15" fill="#e57373" opacity="0.4"/>
                                    <!-- Running character body -->
                                    <circle cx="390" cy="130" r="38" fill="#f5f5f5" stroke="#e0e0e0" stroke-width="2"/>
                                    <!-- Eyes -->
                                    <circle cx="378" cy="122" r="9" fill="#fff" stroke="#333" stroke-width="1.5"/>
                                    <circle cx="400" cy="118" r="9" fill="#fff" stroke="#333" stroke-width="1.5"/>
                                    <circle cx="381" cy="124" r="4" fill="#333"/>
                                    <circle cx="403" cy="120" r="4" fill="#333"/>
                                    <!-- Mouth open surprised -->
                                    <ellipse cx="390" cy="140" rx="7" ry="5" fill="#333"/>
                                    <!-- Running body -->
                                    <ellipse cx="390" cy="185" rx="24" ry="30" fill="#e53935"/>
                                    <!-- Arms -->
                                    <line x1="366" y1="175" x2="345" y2="155" stroke="#e53935" stroke-width="8" stroke-linecap="round"/>
                                    <line x1="414" y1="172" x2="435" y2="152" stroke="#e53935" stroke-width="8" stroke-linecap="round"/>
                                    <!-- Legs -->
                                    <line x1="380" y1="213" x2="365" y2="245" stroke="#e53935" stroke-width="9" stroke-linecap="round"/>
                                    <line x1="400" y1="213" x2="418" y2="240" stroke="#e53935" stroke-width="9" stroke-linecap="round"/>
                                    <!-- Shoes -->
                                    <ellipse cx="358" cy="250" rx="16" ry="9" fill="#c62828"/>
                                    <ellipse cx="424" cy="246" rx="16" ry="9" fill="#c62828"/>
                                    <!-- Speed lines -->
                                    <line x1="320" y1="178" x2="345" y2="178" stroke="#e53935" stroke-width="3" stroke-linecap="round" opacity="0.4"/>
                                    <line x1="314" y1="192" x2="342" y2="192" stroke="#e53935" stroke-width="2" stroke-linecap="round" opacity="0.3"/>
                                    <line x1="320" y1="206" x2="344" y2="206" stroke="#e53935" stroke-width="2" stroke-linecap="round" opacity="0.25"/>
                                </svg>
                            </div>
                            <h2 class="empty-title">Oops! Không tìm thấy sản phẩm</h2>
                            <p class="empty-subtitle">Rất tiếc, Việt Sơn Achieva chưa tìm thấy sản phẩm bạn đang cần.</p>
                            <p class="empty-desc">Sản phẩm có thể chưa được cập nhật hoặc bộ lọc bạn chọn chưa chính xác. Đừng lo, chúng tôi luôn sẵn sàng hỗ trợ để bạn tìm đúng linh kiện mong muốn.</p>
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
