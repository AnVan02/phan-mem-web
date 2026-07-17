<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_NOI_DUNG], '../dang-nhap.php');

    $ma_dm_loc       = isset($_GET['dm']) ? (int) $_GET['dm'] : 0;
    $ma_th_loc       = isset($_GET['th']) ? (int) $_GET['th'] : 0;
    $trang_thai_loc  = isset($_GET['trang_thai']) && $_GET['trang_thai'] !== '' ? (int) $_GET['trang_thai'] : -1;
    $tu_khoa_loc     = isset($_GET['q']) ? trim($_GET['q']) : '';

    $danh_muc_ds     = $pdo->query("SELECT ma_danh_muc, ten_danh_muc FROM danh_muc ORDER BY ten_danh_muc ASC")->fetchAll(PDO::FETCH_ASSOC);
    $thuong_hieu_ds  = $pdo->query("SELECT ma_thuong_hieu, ten_thuong_hieu FROM thuong_hieu ORDER BY ten_thuong_hieu ASC")->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu, dl.ten_dung_luong
        FROM san_pham sp
        LEFT JOIN danh_muc dm ON sp.ma_danh_muc = dm.ma_danh_muc
        LEFT JOIN thuong_hieu th ON sp.ma_thuong_hieu = th.ma_thuong_hieu
        LEFT JOIN dung_luong dl ON sp.ma_dung_luong = dl.ma_dung_luong
        WHERE 1=1";
    $tham_so = [];

    if ($ma_dm_loc > 0) {
        $sql .= " AND sp.ma_danh_muc = :ma_dm";
        $tham_so[':ma_dm'] = $ma_dm_loc;
    }
    if ($ma_th_loc > 0) {
        $sql .= " AND sp.ma_thuong_hieu = :ma_th";
        $tham_so[':ma_th'] = $ma_th_loc;
    }
    if ($trang_thai_loc !== -1) {
        $sql .= " AND sp.trang_thai = :trang_thai";
        $tham_so[':trang_thai'] = $trang_thai_loc;
    }
    if ($tu_khoa_loc !== '') {
        $sql .= " AND (sp.ten_san_pham LIKE :tu_khoa OR sp.ma_san_pham = :ma_sp)";
        $tham_so[':tu_khoa'] = '%' . $tu_khoa_loc . '%';
        $tham_so[':ma_sp']   = ctype_digit($tu_khoa_loc) ? (int) $tu_khoa_loc : 0;
    }

    $dem_stmt = $pdo->prepare(str_replace(
        'SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu, dl.ten_dung_luong',
        'SELECT COUNT(*)',
        $sql
    ));
    $dem_stmt->execute($tham_so);
    $tong_so_sp = (int) $dem_stmt->fetchColumn();

    $so_dong_moi_trang = 15;
    $tong_so_trang     = max(1, (int) ceil($tong_so_sp / $so_dong_moi_trang));
    $trang_hien_tai    = isset($_GET['trang']) ? (int) $_GET['trang'] : 1;
    if ($trang_hien_tai < 1) $trang_hien_tai = 1;
    if ($trang_hien_tai > $tong_so_trang) $trang_hien_tai = $tong_so_trang;
    $bat_dau = ($trang_hien_tai - 1) * $so_dong_moi_trang;

    $sql .= " ORDER BY sp.ma_san_pham DESC LIMIT :gioi_han OFFSET :bat_dau";

    $danh_sach_stmt = $pdo->prepare($sql);
    foreach ($tham_so as $key => $val) {
        $danh_sach_stmt->bindValue($key, $val);
    }
    $danh_sach_stmt->bindValue(':gioi_han', $so_dong_moi_trang, PDO::PARAM_INT);
    $danh_sach_stmt->bindValue(':bat_dau', $bat_dau, PDO::PARAM_INT);
    $danh_sach_stmt->execute();
    $danh_sach = $danh_sach_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Xây lại query string hiện tại (trừ "trang") để build link chuyển trang
    function xay_url_trang_sp($trang)
    {
        $params = $_GET;
        $params['trang'] = $trang;
        return 'danh-sach-san-pham.php?' . http_build_query($params);
    }

    $thong_bao = [
        'da_them'           => ['success', 'Đã thêm sản phẩm mới.'],
        'da_sua'            => ['success', 'Đã cập nhật sản phẩm.'],
        'da_xoa'            => ['success', 'Đã xoá sản phẩm.'],
        'loi_thieu_du_lieu' => ['error', 'Vui lòng nhập đầy đủ tên sản phẩm và danh mục.'],
        'loi_anh'           => ['error', 'Ảnh tải lên không hợp lệ (chỉ nhận jpg, jpeg, png, webp, gif).'],
    ];
    $msg = isset($_GET['msg']) && isset($thong_bao[$_GET['msg']]) ? $thong_bao[$_GET['msg']] : null;

    $ADMIN_ROOT = '../';
    $active_page = 'san-pham';
    $active_sub = 'danh-sach';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm - Admin</title>
    <link rel="shortcut icon" href="../../assets/images/icon/logo VS_icon.jpg"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-layout.css">
    <link rel="stylesheet" href="../assets/css/article.css">
</head>

<body>
    <div class="admin-shell">
        <?php include '../includes/sidebar.php'; ?>

        <main class="admin-main">
            <div class="admin-main-header">
                <h1><img width="35" height="35" src="https://img.icons8.com/isometric/50/product.png" alt="product"/>Danh sách sản phẩm</h1>
                <a href="them-san-pham.php" class="link-out"><i class="fa-solid fa-plus"></i> Thêm sản phẩm mới</a>
            </div>

            <?php if ($msg): ?>
                <div class="admin-flash <?php echo $msg[0]; ?>"><?php echo htmlspecialchars($msg[1]); ?></div>
            <?php endif; ?>

            <div class="admin-panel">
                <form action="" method="GET" class="admin-filter-bar">
                    <select name="dm">
                        <option value="0">Tất cả danh mục</option>
                        <?php foreach ($danh_muc_ds as $dm): ?>
                            <option value="<?php echo (int) $dm['ma_danh_muc']; ?>" <?php echo $ma_dm_loc === (int) $dm['ma_danh_muc'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($dm['ten_danh_muc']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="th">
                        <option value="0">Tất cả thương hiệu</option>
                        <?php foreach ($thuong_hieu_ds as $th): ?>
                            <option value="<?php echo (int) $th['ma_thuong_hieu']; ?>" <?php echo $ma_th_loc === (int) $th['ma_thuong_hieu'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars(trim($th['ten_thuong_hieu'])); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="trang_thai">
                        <option value="">Tất cả trạng thái</option>
                        <option value="1" <?php echo $trang_thai_loc === 1 ? 'selected' : ''; ?>>Đang hiển thị</option>
                        <option value="0" <?php echo $trang_thai_loc === 0 ? 'selected' : ''; ?>>Đã ẩn</option>
                    </select>

                    <input type="text" name="q" placeholder="Tìm theo tên hoặc mã sản phẩm..." value="<?php echo htmlspecialchars($tu_khoa_loc); ?>">

                    <button type="submit" class="btn-admin btn-admin-primary"><i class="fa-solid fa-filter"></i> Lọc</button>
                    <?php if ($ma_dm_loc > 0 || $ma_th_loc > 0 || $trang_thai_loc !== -1 || $tu_khoa_loc !== ''): ?>
                        <a href="danh-sach-san-pham.php" class="btn-admin btn-admin-secondary">Xoá lọc</a>
                    <?php endif; ?>
                </form>

                <h2>Tất cả sản phẩm (<?php echo $tong_so_sp; ?>)</h2>
                <?php if (count($danh_sach) === 0): ?>
                    <div class="admin-empty">Không có sản phẩm nào phù hợp với bộ lọc.</div>
                <?php else: ?>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Danh mục</th>
                                    <th>Thương hiệu</th>
                                    <th>Giá bán</th>
                                    <th>Tồn kho</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($danh_sach as $sp):
                                    $anh_list = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', $sp['hinh_anh']))));
                                    $anh = !empty($anh_list) ? $anh_list[0] : 'assets/image/pc.webp';
                                    $anh_hien = (strpos($anh, 'http') === 0 || strpos($anh, '../') === 0) ? $anh : '../../' . $anh;
                                    $gia_ban = (int) $sp['gia_ban'];
                                    $giam_gia = (int) $sp['giam_gia'];
                                    $gia_sau_giam = $giam_gia > 0 ? (int) round($gia_ban * (100 - $giam_gia) / 100) : $gia_ban;
                                ?>
                                    <tr>
                                        <td><img class="thumb" src="<?php echo htmlspecialchars($anh_hien); ?>" alt=""></td>
                                        <td class="title-cell"><?php echo htmlspecialchars($sp['ten_san_pham']); ?></td>
                                        <td><?php echo htmlspecialchars($sp['ten_danh_muc'] ?? '—'); ?></td>
                                        <td><?php echo htmlspecialchars($sp['ten_thuong_hieu'] ?? '—'); ?></td>
                                        <td>
                                            <?php echo $gia_ban > 0 ? number_format($gia_sau_giam, 0, ',', '.') . '₫' : 'Liên hệ'; ?>
                                            <?php if ($giam_gia > 0): ?><br><small>-<?php echo $giam_gia; ?>%</small><?php endif; ?>
                                        </td>
                                        <td><?php echo (int) $sp['so_luong']; ?></td>
                                        <td>
                                            <?php if ((int) $sp['trang_thai'] === 1): ?>
                                                <span class="admin-badge on">Đang hiển thị</span>
                                            <?php else: ?>
                                                <span class="admin-badge off">Đã ẩn</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="admin-actions">
                                                <a class="edit" href="sua-san-pham.php?id=<?php echo (int) $sp['ma_san_pham']; ?>&tro_ve=<?php echo urlencode($_SERVER['QUERY_STRING']); ?>">Sửa</a>
                                                <a class="delete" href="xoa-san-pham.php?id=<?php echo (int) $sp['ma_san_pham']; ?>&tro_ve=<?php echo urlencode($_SERVER['QUERY_STRING']); ?>" onclick="return confirm('Xoá sản phẩm này?');">Xoá</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ($tong_so_trang > 1): ?>
                        <div class="admin-pagination">
                            <a class="page-nav <?php echo $trang_hien_tai <= 1 ? 'disabled' : ''; ?>"
                               href="<?php echo xay_url_trang_sp(max(1, $trang_hien_tai - 1)); ?>">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>

                            <?php for ($i = 1; $i <= $tong_so_trang; $i++): ?>
                                <a class="page-num <?php echo $i === $trang_hien_tai ? 'active' : ''; ?>"
                                   href="<?php echo xay_url_trang_sp($i); ?>"><?php echo $i; ?></a>
                            <?php endfor; ?>

                            <a class="page-nav <?php echo $trang_hien_tai >= $tong_so_trang ? 'disabled' : ''; ?>"
                               href="<?php echo xay_url_trang_sp(min($tong_so_trang, $trang_hien_tai + 1)); ?>">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>

</html>
