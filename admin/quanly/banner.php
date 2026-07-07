<?php
    require_once '../config/config.php';

    $thuong_hieu_list = $pdo->query("SELECT * FROM thuong_hieu ORDER BY ten_thuong_hieu ASC")->fetchAll(PDO::FETCH_ASSOC);

    // Nếu có ?sua=id thì nạp dữ liệu để sửa, ngược lại là form thêm mới
    $sua_id = isset($_GET['sua']) ? (int) $_GET['sua'] : 0;
    $dang_sua = null;
    if ($sua_id > 0) {
        foreach ($thuong_hieu_list as $th) {
            if ((int) $th['ma_thuong_hieu'] === $sua_id) {
                $dang_sua = $th;
                break;
            }
        }
    }

    $thong_bao = [
        'da_them'          => ['success', 'Đã thêm thương hiệu mới.'],
        'da_sua'           => ['success', 'Đã cập nhật banner.'],
        'da_xoa'           => ['success', 'Đã xoá thương hiệu.'],
        'da_xoa_banner'    => ['success', 'Đã xoá banner (vẫn giữ thương hiệu).'],
        'loi_thieu_ten'    => ['error', 'Vui lòng nhập tên thương hiệu.'],
        'loi_dang_su_dung' => ['error', 'Không thể xoá vì đang có sản phẩm dùng thương hiệu này.'],
        'loi_anh'          => ['error', 'Ảnh banner không hợp lệ. Chỉ chấp nhận jpg, jpeg, png, webp.'],
    ];
    $msg = isset($_GET['msg']) && isset($thong_bao[$_GET['msg']]) ? $thong_bao[$_GET['msg']] : null;

    $ADMIN_ROOT = '../';
    $active_page = 'banner';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banner thương hiệu - Admin</title>
    <link rel="shortcut icon" href="../../assets/images/icon/logo VS_icon.jpg"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-layout.css">
    <link rel="stylesheet" href="../assets/css/article.css">
    <link rel="stylesheet" href="../assets/css/post-editor.css">
    <link rel="stylesheet" href="../assets/css/banner-thuong-hieu.css">
</head>

<body>
    <div class="admin-shell">
        <?php include '../includes/sidebar.php'; ?>

        <main class="admin-main">
            <div class="admin-main-header">
                <h1>Banner thương hiệu</h1>
                <a href="../quanly_sanpham/danh-sach-san-pham.php" class="link-out">← Danh sách sản phẩm</a>
            </div>

            <?php if ($msg): ?>
                <div class="admin-flash <?php echo $msg[0]; ?>"><?php echo htmlspecialchars($msg[1]); ?></div>
            <?php endif; ?>

            <div class="post-box" id="form-banner">
                <h3><?php echo $dang_sua ? 'Sửa thương hiệu / banner' : 'Thêm thương hiệu mới'; ?></h3>

                <form action="xuly-banner.php" method="POST" enctype="multipart/form-data" class="banner-form">
                    <input type="hidden" name="action" value="<?php echo $dang_sua ? 'sua' : 'them'; ?>">
                    <?php if ($dang_sua): ?>
                        <input type="hidden" name="id" value="<?php echo (int) $dang_sua['ma_thuong_hieu']; ?>">
                    <?php endif; ?>

                    <label class="field-label">Tên thương hiệu</label>
                    <input type="text" name="ten_thuong_hieu" placeholder="Vd: AGI, Kingston, AMD..."
                        value="<?php echo $dang_sua ? htmlspecialchars(trim($dang_sua['ten_thuong_hieu'])) : ''; ?>" required>

                    <div class="field-row">
                        <div>
                            <label class="field-label">Ảnh banner (tải lên)</label>
                            <input type="file" name="banner_file" accept="image/png,image/jpeg,image/webp">
                        </div>
                        <div>
                            <label class="field-label">Hoặc dán URL ảnh banner</label>
                            <input type="text" name="banner_url" placeholder="https://..."
                                value="<?php echo $dang_sua && !empty($dang_sua['banner']) ? htmlspecialchars($dang_sua['banner']) : ''; ?>">
                        </div>
                    </div>
                    <span class="hint">Nếu chọn ảnh tải lên, ảnh tải lên sẽ được ưu tiên dùng thay cho URL.</span>

                    <label class="field-label">Nội dung banner</label>
                    <textarea name="noi_dung_banner" rows="3" placeholder="Vd: Khám phá sản phẩm chính hãng từ AGI"><?php echo $dang_sua ? htmlspecialchars(trim($dang_sua['noi_dung_banner'] ?? '')) : ''; ?></textarea>

                    <div class="banner-form-actions">
                        <button type="submit" class="btn-admin btn-admin-primary">
                            <i class="fa-solid <?php echo $dang_sua ? 'fa-floppy-disk' : 'fa-plus'; ?>"></i>
                            <?php echo $dang_sua ? 'Lưu thay đổi' : 'Thêm thương hiệu'; ?>
                        </button>
                        <?php if ($dang_sua): ?>
                            <a href="banner.php" class="btn-admin btn-admin-secondary">Huỷ</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <div class="admin-panel">
                <h2>Danh sách thương hiệu (<?php echo count($thuong_hieu_list); ?>)</h2>

                <?php if (empty($thuong_hieu_list)): ?>
                    <div class="admin-empty">Chưa có thương hiệu nào.</div>
                <?php else: ?>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Banner</th>
                                    <th>Thương hiệu</th>
                                    <th>Nội dung banner</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($thuong_hieu_list as $th):
                                    $co_banner = !empty($th['banner']);
                                ?>
                                    <tr>
                                        <td>
                                            <img class="thumb banner-thumb"
                                                src="<?php
                                                    if ($co_banner) {
                                                        $la_url_ngoai = preg_match('#^https?://#i', $th['banner']);
                                                        if ($la_url_ngoai) {
                                                            echo htmlspecialchars($th['banner']);
                                                        } else {
                                                            $duong_dan_anh = '../../' . $th['banner'];
                                                            $version = file_exists($duong_dan_anh) ? filemtime($duong_dan_anh) : time();
                                                            echo htmlspecialchars($duong_dan_anh) . '?v=' . $version;
                                                        }
                                                    } else {
                                                        echo '../../assets/image/pc.webp';
                                                    }
                                                ?>"
                                                loading="lazy"
                                                onerror="this.onerror=null;this.src='../../assets/image/pc.webp';" alt="">
                                        </td>
                                        <td><?php echo htmlspecialchars(trim($th['ten_thuong_hieu'])); ?></td>
                                        <td class="title-cell"><?php echo htmlspecialchars(mb_substr(trim($th['noi_dung_banner'] ?? ''), 0, 80)); ?></td>
                                        <td>
                                            <?php if ($co_banner): ?>
                                                <span class="admin-badge on">Có banner</span>
                                            <?php else: ?>
                                                <span class="admin-badge off">Chưa có</span>

                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="admin-actions">
                                                <a class="edit" href="banner.php?sua=<?php echo (int) $th['ma_thuong_hieu']; ?>#form-banner">Sửa</a>
                                                <?php if ($co_banner): ?>
                                                    <a class="delete-baner" href="xuly-banner.php?action=xoa_banner&id=<?php echo (int) $th['ma_thuong_hieu']; ?>"
                                                        onclick="return confirm('Xoá banner của thương hiệu này? (Vẫn giữ thương hiệu)');">Xoá banner</a>
                                                <?php endif; ?>
                                                <a class="delete" href="xuly-banner.php?action=xoa&id=<?php echo (int) $th['ma_thuong_hieu']; ?>"
                                                    onclick="return confirm('Xoá hẳn thương hiệu này?');">Xoá</a>
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
