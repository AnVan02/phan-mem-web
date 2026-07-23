<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_DON_HANG], '../dang-nhap.php');

    $tu_khoa = isset($_GET['q']) ? trim($_GET['q']) : '';

    $sql = "SELECT kh.*, COUNT(dh.ma_don_hang) AS so_don_hang
            FROM khach_hang_lien_he kh
            LEFT JOIN don_hang dh ON dh.ma_khach_hang = kh.ma_lien_he
            WHERE kh.mat_khau IS NOT NULL";
    $tham_so = [];
    if ($tu_khoa !== '') {
        $sql .= " AND (kh.customer_name LIKE :tu_khoa OR kh.customer_email LIKE :tu_khoa OR kh.customer_phone LIKE :tu_khoa)";
        $tham_so[':tu_khoa'] = '%' . $tu_khoa . '%';
    }
    $sql .= " GROUP BY kh.ma_lien_he ORDER BY kh.created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($tham_so);
    $danh_sach = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $thong_bao = [
        'da_xoa' => ['success', 'Đã xoá tài khoản khách hàng.'],
    ];
    $msg = isset($_GET['msg']) && isset($thong_bao[$_GET['msg']]) ? $thong_bao[$_GET['msg']] : null;

    $ADMIN_ROOT = '../';
    $active_page = 'khach-hang';
    $active_sub = 'danh-sach';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách khách hàng - Admin</title>
    <link rel="shortcut icon" href="../../assets/images/icon/logo VS_icon.jpg"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-layout.css">
    <link rel="stylesheet" href="../assets/css/article.css">
    <link rel="stylesheet" href="../assets/css/tai-khoan.css">
</head>

<body>
    <div class="admin-shell">
        <?php include '../includes/sidebar.php'; ?>

        <main class="admin-main">
            <div class="admin-main-header">
                <h1><i class="fa-solid fa-users"></i> Danh sách khách hàng</h1>
            </div>

            <?php if ($msg): ?>
                <div class="admin-flash <?php echo $msg[0]; ?>"><?php echo htmlspecialchars($msg[1]); ?></div>
            <?php endif; ?>

            <div class="admin-panel">
                <div class="admin-panel-header" style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                    <h2>Tài khoản khách hàng đã đăng ký (<?php echo count($danh_sach); ?>)</h2>
                    <form method="GET" style="display:flex; gap:8px;">
                        <input type="text" name="q" placeholder="Tìm theo tên, email, SĐT..."
                            value="<?php echo htmlspecialchars($tu_khoa); ?>"
                            style="padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; min-width:220px;">
                        <button type="submit" class="btn-admin btn-admin-secondary"><i class="fa-solid fa-magnifying-glass"></i> Tìm</button>
                    </form>
                </div>

                <?php if (count($danh_sach) === 0): ?>
                    <div class="admin-empty">Chưa có khách hàng nào đăng ký tài khoản.</div>
                <?php else: ?>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th>Địa chỉ</th>
                                <th>Ngày đăng ký</th>
                                <th>Số đơn hàng</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($danh_sach as $kh): ?>
                                <tr>
                                    <td class="title-cell">
                                        <div class="account-name-cell">
                                            <span class="account-avatar"><?php echo htmlspecialchars(mb_substr($kh['customer_name'], 0, 1)); ?></span>
                                            <span><?php echo htmlspecialchars($kh['customer_name']); ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($kh['customer_email']); ?></td>
                                    <td><?php echo htmlspecialchars($kh['customer_phone']); ?></td>
                                    <td><?php echo htmlspecialchars($kh['customer_address'] ?? ''); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($kh['created_at'])); ?></td>
                                    <td><?php echo (int) $kh['so_don_hang']; ?></td>
                                    <td>
                                        <div class="admin-actions">
                                            <a class="delete" href="xuly-khach-hang.php?action=xoa&id=<?php echo (int) $kh['ma_lien_he']; ?>"
                                                onclick="return confirm('Xoá tài khoản khách hàng này? Đơn hàng đã đặt vẫn được giữ lại.');">Xoá</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>

</html>
