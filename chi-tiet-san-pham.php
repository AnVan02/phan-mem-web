<?php
    require_once 'admin/config/config.php';

    $ma_san_pham = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    $stmt = $pdo->prepare("SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu, dl.ten_dung_luong
        FROM san_pham sp
        LEFT JOIN danh_muc dm ON sp.ma_danh_muc = dm.ma_danh_muc
        LEFT JOIN thuong_hieu th ON sp.ma_thuong_hieu = th.ma_thuong_hieu
        LEFT JOIN dung_luong dl ON sp.ma_dung_luong = dl.ma_dung_luong
        WHERE sp.ma_san_pham = :id AND sp.trang_thai = 1
        LIMIT 1");
    $stmt->execute([':id' => $ma_san_pham]);
    $sp = $stmt->fetch(PDO::FETCH_ASSOC);

    $page_title = $sp ? htmlspecialchars($sp['ten_san_pham']) . ' - Viết Sơn Achieva' : 'Chi tiết sản phẩm - Viết Sơn Achieva';
    if ($sp) {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $base_url = $scheme . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
        $canonical_url = rtrim($base_url, '/') . '/chi-tiet-san-pham.php?id=' . (int) $sp['ma_san_pham'] . '&ten-san-pham=' . tao_slug($sp['ten_san_pham']);
    }
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <?php if ($sp): ?><link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>"><?php endif; ?>
    <link rel="shortcut icon" href="assets/images/icon/logo VS_icon.jpg"/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="assets/js/header.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/san-pham.css">
    <link rel="stylesheet" href="assets/css/chi-tiet-san-pham.css">
</head>

<body>
<?php include 'header.php'; ?>

<?php if (!$sp): ?>
    <section class="product-detail">
        <div class="container">
            <div class="product-empty">
                <i class="fa-solid fa-box-open"></i>
                <p>Không tìm thấy sản phẩm bạn yêu cầu.</p>
                <a href="san-pham.php" class="btn-back">← Quay lại danh sách sản phẩm</a>
            </div>
        </div>
    </section>
<?php else:
    $gia_ban      = (int) $sp['gia_ban'];
    $giam_gia     = (int) $sp['giam_gia'];
    $gia_sau_giam = $giam_gia > 0 ? (int) round($gia_ban * (100 - $giam_gia) / 100) : $gia_ban;
    $so_luong     = (int) $sp['so_luong'];

    $images = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', $sp['hinh_anh']))));
    if (empty($images)) {
        $images = ['assets/image/pc.webp'];
    }
    $hinh_anh = $images[0];

    $related_stmt = $pdo->prepare("SELECT sp.*, th.ten_thuong_hieu, dl.ten_dung_luong
        FROM san_pham sp
        LEFT JOIN thuong_hieu th ON sp.ma_thuong_hieu = th.ma_thuong_hieu
        LEFT JOIN dung_luong dl ON sp.ma_dung_luong = dl.ma_dung_luong
        WHERE sp.ma_danh_muc = :ma_danh_muc AND sp.trang_thai = 1 AND sp.ma_san_pham != :id
        ORDER BY sp.ma_san_pham DESC
        LIMIT 4");
    $related_stmt->execute([':ma_danh_muc' => $sp['ma_danh_muc'], ':id' => $sp['ma_san_pham']]);
    $related_list = $related_stmt->fetchAll(PDO::FETCH_ASSOC);

    $related_articles_stmt = $pdo->query("SELECT * FROM article WHERE article_status = 1 ORDER BY article_date DESC, article_id DESC LIMIT 6");
    $related_articles = $related_articles_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <section class="product-detail">
        <div class="container">
            <nav class="product-breadcrumb">
                <a href="index.php">Trang chủ</a>
                <span>/</span>
                <a href="san-pham.php">Sản phẩm</a>
                <?php if (!empty($sp['ten_danh_muc'])): ?>
                    <span>/</span>
                    <a href="san-pham.php?danh_muc=<?php echo (int) $sp['ma_danh_muc']; ?>"><?php echo htmlspecialchars($sp['ten_danh_muc']); ?></a>
                <?php endif; ?>
                <span>/</span>
                <span class="current"><?php echo htmlspecialchars($sp['ten_san_pham']); ?></span>
            </nav>

            <div class="product-detail-main">
                <div class="product-gallery">
                    <div class="product-gallery-main">
                        <?php if ($giam_gia > 0): ?><span class="product-badge">-<?php echo $giam_gia; ?>%</span><?php endif; ?>
                        <?php if (count($images) > 1): ?>
                            <button type="button" class="gallery-nav-btn gallery-nav-prev" aria-label="Ảnh trước"><i class="fa-solid fa-chevron-left"></i></button>
                        <?php endif; ?>
                        <img id="mainProductImage" src="<?php echo htmlspecialchars($hinh_anh); ?>" alt="<?php echo htmlspecialchars($sp['ten_san_pham']); ?>">
                        <?php if (count($images) > 1): ?>
                            <button type="button" class="gallery-nav-btn gallery-nav-next" aria-label="Ảnh sau"><i class="fa-solid fa-chevron-right"></i></button>
                        <?php endif; ?>
                    </div>
                    <?php if (count($images) > 1): ?>
                        <div class="product-gallery-thumbs">
                            <?php foreach ($images as $i => $img): ?>
                                <button type="button" class="thumb <?php echo $i === 0 ? 'active' : ''; ?>" data-src="<?php echo htmlspecialchars($img); ?>">
                                    <img src="<?php echo htmlspecialchars($img); ?>" alt="">
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="product-detail-info">
                    <?php if (!empty($sp['ten_thuong_hieu'])): ?>
                        <span class="product-brand"><?php echo htmlspecialchars($sp['ten_thuong_hieu']); ?></span>
                    <?php endif; ?>

                    <h1 class="product-detail-name"><?php echo htmlspecialchars($sp['ten_san_pham']); ?></h1>

                    <?php if (!empty($sp['ten_dung_luong'])): ?>
                        <span class="product-spec"><?php echo htmlspecialchars($sp['ten_dung_luong']); ?></span>
                    <?php endif; ?>

                    <div class="product-detail-price-row">
                        <?php if ($gia_ban <= 0): ?>
                            <span class="product-price product-price-contact">Liên hệ</span>
                        <?php else: ?>
                            <span class="product-price"><?php echo number_format($gia_sau_giam, 0, ',', '.'); ?>₫</span>
                            <?php if ($giam_gia > 0): ?>
                                <span class="product-price-old"><?php echo number_format($gia_ban, 0, ',', '.'); ?>₫</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <div class="product-detail-actions">
                        <?php if ($so_luong <= 0): ?>
                            <a href="tel:0839293770" class="btn-add-cart btn-contact">
                                <i class="fa-solid fa-phone"></i> Liên hệ
                            </a>
                        <?php else: ?>
                            <div class="quantity-box">
                                <button type="button" class="qty-btn qty-minus">-</button>
                                <input type="number" class="qty-input" value="1" min="1" max="<?php echo $so_luong; ?>">
                                <button type="button" class="qty-btn qty-plus">+</button>
                            </div>
                            <button type="button" class="btn-add-cart">
                                <i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ hàng
                            </button>
                        <?php endif; ?>
                    </div>

                    <ul class="product-benefits">
                        <li><i class="fa-solid fa-truck"></i> Giao hàng toàn quốc</li>
                        <li><i class="fa-solid fa-rotate-left"></i> Đổi trả trong 7 ngày</li>
                        <li><i class="fa-solid fa-sound-truck"></i>Bảo hành chính hãng </li>
                    </ul>
                    
                </div>
            </div>

            <?php if (!empty(trim($sp['thong-so']))): ?>
                <div class="product-detail-specs">
                    <h2>Thông số kỹ thuật</h2>
                    <?php echo $sp['thong-so']; ?>
                </div>
            <?php endif; ?>


            <?php if (!empty(trim($sp['mo_ta']))): ?>
                <div class="product-detail-desc">
                    <h2>Mô tả sản phẩm</h2>
                    <div class="product-detail-desc-content">
                        <?php echo $sp['mo_ta']; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (count($related_list) > 0): ?>
                <div class="product-related">
                    <h2>Sản phẩm liên quan</h2>
                    <div class="product-grid">
                        <?php foreach ($related_list as $rp):
                            $r_gia_ban      = (int) $rp['gia_ban'];
                            $r_giam_gia     = (int) $rp['giam_gia'];
                            $r_gia_sau_giam = $r_giam_gia > 0 ? (int) round($r_gia_ban * (100 - $r_giam_gia) / 100) : $r_gia_ban;
                            $r_hinh_anh     = trim($rp['hinh_anh']) !== '' ? $rp['hinh_anh'] : 'assets/image/pc.webp';
                            $r_slug         = tao_slug($rp['ten_san_pham']);
                        ?>
                            <a class="product-card" href="chi-tiet-san-pham.php?id=<?php echo (int) $rp['ma_san_pham']; ?>&ten-san-pham=<?php echo $r_slug; ?>">
                                <?php if ($r_giam_gia > 0): ?><span class="product-badge">-<?php echo $r_giam_gia; ?>%</span><?php endif; ?>
                                <div class="product-media">
                                    <img src="<?php echo htmlspecialchars($r_hinh_anh); ?>" alt="<?php echo htmlspecialchars($rp['ten_san_pham']); ?>" loading="lazy">
                                </div>
                                <div class="product-body">
                                    <?php if (!empty($rp['ten_thuong_hieu'])): ?>
                                        <span class="product-brand"><?php echo htmlspecialchars($rp['ten_thuong_hieu']); ?></span>
                                    <?php endif; ?>
                                    <h3 class="product-name"><?php echo htmlspecialchars($rp['ten_san_pham']); ?></h3>
                                    <?php if (!empty($rp['ten_dung_luong'])): ?>
                                        <span class="product-spec"><?php echo htmlspecialchars($rp['ten_dung_luong']); ?></span>
                                    <?php endif; ?>
                                    <div class="product-price-row">
                                        <?php if ($r_gia_ban <= 0): ?>
                                            <span class="product-price product-price-contact">Liên hệ</span>
                                        <?php else: ?>
                                            <span class="product-price"><?php echo number_format($r_gia_sau_giam, 0, ',', '.'); ?>₫</span>
                                            <?php if ($r_giam_gia > 0): ?>
                                                <span class="product-price-old"><?php echo number_format($r_gia_ban, 0, ',', '.'); ?>₫</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (count($related_articles) > 0): ?>
                <div class="product-related-articles">
                    <h2>Bài viết liên quan</h2>
                    <div class="article-list">
                        <?php foreach ($related_articles as $a):
                            $art_anh  = trim($a['article_image']) !== '' ? $a['article_image'] : 'assets/image/pc.webp';
                            $art_ngay = date('d/m/Y', strtotime($a['article_date']));
                            $art_slug = tao_slug($a['article_title']);
                        ?>
                            <a class="article-item" href="chi-tiet-tin-tuc.php?ten-bai-viet=<?php echo $art_slug; ?>">
                                <div class="article-thumb">
                                    <img src="<?php echo htmlspecialchars($art_anh); ?>" alt="<?php echo htmlspecialchars($a['article_title']); ?>" loading="lazy">
                                </div>
                                <div class="article-body">
                                    <h3 class="article-title"><?php echo htmlspecialchars($a['article_title']); ?></h3>
                                    <span class="article-author"><i class="fa-solid fa-circle-user"></i> <?php echo htmlspecialchars($a['article_author']); ?></span>
                                    <span class="article-date"><i class="fa-regular fa-clock"></i> <?php echo $art_ngay; ?></span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>

    <?php include 'footer.php'; ?>

    <script src="assets/js/chi-tiet-san-pham.js"></script>
</body>

</html>
