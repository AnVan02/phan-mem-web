<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_NOI_DUNG], '../dang-nhap.php');

    $danh_sach_stmt = $pdo->query("SELECT * FROM policy_page ORDER BY policy_updated DESC, policy_id DESC");
    $danh_sach = $danh_sach_stmt->fetchAll(PDO::FETCH_ASSOC);

    $slug_co_san = ['bao-hanh', 've-chung-toi'];
    function duong_dan_xem_chinh_sach($slug) {
        if ($slug === 'bao-hanh') return '../../chinh-sach-bao-hanh.php';
        if ($slug === 've-chung-toi') return '../../ve-chung-toi.php';
        return '../../chinh-sach.php?slug=' . urlencode($slug);
    }

    $thong_bao = [
        'da_them'           => ['success', 'Đã thêm trang chính sách mới.'],
        'da_sua'            => ['success', 'Đã cập nhật trang chính sách.'],
        'da_xoa'            => ['success', 'Đã xoá trang chính sách.'],
        'loi_thieu_du_lieu' => ['error', 'Vui lòng nhập đầy đủ đường dẫn (slug) và tiêu đề.'],
        'loi_trung_slug'    => ['error', 'Đường dẫn (slug) này đã được sử dụng, vui lòng chọn đường dẫn khác.'],
        'loi_anh'           => ['error', 'Ảnh tải lên không hợp lệ (chỉ nhận jpg, jpeg, png, webp, gif).'],
    ];
    $msg = isset($_GET['msg']) && isset($thong_bao[$_GET['msg']]) ? $thong_bao[$_GET['msg']] : null;

    $ADMIN_ROOT = '../';
    $active_page = 'chinh-sach';
    $active_sub = 'danh-sach';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Chính sách - Admin</title>
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
                <h1><img width="35" height="35" src="https://img.icons8.com/fluency/48/rules.png" alt="policy"/>Danh sách Chính sách</h1>
                <a href="them.php" class="link-out"><i class="fa-solid fa-plus"></i> Thêm trang chính sách mới</a>
            </div>

            <?php if ($msg): ?>
                <div class="admin-flash <?php echo $msg[0]; ?>"><?php echo htmlspecialchars($msg[1]); ?></div>
            <?php endif; ?>

            <div class="admin-panel">
                <h2>Tất cả trang chính sách (<?php echo count($danh_sach); ?>)</h2>
                <?php if (count($danh_sach) === 0): ?>
                    <div class="admin-empty">Chưa có trang chính sách nào.</div>
                <?php else: ?>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Tiêu đề</th>
                                    <th>Đường dẫn (slug)</th>
                                    <th>Cập nhật lần cuối</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($danh_sach as $p): ?>
                                    <tr>
                                        <td class="title-cell"><?php echo htmlspecialchars($p['policy_title']); ?></td>
                                        <td><code><?php echo htmlspecialchars($p['policy_slug']); ?></code></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($p['policy_updated'])); ?></td>
                                        <td>
                                            <?php if ((int) $p['policy_status'] === 1): ?>
                                                <span class="admin-badge on">Đang hiển thị</span>
                                            <?php else: ?>
                                                <span class="admin-badge off">Đã ẩn</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="admin-actions">
                                                <a class="edit" href="sua.php?id=<?php echo (int) $p['policy_id']; ?>">Sửa</a>
                                                <a href="<?php echo htmlspecialchars(duong_dan_xem_chinh_sach($p['policy_slug'])); ?>" target="_blank">Xem</a>
                                                <?php if (!in_array($p['policy_slug'], $slug_co_san, true)): ?>
                                                    <a class="delete" href="xuly.php?action=xoa&id=<?php echo (int) $p['policy_id']; ?>" onclick="return confirm('Xoá trang chính sách này?');">Xoá</a>
                                                <?php endif; ?>
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
