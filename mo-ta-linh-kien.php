<?php
require_once 'admin/config/config.php';

// Danh sách mã sản phẩm khách hàng đang đăng nhập đã yêu thích (1 truy vấn, dùng chung cho mọi thẻ sản phẩm)
$wishlisted_ids = [];
if (isset($_SESSION['khach_hang_id'])) {
    $wl_stmt = $pdo->prepare("SELECT ma_san_pham FROM san_pham_yeu_thich WHERE ma_khach_hang = :kh");
    $wl_stmt->execute([':kh' => $_SESSION['khach_hang_id']]);
    $wishlisted_ids = array_map('intval', $wl_stmt->fetchAll(PDO::FETCH_COLUMN));
}

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

    // Bộ lọc "Dung lượng" (rút ra từ tên sản phẩm) và "Chuẩn kết nối" (cột chuan_ket_noi), có thể chọn nhiều
    $size_filter  = isset($_GET['size']) && is_array($_GET['size']) ? array_values(array_filter(array_map('trim', $_GET['size']))) : [];
    $ck_filter    = isset($_GET['ck'])   && is_array($_GET['ck'])   ? array_values(array_filter(array_map('trim', $_GET['ck']))) : [];

    // Khoảng giá tự nhập (Từ - đến), ưu tiên hơn khoảng giá dựng sẵn nếu có nhập
    $gia_tu_raw   = isset($_GET['gia_tu'])  ? trim($_GET['gia_tu'])  : '';
    $gia_den_raw  = isset($_GET['gia_den']) ? trim($_GET['gia_den']) : '';
    $gia_tu       = $gia_tu_raw  !== '' ? (int) preg_replace('/[^\d]/', '', $gia_tu_raw)  : null;
    $gia_den      = $gia_den_raw !== '' ? (int) preg_replace('/[^\d]/', '', $gia_den_raw) : null;

    // Rút gọn dung lượng (vd: 512GB, 1TB) từ tên sản phẩm để làm bộ lọc "Dung lượng"
    function trich_dung_luong($ten)
    {
        if (preg_match('/(\d+(?:[.,]\d+)?)\s?(TB|GB)\b/i', $ten, $m)) {
            return strtoupper($m[1]) . strtoupper($m[2]);
        }
        return '';
    }

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
            $rong = $v === '' || $v === 0 || (is_array($v) && empty($v));
            if ($k !== 'dm' && $rong) {
                unset($params[$k]);
            }
        }
        return 'mo-ta-linh-kien.php?' . http_build_query($params);
    }

    // Render 1 thẻ sản phẩm
    function render_the_card($sp)
    {
        global $wishlisted_ids;
        $is_wishlisted = in_array((int) $sp['ma_san_pham'], $wishlisted_ids, true);
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
        <img src="<?php echo htmlspecialchars($hinh_anh); ?>" alt="<?php echo htmlspecialchars($sp['ten_san_pham']); ?>"
            loading="lazy" onerror="this.onerror=null;this.src='assets/image/pc.webp';">
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
                    $fields = [
                        'loai_san_pham' => ['label' => 'Loại sản phẩm',  'icon' => 'fa-box-open'],
                        'chuan_ket_noi' => ['label' => 'Chuẩn kết nối',  'icon' => 'fa-plug'],
                        'toc_do_doc'    => ['label' => 'Tốc độ đọc',     'icon' => 'fa-gauge-high'],
                        'toc_do_ghi'    => ['label' => 'Tốc độ ghi',     'icon' => 'fa-gauge'],
                        'kich_thuoc'    => ['label' => 'Kích thước',     'icon' => 'fa-ruler-combined'],
                        'trong_luong'   => ['label' => 'Trọng lượng',    'icon' => 'fa-weight-scale'],
                        'bao_hanh'      => ['label' => 'Bảo hành',       'icon' => 'fa-shield-halved'],
                    ];
                    $co_thong_so = false;
                    foreach ($fields as $key => $f) {
                        if (!empty($sp[$key])) {
                            $co_thong_so = true;
                            break;
                        }
                    }
                    ?>
        <?php if ($co_thong_so): ?>
        <table class="product-desc">
            <?php foreach ($fields as $key => $f): ?>
            <?php if (!empty($sp[$key])): ?>
            <tr>
                <th>
                    <i class="fa-solid <?= $f['icon'] ?>"></i>
                    <span><?= htmlspecialchars($f['label'], ENT_QUOTES, 'UTF-8') ?></span>
                </th>
                <td><?= htmlspecialchars($sp[$key], ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
            <?php endif; ?>
            <?php endforeach; ?>
        </table>
        <?php else:
                    $mo_ta_ngan = trim(strip_tags($sp['mo_ta']));
                    if (mb_strlen($mo_ta_ngan) > 150) {
                        $mo_ta_ngan = mb_substr($mo_ta_ngan, 0, 150) . '...';
                    }
                    ?>
        <?php if ($mo_ta_ngan !== ''): ?>
        <p class="product-desc-text"><?php echo htmlspecialchars($mo_ta_ngan); ?></p>
        <?php endif; ?>
        <?php endif; ?>


        <div class="product-price-row">
            <?php if ($gia_ban <= 0): ?>
                <span class="product-price product-price-contact">Liên hệ</span>
                <div class="product-card-actions">
                    <button type="button" class="btn-wishlist <?php echo $is_wishlisted ? 'active' : ''; ?>"
                        data-ma-san-pham="<?php echo (int) $sp['ma_san_pham']; ?>" aria-label="Yêu thích"
                        title="Yêu thích">
                        <i class="fa-<?php echo $is_wishlisted ? 'solid' : 'regular'; ?> fa-heart"></i>
                    </button>
                    <button type="button" class="btn-share-product" data-share-product
                        data-share-url="chi-tiet-san-pham.php?id=<?php echo (int) $sp['ma_san_pham']; ?>&ten-san-pham=<?php echo $slug; ?>"
                        data-share-title="<?php echo htmlspecialchars($sp['ten_san_pham']); ?>" aria-label="Chia sẻ"
                        title="Chia sẻ">
                        <i class="fa-solid fa-share-nodes"></i>
                    </button>
                </div>
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

    // Xây danh sách "dòng" (dung lượng), thương hiệu, dung lượng thực, chuẩn kết nối và khoảng giá để làm bộ lọc
    $dong_groups          = [];
    $thuong_hieu_options  = [];
    $gia_bucket_counts    = [];
    $size_options         = []; // 'Dung lượng' (vd 512GB, 1TB) => số lượng
    $ck_options           = []; // 'Chuẩn kết nối' (vd PCIe Gen4) => số lượng

    
    
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

        $size_text = trich_dung_luong($sp['ten_san_pham']);
        if ($size_text !== '') {
            $size_options[$size_text] = ($size_options[$size_text] ?? 0) + 1;
        }

        $ck_text = trim($sp['chuan_ket_noi'] ?? '');
        if ($ck_text !== '') {
            $ck_options[$ck_text] = ($ck_options[$ck_text] ?? 0) + 1;
        }
    }

    uksort($size_options, function ($a, $b) {
        $doi_ra_gb = function ($s) {
            preg_match('/(\d+(?:[.,]\d+)?)\s?(TB|GB)/i', $s, $m);
            $so = (float) str_replace(',', '.', $m[1]);
            return strtoupper($m[2]) === 'TB' ? $so * 1024 : $so;
        };
        return $doi_ra_gb($a) <=> $doi_ra_gb($b);
    });
    ksort($ck_options);

    $ten_dong_hien_tai = ($ma_dl_filter > 0 && isset($dong_groups[$ma_dl_filter]))
        ? $dong_groups[$ma_dl_filter]['ten']
        : '';

    // Query string hiện tại, dùng để build lại link khi đổi 1 bộ lọc mà giữ các bộ lọc khác
    // dm/dl dùng tên (slug) thay vì mã số để URL hiển thị tên linh kiện
    $hien_tai = [
        'dm'      => tao_slug($ten_danh_muc),
        'dl'      => $ten_dong_hien_tai !== '' ? tao_slug($ten_dong_hien_tai) : '',
        'th'      => $ma_th_filter,
        'gia'     => $gia_filter,
        'gia_tu'  => $gia_tu_raw,
        'gia_den' => $gia_den_raw,
        'size'    => $size_filter,
        'ck'      => $ck_filter,
        'sort'    => $sort,
        'q'       => $keyword,
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

    if (!empty($size_filter)) {
        $danh_sach_hien_thi = array_filter($danh_sach_hien_thi, function ($sp) use ($size_filter) {
            return in_array(trich_dung_luong($sp['ten_san_pham']), $size_filter, true);
        });
    }

    if (!empty($ck_filter)) {
        $danh_sach_hien_thi = array_filter($danh_sach_hien_thi, function ($sp) use ($ck_filter) {
            return in_array(trim($sp['chuan_ket_noi'] ?? ''), $ck_filter, true);
        });
    }

    if ($gia_tu !== null || $gia_den !== null) {
        // Khoảng giá tự nhập được ưu tiên hơn khoảng giá dựng sẵn
        $danh_sach_hien_thi = array_filter($danh_sach_hien_thi, function ($sp) use ($gia_tu, $gia_den) {
            $g = (int) $sp['gia_ban'];
            if ($gia_tu !== null && $g < $gia_tu) return false;
            if ($gia_den !== null && $g > $gia_den) return false;
            return true;
        });
    } elseif ($gia_filter !== '' && isset($gia_buckets_dinh_nghia[$gia_filter])) {
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

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
                    <a
                        href="<?php echo xay_dung_url_sp(['dl' => '', 'th' => '', 'gia' => '', 'sort' => ''], $hien_tai); ?>"><?php echo htmlspecialchars($ten_danh_muc); ?></a>
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
                    <!-- <button class="hero-arrow hero-arrow-prev" type="button" aria-label="Slide trước">
                        <i class="fas fa-chevron-left"></i>
                    </button> -->

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

                    <!-- <button class="hero-arrow hero-arrow-next" type="button" aria-label="Slide sau">
                        <i class="fas fa-chevron-right"></i>
                    </button> -->

                    <div class="stat-bar">
                        <a href="#don-hang-toi" class="stat-item">
                            <div class="stat-icon" style="background:#fee2e2; color:#dc2626;"><i
                                    class="fa-solid fa-bag-shopping"></i></div>
                            <div>
                                <div class="stat-label">Hàng chính hãng </div>
                                <span class="stat-link" style="color:#dc2626;">100% sản phẩm chính hãng</span>
                            </div>
                        </a>
                        <a href="#yeu-thich" class="stat-item">
                            <div class="stat-icon" style="background:#f3e8ff; color:#9333ea;"><i
                                    class="fa-solid fa-heart"></i></div>
                            <div>
                                <div class="stat-label">Bảo hành từ 3 đến 5 năm</div>
                                <span class="stat-link" style="color:#9333ea;">Hỗ trợ bảo hành nhanh chóng</span>
                            </div>
                        </a>
                        <a href="#danh-gia" class="stat-item">
                            <div class="stat-icon" style="background:#fef3c7; color:#ca8a04;"><i
                                    class="fa-solid fa-star"></i></div>
                            <div>
                                <div class="stat-label">1 đổi 1 trong 30 ngày</div>
                                <span class="stat-link" style="color:#ca8a04;">Nếu sản phẩm lỗi do NSX</span>
                            </div>
                        </a>
                        <a href="#" class="stat-item" title="Tính năng mã giảm giá đang được phát triển">
                            <div class="stat-icon" style="background:#dbeafe; color:#2563eb;"><i
                                    class="fa-solid fa-gift"></i></div>
                            <div>
                                <div class="stat-label">Giao hàng toàn quốc</div>
                                <span class="stat-link" style="color:#2563eb;">Kiểm tra trước khi thanh toán </span>
                            </div>
                        </a>


                    </div>
                </div>
            </section>

            <div class="category-layout">
                <aside class="category-sidebar">
                    <?php if (!empty($dong_groups)): ?>
                    <div class="sidebar-filter-group">
                        <h3 class="sidebar-filter-title"><i class="fa-solid fa-list"></i> Danh mục sản phẩm</h3>
                        <ul class="sidebar-filter-list">
                            <li>
                                <a class="<?php echo $ma_dl_filter === 0 ? 'active' : ''; ?>"
                                    href="<?php echo xay_dung_url_sp(['dl' => ''], $hien_tai); ?>#chon-theo-nhu-cau">Tất
                                    cả sản phẩm</a>
                            </li>
                            <?php foreach ($dong_groups as $ma_dl => $dong): ?>
                            <li>
                                <a class="<?php echo $ma_dl_filter === $ma_dl ? 'active' : ''; ?>"
                                    href="<?php echo xay_dung_url_sp(['dl' => tao_slug($dong['ten'])], $hien_tai); ?>#chon-theo-nhu-cau">
                                    <?php echo htmlspecialchars($dong['ten']); ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <form class="sidebar-filter-form" method="get" action="mo-ta-linh-kien.php#chon-theo-nhu-cau">
                        <input type="hidden" name="dm" value="<?php echo htmlspecialchars($hien_tai['dm']); ?>">
                        <?php if ($hien_tai['dl'] !== ''): ?><input type="hidden" name="dl"
                            value="<?php echo htmlspecialchars($hien_tai['dl']); ?>"><?php endif; ?>
                        <?php if ($sort !== ''): ?><input type="hidden" name="sort"
                            value="<?php echo htmlspecialchars($sort); ?>"><?php endif; ?>
                        <?php if ($keyword !== ''): ?><input type="hidden" name="q"
                            value="<?php echo htmlspecialchars($keyword); ?>"><?php endif; ?>

                        <?php if (!empty($size_options)): ?>
                        <div class="sidebar-filter-group">
                            <h3 class="sidebar-filter-title">Dung lượng</h3>
                            <label class="sidebar-check-row sidebar-check-row-muted">
                                <input type="checkbox" disabled <?php echo empty($size_filter) ? 'checked' : ''; ?>> Tất
                                cả
                            </label>
                            <?php foreach ($size_options as $size_text => $sl): ?>
                            <label class="sidebar-check-row">
                                <input type="checkbox" name="size[]" value="<?php echo htmlspecialchars($size_text); ?>"
                                    <?php echo in_array($size_text, $size_filter, true) ? 'checked' : ''; ?>>
                                <?php echo htmlspecialchars($size_text); ?>
                                <span class="sidebar-filter-count">(<?php echo $sl; ?>)</span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($ck_options)): ?>
                        <div class="sidebar-filter-group">
                            <h3 class="sidebar-filter-title">Chuẩn kết nối</h3>
                            <label class="sidebar-check-row sidebar-check-row-muted">
                                <input type="checkbox" disabled <?php echo empty($ck_filter) ? 'checked' : ''; ?>> Tất
                                cả
                            </label>
                            <?php foreach ($ck_options as $ck_text => $sl): ?>
                            <label class="sidebar-check-row">
                                <input type="checkbox" name="ck[]" value="<?php echo htmlspecialchars($ck_text); ?>"
                                    <?php echo in_array($ck_text, $ck_filter, true) ? 'checked' : ''; ?>>
                                <?php echo htmlspecialchars($ck_text); ?>
                                <span class="sidebar-filter-count">(<?php echo $sl; ?>)</span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <div class="sidebar-filter-group">
                            <h3 class="sidebar-filter-title">Mức giá</h3>
                            <label class="sidebar-check-row">
                                <input type="radio" name="gia" value=""
                                    <?php echo ($gia_filter === '' && $gia_tu === null && $gia_den === null) ? 'checked' : ''; ?>>
                                Tất cả
                            </label>
                            <?php foreach ($gia_buckets_dinh_nghia as $key => $bucket): ?>
                            <label class="sidebar-check-row">
                                <input type="radio" name="gia" value="<?php echo $key; ?>"
                                    <?php echo ($gia_filter === $key && $gia_tu === null && $gia_den === null) ? 'checked' : ''; ?>>
                                <?php echo htmlspecialchars($bucket['label']); ?>
                                <?php if (!empty($gia_bucket_counts[$key])): ?><span
                                    class="sidebar-filter-count">(<?php echo $gia_bucket_counts[$key]; ?>)</span><?php endif; ?>
                            </label>
                            <?php endforeach; ?>
                            <div class="sidebar-price-range">
                                <input type="text" name="gia_tu" placeholder="Từ"
                                    value="<?php echo htmlspecialchars($gia_tu_raw); ?>" inputmode="numeric">
                                <span>đến</span>
                                <input type="text" name="gia_den" placeholder="đến"
                                    value="<?php echo htmlspecialchars($gia_den_raw); ?>" inputmode="numeric">
                            </div>
                        </div>
                        <div class="sidebar-filter-actions">
                            <button type="submit" class="btn-filter-apply">Lọc sản phẩm</button>
                            <a href="<?php echo xay_dung_url_sp(['dl' => '', 'gia' => '', 'gia_tu' => '', 'gia_den' => '', 'size' => [], 'ck' => []], $hien_tai); ?>#chon-theo-nhu-cau"
                                class="btn-filter-reset">Xóa bộ lọc</a>
                        </div>
                    </form>

                    <div class="support-box">
                        <h3 class="support-box-title">Hỗ trợ khách hàng</h3>
                        <div class="support-box-item">
                            <i class="fa-solid fa-phone"></i>
                            <div>
                                <span class="support-box-label">Tư vấn bán hàng</span>
                                <span class="support-box-value">(028) 39293770</span>
                            </div>
                        </div>
                        <div class="support-box-item">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                            <div>
                                <span class="support-box-label">Kỹ thuật - Bảo hành</span>
                                <span class="support-box-value">(028) 39260996</span>
                            </div>
                        </div>
                        <div class="support-box-item">
                            <i class="fa-regular fa-clock"></i>
                            <div>
                                <span class="support-box-label">Thời gian làm việc</span>
                                <span class="support-box-value">8:00 - 17:30 (T2 - T7)</span>
                            </div>
                        </div>
                    </div>
                </aside>

                <div class="category-main">

                    <?php if (!empty($dong_groups)): ?>
                    <div class="needs-card" id="chon-theo-nhu-cau">
                        <div class="needs-card-header">
                            <span class="needs-card-title"><i class="fa-solid fa-sliders"></i> Chọn dòng sản phẩm</span>
                        </div>
                        <div class="category-pills">
                            <a class="category-pill <?php echo $ma_dl_filter === 0 ? 'active' : ''; ?>"
                                href="<?php echo xay_dung_url_sp(['dl' => ''], $hien_tai); ?>#chon-theo-nhu-cau">
                                Tất cả
                            </a>
                            <?php foreach ($dong_groups as $ma_dl => $dong): ?>
                            <a class="category-pill <?php echo $ma_dl_filter === $ma_dl ? 'active' : ''; ?>"
                                href="<?php echo xay_dung_url_sp(['dl' => tao_slug($dong['ten'])], $hien_tai); ?>#chon-theo-nhu-cau">
                                <?php echo htmlspecialchars($dong['ten']); ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="sort-bar">
                        <span class="sort-bar-count">Hiển thị <?php echo count($danh_sach_hien_thi); ?> sản phẩm</span>
                        <div class="sort-bar-right">
                            <label class="sort-bar-label">
                                Sắp xếp
                                <select onchange="window.location.href=this.value">
                                    <option
                                        value="<?php echo xay_dung_url_sp(['sort' => ''], $hien_tai); ?>#chon-theo-nhu-cau"
                                        <?php echo $sort === '' ? 'selected' : ''; ?>>Mới nhất </option>
                                    <option
                                        value="<?php echo xay_dung_url_sp(['sort' => 'gia-tang'], $hien_tai); ?>#chon-theo-nhu-cau"
                                        <?php echo $sort === 'gia-tang' ? 'selected' : ''; ?>>Giá tăng dần</option>
                                    <option
                                        value="<?php echo xay_dung_url_sp(['sort' => 'gia-giam'], $hien_tai); ?>#chon-theo-nhu-cau"
                                        <?php echo $sort === 'gia-giam' ? 'selected' : ''; ?>>Giá giảm dần</option>
                                </select>
                            </label>
                            <div class="view-toggle" data-view-toggle>
                                <button type="button" class="view-toggle-btn active" data-view="grid"
                                    aria-label="Xem dạng lưới">
                                    <i class="fa-solid fa-table-cells-large"></i>
                                </button>
                                <button type="button" class="view-toggle-btn" data-view="list"
                                    aria-label="Xem dạng danh sách">
                                    <i class="fa-solid fa-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <?php if (count($danh_sach_hien_thi) === 0): ?>
                    <div class="product-empty">
                        <div class="empty-illustration">
                            <svg viewBox="0 0 520 320" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <!-- Monitor -->
                                <rect x="130" y="60" width="220" height="155" rx="14" fill="#f5f5f5" stroke="#e0e0e0"
                                    stroke-width="2" />
                                <rect x="145" y="75" width="190" height="125" rx="8" fill="#fff" stroke="#eee"
                                    stroke-width="1" />
                                <!-- Monitor stand -->
                                <rect x="220" y="215" width="40" height="22" rx="4" fill="#e0e0e0" />
                                <rect x="195" y="234" width="90" height="10" rx="5" fill="#d0d0d0" />
                                <!-- 404 text on screen -->
                                <text x="240" y="155" text-anchor="middle" font-family="Arial Black, sans-serif"
                                    font-size="52" font-weight="900" fill="#e53935" opacity="0.9">404</text>
                                <!-- Lightning bolt on screen -->
                                <polygon points="248,85 238,118 248,118 232,148 252,108 240,108" fill="#FFD600"
                                    opacity="0.85" />
                                <!-- Warning icon top-right of monitor -->
                                <circle cx="348" cy="58" r="16" fill="#fff5f5" stroke="#e53935" stroke-width="2" />
                                <text x="348" y="64" text-anchor="middle" font-family="Arial" font-size="16"
                                    fill="#e53935">!</text>
                                <!-- Plant / decoration left -->
                                <ellipse cx="118" cy="230" rx="18" ry="8" fill="#ffcdd2" opacity="0.5" />
                                <rect x="114" y="195" width="8" height="38" rx="4" fill="#ef9a9a" />
                                <ellipse cx="118" cy="195" rx="16" ry="22" fill="#ef9a9a" opacity="0.5" />
                                <ellipse cx="105" cy="200" rx="12" ry="16" fill="#e57373" opacity="0.4" />
                                <ellipse cx="131" cy="198" rx="11" ry="15" fill="#e57373" opacity="0.4" />
                                <!-- Running character body -->
                                <circle cx="390" cy="130" r="38" fill="#f5f5f5" stroke="#e0e0e0" stroke-width="2" />
                                <!-- Eyes -->
                                <circle cx="378" cy="122" r="9" fill="#fff" stroke="#333" stroke-width="1.5" />
                                <circle cx="400" cy="118" r="9" fill="#fff" stroke="#333" stroke-width="1.5" />
                                <circle cx="381" cy="124" r="4" fill="#333" />
                                <circle cx="403" cy="120" r="4" fill="#333" />
                                <!-- Mouth open surprised -->
                                <ellipse cx="390" cy="140" rx="7" ry="5" fill="#333" />
                                <!-- Running body -->
                                <ellipse cx="390" cy="185" rx="24" ry="30" fill="#e53935" />
                                <!-- Arms -->
                                <line x1="366" y1="175" x2="345" y2="155" stroke="#e53935" stroke-width="8"
                                    stroke-linecap="round" />
                                <line x1="414" y1="172" x2="435" y2="152" stroke="#e53935" stroke-width="8"
                                    stroke-linecap="round" />
                                <!-- Legs -->
                                <line x1="380" y1="213" x2="365" y2="245" stroke="#e53935" stroke-width="9"
                                    stroke-linecap="round" />
                                <line x1="400" y1="213" x2="418" y2="240" stroke="#e53935" stroke-width="9"
                                    stroke-linecap="round" />
                                <!-- Shoes -->
                                <ellipse cx="358" cy="250" rx="16" ry="9" fill="#c62828" />
                                <ellipse cx="424" cy="246" rx="16" ry="9" fill="#c62828" />
                                <!-- Speed lines -->
                                <line x1="320" y1="178" x2="345" y2="178" stroke="#e53935" stroke-width="3"
                                    stroke-linecap="round" opacity="0.4" />
                                <line x1="314" y1="192" x2="342" y2="192" stroke="#e53935" stroke-width="2"
                                    stroke-linecap="round" opacity="0.3" />
                                <line x1="320" y1="206" x2="344" y2="206" stroke="#e53935" stroke-width="2"
                                    stroke-linecap="round" opacity="0.25" />
                            </svg>
                        </div>
                        <h2 class="empty-title">Oops! Không tìm thấy sản phẩm</h2>
                        <p class="empty-subtitle">Rất tiếc, Việt Sơn Achieva chưa tìm thấy sản phẩm bạn đang cần.</p>
                        <p class="empty-desc">Sản phẩm có thể chưa được cập nhật hoặc bộ lọc bạn chọn chưa chính xác.
                            Đừng lo,
                            chúng tôi luôn sẵn sàng hỗ trợ để bạn tìm đúng linh kiện mong muốn.</p>
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
                    <div class="product-grid" data-view-target>
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
                                    <img src="<?php echo htmlspecialchars($art_anh); ?>"
                                        alt="<?php echo htmlspecialchars($a['article_title']); ?>" loading="lazy">
                                </div>
                                <div class="article-body">
                                    <h3 class="article_title"><?php echo htmlspecialchars($a['article_title']); ?></h3>
                                    <span class="article-date"><i class="fa-regular fa-clock"></i>
                                        <?php echo $art_ngay; ?></span>
                                    <span class="article_author"><i class="fa-solid fa-circle-user"></i>
                                        <?php echo htmlspecialchars($a['article_author']); ?></span>
                                    <?php if ($mo_ta_ngan !== ''): ?>
                                    <span class="article_content"></i>
                                        <?php echo htmlspecialchars($mo_ta_ngan); ?></span>
                                    <?php endif; ?>

                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
            <div class="needs-card-header">
                <span class="needs-card-title"><i class="fa-solid fa-sliders"></i>Vì sao nên chọn 
                    <?php echo htmlspecialchars(trim($b['ten_thuong_hieu'])); ?></span>
            </div>
            <div class="info-list">
                <!-- Dòng 1 -->
                <div class="info-icon">
                    <i class="fa-solid fa-gauge-high icon-perform"></i>
                    <div class="info-content">
                        <div class="info-label">Hiệu suất cao</div>
                        <div class="info-text">Tốc độ đọc ghi vượt trội mọi tác vụ</div>
                    </div>
                </div>

                <div class="info-icon">
                    <i class="fa-solid fa-shield-halved icon-durable"></i>
                    <div class="info-content">
                        <div class="info-label">Bền bỉ đáng tin cậy</div>
                        <div class="info-text">MTBF lên đến 2 triệu giờ hoạt động ổn định</div>
                    </div>
                </div>

                <div class="info-icon">
                    <i class="fa-solid fa-microchip icon-tech"></i>
                    <div class="info-content">
                        <div class="info-label">Công nghệ hiện đại</div>
                        <div class="info-text">Ứng dụng công nghệ 3D NAND mới nhất</div>
                    </div>
                </div>

                <div class="info-icon">
                    <i class="fa-solid fa-computer icon-tech"></i>
                    <div class="info-content">
                        <div class="info-label">Tương thích rộng rãi</div>
                        <div class="info-text">Hỗ trợ Windows, macOS, Linux</div>
                    </div>
                </div>

                <div class="info-icon">
                    <i class="fa-solid fa-leaf icon-save"></i>
                    <div class="info-content">
                        <div class="info-label">Tiết kiệm năng lượng</div>
                        <div class="info-text">Hiệu quả tối ưu, giảm nhiệt tiết kiệm điện năng</div>
                    </div>
                </div>

                <!-- Dòng 2 -->
                <div class="info-icon">
                    <i class="fa-solid fa-certificate icon-authen"></i>
                    <div class="info-content">
                        <div class="info-label">Sản phẩm chính hãng</div>
                        <div class="info-text">100% chính hãng
                            <?php echo htmlspecialchars(trim($b['ten_thuong_hieu'])); ?></div>
                    </div>
                </div>

                <div class="info-icon">
                    <i class="fa-solid fa-arrows-rotate icon-warranty"></i>
                    <div class="info-content">
                        <div class="info-label">Đổi trả 30 ngày</div>
                        <div class="info-text">Nếu sản phẩm lỗi do nhà sản xuất</div>
                    </div>
                </div>

                <div class="info-icon">
                    <i class="fa-solid fa-file-contract icon-warranty"></i>
                    <div class="info-content">
                        <div class="info-label">Bảo hành chính hãng</div>
                        <div class="info-text">Từ 3 đến 5 năm toàn quốc</div>
                    </div>
                </div>

                <div class="info-icon">
                    <i class="fa-solid fa-truck-fast icon-delivery"></i>
                    <div class="info-content">
                        <div class="info-label">Giao hàng toàn quốc</div>
                        <div class="info-text">Kiểm tra trước khi thanh toán</div>
                    </div>
                </div>


                <div class="info-icon">
                    <i class="fa-solid fa-headset icon-support"></i>
                    <div class="info-content">
                        <div class="info-label">Hỗ trợ 24/7</div>
                        <div class="info-text">Tư vấn kỹ thuật tận tâm</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include 'footer.php'; ?>