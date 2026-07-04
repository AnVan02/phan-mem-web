<?php
    require_once '../config/config.php';

    $ma_san_pham = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    $stmt = $pdo->prepare("SELECT * FROM san_pham WHERE ma_san_pham = :id LIMIT 1");
    $stmt->execute([':id' => $ma_san_pham]);
    $sp = $stmt->fetch(PDO::FETCH_ASSOC);

    $danh_muc_list = $pdo->query("SELECT * FROM danh_muc ORDER BY ten_danh_muc ASC")->fetchAll(PDO::FETCH_ASSOC);
    $thuong_hieu_list = $pdo->query("SELECT * FROM thuong_hieu ORDER BY ten_thuong_hieu ASC")->fetchAll(PDO::FETCH_ASSOC);
    $dung_luong_list = $pdo->query("SELECT * FROM dung_luong ORDER BY ten_dung_luong ASC")->fetchAll(PDO::FETCH_ASSOC);

    $thong_bao = [
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
    <title>Sửa sản phẩm - Admin</title>
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
            <h1>Sửa sản phẩm</h1>
            <a href="danh-sach-san-pham.php" class="link-out">← Quay lại danh sách</a>
        </div>

        <?php if ($msg): ?>
            <div class="admin-flash <?php echo $msg[0]; ?>"><?php echo htmlspecialchars($msg[1]); ?></div>
        <?php endif; ?>

        <?php if (!$sp): ?>
            <div class="admin-panel">
                <div class="admin-empty">Không tìm thấy sản phẩm bạn muốn sửa.</div>
            </div>
        <?php else: ?>
            <form action="xuly-san-pham.php" method="POST" enctype="multipart/form-data" class="post-editor-grid">
                <input type="hidden" name="action" value="sua">
                <input type="hidden" name="ma_san_pham" value="<?php echo (int) $sp['ma_san_pham']; ?>">
                <input type="hidden" name="anh_hien_tai" value="<?php echo htmlspecialchars($sp['hinh_anh']); ?>">

                <div class="post-main">
                    <div class="post-box">
                        <input type="text" name="ten_san_pham" class="post-title-input" value="<?php echo htmlspecialchars($sp['ten_san_pham']); ?>" placeholder="Nhập tên sản phẩm..." required>
                    </div>
                  
                    <div class="post-box">
                        <h3>Thông số kỹ thuật</h3>
                        <textarea name="thong_so" id="thong_so" rows="12"><?php echo $sp['thong-so']; ?></textarea>
                    </div>

                    <div class="post-box">
                        <h3>Mô tả sản phẩm</h3>
                        <textarea name="mo_ta" id="mo_ta" rows="12"><?php echo $sp['mo_ta']; ?></textarea>
                    </div>

                </div>

                <div class="post-side">
                    <div class="post-box">
                        <h3>Xuất bản</h3>
                        <label class="admin-checkbox">
                            <input type="checkbox" name="trang_thai" value="1" <?php echo (int) $sp['trang_thai'] === 1 ? 'checked' : ''; ?>>
                            Hiển thị trên trang sản phẩm
                        </label>
                        <button type="submit" class="btn-admin btn-admin-primary post-publish-btn">
                            <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
                        </button>
                        <a href="danh-sach-san-pham.php" class="btn-admin btn-admin-secondary post-publish-btn" style="margin-top:8px;">Huỷ</a>
                    </div>

                    <div class="post-box">
                        <h3>Phân loại</h3>
                        <label class="field-label">Danh mục</label>
                        <select name="ma_danh_muc" required>
                            <option value="">— Chọn danh mục —</option>
                            <?php foreach ($danh_muc_list as $dm): ?>
                                <option value="<?php echo (int) $dm['ma_danh_muc']; ?>" <?php echo (int) $sp['ma_danh_muc'] === (int) $dm['ma_danh_muc'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($dm['ten_danh_muc']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label class="field-label">Thương hiệu</label>
                        <select name="ma_thuong_hieu">
                            <option value="0">— Không có —</option>
                            <?php foreach ($thuong_hieu_list as $th): ?>
                                <option value="<?php echo (int) $th['ma_thuong_hieu']; ?>" <?php echo (int) $sp['ma_thuong_hieu'] === (int) $th['ma_thuong_hieu'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($th['ten_thuong_hieu']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label class="field-label">Dung lượng</label>
                        <select name="ma_dung_luong">
                            <option value="0">— Không có —</option>
                            <?php foreach ($dung_luong_list as $dl): ?>
                                <option value="<?php echo (int) $dl['ma_dung_luong']; ?>" <?php echo (int) $sp['ma_dung_luong'] === (int) $dl['ma_dung_luong'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($dl['ten_dung_luong']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="post-box">
                        <h3>Giá & kho</h3>

                        <div class="price-stock-group">
                            <span class="price-stock-group-label">Giá</span>
                            <div class="field-row">
                                <div>
                                    <label class="field-label">Giá nhập</label>
                                    <div class="input-suffix">
                                        <input type="number" name="gia_nhap" id="gia_nhap" min="0" value="<?php echo (int) $sp['gia_nhap']; ?>" required>
                                        <span class="suffix">₫</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="field-label">Giá bán</label>
                                    <div class="input-suffix">
                                        <input type="number" name="gia_ban" id="gia_ban" min="0" value="<?php echo (int) $sp['gia_ban']; ?>" required>
                                        <span class="suffix">₫</span>
                                    </div>
                                </div>
                            </div>
                            <div class="field-row">
                                <div>
                                    <label class="field-label">Giảm giá</label>
                                    <div class="input-suffix">
                                        <input type="number" name="giam_gia" id="giam_gia" min="0" max="100" value="<?php echo (int) $sp['giam_gia']; ?>">
                                        <span class="suffix">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="price-preview">Giá sau giảm: <strong id="gia_sau_giam_preview">0 ₫</strong></div>
                        </div>

                        <div class="price-stock-group">
                            <span class="price-stock-group-label">Kho</span>
                            <div class="field-row">
                                <div>
                                    <label class="field-label">Số lượng</label>
                                    <div class="input-suffix">
                                        <input type="number" name="so_luong" min="0" value="<?php echo (int) $sp['so_luong']; ?>">
                                        <span class="suffix">SP</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="field-label">Đã bán</label>
                                    <div class="input-suffix">
                                        <input type="number" name="da_ban" min="0" value="<?php echo (int) $sp['da_ban']; ?>">
                                        <span class="suffix">SP</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="post-box">
                        <h3>Hình ảnh</h3>
                        <?php if (trim($sp['hinh_anh']) !== ''): ?>
                            <span class="hint">Ảnh hiện tại: <?php echo htmlspecialchars($sp['hinh_anh']); ?></span>
                        <?php endif; ?>
                        <input type="file" name="hinh_anh_files[]" class="js-anh-preview" accept=".jpg,.jpeg,.png,.webp,.gif" multiple>
                        <span class="hint">Hoặc dán đường dẫn/URL ảnh (cách nhau bằng dấu phẩy):</span>
                        <input type="text" name="hinh_anh" value="<?php echo htmlspecialchars($sp['hinh_anh']); ?>">
                    </div>
                </div>
            </form>
        <?php endif; ?>
        </main>
    </div>

    <script>
        tinymce.init({
            selector: '#mo_ta, #thong_so',
            height: 300,
            menubar: false,
            plugins: 'link image media lists table code',
            toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link image media table | code'
        });
    </script>
    <script src="../assets/js/anh-preview.js"></script>
    <script src="../assets/js/gia-kho-preview.js"></script>
</body>

</html>
