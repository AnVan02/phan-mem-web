<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_NOI_DUNG], '../dang-nhap.php');

    $thong_bao = [
        'da_them'           => ['success', 'Đã thêm bài viết mới.'],
        'da_sua'            => ['success', 'Đã cập nhật bài viết.'],
        'da_xoa'            => ['success', 'Đã xoá bài viết.'],
        'loi_thieu_du_lieu' => ['error', 'Vui lòng nhập đầy đủ tiêu đề và tác giả.'],
        'loi_anh'           => ['error', 'Ảnh tải lên không hợp lệ (chỉ nhận jpg, jpeg, png, webp, gif).'],
    ];
    $msg = isset($_GET['msg']) && isset($thong_bao[$_GET['msg']]) ? $thong_bao[$_GET['msg']] : null;

    $ds_tai_khoan = $pdo->query("SELECT account_id, account_name, account_avatar FROM account ORDER BY account_name ASC")->fetchAll(PDO::FETCH_ASSOC);

    $ADMIN_ROOT = '../';
    $active_page = 'tin-tuc';
    $active_sub = 'them';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm bài viết - Admin</title>
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
            <h1>Thêm bài viết mới</h1>
            <a href="quanly_baiviet/danh-sach-bai-viet.php" class="link-out">← Danh sách bài viết</a>
        </div>

        <?php if ($msg): ?>
            <div class="admin-flash <?php echo $msg[0]; ?>"><?php echo htmlspecialchars($msg[1]); ?></div>
        <?php endif; ?>

        <form action="xuly.php" method="POST" enctype="multipart/form-data" class="post-editor-grid">
            <input type="hidden" name="action" value="them">

            <div class="post-main">
                <div class="post-box">
                    <input type="text" name="article_title" class="post-title-input" placeholder="Nhập tiêu đề bài viết..." required>
                </div>

                
                <div class="post-box">
                    <h3>Tóm tắt bài viết</h3>
                    <textarea name="article_summary" id="article_summary" rows="3" placeholder="Mô tả ngắn hiển thị ở trang danh sách Tin tức & Blog..."></textarea>
                </div>

                <div class="post-box">
                    <h3>Nội dung bài viết</h3>
                    <textarea name="article_content" id="article_content" rows="14"></textarea>
                </div>

            </div>

            <div class="post-side">
                <div class="post-box">
                    <h3>Xuất bản</h3>
                    <label class="admin-checkbox">
                        <input type="checkbox" name="article_status" value="1" checked>
                        Hiển thị trên Tin tức & Blog
                    </label>
                    <button type="submit" class="btn-admin btn-admin-primary post-publish-btn">
                        <i class="fa-solid fa-plus"></i> Đăng bài viết
                    </button>
                </div>

                <div class="post-box">
                    <h3>Ảnh đại diện</h3>
                    <input type="file" name="article_image_file" accept=".jpg,.jpeg,.png,.webp,.gif">
                    <span class="hint">Hoặc dán đường dẫn/URL ảnh:</span>
                    <input type="text" name="article_image" placeholder="assets/image/... hoặc https://...">
                </div>

                <div class="post-box">
                    <h3>Chuyên mục</h3>
                    <select name="article_linh">
                        <option value="">— Không thuộc chuyên mục nào —</option>
                        <?php foreach ($article_categories as $l): ?>
                            <option value="<?php echo htmlspecialchars($l); ?>"><?php echo htmlspecialchars(trim($l)); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="post-box">
                    <h3>Thẻ bài viết</h3>
                    <input type="text" name="tab_baiviet" placeholder="Vd: Điện thoại, Xiaomi, POCO F9 Pro (cách nhau bởi dấu phẩy)">
                    <span class="hint">Các thẻ này hiển thị ở cuối bài viết và dùng để gợi ý sản phẩm liên quan.</span>
                </div>

                <div class="post-box">
                    <h3>Tác giả</h3>
                    <select name="article_account_id" required>
                        <option value="">— Chọn tài khoản —</option>
                        <?php foreach ($ds_tai_khoan as $tk): ?>
                            <option value="<?php echo (int) $tk['account_id']; ?>"><?php echo htmlspecialchars($tk['account_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="post-box">
                    <h3>Ngày đăng</h3>
                    <input type="date" name="article_date" value="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="post-box">
                    <h3>Video (không bắt buộc)</h3>
                    <input type="text" name="article_video" placeholder="https://www.youtube.com/embed/...">
                </div>
                
            </div>
        </form>
        </main>
    </div>

    <script>
        tinymce.init({
            selector: '#article_content',
            height: 420,
            menubar: false,
            plugins: 'link image media lists table code',
            toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link image media table | code',
            content_style: `
                @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap');
                body { font-family: 'Montserrat', Arial, sans-serif; font-size: 16px; line-height: 1.8; color: #333; }
                h1, h2, h3, h4, h5, h6 { color: #1e3c72; font-weight: 700; line-height: 1.35; }
                h2 { font-size: 22px; } h3 { font-size: 19px; } h4 { font-size: 17px; }
                p { margin: 0 0 14px; }
                a { color: #2563eb; font-weight: 600; }
                img { max-width: 100%; height: auto; border-radius: 10px; }
                table { border-collapse: collapse; width: 100%; }
                table td, table th { border: 1px solid #e0e0e3; padding: 8px 10px; }
                blockquote { margin: 0 0 14px; padding: 10px 18px; border-left: 4px solid #1e3c72; background: #f4f4f6; color: #555; }
                code { background: #f1f1f3; padding: 2px 6px; border-radius: 4px; }
                pre { background: #1e1e1e; color: #eee; padding: 14px; border-radius: 8px; overflow-x: auto; }
            `
        });

        tinymce.init({
            selector: '#article_summary',
            height: 220,
            menubar: false,
            plugins: 'link image lists',
            toolbar: 'undo redo | bold italic underline | bullist numlist | link image',
            content_style: `
                @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap');
                body { font-family: 'Montserrat', Arial, sans-serif; font-size: 16px; line-height: 1.8; color: #333; }
                p { margin: 0 0 14px; }
                a { color: #2563eb; font-weight: 600; }
                img { max-width: 100%; height: auto; border-radius: 10px; }
            `
        });
    </script>
</body>

</html>
