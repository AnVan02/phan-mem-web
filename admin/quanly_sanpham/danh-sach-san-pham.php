<?php
    require_once '../config/config.php';

    $danh_sach_stmt = $pdo->query("SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu, dl.ten_dung_luong
        FROM san_pham sp
        LEFT JOIN danh_muc dm ON sp.ma_danh_muc = dm.ma_danh_muc
        LEFT JOIN thuong_hieu th ON sp.ma_thuong_hieu = th.ma_thuong_hieu
        LEFT JOIN dung_luong dl ON sp.ma_dung_luong = dl.ma_dung_luong
        ORDER BY sp.ma_san_pham DESC");
    $danh_sach = $danh_sach_stmt->fetchAll(PDO::FETCH_ASSOC);

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
                <h1>Danh sách sản phẩm</h1>
                <a href="them-san-pham.php" class="link-out"><i class="fa-solid fa-plus"></i> Thêm sản phẩm mới</a>
            </div>

            <?php if ($msg): ?>
                <div class="admin-flash <?php echo $msg[0]; ?>"><?php echo htmlspecialchars($msg[1]); ?></div>
            <?php endif; ?>

            <div class="admin-panel">
                <h2>Tất cả sản phẩm (<?php echo count($danh_sach); ?>)</h2>
                <?php if (count($danh_sach) === 0): ?>
                    <div class="admin-empty">Chưa có sản phẩm nào.</div>
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
                                                <a class="edit" href="sua-san-pham.php?id=<?php echo (int) $sp['ma_san_pham']; ?>">Sửa</a>
                                                <a class="delete" href="xoa-san-pham.php?id=<?php echo (int) $sp['ma_san_pham']; ?>" onclick="return confirm('Xoá sản phẩm này?');">Xoá</a>
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
