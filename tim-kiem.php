<?php
$keyword_tieu_de = isset($_GET['q']) ? trim($_GET['q']) : '';
$page_title       = ($keyword_tieu_de !== '' ? 'Kết quả tìm kiếm "' . $keyword_tieu_de . '"' : 'Tìm kiếm sản phẩm') . ' - Viết Sơn Achieva';
$extra_css        = ['assets/css/san-pham.css'];
$post_css_scripts = ['assets/js/san-pham.js'];
require 'head.php';
?>
    <?php
    require_once 'admin/config/config.php';
    include 'header.php';

    // Danh sách mã sản phẩm khách hàng đang đăng nhập đã yêu thích (dùng cho nút trái tim trên thẻ sản phẩm)
    $wishlisted_ids = [];
    if (isset($_SESSION['khach_hang_id'])) {
        $wl_stmt = $pdo->prepare("SELECT ma_san_pham FROM san_pham_yeu_thich WHERE ma_khach_hang = :kh");
        $wl_stmt->execute([':kh' => $_SESSION['khach_hang_id']]);
        $wishlisted_ids = array_map('intval', $wl_stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';

    // Render 1 thẻ sản phẩm (giống hệt san-pham.php để đồng bộ giao diện)
    function render_the_card($sp)
    {
        global $wishlisted_ids;
        $is_wishlisted = in_array((int) $sp['ma_san_pham'], $wishlisted_ids, true);
        $gia_ban      = (int) $sp['gia_ban'];
        $giam_gia     = (int) $sp['giam_gia'];
        $gia_sau_giam = $giam_gia > 0 ? (int) round($gia_ban * (100 - $giam_gia) / 100) : $gia_ban;
        $anh_list_sp  = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', $sp['hinh_anh']))));
        $hinh_anh     = !empty($anh_list_sp) ? $anh_list_sp[0] : 'assets/image/pc.webp';
        $hinh_anh_hover = !empty($anh_list_sp[1]) ? $anh_list_sp[1] : '';
        $tra_truoc    = $gia_ban > 0 ? (int) round($gia_sau_giam * 0.3 / 100000) * 100000 : 0;
        ?>
    <a class="product-card<?php echo $hinh_anh_hover !== '' ? ' has-hover-image' : ''; ?>"
        href="<?php echo tao_url_san_pham($sp['ma_san_pham'], $sp['ten_san_pham']); ?>">
        <?php if ($giam_gia > 0): ?><span class="product-badge">-<?php echo $giam_gia; ?>%</span><?php endif; ?>
        <span class="product-badge-official"><i class="fa-solid fa-circle-check"></i> Chính hãng</span>
        <div class="product-media">
            <img class="product-media-img is-primary" src="<?php echo htmlspecialchars($hinh_anh); ?>"
                alt="<?php echo htmlspecialchars($sp['ten_san_pham']); ?>" loading="lazy"
                onerror="this.onerror=null;this.src='assets/image/pc.webp';">
            <?php if ($hinh_anh_hover !== ''): ?>
            <img class="product-media-img is-secondary" src="<?php echo htmlspecialchars($hinh_anh_hover); ?>"
                alt="<?php echo htmlspecialchars($sp['ten_san_pham']); ?>" loading="lazy"
                onerror="this.onerror=null;this.src='assets/image/pc.webp';">
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
            $mo_ta_ngan = trim(strip_tags(html_entity_decode($sp['mo_ta'] ?? '', ENT_QUOTES, 'UTF-8')));
            if (mb_strlen($mo_ta_ngan) > 250) {
                $mo_ta_ngan = mb_substr($mo_ta_ngan, 0, 250) . '...';
            }
            ?>
            <p class="product-desc-text"><?php echo htmlspecialchars($mo_ta_ngan); ?></p>
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
                        data-share-url="<?php echo tao_url_san_pham($sp['ma_san_pham'], $sp['ten_san_pham']); ?>"
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

            <?php if ($gia_ban > 0): ?>
            <div class="product-prepay">Hoặc trả trước <b><?php echo number_format($tra_truoc, 0, ',', '.'); ?>đ</b>
            </div>
            <?php endif; ?>
        </div>
    </a>
    <?php
    }

    // Tìm kiếm mở rộng: tên sản phẩm, mô tả, thương hiệu, danh mục, hoặc đúng mã sản phẩm
    $san_pham_list = [];
    $tong_ket_qua  = 0;
    $SO_SP_MOI_TRANG = 20;
    $trang_hien_tai   = isset($_GET['trang']) ? max(1, (int) $_GET['trang']) : 1;

    if ($keyword !== '') {
        $where = "sp.trang_thai = 1 AND (
                sp.ten_san_pham LIKE :q1
                OR sp.mo_ta LIKE :q2
                OR th.ten_thuong_hieu LIKE :q3
                OR dm.ten_danh_muc LIKE :q4
                OR sp.ma_san_pham = :id
            )";
        $like   = '%' . $keyword . '%';
        $params = [
            ':q1' => $like,
            ':q2' => $like,
            ':q3' => $like,
            ':q4' => $like,
            ':id' => ctype_digit($keyword) ? (int) $keyword : 0,
        ];

        $dem_stmt = $pdo->prepare("SELECT COUNT(*) FROM san_pham sp
            LEFT JOIN thuong_hieu th ON sp.ma_thuong_hieu = th.ma_thuong_hieu
            LEFT JOIN danh_muc dm ON sp.ma_danh_muc = dm.ma_danh_muc
            WHERE $where");
        $dem_stmt->execute($params);
        $tong_ket_qua = (int) $dem_stmt->fetchColumn();

        $tong_trang = max(1, (int) ceil($tong_ket_qua / $SO_SP_MOI_TRANG));
        if ($trang_hien_tai > $tong_trang) {
            $trang_hien_tai = $tong_trang;
        }
        $bat_dau = ($trang_hien_tai - 1) * $SO_SP_MOI_TRANG;

        $sql = "SELECT sp.*, th.ten_thuong_hieu, dl.ten_dung_luong
                FROM san_pham sp
                LEFT JOIN thuong_hieu th ON sp.ma_thuong_hieu = th.ma_thuong_hieu
                LEFT JOIN danh_muc dm ON sp.ma_danh_muc = dm.ma_danh_muc
                LEFT JOIN dung_luong dl ON sp.ma_dung_luong = dl.ma_dung_luong
                WHERE $where
                ORDER BY (sp.ten_san_pham LIKE :q5) DESC, sp.da_ban DESC, sp.ma_san_pham DESC
                LIMIT $SO_SP_MOI_TRANG OFFSET $bat_dau";
        $params[':q5'] = $like;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $san_pham_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $tong_trang = 1;
    }

    function xay_url_trang_tim_kiem($trang)
    {
        $params = $_GET;
        $params['trang'] = $trang;
        return 'tim-kiem.php?' . http_build_query($params);
    }
    ?>

    <section class="product-page">
        <div class="container">
            <div class="product-page-header">
                <a href="/index.php">Trang chủ</a>
                <span class="product-eyebrow">— Kết quả tìm kiếm</span>
                <h1 class="product-title">
                    <?php echo $keyword !== '' ? 'Tìm kiếm: "' . htmlspecialchars($keyword) . '"' : 'Tìm kiếm sản phẩm'; ?>
                </h1>
                <?php if ($keyword !== ''): ?>
                <p class="search-result-count"><?php echo $tong_ket_qua; ?> sản phẩm phù hợp</p>
                <?php endif; ?>
            </div>

            <?php if ($keyword === ''): ?>
            <p style="padding: 40px 0; text-align:center; color:#888;">
                Vui lòng nhập từ khóa vào ô tìm kiếm ở trên để tìm sản phẩm.</p>
            <?php elseif (empty($san_pham_list)): ?>
            <p style="padding: 40px 0; text-align:center; color:#888;">
                Không tìm thấy sản phẩm nào phù hợp với "<?php echo htmlspecialchars($keyword); ?>".<br>
                Vui lòng thử từ khóa khác hoặc xem <a href="san-pham.php">toàn bộ sản phẩm</a>.</p>
            <?php else: ?>
            <div class="product-grid">
                <?php foreach ($san_pham_list as $sp): ?>
                <?php render_the_card($sp); ?>
                <?php endforeach; ?>
            </div>

            <?php if ($tong_trang > 1): ?>
            <div class="product-pagination">
                <a class="page-nav <?php echo $trang_hien_tai <= 1 ? 'disabled' : ''; ?>"
                    href="<?php echo xay_url_trang_tim_kiem(max(1, $trang_hien_tai - 1)); ?>">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                <?php for ($i = 1; $i <= $tong_trang; $i++): ?>
                <a class="page-num <?php echo $i === $trang_hien_tai ? 'active' : ''; ?>"
                    href="<?php echo xay_url_trang_tim_kiem($i); ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
                <a class="page-nav <?php echo $trang_hien_tai >= $tong_trang ? 'disabled' : ''; ?>"
                    href="<?php echo xay_url_trang_tim_kiem(min($tong_trang, $trang_hien_tai + 1)); ?>">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'footer.php'; ?>

</body>

</html>
