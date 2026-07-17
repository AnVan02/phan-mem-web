<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_NOI_DUNG], '../dang-nhap.php');

    $danh_sach_stmt = $pdo->query("SELECT * FROM article ORDER BY article_date DESC, article_id DESC");
    $danh_sach = $danh_sach_stmt->fetchAll(PDO::FETCH_ASSOC);

    $thong_bao = [
        'da_them'           => ['success', 'Đã thêm bài viết mới.'],
        'da_sua'            => ['success', 'Đã cập nhật bài viết.'],
        'da_xoa'            => ['success', 'Đã xoá bài viết.'],
        'loi_thieu_du_lieu' => ['error', 'Vui lòng nhập đầy đủ tiêu đề và tác giả.'],
        'loi_anh'           => ['error', 'Ảnh tải lên không hợp lệ (chỉ nhận jpg, jpeg, png, webp, gif).'],
    ];
    $msg = isset($_GET['msg']) && isset($thong_bao[$_GET['msg']]) ? $thong_bao[$_GET['msg']] : null;

    $ADMIN_ROOT = '../';
    $active_page = 'tin-tuc';
    $active_sub = 'danh-sach';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Tin tức - Admin</title>
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
                <h1><img width="35" height="35" src="https://img.icons8.com/fluency/48/news.png" alt="news"/>Danh sách Tin tức</h1>
                <a href="them.php" class="link-out"><i class="fa-solid fa-plus"></i> Thêm bài viết mới</a>
            </div>

            <?php if ($msg): ?>
                <div class="admin-flash <?php echo $msg[0]; ?>"><?php echo htmlspecialchars($msg[1]); ?></div>
            <?php endif; ?>

            <div class="admin-panel">
                <h2>Tất cả bài viết (<?php echo count($danh_sach); ?>)</h2>
                <?php if (count($danh_sach) === 0): ?>
                    <div class="admin-empty">Chưa có bài viết nào.</div>
                <?php else: ?>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Ảnh</th>
                                    <th>Tiêu đề</th>
                                    <th>Chuyên mục</th>
                                    <th>Tác giả</th>
                                    <th>Ngày đăng</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($danh_sach as $a):
                                    $anh = trim($a['article_image']) !== '' ? $a['article_image'] : '../../assets/image/pc.webp';
                                    $anh_hien = (strpos($anh, 'http') === 0 || strpos($anh, '../') === 0) ? $anh : '../../' . $anh;
                                ?>
                                    <tr>
                                        <td><img class="thumb" src="<?php echo htmlspecialchars($anh_hien); ?>" alt=""></td>
                                        <td class="title-cell"><?php echo htmlspecialchars($a['article_title']); ?></td>
                                        <td><?php echo htmlspecialchars(trim($a['article_linh'])); ?></td>
                                        <td><?php echo htmlspecialchars($a['article_author']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($a['article_date'])); ?></td>
                                        <td>
                                            <?php if ((int) $a['article_status'] === 1): ?>
                                                <span class="admin-badge on">Đang hiển thị</span>
                                            <?php else: ?>
                                                <span class="admin-badge off">Đã ẩn</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="admin-actions">
                                                <a class="edit" href="sua.php?id=<?php echo (int) $a['article_id']; ?>">Sửa</a>
                                                <a class="delete" href="xuly.php?action=xoa&id=<?php echo (int) $a['article_id']; ?>" onclick="return confirm('Xoá bài viết này?');">Xoá</a>
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
