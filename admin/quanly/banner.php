<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_NOI_DUNG], '../dang-nhap.php');

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
            <div class="bh-page-header">
                <div>
                    <h1 class="bh-page-title">Banner thương hiệu</h1>
                    <p class="bh-page-subtitle"><span class="bh-dot"></span> Quản lý banner của các thương hiệu</p>
                </div>
                <a href="../quanly_sanpham/danh-sach-san-pham.php" class="bh-link-back">← Danh sách sản phẩm</a>
            </div>

            <?php if ($msg): ?>
                <div class="admin-flash <?php echo $msg[0]; ?>">
                    <i class="fa-solid <?php echo $msg[0] === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'; ?>"></i>
                    <?php echo htmlspecialchars($msg[1]); ?>
                </div>
            <?php endif; ?>

            <div class="bh-layout">
                <!-- Form Card -->
                <div class="bh-card" id="form-banner">
                    <div class="bh-card-header">
                        <div class="bh-card-icon"><i class="fa-solid fa-images"></i></div>
                        <h2 class="bh-card-title"><?php echo $dang_sua ? 'SỬA THƯƠNG HIỆU / BANNER' : 'THÊM THƯƠNG HIỆU MỚI'; ?></h2>
                    </div>
                    
                    <div class="bh-card-body">
                        <form action="xuly-banner.php" method="POST" enctype="multipart/form-data" class="bh-form">
                            <input type="hidden" name="action" value="<?php echo $dang_sua ? 'sua' : 'them'; ?>">
                            <?php if ($dang_sua): ?>
                                <input type="hidden" name="id" value="<?php echo (int) $dang_sua['ma_thuong_hieu']; ?>">
                            <?php endif; ?>

                            <div class="bh-field">
                                <label class="bh-label">Tên thương hiệu</label>
                                <input type="text" name="ten_thuong_hieu" class="bh-input" placeholder="Vd: AGI, Kingston, AMD..."
                                    value="<?php echo $dang_sua ? htmlspecialchars(trim($dang_sua['ten_thuong_hieu'])) : ''; ?>" required>
                            </div>

                            <div class="bh-field">
                                <label class="bh-label">Ảnh banner (tải lên)</label>
                                <label class="bh-dropzone" for="bannerFile">
                                    <div class="bh-dropzone-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                                    <p class="bh-dropzone-title">Click hoặc kéo thả ảnh</p>
                                    <p class="bh-dropzone-sub">Định dạng: JPG, PNG, WEBP</p>
                                    <input type="file" name="banner_file" id="bannerFile" accept="image/png,image/jpeg,image/webp">
                                </label>
                            </div>

                            <div class="bh-or-divider">HOẶC</div>

                            <div class="bh-field">
                                <label class="bh-label">Dán URL ảnh banner <span class="bh-label-hint">(Ưu tiên ảnh tải lên)</span></label>
                                <input type="text" name="banner_url" class="bh-input" placeholder="https://..."
                                    value="<?php echo $dang_sua && !empty($dang_sua['banner']) ? htmlspecialchars($dang_sua['banner']) : ''; ?>">
                            </div>

                            <div class="bh-field">
                                <label class="bh-label">Nội dung banner</label>
                                <textarea name="noi_dung_banner" class="bh-textarea" placeholder="Vd: Khám phá sản phẩm chính hãng từ AGI"><?php echo $dang_sua ? htmlspecialchars(trim($dang_sua['noi_dung_banner'] ?? '')) : ''; ?></textarea>
                            </div>

                            <div class="bh-form-actions">
                                <button type="submit" class="bh-btn-primary">
                                    <i class="fa-solid <?php echo $dang_sua ? 'fa-floppy-disk' : 'fa-plus'; ?>"></i>
                                    <?php echo $dang_sua ? 'Lưu thay đổi' : 'Thêm thương hiệu'; ?>
                                </button>
                                <?php if ($dang_sua): ?>
                                    <a href="banner.php" class="bh-btn-secondary">Huỷ</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="bh-card">
                    <div class="bh-card-header">
                        <div class="bh-card-icon icon-list"><i class="fa-solid fa-list"></i></div>
                        <h2 class="bh-card-title">DANH SÁCH THƯƠNG HIỆU</h2>
                        <span class="bh-card-count"><?php echo count($thuong_hieu_list); ?></span>
                    </div>

                    <?php if (empty($thuong_hieu_list)): ?>
                        <div class="bh-empty">
                            <i class="fa-solid fa-box-open"></i>
                            <p>Chưa có thương hiệu nào.</p>
                        </div>
                    <?php else: ?>
                        <div class="bh-table-wrap">
                            <table class="bh-table">
                                <thead>
                                    <tr>
                                        <th>Banner</th>
                                        <th>Thương hiệu</th>
                                        <th>Nội dung</th>
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
                                                <div class="bh-thumb-wrap">
                                                    <img src="<?php
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
                                                </div>
                                            </td>
                                            <td>
                                                <div class="bh-name-cell">
                                                    <strong><?php echo htmlspecialchars(trim($th['ten_thuong_hieu'])); ?></strong>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="bh-content-cell" title="<?php echo htmlspecialchars(trim($th['noi_dung_banner'] ?? '')); ?>">
                                                    <?php echo htmlspecialchars(mb_substr(trim($th['noi_dung_banner'] ?? ''), 0, 40)) . (mb_strlen(trim($th['noi_dung_banner'] ?? '')) > 40 ? '...' : ''); ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if ($co_banner): ?>
                                                    <span class="bh-badge on">Có banner</span>
                                                <?php else: ?>
                                                    <span class="bh-badge off">Chưa có</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="bh-actions">
                                                    <a href="banner.php?sua=<?php echo (int) $th['ma_thuong_hieu']; ?>#form-banner" class="bh-action-btn bh-action-edit" title="Sửa">
                                                        <i class="fa-solid fa-pen"></i> Sửa
                                                    </a>
                                                    <?php if ($co_banner): ?>
                                                        <a href="xuly-banner.php?action=xoa_banner&id=<?php echo (int) $th['ma_thuong_hieu']; ?>" 
                                                           class="bh-action-btn bh-action-del-banner" 
                                                           onclick="return confirm('Xoá banner của thương hiệu này? (Vẫn giữ thương hiệu)');" title="Xoá banner">
                                                            <i class="fa-regular fa-image"></i> Xoá ảnh
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="xuly-banner.php?action=xoa&id=<?php echo (int) $th['ma_thuong_hieu']; ?>" 
                                                       class="bh-action-btn bh-action-delete" 
                                                       onclick="return confirm('Xoá hẳn thương hiệu này?');" title="Xoá thương hiệu">
                                                        <i class="fa-solid fa-trash-can"></i> Xoá
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
