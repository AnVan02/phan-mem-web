<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI], '../dang-nhap.php');

    $DS_HANH_DONG = [
        'them'                => ['Thêm mới', 'on'],
        'sua'                 => ['Sửa', 'on'],
        'xoa'                 => ['Xoá', 'off'],
        'dang_nhap'           => ['Đăng nhập', 'on'],
        'dang_nhap_that_bai'  => ['Đăng nhập thất bại', 'off'],
        'dang_xuat'           => ['Đăng xuất', 'off'],
    ];

    $DS_DOI_TUONG = [
        'tai_khoan'   => 'Tài khoản quản trị',
        'san_pham'    => 'Sản phẩm',
        'bai_viet'    => 'Bài viết',
        'chinh_sach'  => 'Trang chính sách',
        'banner'      => 'Banner / thương hiệu',
        'don_hang'    => 'Đơn hàng',
        'danh_muc'    => 'Danh mục',
        'thuong_hieu' => 'Thương hiệu (thuộc tính)',
        'dung_luong'  => 'Dung lượng',
        'bao_hanh'    => 'Bảo hành',
    ];

    // ==== Bộ lọc ====
    $account_id_loc = isset($_GET['account_id']) && $_GET['account_id'] !== '' ? (int) $_GET['account_id'] : 0;
    $hanh_dong_loc  = isset($_GET['hanh_dong']) ? trim($_GET['hanh_dong']) : '';
    $doi_tuong_loc  = isset($_GET['doi_tuong']) ? trim($_GET['doi_tuong']) : '';
    $tu_ngay_loc    = isset($_GET['tu_ngay']) ? trim($_GET['tu_ngay']) : '';
    $den_ngay_loc   = isset($_GET['den_ngay']) ? trim($_GET['den_ngay']) : '';
    $tu_khoa_loc    = isset($_GET['q']) ? trim($_GET['q']) : '';

    $sql = "SELECT * FROM nhat_ky_hoat_dong WHERE 1=1";
    $tham_so = [];

    if ($account_id_loc > 0) {
        $sql .= " AND account_id = :account_id";
        $tham_so[':account_id'] = $account_id_loc;
    }
    
    if ($hanh_dong_loc !== '' && isset($DS_HANH_DONG[$hanh_dong_loc])) {
        $sql .= " AND hanh_dong = :hanh_dong";
        $tham_so[':hanh_dong'] = $hanh_dong_loc;
    }
    if ($doi_tuong_loc !== '' && isset($DS_DOI_TUONG[$doi_tuong_loc])) {
        $sql .= " AND doi_tuong = :doi_tuong";
        $tham_so[':doi_tuong'] = $doi_tuong_loc;
    }

    if ($tu_ngay_loc !== '') {
        $sql .= " AND thoi_gian >= :tu_ngay";
        $tham_so[':tu_ngay'] = $tu_ngay_loc . ' 00:00:00';
    }
    if ($den_ngay_loc !== '') {
        $sql .= " AND thoi_gian <= :den_ngay";
        $tham_so[':den_ngay'] = $den_ngay_loc . ' 23:59:59';
    }
    if ($tu_khoa_loc !== '') {
        $sql .= " AND (mo_ta LIKE :tu_khoa OR account_name LIKE :tu_khoa)";
        $tham_so[':tu_khoa'] = '%' . $tu_khoa_loc . '%';
    }

    $dem_stmt = $pdo->prepare(str_replace('SELECT *', 'SELECT COUNT(*)', $sql));
    $dem_stmt->execute($tham_so);
    $tong_so_dong = (int) $dem_stmt->fetchColumn();

    $so_dong_moi_trang = 25;
    $tong_so_trang      = max(1, (int) ceil($tong_so_dong / $so_dong_moi_trang));
    $trang_hien_tai     = isset($_GET['trang']) ? (int) $_GET['trang'] : 1;
    if ($trang_hien_tai < 1) $trang_hien_tai = 1;
    if ($trang_hien_tai > $tong_so_trang) $trang_hien_tai = $tong_so_trang;
    $bat_dau = ($trang_hien_tai - 1) * $so_dong_moi_trang;

    $sql .= " ORDER BY id DESC LIMIT :gioi_han OFFSET :bat_dau";

    $danh_sach_stmt = $pdo->prepare($sql);
    foreach ($tham_so as $key => $val) {
        $danh_sach_stmt->bindValue($key, $val);
    }
    $danh_sach_stmt->bindValue(':gioi_han', $so_dong_moi_trang, PDO::PARAM_INT);
    $danh_sach_stmt->bindValue(':bat_dau', $bat_dau, PDO::PARAM_INT);
    $danh_sach_stmt->execute();
    $danh_sach = $danh_sach_stmt->fetchAll(PDO::FETCH_ASSOC);

    $danh_sach_tai_khoan = $pdo->query("SELECT account_id, account_name FROM account ORDER BY account_name ASC")->fetchAll(PDO::FETCH_ASSOC);

    function xay_url_trang_nhat_ky($trang)
    {
        $params = $_GET;
        $params['trang'] = $trang;
        return 'nhat-ky.php?' . http_build_query($params);
    }

    $co_loc = $account_id_loc > 0 || $hanh_dong_loc !== '' || $doi_tuong_loc !== '' || $tu_ngay_loc !== '' || $den_ngay_loc !== '' || $tu_khoa_loc !== '';

    $ADMIN_ROOT = '../';
    $active_page = 'nhat-ky';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhật ký hoạt động - Admin</title>
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
                <h1><i class="fa-solid fa-clock-rotate-left"></i> Nhật ký hoạt động</h1>
            </div>

            <div class="admin-panel">
                <form action="" method="GET" class="admin-filter-bar">
                    <select name="account_id">
                        <option value="">Tất cả tài khoản</option>
                        <?php foreach ($danh_sach_tai_khoan as $tk): ?>
                            <option value="<?php echo (int) $tk['account_id']; ?>" <?php echo $account_id_loc === (int) $tk['account_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($tk['account_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="hanh_dong">
                        <option value="">Tất cả hành động</option>
                        <?php foreach ($DS_HANH_DONG as $ma => $nhan): ?>
                            <option value="<?php echo $ma; ?>" <?php echo $hanh_dong_loc === $ma ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($nhan[0]); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="doi_tuong">
                        <option value="">Tất cả đối tượng</option>
                        <?php foreach ($DS_DOI_TUONG as $ma => $nhan): ?>
                            <option value="<?php echo $ma; ?>" <?php echo $doi_tuong_loc === $ma ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($nhan); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <input type="date" name="tu_ngay" value="<?php echo htmlspecialchars($tu_ngay_loc); ?>" title="Từ ngày">
                    <input type="date" name="den_ngay" value="<?php echo htmlspecialchars($den_ngay_loc); ?>" title="Đến ngày">
                    <input type="text" name="q" placeholder="Tìm theo mô tả, tên tài khoản..." value="<?php echo htmlspecialchars($tu_khoa_loc); ?>">

                    <button type="submit" class="btn-admin btn-admin-primary"><i class="fa-solid fa-filter"></i> Lọc</button>
                    <?php if ($co_loc): ?>
                        <a href="nhat-ky.php" class="btn-admin btn-admin-secondary">Xoá lọc</a>
                    <?php endif; ?>
                </form>

                <h2>Tất cả hoạt động (<?php echo $tong_so_dong; ?>)</h2>
                <?php if (count($danh_sach) === 0): ?>
                    <div class="admin-empty">Chưa có hoạt động nào phù hợp.</div>
                <?php else: ?>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Tài khoản</th>
                                    <th>Hành động</th>
                                    <th>Đối tượng</th>
                                    <th>Mô tả</th>
                                    <th>IP</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($danh_sach as $nk):
                                    $nhan_hanh_dong = $DS_HANH_DONG[$nk['hanh_dong']] ?? [$nk['hanh_dong'], 'off'];
                                ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y H:i:s', strtotime($nk['thoi_gian'])); ?></td>
                                        <td class="title-cell"><?php echo htmlspecialchars($nk['account_name']); ?></td>
                                        <td><span class="admin-badge <?php echo $nhan_hanh_dong[1]; ?>"><?php echo htmlspecialchars($nhan_hanh_dong[0]); ?></span></td>
                                        <td><?php echo htmlspecialchars($DS_DOI_TUONG[$nk['doi_tuong']] ?? $nk['doi_tuong']); ?><?php echo $nk['doi_tuong_id'] ? ' #' . (int) $nk['doi_tuong_id'] : ''; ?></td>
                                        <td><?php echo htmlspecialchars($nk['mo_ta'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($nk['dia_chi_ip'] ?? ''); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ($tong_so_trang > 1): ?>
                        <div class="admin-pagination">
                            <a class="page-nav <?php echo $trang_hien_tai <= 1 ? 'disabled' : ''; ?>"
                               href="<?php echo xay_url_trang_nhat_ky(max(1, $trang_hien_tai - 1)); ?>">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>

                            <?php for ($i = 1; $i <= $tong_so_trang; $i++): ?>
                                <a class="page-num <?php echo $i === $trang_hien_tai ? 'active' : ''; ?>"
                                   href="<?php echo xay_url_trang_nhat_ky($i); ?>"><?php echo $i; ?></a>
                            <?php endfor; ?>

                            <a class="page-nav <?php echo $trang_hien_tai >= $tong_so_trang ? 'disabled' : ''; ?>"
                               href="<?php echo xay_url_trang_nhat_ky(min($tong_so_trang, $trang_hien_tai + 1)); ?>">
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
