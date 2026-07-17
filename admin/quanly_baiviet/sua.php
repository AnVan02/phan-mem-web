<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_NOI_DUNG], '../dang-nhap.php');

    $ma_bai_viet = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    $stmt = $pdo->prepare("SELECT * FROM article WHERE article_id = :id LIMIT 1");
    $stmt->execute([':id' => $ma_bai_viet]);
    $bai_viet = $stmt->fetch(PDO::FETCH_ASSOC);

    $thong_bao = [
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
    <title>Sửa bài viết - Admin</title>
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
            <h1>Sửa bài viết</h1>
            <a href="dah-sach-bai-viet.php" class="link-out">← Quay lại danh sách</a>
        </div>

        <?php if ($msg): ?>
            <div class="admin-flash <?php echo $msg[0]; ?>"><?php echo htmlspecialchars($msg[1]); ?></div>
        <?php endif; ?>

        <?php if (!$bai_viet): ?>
            <div class="admin-panel">
                <div class="admin-empty">Không tìm thấy bài viết bạn muốn sửa.</div>
            </div>
        <?php else: ?>
            <form action="xuly.php" method="POST" enctype="multipart/form-data" class="post-editor-grid">
                <input type="hidden" name="action" value="sua">
                <input type="hidden" name="article_id" value="<?php echo (int) $bai_viet['article_id']; ?>">
                <input type="hidden" name="anh_hien_tai" value="<?php echo htmlspecialchars($bai_viet['article_image']); ?>">

                <div class="post-main">
                    <div class="post-box">
                        <input type="text" name="article_title" class="post-title-input" value="<?php echo htmlspecialchars($bai_viet['article_title']); ?>" placeholder="Nhập tiêu đề bài viết..." required>
                    </div>

                    <div class="post-box">
                        <h3>Tóm tắt ngắn</h3>
                        <textarea name="article_summary" rows="3"><?php echo htmlspecialchars($bai_viet['article_summary']); ?></textarea>
                    </div>

                    <div class="post-box">
                        <h3>Nội dung bài viết</h3>
                        <textarea name="article_content" id="article_content" rows="14"><?php echo $bai_viet['article_content']; ?></textarea>
                    </div>

                </div>

                <div class="post-side">
                    <div class="post-box">
                        <h3>Xuất bản</h3>
                        <label class="admin-checkbox">
                            <input type="checkbox" name="article_status" value="1" <?php echo (int) $bai_viet['article_status'] === 1 ? 'checked' : ''; ?>>
                            Hiển thị trên Tin tức & Blog
                        </label>
                        <button type="submit" class="btn-admin btn-admin-primary post-publish-btn">
                            <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
                        </button>
                        <a href="dah-sach-bai-viet.php" class="btn-admin btn-admin-secondary post-publish-btn" style="margin-top:8px;">Huỷ</a>
                    </div>

                    <div class="post-box">
                        <h3>Ảnh đại diện</h3>
                        <?php if (trim($bai_viet['article_image']) !== ''): ?>
                            <span class="hint">Ảnh hiện tại: <?php echo htmlspecialchars($bai_viet['article_image']); ?></span>
                        <?php endif; ?>
                        <input type="file" name="article_image_file" accept=".jpg,.jpeg,.png,.webp,.gif">
                        <span class="hint">Hoặc dán đường dẫn/URL ảnh:</span>
                        <input type="text" name="article_image" value="<?php echo htmlspecialchars($bai_viet['article_image']); ?>">
                    </div>

                    <div class="post-box">
                        <h3>Chuyên mục</h3>
                        <select name="article_linh">
                            <option value="">— Không thuộc chuyên mục nào —</option>
                            <?php foreach ($article_categories as $l): ?>
                                <option value="<?php echo htmlspecialchars($l); ?>" <?php echo $bai_viet['article_linh'] === $l ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars(trim($l)); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="post-box">
                        <h3>Tác giả</h3>
                        <input type="text" name="article_author" value="<?php echo htmlspecialchars($bai_viet['article_author']); ?>" required>
                    </div>

                    <div class="post-box">
                        <h3>Ngày đăng</h3>
                        <input type="date" name="article_date" value="<?php echo htmlspecialchars($bai_viet['article_date']); ?>">
                    </div>

                    <div class="post-box">
                        <h3>Video (không bắt buộc)</h3>
                        <input type="text" name="article_video" value="<?php echo htmlspecialchars($bai_viet['article_video']); ?>" placeholder="https://www.youtube.com/embed/...">
                    </div>
                </div>
            </form>
        <?php endif; ?>
        </main>
    </div>

    <script>
        tinymce.init({
            selector: '#article_content',
            height: 420,
            menubar: false,
            plugins: 'link image media lists table code',
            toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link image media table | code'
        });
    </script>
</body>

</html>
