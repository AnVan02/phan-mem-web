<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm - Viết Sơn Achieva</title>
    <link rel="shortcut icon" href="assets/images/icon/logo VS_icon.jpg" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script src="assets/js/header.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/san-pham.css">
    <script src="assets/js/san-pham.js" defer></script>

    <!--
        Ghi chú: khối CSS bên dưới dành riêng cho sidebar "Danh mục sản phẩm" vừa thêm.
        Bạn có thể copy phần này qua assets/css/san-pham.css rồi xoá thẻ <style> này đi,
        và chỉnh lại màu sắc (--vs-primary...) cho khớp theme hiện tại của site.
    -->
</head>

<body>
    <?php
    require_once 'admin/config/config.php';
    include 'header.php';

    $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';

    // Query string dùng chung để build lại link (giữ nguyên từ khóa tìm kiếm)
    // -- vẫn giữ lại biến này vì đang được dùng cho link sang mo-ta-linh-kien.php bên dưới
    $qs_giu_keyword = $keyword !== '' ? '&q=' . urlencode($keyword) : '';

    /**
     * Gộp các tham số lọc hiện tại (q, th, dl, gia, gia_min, gia_max) với phần
     * ghi đè trong $ghi_de, bỏ các giá trị rỗng, rồi trả về chuỗi query string
     * (bắt đầu bằng "?", hoặc rỗng nếu không còn tham số nào).
     * Dùng để xây link cho các bộ lọc trong sidebar mà không làm mất các lựa chọn khác.
     */
    function xay_qs($ghi_de = [])
    {
        $params = [
            'q'       => isset($_GET['q']) ? trim($_GET['q']) : '',
            'th'      => isset($_GET['th']) ? trim($_GET['th']) : '',
            'dl'      => isset($_GET['dl']) ? trim($_GET['dl']) : '',
            'gia'     => isset($_GET['gia']) ? trim($_GET['gia']) : '',
            'gia_min' => isset($_GET['gia_min']) ? trim($_GET['gia_min']) : '',
            'gia_max' => isset($_GET['gia_max']) ? trim($_GET['gia_max']) : '',
        ];
        $params = array_merge($params, $ghi_de);
        $params = array_filter($params, function ($v) {
            return $v !== '' && $v !== null;
        });
        return !empty($params) ? '?' . http_build_query($params) : '';
    }

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

    // Danh sách dung lượng để làm bộ lọc, kèm số lượng sản phẩm đang active mỗi dung lượng
    $dung_luong_stmt = $pdo->query("SELECT dl.ma_dung_luong, dl.ten_dung_luong, COUNT(*) AS so_luong
        FROM san_pham sp
        JOIN dung_luong dl ON sp.ma_dung_luong = dl.ma_dung_luong
        WHERE sp.trang_thai = 1
        GROUP BY dl.ma_dung_luong, dl.ten_dung_luong
        ORDER BY dl.ma_dung_luong ASC");
    $dung_luong_options = $dung_luong_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Nhận dung lượng lọc qua tên (slug), vd: ?dl=512gb
    $dl_slug      = isset($_GET['dl']) ? trim($_GET['dl']) : '';
    $ma_dl_filter = 0;
    if ($dl_slug !== '') {
        foreach ($dung_luong_options as $dl_row) {
            if (tao_slug($dl_row['ten_dung_luong']) === $dl_slug) {
                $ma_dl_filter = (int) $dl_row['ma_dung_luong'];
                break;
            }
        }
    }

    // Các khoảng mức giá cố định (lọc theo giá gốc gia_ban) + khoảng tuỳ chỉnh (gia_min, gia_max)
    // Lưu ý: bảng san_pham hiện chưa có cột "chuẩn kết nối" nên phần lọc đó chỉ để placeholder,
    // cần bổ sung cột tương ứng (vd: chuan_ket_noi) nếu muốn lọc thật.
    $gia_khoang = [
        'duoi-2tr'  => ['nhan' => 'Dưới 2 triệu',  'min' => 0,         'max' => 2000000],
        '2-5tr'     => ['nhan' => '2 - 5 triệu',   'min' => 2000000,   'max' => 5000000],
        '5-10tr'    => ['nhan' => '5 - 10 triệu',  'min' => 5000000,   'max' => 10000000],
        '10-20tr'   => ['nhan' => '10 - 20 triệu', 'min' => 10000000,  'max' => 20000000],
        'tren-20tr' => ['nhan' => 'Trên 20 triệu', 'min' => 20000000,  'max' => null],
    ];

    $gia_dem_stmt = $pdo->query("SELECT
            SUM(CASE WHEN gia_ban < 2000000 THEN 1 ELSE 0 END) AS duoi_2tr,
            SUM(CASE WHEN gia_ban >= 2000000 AND gia_ban < 5000000 THEN 1 ELSE 0 END) AS tu_2_5tr,
            SUM(CASE WHEN gia_ban >= 5000000 AND gia_ban < 10000000 THEN 1 ELSE 0 END) AS tu_5_10tr,
            SUM(CASE WHEN gia_ban >= 10000000 AND gia_ban < 20000000 THEN 1 ELSE 0 END) AS tu_10_20tr,
            SUM(CASE WHEN gia_ban >= 20000000 THEN 1 ELSE 0 END) AS tren_20tr
        FROM san_pham WHERE trang_thai = 1");
    $gia_dem = $gia_dem_stmt->fetch(PDO::FETCH_ASSOC);
    $gia_khoang['duoi-2tr']['so_luong']  = (int) ($gia_dem['duoi_2tr'] ?? 0);
    $gia_khoang['2-5tr']['so_luong']     = (int) ($gia_dem['tu_2_5tr'] ?? 0);
    $gia_khoang['5-10tr']['so_luong']    = (int) ($gia_dem['tu_5_10tr'] ?? 0);
    $gia_khoang['10-20tr']['so_luong']   = (int) ($gia_dem['tu_10_20tr'] ?? 0);
    $gia_khoang['tren-20tr']['so_luong'] = (int) ($gia_dem['tren_20tr'] ?? 0);

    $gia_slug       = isset($_GET['gia']) ? trim($_GET['gia']) : '';
    $gia_min_custom = (isset($_GET['gia_min']) && $_GET['gia_min'] !== '') ? (int) $_GET['gia_min'] : null;
    $gia_max_custom = (isset($_GET['gia_max']) && $_GET['gia_max'] !== '') ? (int) $_GET['gia_max'] : null;

    // Có đang áp dụng bộ lọc nào không (dùng để tô sáng "Tất cả sản phẩm" / hiện nút Xóa bộ lọc)
    $dang_loc = ($ma_th_filter > 0 || $ma_dl_filter > 0 || $gia_slug !== '' || $gia_min_custom !== null || $gia_max_custom !== null);

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
                $fields = [
                    'sku' => 'SKU',
                    'loai_san_pham' => 'Loại sản phẩm',
                    'chuan_ket_noi' => 'Chuẩn kết nối',
                    'toc_do_doc' => 'Tốc độ đọc',
                    'toc_do_ghi' => 'Tốc độ ghi',
                    'kich_thuoc' => 'Kích thước',
                    'trong_luong' => 'Trọng lượng',
                    'bao_hanh' => 'Bảo hành'
                ];
            ?>

            <?php if (!empty($fields) && !empty($sp)): ?>
            <table class="table table-bordered product-desc">
                <?php foreach ($fields as $key => $label): ?>
                <?php if (!empty($sp[$key])): ?>
                <tr>
                    <th><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></th>
                    <td><?= htmlspecialchars($sp[$key], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
                <?php endif; ?>
                <?php endforeach; ?>
            </table>
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
            <div class="product-prepay">Hoặc trả trước <b><?php echo number_format($tra_truoc, 0, ',', '.'); ?>đ</b>
            </div>
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

    if ($ma_dl_filter > 0) {
        $sql .= " AND sp.ma_dung_luong = :ma_dl";
        $params[':ma_dl'] = $ma_dl_filter;
    }

    if ($gia_slug !== '' && isset($gia_khoang[$gia_slug])) {
        $sql .= " AND sp.gia_ban >= :gia_tu";
        $params[':gia_tu'] = $gia_khoang[$gia_slug]['min'];
        if ($gia_khoang[$gia_slug]['max'] !== null) {
            $sql .= " AND sp.gia_ban < :gia_den";
            $params[':gia_den'] = $gia_khoang[$gia_slug]['max'];
        }
    } elseif ($gia_min_custom !== null || $gia_max_custom !== null) {
        if ($gia_min_custom !== null) {
            $sql .= " AND sp.gia_ban >= :gia_tu_tuy_chon";
            $params[':gia_tu_tuy_chon'] = $gia_min_custom;
        }
        if ($gia_max_custom !== null) {
            $sql .= " AND sp.gia_ban <= :gia_den_tuy_chon";
            $params[':gia_den_tuy_chon'] = $gia_max_custom;
        }
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

            <div class="product-page-layout">

                <!-- ===== SIDEBAR: Danh mục sản phẩm + bộ lọc ===== -->
                <aside class="product-sidebar">

                    <div class="sidebar-block">
                        <h3 class="sidebar-title">Danh mục sản phẩm</h3>
                        <ul class="sidebar-category-list">
                            <li>
                                <a class="sidebar-category-link <?php echo !$dang_loc ? 'active' : ''; ?>"
                                    href="san-pham.php<?php echo $keyword !== '' ? '?q=' . urlencode($keyword) : ''; ?>">
                                    Tất cả sản phẩm
                                </a>
                            </li>
                            <?php foreach ($nhom_danh_muc as $ma_dm => $nhom): ?>
                            <li>
                                <a class="sidebar-category-link" href="#section-<?php echo $ma_dm; ?>">
                                    <?php echo htmlspecialchars($nhom['ten']); ?>
                                    <span class="sidebar-count">(<?php echo count($nhom['san_pham']); ?>)</span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <?php if (!empty($dung_luong_options)): ?>
                    <div class="sidebar-block">
                        <h3 class="sidebar-title">Dung lượng</h3>
                        <ul class="sidebar-filter-list" id="filter-dung-luong">
                            <li>
                                <a class="sidebar-filter-link <?php echo $ma_dl_filter === 0 ? 'active' : ''; ?>"
                                    href="san-pham.php<?php echo xay_qs(['dl' => '']); ?>">
                                    Tất cả
                                </a>
                            </li>

                            <?php 
                // CẤU HÌNH: Số lượng hiển thị ban đầu
                $limit = 5; 
                $count = 0;
                $total = count($dung_luong_options);
            ?>

                            <?php foreach ($dung_luong_options as $dl): ?>
                            <?php 
                $count++;
                // Thêm class 'hidden-item' cho các mục vượt quá giới hạn
                $hidden_class = ($count > $limit) ? 'hidden-item' : ''; 
                ?>
                            <li class="filter-item <?php echo $hidden_class; ?>">
                                <a class="sidebar-filter-link <?php echo $ma_dl_filter === (int) $dl['ma_dung_luong'] ? 'active' : ''; ?>"
                                    href="san-pham.php<?php echo xay_qs(['dl' => tao_slug($dl['ten_dung_luong'])]); ?>">
                                    <?php echo htmlspecialchars($dl['ten_dung_luong']); ?>
                                    <span class="sidebar-count">(<?php echo (int) $dl['so_luong']; ?>)</span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>

                        <!-- Nút Xem thêm (Chỉ hiện nếu tổng số mục > giới hạn) -->
                        <?php if ($total > $limit): ?>
                        <button class="btn-show-more" onclick="toggleShowMore(this)">
                            <span>Xem thêm</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <div class="sidebar-block">
                        <h3 class="sidebar-title">Chuẩn kết nối</h3>
                        <ul class="sidebar-filter-list">
                            <li><a class="sidebar-filter-link active" href="san-pham.php<?php echo xay_qs(); ?>">Tất
                                    cả</a></li>
                        </ul>
                        <p class="sidebar-note">* Bảng sản phẩm hiện chưa có cột lưu "chuẩn kết nối" nên chưa lọc
                            được theo mục này — cần bổ sung cột tương ứng (vd: chuan_ket_noi) trong bảng san_pham để
                            bật bộ lọc thật.</p>
                    </div>

                    <div class="sidebar-block">
                        <h3 class="sidebar-title">Mức giá</h3>
                        <ul class="sidebar-filter-list">
                            <li>
                                <a class="sidebar-filter-link <?php echo ($gia_slug === '' && $gia_min_custom === null && $gia_max_custom === null) ? 'active' : ''; ?>"
                                    href="san-pham.php<?php echo xay_qs(['gia' => '', 'gia_min' => '', 'gia_max' => '']); ?>">
                                    Tất cả
                                </a>
                            </li>
                            <?php foreach ($gia_khoang as $ma_khoang => $khoang): ?>
                            <li>
                                <a class="sidebar-filter-link <?php echo $gia_slug === $ma_khoang ? 'active' : ''; ?>"
                                    href="san-pham.php<?php echo xay_qs(['gia' => $ma_khoang, 'gia_min' => '', 'gia_max' => '']); ?>">
                                    <?php echo htmlspecialchars($khoang['nhan']); ?>
                                    <span class="sidebar-count">(<?php echo (int) $khoang['so_luong']; ?>)</span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <form class="sidebar-gia-tuy-chinh" method="get" action="san-pham.php">
                            <?php if ($keyword !== ''): ?>
                            <input type="hidden" name="q" value="<?php echo htmlspecialchars($keyword); ?>">
                            <?php endif; ?>
                            <?php if ($th_slug !== ''): ?>
                            <input type="hidden" name="th" value="<?php echo htmlspecialchars($th_slug); ?>">
                            <?php endif; ?>
                            <?php if ($dl_slug !== ''): ?>
                            <input type="hidden" name="dl" value="<?php echo htmlspecialchars($dl_slug); ?>">
                            <?php endif; ?>
                            <input type="number" name="gia_min" min="0" step="100000" placeholder="Từ"
                                value="<?php echo $gia_min_custom !== null ? (int) $gia_min_custom : ''; ?>">
                            <span>đến</span>
                            <input type="number" name="gia_max" min="0" step="100000" placeholder="Đến"
                                value="<?php echo $gia_max_custom !== null ? (int) $gia_max_custom : ''; ?>">
                            <button type="submit">Áp dụng</button>
                        </form>
                    </div>

                    <?php if ($dang_loc): ?>
                    <a class="sidebar-clear-filter"
                        href="san-pham.php<?php echo $keyword !== '' ? '?q=' . urlencode($keyword) : ''; ?>">
                        <i class="fa-solid fa-xmark"></i> Xóa bộ lọc
                    </a>
                    <?php endif; ?>

                    <div class="sidebar-block sidebar-support">
                        <h3 class="sidebar-title">Hỗ trợ khách hàng</h3>
                        <p><strong>Tư vấn bán hàng</strong><br><a href="tel:0283929377    0">(028) 3929 3770</a></p>
                        <p><strong>Kỹ thuật - Bảo hành</strong><br><a href="tel:02839260996">(028) 3926 0996</a></p>
                        <p><strong>Thời gian làm việc</strong><br>8:00 - 17:30 (T2 - T7)</p>
                    </div>




                </aside>
                <!-- ===== /SIDEBAR ===== -->
                <div class="product-main">

                    <?php if (!empty($thuong_hieu_options)): ?>
                    <div class="brand-filter-bar">
                        <span class="brand-filter-label"><i class="fa-solid fa-filter"></i> Thương hiệu</span>
                        <div class="brand-filter-list">
                            <a class="brand-filter-pill <?php echo $ma_th_filter === 0 ? 'active' : ''; ?>"
                                href="san-pham.php<?php echo xay_qs(['th' => '']); ?>">
                                Tất cả
                            </a>
                            <?php foreach ($thuong_hieu_options as $th): ?>
                            <a class="brand-filter-pill <?php echo $ma_th_filter === (int) $th['ma_thuong_hieu'] ? 'active' : ''; ?>"
                                href="san-pham.php<?php echo xay_qs(['th' => tao_slug($th['ten_thuong_hieu'])]); ?>">
                                <?php echo htmlspecialchars(trim($th['ten_thuong_hieu'])); ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>


                    <?php foreach ($nhom_danh_muc as $ma_dm => $nhom): ?>
                    <div class="product-section">
                        <!-- Tiêu đề danh mục -->
                        <div class="product-section-header">
                            <h2 class="product-section-title">Sản Phẩm Nổi Bật
                                <?php echo htmlspecialchars($nhom['ten']); ?>
                            </h2>
                            <div class="product-section-line"></div>
                        </div>
                        <?php if (!empty($nhom['dong_groups'])): ?>
                        <div class="category-strip" id="strip-<?php echo $ma_dm; ?>">
                            <?php foreach ($nhom['dong_groups'] as $ma_dl => $dong): ?>
                            <a class="category-circle"
                                href="mo-ta-linh-kien.php?dm=<?php echo tao_slug($nhom['ten']); ?>&dl=<?php echo tao_slug($dong['ten']) . $qs_giu_keyword; ?>">
                                <span class="category-circle-img">
                                    <img src="<?php echo !empty($dong['hinh_anh']) ? htmlspecialchars($dong['hinh_anh']) : 'assets/image/pc.webp'; ?>"
                                        loading="lazy" onerror="this.onerror=null;this.src='assets/image/pc.webp';">
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

                    <?php if (empty($nhom_danh_muc)): ?>
                    <p style="padding: 40px 0; text-align:center; color:#888;">
                        Không tìm thấy sản phẩm phù hợp với bộ lọc đã chọn.</p>
                    <?php endif; ?>
                </div>
            </div><!-- /.product-main -->
        </div><!-- /.product-page-layout -->
        </div>
        <div class="info-list">

            <div class="info-item">
                <i class="fa-solid fa-certificate"></i>
                <div class="info-content">
                    <div class="info-label">Sản phẩm chính hãng</div>
                    <div class="info-text">100% chính hãng</div>
                </div>
            </div>

            <div class="info-item">
                <i class="fa-solid fa-arrows-rotate"></i>
                <div class="info-content">
                    <div class="info-label">Đổi trả 30 ngày</div>
                    <div class="info-text">Nếu sản phẩm lỗi do nhà sản xuất</div>
                </div>
            </div>

            <div class="info-item">
                <i class="fa-solid fa-file-contract"></i>
                <div class="info-content">
                    <div class="info-label">Bảo hành chính hãng</div>
                    <div class="info-text">Từ 3 đến 5 năm toàn quốc</div>
                </div>
            </div>

            <div class="info-item">
                <i class="fa-solid fa-truck-fast"></i>
                <div class="info-content">
                    <div class="info-label">Giao hàng toàn quốc</div>
                    <div class="info-text">Kiểm tra trước khi thanh toán</div>
                </div>
            </div>

            <div class="info-item">
                <i class="fa-solid fa-headset"></i>
                <div class="info-content">
                    <div class="info-label">Hỗ trợ 24/7</div>
                    <div class="info-text">Tư vấn kỹ thuật tận tâm</div>
                </div>
            </div>

        </div>
    </section>

    <?php include 'footer.php'; ?>

</body>

</html>