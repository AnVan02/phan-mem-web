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
    <script src="assets/js/san-pham.js" defer></script>
</head>

<body>
<?php
    require_once 'admin/config/config.php';
    include 'header.php';

    $ma_danh_muc = isset($_GET['danh_muc']) ? (int) $_GET['danh_muc'] : 0;
    $keyword     = isset($_GET['q']) ? trim($_GET['q']) : '';
    $dong        = isset($_GET['dong']) ? trim($_GET['dong']) : '';

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

    // Nhóm sản phẩm trong danh mục đang chọn theo "dòng" (dữ liệu thật từ bảng dung_luong), dùng để hiện dải vòng tròn con.
    $dong_groups = [];
    if ($ma_danh_muc > 0 && $keyword === '') {
        foreach ($san_pham_list as $sp_tmp) {
            $ten_dong = trim($sp_tmp['ten_dung_luong'] ?? '');
            if ($ten_dong === '') {
                continue;
            }
            if (!isset($dong_groups[$ten_dong])) {
                $anh_goc = trim($sp_tmp['hinh_anh']) !== ''
                    ? array_values(array_filter(array_map('trim', preg_split('/[,;]+/', $sp_tmp['hinh_anh']))))
                    : [];
                $dong_groups[$ten_dong] = [
                    'ten'  => $ten_dong,
                    'anh'  => !empty($anh_goc) ? $anh_goc[0] : '',
                ];
            }
        }
    }
    if (count($dong_groups) < 2) {
        $dong_groups = [];
    }

    if ($dong !== '') {
        $san_pham_list = array_values(array_filter($san_pham_list, function ($sp_tmp) use ($dong) {
            return trim($sp_tmp['ten_dung_luong'] ?? '') === $dong;
        }));
    }

    // Danh mục/dòng chưa có sản phẩm nào thì vẫn hiện hết tất cả sản phẩm thay vì để trang trống.
    $hien_fallback_tat_ca = false;
    if (count($san_pham_list) === 0 && $keyword === '' && ($ma_danh_muc > 0 || $dong !== '')) {
        $san_pham_list = $pdo->query("SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu, dl.ten_dung_luong
                FROM san_pham sp
                LEFT JOIN danh_muc dm ON sp.ma_danh_muc = dm.ma_danh_muc
                LEFT JOIN thuong_hieu th ON sp.ma_thuong_hieu = th.ma_thuong_hieu
                LEFT JOIN dung_luong dl ON sp.ma_dung_luong = dl.ma_dung_luong
                WHERE sp.trang_thai = 1
                ORDER BY sp.ma_san_pham DESC")->fetchAll(PDO::FETCH_ASSOC);
        $hien_fallback_tat_ca = true;
    }

    $danh_muc_list = $pdo->query("SELECT * FROM danh_muc ORDER BY ten_danh_muc ASC")->fetchAll(PDO::FETCH_ASSOC);

    $anh_dai_dien_stmt = $pdo->prepare("SELECT hinh_anh FROM san_pham
        WHERE ma_danh_muc = :ma_danh_muc AND trang_thai = 1 AND hinh_anh <> ''
        ORDER BY ma_san_pham DESC LIMIT 1");
    foreach ($danh_muc_list as &$dm) {
        $anh_dai_dien_stmt->execute([':ma_danh_muc' => $dm['ma_danh_muc']]);
        $anh_goc = $anh_dai_dien_stmt->fetchColumn();
        $anh_list = $anh_goc ? array_values(array_filter(array_map('trim', preg_split('/[,;]+/', $anh_goc)))) : [];
        $dm['anh_dai_dien'] = !empty($anh_list) ? $anh_list[0] : '';
    }
    unset($dm);
?>

    <section class="product-page">
        <div class="container">
            <div class="product-page-header">
                <span class="product-eyebrow">— Cửa hàng Viết Sơn</span>
                <h1 class="product-title">Sản phẩm</h1>
            </div>

            <div class="category-strip">
                <a href="san-pham.php" class="category-circle <?php echo $ma_danh_muc === 0 ? 'active' : ''; ?>">
                    <span class="category-circle-img category-circle-all"><i class="fa-solid fa-border-all"></i></span>
                    <span class="category-circle-name">Tất cả</span>
                </a>
                <?php foreach ($danh_muc_list as $dm):
                    $anh_dm = $dm['anh_dai_dien'] !== '' ? $dm['anh_dai_dien'] : 'assets/image/pc.webp';
                ?>
                    <a href="san-pham.php?danh_muc=<?php echo (int) $dm['ma_danh_muc']; ?>"
                       class="category-circle <?php echo $ma_danh_muc === (int) $dm['ma_danh_muc'] ? 'active' : ''; ?>">
                        <span class="category-circle-img">
                            <img src="<?php echo htmlspecialchars($anh_dm); ?>" alt="<?php echo htmlspecialchars($dm['ten_danh_muc']); ?>" loading="lazy" onerror="this.onerror=null;this.src='assets/image/pc.webp';">
                        </span>
                        <span class="category-circle-name"><?php echo htmlspecialchars($dm['ten_danh_muc']); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>

            <?php if (!empty($dong_groups)): ?>
                <div class="category-strip category-strip-sub">
                    <a href="san-pham.php?danh_muc=<?php echo $ma_danh_muc; ?>" class="category-circle category-circle-sm <?php echo $dong === '' ? 'active' : ''; ?>">
                        <span class="category-circle-img category-circle-all"><i class="fa-solid fa-border-all"></i></span>
                        <span class="category-circle-name">Tất cả</span>
                    </a>
                    <?php foreach ($dong_groups as $nhom):
                        $anh_dong = $nhom['anh'] !== '' ? $nhom['anh'] : 'assets/image/pc.webp';
                    ?>
                        <a href="san-pham.php?danh_muc=<?php echo $ma_danh_muc; ?>&dong=<?php echo urlencode($nhom['ten']); ?>"
                           class="category-circle category-circle-sm <?php echo $dong === $nhom['ten'] ? 'active' : ''; ?>">
                            <span class="category-circle-img">
                                <img src="<?php echo htmlspecialchars($anh_dong); ?>" alt="<?php echo htmlspecialchars($nhom['ten']); ?>" loading="lazy" onerror="this.onerror=null;this.src='assets/image/pc.webp';">
                            </span>
                            <span class="category-circle-name"><?php echo htmlspecialchars($nhom['ten']); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="product-toolbar">
                <form class="product-search" action="san-pham.php" method="get">
                    <?php if ($ma_danh_muc > 0): ?>
                        <input type="hidden" name="danh_muc" value="<?php echo $ma_danh_muc; ?>">
                    <?php endif; ?>
                    <input type="text" name="q" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Tìm sản phẩm...">
                    <button type="submit" aria-label="Tìm kiếm"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <?php if (count($san_pham_list) === 0): ?>
                <div class="product-empty">
                    <i class="fa-solid fa-box-open"></i>
                    <p>Hiện chưa có sản phẩm nào<?php echo $keyword !== '' ? ' phù hợp với từ khóa "' . htmlspecialchars($keyword) . '"' : ''; ?>.</p>
                </div>
            <?php else: ?>
                <?php if ($hien_fallback_tat_ca): ?>
                    <div class="product-notice">
                        <i class="fa-solid fa-circle-info"></i>
                        Mục này chưa có sản phẩm nào, dưới đây là tất cả sản phẩm hiện có.
                    </div>
                <?php endif; ?>
                <div class="product-grid">
                    <?php foreach ($san_pham_list as $sp):
                        $gia_ban      = (int) $sp['gia_ban'];
                        $giam_gia     = (int) $sp['giam_gia'];
                        $gia_sau_giam = $giam_gia > 0 ? (int) round($gia_ban * (100 - $giam_gia) / 100) : $gia_ban;
                        $anh_list_sp  = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', $sp['hinh_anh']))));
                        $hinh_anh     = !empty($anh_list_sp) ? $anh_list_sp[0] : 'assets/image/pc.webp';
                        $slug         = tao_slug($sp['ten_san_pham']);
                    ?>
                        <?php $tra_truoc = $gia_ban > 0 ? (int) round($gia_sau_giam * 0.3 / 100000) * 100000 : 0; ?>
                        <a class="product-card" href="chi-tiet-san-pham.php?id=<?php echo (int) $sp['ma_san_pham']; ?>&ten-san-pham=<?php echo $slug; ?>">
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

                                <?php if ($gia_ban > 0): ?>
                                    <div class="product-installment">
                                        <span>Trả góp <b>0%</b></span>
                                        <span class="dot">·</span>
                                        <span>Trước <b>0đ</b></span>
                                        <span class="dot">·</span>
                                        <span>Phí <b>0đ</b></span>
                                    </div>
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

                                <div class="product-rating">
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
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
