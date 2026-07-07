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

    $page_title = $sp ? htmlspecialchars($sp['ten_san_pham']) . ' - ACHIVA Achieva' : 'Chi tiết sản phẩm - ACHIVA Achieva';
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

            <div class="product-detail-layout">
                <!-- Cột trái -->
                <div class="product-detail-left">
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
                    <?php if (count($related_list) > 0): ?>
                        <div class="product-related-inline">
                            <div class="related-inline-header">
                                <h2><i class="fa-solid fa-layer-group"></i> Sản phẩm nên mua cùng</h2>
                                <span class="related-inline-count"><?php echo count($related_list); ?> sản phẩm</span>
                            </div>
                            <div class="product-grid-small">
                                <?php foreach ($related_list as $rp):
                                    $r_gia_ban      = (int) $rp['gia_ban'];
                                    $r_giam_gia     = (int) $rp['giam_gia'];
                                    $r_gia_sau_giam = $r_giam_gia > 0 ? (int) round($r_gia_ban * (100 - $r_giam_gia) / 100) : $r_gia_ban;
                                    $r_anh_list     = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', $rp['hinh_anh']))));
                                    $r_hinh_anh     = !empty($r_anh_list) ? $r_anh_list[0] : 'assets/image/pc.webp';
                                    $r_slug         = tao_slug($rp['ten_san_pham']);
                                ?>
                                    <a class="product-card-small" href="chi-tiet-san-pham.php?id=<?php echo (int) $rp['ma_san_pham']; ?>&ten-san-pham=<?php echo $r_slug; ?>">
                                        <?php if ($r_giam_gia > 0): ?><span class="product-badge">-<?php echo $r_giam_gia; ?>%</span><?php endif; ?>
                                        <div class="product-media">
                                            <img src="<?php echo htmlspecialchars($r_hinh_anh); ?>" alt="<?php echo htmlspecialchars($rp['ten_san_pham']); ?>" loading="lazy" onerror="this.onerror=null;this.src='assets/image/pc.webp';">
                                        </div>
                                        <div class="product-body">
                                            <?php if (!empty($rp['ten_thuong_hieu'])): ?>
                                                <span class="product-brand"><?php echo htmlspecialchars($rp['ten_thuong_hieu']); ?></span>
                                            <?php endif; ?>
                                            <h3 class="product-name"><?php echo htmlspecialchars($rp['ten_san_pham']); ?></h3>
                                            <div class="product-price-row">
                                                <?php if ($r_gia_ban <= 0): ?>
                                                    <span class="product-price">Liên hệ</span>
                                                <?php else: ?>
                                                    <span class="product-price"><?php echo number_format($r_gia_sau_giam, 0, ',', '.'); ?>₫</span>
                                                    <?php if ($r_giam_gia > 0): ?>
                                                        <span class="product-price-old"><?php echo number_format($r_gia_ban, 0, ',', '.'); ?>₫</span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <span class="product-card-small-cta">Xem chi tiết <i class="fa-solid fa-arrow-right"></i></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty(trim($sp['mo_ta'] ?? ''))): ?>
                        <div class="product-detail-desc-wrapper">
                            <div class="desc-tab-header">
                                <span class="desc-tab-active">Thông tin sản phẩm</span>
                            </div>
                            <div class="product-detail-desc-box">
                                <div class="product-detail-desc-content" id="descContent">
                                    <?php echo $sp['mo_ta']; ?>
                                </div>
                                <div class="desc-fade-bg" id="descFade"></div>
                                <div class="desc-btn-wrapper">
                                    <button type="button" class="btn-read-more" id="btnReadMoreDesc">Xem Thêm Nội Dung <i class="fa-solid fa-caret-down"></i></button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Cột phải -->
                <div class="product-detail-right">
                    
                    <div class="product-header-right">
                        <?php if (!empty($sp['ten_thuong_hieu'])): ?>
                            <span class="product-brand"><?php echo htmlspecialchars($sp['ten_thuong_hieu']); ?></span>
                        <?php endif; ?>
                        <h1 class="product-detail-name"><?php echo htmlspecialchars($sp['ten_san_pham']); ?></h1>
                        <div class="product-rating-row">
                            <div class="stars">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            </div>
                            <!-- <span class="rating-count">12 Đánh giá</span> -->
                            <span class="compare-btn" id="btnOpenCompareModal"><i class="fa-solid fa-plus"></i> So sánh</span>
                        </div>
                    </div>

                    <?php if (!empty ($sp['ten_dung_luong'])):?>
                    <div class="product-variants">
                        <div class="variant-group">
                            <div class="variant-label">Dung lượng:</div>
                            <div class="variant-list">
                                <span class="variant-item active"><?php echo htmlspecialchars($sp['ten_dung_luong']);?></span>
                            </div>
                        </div>
                    </div>
                    <?php endif;?>

                    <div class="product-price-block">
                        <?php if ($gia_ban <= 0): ?>
                            <span class="product-price product-price-contact">Liên hệ</span>
                        <?php else: ?>
                            <span class="product-price"><?php echo number_format($gia_sau_giam, 0, ',', '.'); ?>₫</span>
                            <?php if ($giam_gia > 0): ?>
                                <span class="product-price-old"><?php echo number_format($gia_ban, 0, ',', '.'); ?>₫</span>
                                <span class="product-discount-badge">-<?php echo $giam_gia; ?>%</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <!-- <div class="promo-box">
                        <div class="promo-header">
                            <i class="fa-solid fa-gift"></i>
                        </div>
                        <div class="promo-body">
                            <p class="promo-note">Giá và khuyến mãi dự kiến áp dụng đến 23:59 hôm nay</p>
                            <ul>
                                <li><i class="fa-solid fa-circle-check"></i> Giảm thêm 5% khi thanh toán qua ví điện tử.</li>
                                <li><i class="fa-solid fa-circle-check"></i> Thu cũ đổi mới trợ giá đến 2 triệu đồng.</li>
                            </ul>
                        </div>
                    </div> -->

                    <div class="product-detail-actions-vertical">
                        <?php if ($so_luong <= 0 || $gia_ban <= 0): ?>
                            <a href="tel:" class="btn-buy-now btn-contact">
                                <strong>Liên hệ đặt hàng</strong>
                            </a>
                        <?php else: ?>
                            <div class="qty-selector">
                                <span class="qty-label">Số lượng:</span>
                                <div class="qty-control">
                                    <button type="button" class="qty-minus" aria-label="Giảm số lượng">-</button>
                                    <input type="number" class="qty-input" value="1" min="1" max="<?php echo $so_luong; ?>">
                                    <button type="button" class="qty-plus" aria-label="Tăng số lượng">+</button>
                                </div>
                                <span class="qty-stock">Còn <?php echo $so_luong; ?> sản phẩm</span>
                            </div>
                            <button type="button" class="btn-add-cart" data-ma-san-pham="<?php echo (int) $sp['ma_san_pham']; ?>">
                                <i class="fa-solid fa-cart-plus"></i>
                                <strong>Thêm vào giỏ hàng</strong>
                            </button>
                        <?php endif; ?>
                    </div>

                    <ul class="product-policies-box">
                        <li><i class="fa-solid fa-box-open"></i>Lựa chọn sản phẩm chính hãng</li>
                        <li><i class="fa-solid fa-rotate-left"></i> 1 đổi 1 trong 30 ngày đối với sản phẩm lỗi</li>
                        <li><i class="fa-solid fa-shield-halved"></i> Bảo hành chính hãng 12 tháng</li>
                        <li><i class="fa-solid fa-truck-fast"></i> Giao hàng hỏa tốc</li>
                    </ul>

                    <?php if (!empty(trim($sp['thong-so'] ?? ''))): ?>
                        <div class="product-detail-specs-right">
                            <h3>Thông số kỹ thuật</h3>
                            <div class="specs-content specs-content-collapsed">
                                <?php echo $sp['thong-so']; ?>
                            </div>
                            <button type="button" class="btn-view-more-specs" id="btnOpenSpecsModal">Xem cấu hình chi tiết <i class="fa-solid fa-caret-down"></i></button>
                        </div>

                        <!-- Specs Modal -->
                        <div id="specsModal" class="specs-modal">
                            <div class="specs-modal-dialog">
                                <div class="specs-modal-header">
                                    <h3>Thông Số Kỹ Thuật</h3>
                                    <button type="button" class="btn-close-modal" id="btnCloseSpecsModal"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                <div class="specs-modal-body">
                                    <div class="specs-content">
                                        <?php echo $sp['thong-so']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Compare Modal -->
                    <div id="compareModal" class="compare-modal" data-current-id="<?php echo (int) $sp['ma_san_pham']; ?>" data-danh-muc="<?php echo (int) $sp['ma_danh_muc']; ?>">
                        <div class="compare-modal-dialog">
                            <div class="compare-modal-header">
                                <h3>So Sánh Sản Phẩm</h3>
                                <button type="button" class="btn-close-modal" id="btnCloseCompareModal"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="compare-modal-body">
                                <div class="compare-search-box">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    <input type="text" id="compareSearchInput" placeholder="Nhập tên sản phẩm cần so sánh...">
                                    <div class="compare-search-results" id="compareSearchResults"></div>
                                </div>
                                <div class="compare-placeholder" id="comparePlaceholder">
                                    <i class="fa-solid fa-scale-balanced"></i>
                                    <p>Nhập tên sản phẩm ở trên để bắt đầu so sánh.</p>
                                </div>
                                <div class="compare-table-wrapper" id="compareTableWrapper" style="display:none;">
                                    <table class="compare-table">
                                        <tbody>
                                            <tr class="compare-row-image">
                                                <th></th>
                                                <td>
                                                    <img src="<?php echo htmlspecialchars($hinh_anh); ?>" alt="<?php echo htmlspecialchars($sp['ten_san_pham']); ?>">
                                                    <div class="compare-product-name"><?php echo htmlspecialchars($sp['ten_san_pham']); ?></div>
                                                </td>
                                                <td id="compareOtherImage"></td>
                                            </tr>
                                            <tr>
                                                <th>Thương hiệu</th>
                                                <td><?php echo htmlspecialchars($sp['ten_thuong_hieu'] ?? ''); ?></td>
                                                <td id="compareOtherBrand"></td>
                                            </tr>
                                            <tr>
                                                <th>Dung lượng</th>
                                                <td><?php echo htmlspecialchars($sp['ten_dung_luong'] ?? ''); ?></td>
                                                <td id="compareOtherCapacity"></td>
                                            </tr>
                                            <tr>
                                                <th>Giá</th>
                                                <td><?php echo $gia_ban <= 0 ? 'Liên hệ' : number_format($gia_sau_giam, 0, ',', '.') . '₫'; ?></td>
                                                <td id="compareOtherPrice"></td>
                                            </tr>
                                            <tr class="compare-row-specs">
                                                <th>Thông số kỹ thuật</th>
                                                <td><?php echo $sp['thong-so'] ?? ''; ?></td>
                                                <td id="compareOtherSpecs"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero-slide active" style="background-image: url('assets/image/banner1.png');">
                <div class="hero-overlay"></div>
                <div class="hero-slide-content">
                    <!-- <h2 class="hero-title">Chào mừng đến với cửa hàng</h2> -->
                </div>
            </div>

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
                                    <!-- <span class="article-content"><i class=""></i><?php echo htmlspecialchars($a['article_content']);?></span> -->
                                    <span class="article-summary"><i class=""></i><?php echo htmlspecialchars($a['article_summary']);?>
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
