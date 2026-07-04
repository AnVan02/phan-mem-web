<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm - Viết Sơn Achieva</title>
    <link rel="shortcut icon" href="assets/images/icon/logo VS_icon.jpg"/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="assets/js/header.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/san-pham.css">
</head>

<body>
<?php
    require_once 'admin/config/config.php';
    include 'header.php';

    $ma_danh_muc = isset($_GET['danh_muc']) ? (int) $_GET['danh_muc'] : 0;
    $keyword     = isset($_GET['q']) ? trim($_GET['q']) : '';

    $sql = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu, dl.ten_dung_luong
            FROM san_pham sp
            LEFT JOIN danh_muc dm ON sp.ma_danh_muc = dm.ma_danh_muc
            LEFT JOIN thuong_hieu th ON sp.ma_thuong_hieu = th.ma_thuong_hieu
            LEFT JOIN dung_luong dl ON sp.ma_dung_luong = dl.ma_dung_luong
            WHERE sp.trang_thai = 1";

    $params = [];

    if ($ma_danh_muc > 0) {
        $sql .= " AND sp.ma_danh_muc = :ma_danh_muc";
        $params[':ma_danh_muc'] = $ma_danh_muc;
    }

    if ($keyword !== '') {
        $sql .= " AND (sp.ten_san_pham LIKE :keyword OR sp.ma_san_pham = :ma_san_pham)";
        $params[':keyword'] = '%' . $keyword . '%';
        $params[':ma_san_pham'] = ctype_digit($keyword) ? (int) $keyword : 0;
    }

    $sql .= " ORDER BY sp.ma_san_pham DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $san_pham_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $danh_muc_list = $pdo->query("SELECT * FROM danh_muc ORDER BY ten_danh_muc ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

    <section class="product-page">
        <div class="container">
            <div class="product-page-header">
                <span class="product-eyebrow">— Cửa hàng Viết Sơn</span>
                <h1 class="product-title">Sản phẩm</h1>
            </div>

            <div class="product-toolbar">
                <form class="product-search" action="san-pham.php" method="get">
                    <?php if ($ma_danh_muc > 0): ?>
                        <input type="hidden" name="danh_muc" value="<?php echo $ma_danh_muc; ?>">
                    <?php endif; ?>
                    <input type="text" name="q" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Tìm sản phẩm...">
                    <button type="submit" aria-label="Tìm kiếm"><i class="fas fa-search"></i></button>
                </form>

                <div class="product-filter">
                    <a href="san-pham.php" class="filter-chip <?php echo $ma_danh_muc === 0 ? 'active' : ''; ?>">Tất cả</a>
                    <?php foreach ($danh_muc_list as $dm): ?>
                        <a href="san-pham.php?danh_muc=<?php echo (int) $dm['ma_danh_muc']; ?>"
                           class="filter-chip <?php echo $ma_danh_muc === (int) $dm['ma_danh_muc'] ? 'active' : ''; ?>">
                            <?php echo htmlspecialchars($dm['ten_danh_muc']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if (count($san_pham_list) === 0): ?>
                <div class="product-empty">
                    <i class="fa-solid fa-box-open"></i>
                    <p>Hiện chưa có sản phẩm nào<?php echo $keyword !== '' ? ' phù hợp với từ khóa "' . htmlspecialchars($keyword) . '"' : ''; ?>.</p>
                </div>
            <?php else: ?>
                <div class="product-grid">
                    <?php foreach ($san_pham_list as $sp):
                        $gia_ban      = (int) $sp['gia_ban'];
                        $giam_gia     = (int) $sp['giam_gia'];
                        $gia_sau_giam = $giam_gia > 0 ? (int) round($gia_ban * (100 - $giam_gia) / 100) : $gia_ban;
                        $hinh_anh     = trim($sp['hinh_anh']) !== '' ? $sp['hinh_anh'] : 'assets/image/pc.webp';
                        $slug         = tao_slug($sp['ten_san_pham']);
                    ?>
                        <a class="product-card" href="chi-tiet-san-pham.php?id=<?php echo (int) $sp['ma_san_pham']; ?>&ten-san-pham=<?php echo $slug; ?>">
                            <?php if ($giam_gia > 0): ?><span class="product-badge">-<?php echo $giam_gia; ?>%</span><?php endif; ?>
                            <div class="product-media">
                                <img src="<?php echo htmlspecialchars($hinh_anh); ?>" alt="<?php echo htmlspecialchars($sp['ten_san_pham']); ?>" loading="lazy">
                            </div>
                            <div class="product-body">
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

                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'footer.php'; ?>

</body>

</html>
