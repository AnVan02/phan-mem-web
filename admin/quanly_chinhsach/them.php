<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_NOI_DUNG], '../dang-nhap.php');

    $thong_bao = [
        'loi_thieu_du_lieu' => ['error', 'Vui lòng nhập đầy đủ đường dẫn (slug) và tiêu đề.'],
        'loi_trung_slug'    => ['error', 'Đường dẫn (slug) này đã được sử dụng, vui lòng chọn đường dẫn khác.'],
        'loi_anh'           => ['error', 'Ảnh tải lên không hợp lệ (chỉ nhận jpg, jpeg, png, webp, gif).'],
    ];
    $msg = isset($_GET['msg']) && isset($thong_bao[$_GET['msg']]) ? $thong_bao[$_GET['msg']] : null;

    $ADMIN_ROOT = '../';
    $active_page = 'chinh-sach';
    $active_sub = 'them';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm trang chính sách - Admin</title>
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
            <h1>Thêm trang chính sách mới</h1>
            <a href="danh-sach.php" class="link-out">← Danh sách chính sách</a>
        </div>

        <?php if ($msg): ?>
            <div class="admin-flash <?php echo $msg[0]; ?>"><?php echo htmlspecialchars($msg[1]); ?></div>
        <?php endif; ?>

        <form action="xuly.php" method="POST" enctype="multipart/form-data" class="post-editor-grid">
            <input type="hidden" name="action" value="them">

            <div class="post-main">
                <div class="post-box">
                    <input type="text" name="policy_title" class="post-title-input" placeholder="Nhập tiêu đề trang chính sách..." required>
                </div>

                <div class="post-box">
                    <h3>Mô tả ngắn (hiển thị dưới tiêu đề)</h3>
                    <textarea name="policy_subtitle" rows="2" placeholder="Ví dụ: Cam kết vận chuyển nhanh chóng, an toàn toàn quốc..."></textarea>
                </div>

                <div class="post-box">
                    <h3>Nội dung trang</h3>
                    <textarea name="policy_content" id="policy_content" rows="16"></textarea>
                </div>
            </div>

            <div class="post-side">
                <div class="post-box">
                    <h3>Xuất bản</h3>
                    <label class="admin-checkbox">
                        <input type="checkbox" name="policy_status" value="1" checked>
                        Hiển thị trang này trên website
                    </label>
                    <button type="submit" class="btn-admin btn-admin-primary post-publish-btn">
                        <i class="fa-solid fa-plus"></i> Tạo trang chính sách
                    </button>
                </div>

                <div class="post-box">
                    <h3>Đường dẫn (slug)</h3>
                    <input type="text" name="policy_slug" placeholder="vi-du: chinh-sach-van-chuyen" required>
                    <span class="hint">Chỉ dùng chữ thường, số và dấu gạch ngang. Đây là phần đường dẫn trang: chinh-sach.php?slug=...</span>
                </div>

                <div class="post-box">
                    <h3>Ảnh minh hoạ (không bắt buộc)</h3>
                    <input type="file" name="policy_image_file" accept=".jpg,.jpeg,.png,.webp,.gif">
                    <span class="hint">Hoặc dán đường dẫn/URL ảnh:</span>
                    <input type="text" name="policy_image" placeholder="assets/image/... hoặc https://...">
                </div>
            </div>
        </form>
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
