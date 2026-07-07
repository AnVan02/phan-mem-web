<?php
    require_once '../config/config.php';

    $trang_thai_nhan = [
        0 => ['Chờ xử lý', 'off'],
        1 => ['Đã xác nhận', 'on'],
        2 => ['Đang giao', 'on'],
        3 => ['Hoàn thành', 'on'],
        4 => ['Đã huỷ', 'off'],
    ];

    $trang_thai_loc = isset($_GET['trang_thai']) && $_GET['trang_thai'] !== '' ? (int) $_GET['trang_thai'] : -1;
    $tu_khoa_loc    = isset($_GET['q']) ? trim($_GET['q']) : '';

    $sql = "SELECT * FROM don_hang WHERE 1=1";
    $tham_so = [];

    if ($trang_thai_loc !== -1) {
        $sql .= " AND trang_thai = :trang_thai";
        $tham_so[':trang_thai'] = $trang_thai_loc;
    }
    if ($tu_khoa_loc !== '') {
        $sql .= " AND (ten_khach_hang LIKE :tu_khoa OR so_dien_thoai LIKE :tu_khoa OR ma_don_hang = :ma_don)";
        $tham_so[':tu_khoa'] = '%' . $tu_khoa_loc . '%';
        $tham_so[':ma_don']  = ctype_digit($tu_khoa_loc) ? (int) $tu_khoa_loc : 0;
    }

    $dem_stmt = $pdo->prepare(str_replace('SELECT *', 'SELECT COUNT(*)', $sql));
    $dem_stmt->execute($tham_so);
    $tong_so_don = (int) $dem_stmt->fetchColumn();

    $so_dong_moi_trang = 15;
    $tong_so_trang     = max(1, (int) ceil($tong_so_don / $so_dong_moi_trang));
    $trang_hien_tai    = isset($_GET['trang']) ? (int) $_GET['trang'] : 1;
    if ($trang_hien_tai < 1) $trang_hien_tai = 1;
    if ($trang_hien_tai > $tong_so_trang) $trang_hien_tai = $tong_so_trang;
    $bat_dau = ($trang_hien_tai - 1) * $so_dong_moi_trang;

    $sql .= " ORDER BY ma_don_hang DESC LIMIT :gioi_han OFFSET :bat_dau";

    $danh_sach_stmt = $pdo->prepare($sql);
    foreach ($tham_so as $key => $val) {
        $danh_sach_stmt->bindValue($key, $val);
    }
    $danh_sach_stmt->bindValue(':gioi_han', $so_dong_moi_trang, PDO::PARAM_INT);
    $danh_sach_stmt->bindValue(':bat_dau', $bat_dau, PDO::PARAM_INT);
    $danh_sach_stmt->execute();
    $danh_sach = $danh_sach_stmt->fetchAll(PDO::FETCH_ASSOC);

    function xay_url_trang_don($trang)
    {
        $params = $_GET;
        $params['trang'] = $trang;
        return 'don-hang.php?' . http_build_query($params);
    }

    $thong_bao = [
        'da_cap_nhat' => ['success', 'Đã cập nhật trạng thái đơn hàng.'],
        'da_xoa'      => ['success', 'Đã xoá đơn hàng.'],
    ];
    $msg = isset($_GET['msg']) && isset($thong_bao[$_GET['msg']]) ? $thong_bao[$_GET['msg']] : null;

    $ADMIN_ROOT = '../';
    $active_page = 'don-hang';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng - Admin</title>
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
                <h1><i class="fa-solid fa-receipt"></i> Đơn hàng</h1>
            </div>

            <?php if ($msg): ?>
                <div class="admin-flash <?php echo $msg[0]; ?>"><?php echo htmlspecialchars($msg[1]); ?></div>
            <?php endif; ?>

            <div class="admin-panel">
                <form action="" method="GET" class="admin-filter-bar">
                    <select name="trang_thai">
                        <option value="">Tất cả trạng thái</option>
                        <?php foreach ($trang_thai_nhan as $ma => $tt): ?>
                            <option value="<?php echo $ma; ?>" <?php echo $trang_thai_loc === $ma ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($tt[0]); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <input type="text" name="q" placeholder="Tìm theo tên, SĐT hoặc mã đơn..." value="<?php echo htmlspecialchars($tu_khoa_loc); ?>">

                    <button type="submit" class="btn-admin btn-admin-primary"><i class="fa-solid fa-filter"></i> Lọc</button>
                    <?php if ($trang_thai_loc !== -1 || $tu_khoa_loc !== ''): ?>
                        <a href="don-hang.php" class="btn-admin btn-admin-secondary">Xoá lọc</a>
                    <?php endif; ?>
                </form>

                <h2>Tất cả đơn hàng (<?php echo $tong_so_don; ?>)</h2>
                <?php if (count($danh_sach) === 0): ?>
                    <div class="admin-empty">Chưa có đơn hàng nào phù hợp.</div>
                <?php else: ?>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Khách hàng</th>
                                    <th>Số điện thoại</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày đặt</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($danh_sach as $dh): ?>
                                    <tr>
                                        <td>#<?php echo (int) $dh['ma_don_hang']; ?></td>
                                        <td class="title-cell"><?php echo htmlspecialchars($dh['ten_khach_hang']); ?></td>
                                        <td><?php echo htmlspecialchars($dh['so_dien_thoai']); ?></td>
                                        <td><?php echo number_format((int) $dh['tong_tien'], 0, ',', '.'); ?>₫</td>
                                        <td>
                                            <span class="admin-badge <?php echo $trang_thai_nhan[(int) $dh['trang_thai']][1]; ?>">
                                                <?php echo htmlspecialchars($trang_thai_nhan[(int) $dh['trang_thai']][0]); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($dh['ngay_dat'])); ?></td>
                                        <td>
                                            <div class="admin-actions">
                                                <a class="edit" href="chi-tiet-don-hang.php?id=<?php echo (int) $dh['ma_don_hang']; ?>">Xem</a>
                                                <a class="delete" href="xuly-don-hang.php?action=xoa&id=<?php echo (int) $dh['ma_don_hang']; ?>"
                                                    onclick="return confirm('Xoá đơn hàng này? Hành động không thể hoàn tác.');">Xoá</a>
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
                               href="<?php echo xay_url_trang_don(max(1, $trang_hien_tai - 1)); ?>">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>

                            <?php for ($i = 1; $i <= $tong_so_trang; $i++): ?>
                                <a class="page-num <?php echo $i === $trang_hien_tai ? 'active' : ''; ?>"
                                   href="<?php echo xay_url_trang_don($i); ?>"><?php echo $i; ?></a>
                            <?php endfor; ?>

                            <a class="page-nav <?php echo $trang_hien_tai >= $tong_so_trang ? 'disabled' : ''; ?>"
                               href="<?php echo xay_url_trang_don(min($tong_so_trang, $trang_hien_tai + 1)); ?>">
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
