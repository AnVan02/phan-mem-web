<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_NOI_DUNG], '../dang-nhap.php');

    $ma_chinh_sach = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    $stmt = $pdo->prepare("SELECT * FROM policy_page WHERE policy_id = :id LIMIT 1");
    $stmt->execute([':id' => $ma_chinh_sach]);
    $trang = $stmt->fetch(PDO::FETCH_ASSOC);

    $slug_khoa_cung = in_array($trang['policy_slug'] ?? '', ['bao-hanh', 've-chung-toi'], true);

    $thong_bao = [
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
    <title>Sửa trang chính sách - Admin</title>
    <link rel="shortcut icon" href="../../assets/images/icon/logo VS_icon.jpg"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-layout.css">
    <link rel="stylesheet" href="../assets/css/article.css">
    <link rel="stylesheet" href="../assets/css/post-editor.css">
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
</head>

<body>
    <div class="admin-shell">
        <?php include '../includes/sidebar.php'; ?>

        <main class="admin-main">
        <div class="admin-main-header">
            <h1>Sửa trang chính sách</h1>
            <a href="danh-sach.php" class="link-out">← Quay lại danh sách</a>
        </div>

        <?php if ($msg): ?>
            <div class="admin-flash <?php echo $msg[0]; ?>"><?php echo htmlspecialchars($msg[1]); ?></div>
        <?php endif; ?>

        <?php if (!$trang): ?>
            <div class="admin-panel">
                <div class="admin-empty">Không tìm thấy trang chính sách bạn muốn sửa.</div>
            </div>
        <?php else: ?>
            <form action="xuly.php" method="POST" enctype="multipart/form-data" class="post-editor-grid">
                <input type="hidden" name="action" value="sua">
                <input type="hidden" name="policy_id" value="<?php echo (int) $trang['policy_id']; ?>">
                <input type="hidden" name="anh_hien_tai" value="<?php echo htmlspecialchars($trang['policy_image']); ?>">

                <div class="post-main">
                    <div class="post-box">
                        <input type="text" name="policy_title" class="post-title-input" value="<?php echo htmlspecialchars($trang['policy_title']); ?>" placeholder="Nhập tiêu đề trang chính sách..." required>
                    </div>

                    <div class="post-box">
                        <h3>Mô tả ngắn (hiển thị dưới tiêu đề)</h3>
                        <textarea name="policy_subtitle" rows="2"><?php echo htmlspecialchars($trang['policy_subtitle']); ?></textarea>
                    </div>

                    <div class="post-box">
                        <h3>Nội dung trang</h3>
                        <textarea name="policy_content" id="policy_content" rows="16"><?php echo $trang['policy_content']; ?></textarea>
                    </div>
                </div>

                <div class="post-side">
                    <div class="post-box">
                        <h3>Xuất bản</h3>
                        <label class="admin-checkbox">
                            <input type="checkbox" name="policy_status" value="1" <?php echo (int) $trang['policy_status'] === 1 ? 'checked' : ''; ?>>
                            Hiển thị trang này trên website
                        </label>
                        <button type="submit" class="btn-admin btn-admin-primary post-publish-btn">
                            <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
                        </button>
                        <a href="danh-sach.php" class="btn-admin btn-admin-secondary post-publish-btn" style="margin-top:8px;">Huỷ</a>
                    </div>

                    <div class="post-box">
                        <h3>Đường dẫn (slug)</h3>
                        <?php if ($slug_khoa_cung): ?>
                            <input type="text" value="<?php echo htmlspecialchars($trang['policy_slug']); ?>" disabled>
                            <input type="hidden" name="policy_slug" value="<?php echo htmlspecialchars($trang['policy_slug']); ?>">
                            <span class="hint">Đường dẫn này gắn với trang mặc định của hệ thống nên không thể đổi.</span>
                        <?php else: ?>
                            <input type="text" name="policy_slug" value="<?php echo htmlspecialchars($trang['policy_slug']); ?>" required>
                            <span class="hint">Chỉ dùng chữ thường, số và dấu gạch ngang.</span>
                        <?php endif; ?>
                    </div>

                    <div class="post-box">
                        <h3>Ảnh minh hoạ (không bắt buộc)</h3>
                        <?php if (trim($trang['policy_image']) !== ''): ?>
                            <span class="hint">Ảnh hiện tại: <?php echo htmlspecialchars($trang['policy_image']); ?></span>
                        <?php endif; ?>
                        <input type="file" name="policy_image_file" accept=".jpg,.jpeg,.png,.webp,.gif">
                        <span class="hint">Hoặc dán đường dẫn/URL ảnh:</span>
                        <input type="text" name="policy_image" value="<?php echo htmlspecialchars($trang['policy_image']); ?>">
                    </div>
                </div>
            </form>
        <?php endif; ?>
        </main>
    </div>

    <script>
        tinymce.init({
            selector: '#policy_content',
            height: 480,
            menubar: false,
            plugins: 'link image media lists table code',
            toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link image media table | code'
        });
    </script>
</body>

</html>
