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

    $is_wishlisted = false;
    if ($sp && isset($_SESSION['khach_hang_id'])) {
        $w_stmt = $pdo->prepare("SELECT 1 FROM san_pham_yeu_thich WHERE ma_khach_hang = :kh AND ma_san_pham = :sp LIMIT 1");
        $w_stmt->execute([':kh' => $_SESSION['khach_hang_id'], ':sp' => $ma_san_pham]);
        $is_wishlisted = (bool) $w_stmt->fetchColumn();
    }

    $reviews = [];
    $avg_rating = 5;
    if ($sp) {
        $r_stmt = $pdo->prepare("SELECT dg.*, kh.customer_name FROM danh_gia_san_pham dg JOIN khach_hang_lien_he kh ON dg.ma_khach_hang = kh.ma_lien_he WHERE dg.ma_san_pham = :sp ORDER BY dg.ngay_danh_gia DESC");
        $r_stmt->execute([':sp' => $ma_san_pham]);
        $reviews = $r_stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($reviews) > 0) {
            $sum = 0;
            foreach ($reviews as $r) {
                $sum += $r['so_sao'];
            }
            $avg_rating = round($sum / count($reviews), 1);
        }
    }

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
                            <div class="stars" style="color: #fbbf24;">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fa-<?php echo $i <= round($avg_rating) ? 'solid' : 'regular'; ?> fa-star"></i>
                                <?php endfor; ?>
                                <span style="color: #6b7280; font-size: 14px; margin-left: 5px;">(<?php echo count($reviews); ?> đánh giá)</span>
                            </div>
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
                            <button type="button" class="btn-add-cart" data-ma-san-pham="<?php echo (int) $sp['ma_san_pham']; ?>" style="flex: 1;">
                                <i class="fa-solid fa-cart-plus"></i>
                                <strong>Thêm vào giỏ hàng</strong>
                            </button>
                            <button type="button" class="btn-wishlist <?php echo $is_wishlisted ? 'active' : ''; ?>" data-ma-san-pham="<?php echo (int) $sp['ma_san_pham']; ?>" style="padding: 0 20px; border: 1px solid #e5e7eb; background: #fff; color: <?php echo $is_wishlisted ? '#e11d48' : '#6b7280'; ?>; border-radius: 8px; font-size: 20px; cursor: pointer; transition: all 0.2s;" title="Yêu thích">
                                <i class="fa-<?php echo $is_wishlisted ? 'solid' : 'regular'; ?> fa-heart"></i>
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

            <!-- Reviews Section -->
            <div class="product-reviews-section" style="margin-top: 40px; padding: 20px; background: #fff; border-radius: 8px; border: 1px solid #e5e7eb;">
                <h2 style="font-size: 20px; font-weight: 600; margin-bottom: 20px;"><i class="fa-solid fa-comments"></i> Đánh giá sản phẩm</h2>
                
                <?php if (isset($_GET['msg'])): ?>
                    <?php 
                        $m = $_GET['msg'];
                        $m_text = '';
                        $m_color = '#dc2626';
                        if ($m === 'danh_gia_thanh_cong') { $m_text = 'Cảm ơn bạn đã đánh giá sản phẩm!'; $m_color = '#16a34a'; }
                        elseif ($m === 'loi_dang_nhap') $m_text = 'Vui lòng đăng nhập để đánh giá.';
                        elseif ($m === 'loi_chua_mua') $m_text = 'Bạn cần mua sản phẩm này để có thể đánh giá.';
                        elseif ($m === 'loi_thieu_thong_tin') $m_text = 'Vui lòng nhập đầy đủ thông tin đánh giá.';
                    ?>
                    <?php if ($m_text): ?>
                        <div style="padding: 10px; background: <?php echo $m_color; ?>20; color: <?php echo $m_color; ?>; border-radius: 5px; margin-bottom: 20px;">
                            <?php echo $m_text; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <div style="display: flex; gap: 40px; flex-wrap: wrap;">
                    <!-- Form đánh giá -->
                    <div style="flex: 1; min-width: 300px;">
                        <h3 style="font-size: 16px; margin-bottom: 10px;">Gửi đánh giá của bạn</h3>
                        <form action="xuly-danh-gia.php" method="POST">
                            <input type="hidden" name="ma_san_pham" value="<?php echo (int) $sp['ma_san_pham']; ?>">
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px;">Đánh giá sao:</label>
                                <select name="so_sao" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="5">5 Sao - Tuyệt vời</option>
                                    <option value="4">4 Sao - Tốt</option>
                                    <option value="3">3 Sao - Bình thường</option>
                                    <option value="2">2 Sao - Kém</option>
                                    <option value="1">1 Sao - Tệ</option>
                                </select>
                            </div>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px;">Nội dung đánh giá:</label>
                                <textarea name="noi_dung" rows="4" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;" required placeholder="Chia sẻ cảm nhận của bạn về sản phẩm..."></textarea>
                            </div>
                            <button type="submit" class="btn-submit" style="background: #2563eb; color: #fff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Gửi đánh giá</button>
                        </form>
                    </div>

                    <!-- Danh sách đánh giá -->
                    <div style="flex: 2; min-width: 300px;">
                        <h3 style="font-size: 16px; margin-bottom: 10px;">Khách hàng nhận xét (<?php echo count($reviews); ?>)</h3>
                        <?php if (empty($reviews)): ?>
                            <p style="color: #6b7280; font-style: italic;">Chưa có đánh giá nào cho sản phẩm này.</p>
                        <?php else: ?>
                            <div style="max-height: 400px; overflow-y: auto; padding-right: 10px;">
                                <?php foreach ($reviews as $r): ?>
                                    <div style="border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 15px;">
                                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                            <strong style="font-size: 15px;"><?php echo htmlspecialchars($r['customer_name']); ?></strong>
                                            <span style="color: #9ca3af; font-size: 13px;"><?php echo date('d/m/Y', strtotime($r['ngay_danh_gia'])); ?></span>
                                        </div>
                                        <div style="color: #fbbf24; margin-bottom: 8px; font-size: 12px;">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fa-<?php echo $i <= (int)$r['so_sao'] ? 'solid' : 'regular'; ?> fa-star"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <p style="margin: 0; font-size: 14px; line-height: 1.5; color: #374151;"><?php echo nl2br(htmlspecialchars($r['noi_dung'])); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
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
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const btnWishlist = document.querySelector('.btn-wishlist');
        if (btnWishlist) {
            btnWishlist.addEventListener('click', function() {
                const maSp = this.dataset.maSanPham;
                const formData = new FormData();
                formData.append('action', 'toggle');
                formData.append('ma_san_pham', maSp);
                
                fetch('yeu-thich-ajax.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (data.status === 'added') {
                            this.classList.add('active');
                            this.style.color = '#e11d48';
                            this.querySelector('i').classList.remove('fa-regular');
                            this.querySelector('i').classList.add('fa-solid');
                        } else {
                            this.classList.remove('active');
                            this.style.color = '#6b7280';
                            this.querySelector('i').classList.remove('fa-solid');
                            this.querySelector('i').classList.add('fa-regular');
                        }
                    } else {
                        alert(data.message);
                        if (data.message.includes('đăng nhập')) {
                            window.location.href = 'tai-khoan.php';
                        }
                    }
                })
                .catch(err => console.error('Lỗi khi thêm yêu thích:', err));
            });
        }
    });
    </script>
</body>
</html>
