<?php
    require_once '../config/config.php';

    $trang_thai_nhan = [
        0 => ['Chờ xử lý', 'off'],
        1 => ['Đã xác nhận', 'on'],
        2 => ['Đang giao', 'on'],
        3 => ['Hoàn thành', 'on'],
        4 => ['Đã huỷ', 'off'],
    ];

    $ma_don_hang = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    $stmt = $pdo->prepare("SELECT * FROM don_hang WHERE ma_don_hang = :id LIMIT 1");
    $stmt->execute([':id' => $ma_don_hang]);
    $don_hang = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$don_hang) {
        header('Location: don-hang.php');
        exit;
    }

    $ct_stmt = $pdo->prepare("SELECT * FROM don_hang_chi_tiet WHERE ma_don_hang = :id ORDER BY ma_chi_tiet ASC");
    $ct_stmt->execute([':id' => $ma_don_hang]);
    $chi_tiet_list = $ct_stmt->fetchAll(PDO::FETCH_ASSOC);

    $thong_bao = [
        'da_cap_nhat' => ['success', 'Đã cập nhật trạng thái đơn hàng.'],
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
    <title>Đơn hàng #<?php echo (int) $don_hang['ma_don_hang']; ?> - Admin</title>
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
                <h1><i class="fa-solid fa-receipt"></i> Đơn hàng #<?php echo (int) $don_hang['ma_don_hang']; ?></h1>
                <div class="admin-actions" style="align-items:center;">
                    <a href="don-hang.php" class="link-out">← Danh sách đơn hàng</a>
                    <a class="delete" href="xuly-don-hang.php?action=xoa&id=<?php echo (int) $don_hang['ma_don_hang']; ?>"
                        onclick="return confirm('Xoá đơn hàng này? Hành động không thể hoàn tác.');">Xoá đơn hàng</a>
                </div>
            </div>

            <?php if ($msg): ?>
                <div class="admin-flash <?php echo $msg[0]; ?>"><?php echo htmlspecialchars($msg[1]); ?></div>
            <?php endif; ?>

            <div class="admin-panel">
                <h2>Thông tin khách hàng</h2>
                <table class="admin-table">
                    <tbody>
                        <tr><th style="width:200px;">Họ và tên</th><td><?php echo htmlspecialchars($don_hang['ten_khach_hang']); ?></td></tr>
                        <tr><th>Số điện thoại</th><td><?php echo htmlspecialchars($don_hang['so_dien_thoai']); ?></td></tr>
                        <tr><th>Địa chỉ nhận hàng</th><td><?php echo htmlspecialchars($don_hang['dia_chi']); ?></td></tr>
                        <?php if (!empty($don_hang['ghi_chu'])): ?>
                            <tr><th>Ghi chú</th><td><?php echo htmlspecialchars($don_hang['ghi_chu']); ?></td></tr>
                        <?php endif; ?>
                        <tr><th>Ngày đặt</th><td><?php echo date('d/m/Y H:i', strtotime($don_hang['ngay_dat'])); ?></td></tr>
                    </tbody>
                </table>
            </div>

            <div class="admin-panel">
                <h2>Sản phẩm đã đặt</h2>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Tên sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Đơn giá</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($chi_tiet_list as $ct): ?>
                                <tr>
                                    <td class="title-cell"><?php echo htmlspecialchars($ct['ten_san_pham']); ?></td>
                                    <td><?php echo (int) $ct['so_luong']; ?></td>
                                    <td><?php echo number_format((int) $ct['don_gia'], 0, ',', '.'); ?>₫</td>
                                    <td><?php echo number_format((int) $ct['don_gia'] * (int) $ct['so_luong'], 0, ',', '.'); ?>₫</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" style="text-align:right;">Tổng tiền</th>
                                <th><?php echo number_format((int) $don_hang['tong_tien'], 0, ',', '.'); ?>₫</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="admin-panel">
                <h2>Trạng thái đơn hàng</h2>
                <form action="xuly-don-hang.php" method="POST" class="admin-filter-bar">
                    <input type="hidden" name="ma_don_hang" value="<?php echo (int) $don_hang['ma_don_hang']; ?>">
                    <select name="trang_thai">
                        <?php foreach ($trang_thai_nhan as $ma => $tt): ?>
                            <option value="<?php echo $ma; ?>" <?php echo (int) $don_hang['trang_thai'] === $ma ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($tt[0]); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn-admin btn-admin-primary"><i class="fa-solid fa-floppy-disk"></i> Cập nhật</button>
                </form>
            </div>
        </main>
    </div>
</body>

</html>
