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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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

            <!-- Header -->
            <div class="pe-header">
                <div class="pe-header-left">
                    <a href="danh-sach-bai-viet.php" class="pe-btn-back" title="Quay lại danh sách">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <h1 class="pe-page-title">Thêm bài viết mới</h1>
                </div>
                <a href="danh-sach-bai-viet.php" class="pe-btn-top-back">
                    <i class="fa-solid fa-list"></i> Danh sách bài viết
                </a>
            </div>

            <?php if ($msg): ?>
                <div class="admin-flash <?php echo $msg[0]; ?>"><?php echo htmlspecialchars($msg[1]); ?></div>
            <?php endif; ?>

            <form action="xuly.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="them">

                <div class="pe-grid">

                    <!-- ===== CỘT CHÍNH ===== -->
                    <div class="pe-main">

                        <!-- Tiêu đề -->
                        <div class="pe-card pe-title-card">
                            <input type="text"
                                   name="article_title"
                                   class="pe-title-input"
                                   placeholder="Nhập tiêu đề bài viết..."
                                   required
                                   style="font-size:20px; font-weight:700; color:#0f172a;">
                        </div>

                        <!-- Tóm tắt -->
                        <div class="pe-card">
                            <div class="pe-card-header">
                                <i class="fa-solid fa-align-left pe-card-icon"></i>
                                <h3 class="pe-card-title">Tóm tắt bài viết</h3>
                            </div>
                            <textarea name="article_summary" id="article_summary" rows="3"
                                      placeholder="Mô tả ngắn hiển thị ở trang danh sách Tin tức & Blog..."></textarea>
                        </div>

                        <!-- Nội dung -->
                        <div class="pe-card">
                            <div class="pe-card-header">
                                <i class="fa-solid fa-file-lines pe-card-icon"></i>
                                <h3 class="pe-card-title">Nội dung bài viết</h3>
                            </div>
                            <textarea name="article_content" id="article_content" rows="14"></textarea>
                        </div>

                    </div>

                    <!-- ===== CỘT SIDEBAR ===== -->
                    <div class="pe-side">

                        <!-- Xuất bản -->
                        <div class="pe-card">
                            <div class="pe-card-header">
                                <i class="fa-solid fa-rocket pe-card-icon"></i>
                                <h3 class="pe-card-title">Xuất bản</h3>
                            </div>
                            <div class="pe-publish-row">
                                <div class="pe-toggle-wrap">
                                    <label class="pe-switch">
                                        <input type="checkbox" name="article_status" value="1" checked>
                                        <span class="pe-slider"></span>
                                    </label>
                                    <span style="font-size:13.5px; color:#334155; font-weight:600;">Hiển thị trên Tin tức & Blog</span>
                                </div>
                            </div>
                            <button type="submit" class="pe-btn-publish">
                                <i class="fa-solid fa-paper-plane"></i> Đăng bài viết
                            </button>
                            <a href="danh-sach-bai-viet.php" class="pe-btn-cancel-link">Huỷ, quay lại danh sách</a>
                        </div>

                        <!-- Ảnh đại diện -->
                        <div class="pe-card">
                            <div class="pe-card-header">
                                <i class="fa-solid fa-image pe-card-icon"></i>
                                <h3 class="pe-card-title">Ảnh đại diện</h3>
                            </div>
                            <label class="pe-label">Tải ảnh lên</label>
                            <div class="pe-image-dropzone" onclick="document.getElementById('file_anh').click()">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                <p>Nhấn để chọn ảnh</p>
                                <span>jpg, jpeg, png, webp, gif</span>
                                <input type="file" id="file_anh" name="article_image_file"
                                       accept=".jpg,.jpeg,.png,.webp,.gif"
                                       style="display:none;"
                                       onchange="previewAnh(this)">
                            </div>
                            <div id="anh-preview" style="display:none; margin-top:12px;">
                                <img id="anh-preview-img" src="" alt="preview"
                                     style="width:100%; border-radius:10px; object-fit:cover; max-height:180px;">
                            </div>
                            <label class="pe-label" style="margin-top:14px;">Hoặc dán đường dẫn / URL ảnh</label>
                            <input type="text" name="article_image" class="pe-input"
                                   placeholder="assets/image/... hoặc https://...">
                        </div>

                        <!-- Chuyên mục -->
                        <div class="pe-card">
                            <div class="pe-card-header">
                                <i class="fa-solid fa-folder-open pe-card-icon"></i>
                                <h3 class="pe-card-title">Chuyên mục</h3>
                            </div>
                            <select name="article_linh" class="pe-select">
                                <option value="">— Không thuộc chuyên mục nào —</option>
                                <?php foreach ($article_categories as $l): ?>
                                    <option value="<?php echo htmlspecialchars($l); ?>"><?php echo htmlspecialchars(trim($l)); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Thẻ bài viết -->
                        <div class="pe-card">
                            <div class="pe-card-header">
                                <i class="fa-solid fa-tags pe-card-icon"></i>
                                <h3 class="pe-card-title">Thẻ bài viết</h3>
                            </div>
                            <input type="text" name="tab_baiviet" class="pe-input"
                                   placeholder="Vd: Điện thoại, Xiaomi, POCO F9 Pro">
                            <p style="font-size:12px; color:#94a3b8; margin:8px 0 0;">
                                Các thẻ cách nhau bởi dấu phẩy. Dùng để gợi ý sản phẩm liên quan.
                            </p>
                        </div>

                        <!-- Tác giả -->
                        <div class="pe-card">
                            <div class="pe-card-header">
                                <i class="fa-solid fa-user-pen pe-card-icon"></i>
                                <h3 class="pe-card-title">Tác giả</h3>
                            </div>
                            <select name="article_account_id" class="pe-select" required>
                                <option value="">— Chọn tài khoản —</option>
                                <?php foreach ($ds_tai_khoan as $tk): ?>
                                    <option value="<?php echo (int) $tk['account_id']; ?>"><?php echo htmlspecialchars($tk['account_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Ngày đăng -->
                        <div class="pe-card">
                            <div class="pe-card-header">
                                <i class="fa-solid fa-calendar-days pe-card-icon"></i>
                                <h3 class="pe-card-title">Ngày đăng</h3>
                            </div>
                            <div class="pe-date-picker-box">
                                <i class="fa-regular fa-calendar pe-date-picker-icon"></i>
                                <div class="pe-date-picker-text">
                                    <span class="pe-date-picker-label">Ngày xuất bản</span>
                                    <input type="date" name="article_date"
                                           value="<?php echo date('Y-m-d'); ?>"
                                           style="border:none; background:transparent; font-size:13.5px; font-weight:600; color:#334155; outline:none; font-family:inherit; cursor:pointer;">
                                </div>
                            </div>
                        </div>

                        <!-- Video -->
                        <div class="pe-card">
                            <div class="pe-card-header">
                                <i class="fa-brands fa-youtube pe-card-icon"></i>
                                <h3 class="pe-card-title">Video (không bắt buộc)</h3>
                            </div>
                            <input type="text" name="article_video" class="pe-input"
                                   placeholder="https://www.youtube.com/embed/...">
                        </div>

                    </div>
                </div><!-- .pe-grid -->
            </form>

        </main>
    </div>

    <script>
        // Preview ảnh khi chọn file
        function previewAnh(input) {
            const preview = document.getElementById('anh-preview');
            const img = document.getElementById('anh-preview-img');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    img.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        tinymce.init({
            selector: '#article_content',
            height: 420,
            menubar: false,
            plugins: 'link image media lists table code',
            toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link image media table | code',
            content_style: `
                @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap');
                body { font-family: 'Montserrat', Arial, sans-serif; font-size: 18px; font-weight: 500; line-height: 1.8; color: #333; }
                h1, h2, h3, h4, h5, h6 { color: #1e3c72; font-weight: 700; line-height: 1.35; margin: 22px 0 14px; }
                h2 { font-size: 22px; } h3 { font-size: 19px; } h4 { font-size: 17px; }
                p { margin: 0 0 14px; }
                ul, ol { margin: 0 0 14px; padding-left: 22px; }
                li { margin-bottom: 6px; }
                a { color: #2563eb; font-weight: 600; text-decoration: none; }
                a:hover { text-decoration: underline; }
                img { max-width: 100%; height: auto; border-radius: 10px; }
                table { border-collapse: collapse; width: 100%; margin: 0 0 14px; }
                table td, table th { border: 1px solid #e0e0e3; padding: 8px 10px; }
                blockquote { margin: 0 0 14px; padding: 10px 18px; border-left: 4px solid #1e3c72; background: #f4f4f6; color: #555; }
                code { background: #f1f1f3; padding: 2px 6px; border-radius: 4px; }
                pre { background: #1e1e1e; color: #eee; padding: 14px; border-radius: 8px; overflow-x: auto; margin: 0 0 14px; }
            `
        });

        tinymce.init({
            selector: '#article_summary',
            height: 200,
            menubar: false,
            plugins: 'link image lists',
            toolbar: 'undo redo | bold italic underline | bullist numlist | link image',
            content_style: `
                @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap');
                body { font-family: 'Montserrat', Arial, sans-serif; font-size: 16px; font-weight: 500; line-height: 1.8; color: #333; }
                p { margin: 0 0 14px; }
                ul, ol { margin: 0 0 14px; padding-left: 22px; }
                li { margin-bottom: 6px; }
                a { color: #2563eb; font-weight: 600; text-decoration: none; }
                a:hover { text-decoration: underline; }
                img { max-width: 100%; height: auto; border-radius: 10px; }
            `
        });
    </script>
</body>

</html>
