<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_NOI_DUNG], '../dang-nhap.php');

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
    $active_sub = 'them';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sản phẩm - Admin</title>
    <link rel="shortcut icon" href="../../assets/images/icon/logo VS_icon.jpg"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
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
        <!-- Header Section -->
        <div class="pe-header">
            <div class="pe-header-left">
                <a href="danh-sach-san-pham.php" class="pe-btn-back" title="Quay lại"><i class="fa-solid fa-arrow-left"></i></a>
                <h1 class="pe-page-title">Thêm sản phẩm</h1>
            </div>
            <a href="danh-sach-san-pham.php" class="pe-btn-top-back"><i class="fa-solid fa-chevron-left"></i> Quay lại danh sách</a>
        </div>

        <?php if ($msg): ?>
            <div class="bv-flash bv-flash-<?php echo $msg[0]; ?>">
                <i class="fa-solid <?php echo $msg[0] === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'; ?>"></i>
                <?php echo htmlspecialchars($msg[1]); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($danh_muc_list)): ?>
            <div class="bv-flash bv-flash-error">Chưa có danh mục nào trong CSDL. Vui lòng thêm ít nhất 1 danh mục trước khi tạo sản phẩm.</div>
        <?php endif; ?>

        <!-- Form post editor -->
        <form action="xuly-san-pham.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="them">

            <div class="pe-grid">
                <!-- Left Main Panel -->
                <div class="pe-main">
                    <!-- Title Input -->
                    <div class="pe-card pe-title-card">
                        <label class="pe-label" style="font-weight:700; color:var(--text-muted); font-size:12px; text-transform:uppercase;">Tên sản phẩm</label>
                        <input type="text" name="ten_san_pham" class="pe-title-input" placeholder="VD: ASROCK X870E Taichi" required>
                    </div>

                    <!-- Technical Specs TinyMCE -->
                    <div class="pe-card">
                        <div class="pe-card-header">
                            <i class="fa-solid fa-laptop-code pe-card-icon"></i>
                            <h3 class="pe-card-title">Thông số kỹ thuật</h3>
                        </div>
                        <textarea name="thong_so" id="thong_so" rows="12"></textarea>
                    </div>

                    <!-- Product Description TinyMCE -->
                    <div class="pe-card">
                        <div class="pe-card-header">
                            <i class="fa-regular fa-file-lines pe-card-icon"></i>
                            <h3 class="pe-card-title">Mô tả sản phẩm</h3>
                        </div>
                        <textarea name="mo_ta" id="mo_ta" rows="12"></textarea>
                    </div>
                </div>

                <!-- Right Side Panel -->
                <div class="pe-side">
                    <!-- Publish Settings Card -->
                    <div class="pe-card">
                        <div class="pe-card-header">
                            <i class="fa-regular fa-paper-plane pe-card-icon"></i>
                            <h3 class="pe-card-title">Xuất bản</h3>
                        </div>
                        
                        <div class="pe-publish-row">
                            <span class="pe-label" style="margin:0; font-weight:600;">Hiển thị trên trang sản phẩm</span>
                            <label class="pe-switch">
                                <input type="checkbox" name="trang_thai" value="1" checked>
                                <span class="pe-slider"></span>
                            </label>
                        </div>

                        <div class="pe-date-picker-box">
                            <i class="fa-regular fa-calendar-days pe-date-picker-icon"></i>
                            <div class="pe-date-picker-text">
                                <span class="pe-date-picker-label">Ngày tạo / cập nhật</span>
                                <span class="pe-date-picker-val"><?php echo date('d/m/Y H:i'); ?></span>
                            </div>
                        </div>

                        <button type="submit" class="pe-btn-publish">
                            <i class="fa-regular fa-floppy-disk"></i> Lưu thay đổi
                        </button>
                        <a href="danh-sach-san-pham.php" class="pe-btn-cancel-link">Hủy</a>
                    </div>

                    <!-- Categories & Brands Card -->
                    <div class="pe-card">
                        <div class="pe-card-header">
                            <i class="fa-solid fa-tags pe-card-icon"></i>
                            <h3 class="pe-card-title">Phân loại</h3>
                        </div>

                        <label class="pe-label">Danh mục</label>
                        <select name="ma_danh_muc" class="pe-select" required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php foreach ($danh_muc_list as $dm): ?>
                                <option value="<?php echo (int) $dm['ma_danh_muc']; ?>"><?php echo htmlspecialchars($dm['ten_danh_muc']); ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label class="pe-label">Thương hiệu</label>
                        <select name="ma_thuong_hieu" class="pe-select">
                            <option value="0">-- Không có --</option>
                            <?php foreach ($thuong_hieu_list as $th): ?>
                                <option value="<?php echo (int) $th['ma_thuong_hieu']; ?>"><?php echo htmlspecialchars($th['ten_thuong_hieu']); ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label class="pe-label">Dòng sản phẩm (Dung lượng)</label>
                        <select name="ma_dung_luong" class="pe-select">
                            <option value="0">-- Không có --</option>
                            <?php foreach ($dung_luong_list as $dl): ?>
                                <option value="<?php echo (int) $dl['ma_dung_luong']; ?>"><?php echo htmlspecialchars($dl['ten_dung_luong']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Fast specifications inputs Card -->
                    <div class="pe-card">
                        <div class="pe-card-header">
                            <i class="fa-solid fa-info pe-card-icon"></i>
                            <h3 class="pe-card-title">Thông tin nhanh</h3>
                        </div>

                        <label class="pe-label">Mã sản phẩm (SKU)</label>
                        <input type="text" name="sku" class="pe-input" placeholder="VD: AGI-AI238-1TB">

                        <label class="pe-label">Loại sản phẩm</label>
                        <input type="text" name="loai_san_pham" class="pe-input" placeholder="VD: SSD gắn ngoài">

                        <label class="pe-label">Chuẩn kết nối</label>
                        <input type="text" name="chuan_ket_noi" class="pe-input" placeholder="VD: USB 3.2 Gen 1">

                        <div class="pe-row">
                            <div>
                                <label class="pe-label">Tốc độ đọc</label>
                                <input type="text" name="toc_do_doc" class="pe-input" placeholder="VD: 500 MB/s">
                            </div>
                            <div>
                                <label class="pe-label">Tốc độ ghi</label>
                                <input type="text" name="toc_do_ghi" class="pe-input" placeholder="VD: 400 MB/s">
                            </div>
                        </div>

                        <div class="pe-row">
                            <div>
                                <label class="pe-label">Kích thước</label>
                                <input type="text" name="kich_thuoc" class="pe-input" placeholder="VD: 110 x 41 x 9 mm">
                            </div>
                            <div>
                                <label class="pe-label">Trọng lượng</label>
                                <input type="text" name="trong_luong" class="pe-input" placeholder="VD: ~80g">
                            </div>
                        </div>

                        <label class="pe-label">Bảo hành</label>
                        <input type="text" name="bao_hanh" class="pe-input" placeholder="VD: 36 tháng chính hãng">
                    </div>

                    <!-- Prices and Inventory Card -->
                    <div class="pe-card">
                        <div class="pe-card-header">
                            <i class="fa-solid fa-calculator pe-card-icon"></i>
                            <h3 class="pe-card-title">Giá & kho</h3>
                        </div>

                        <div class="pe-row">
                            <div>
                                <label class="pe-label">Giá nhập</label>
                                <div class="pe-input-suffix-wrap">
                                    <input type="number" name="gia_nhap" id="gia_nhap" class="pe-input" min="0" value="0" required>
                                    <span class="pe-input-suffix">₫</span>
                                </div>
                            </div>
                            <div>
                                <label class="pe-label">Giá bán</label>
                                <div class="pe-input-suffix-wrap">
                                    <input type="number" name="gia_ban" id="gia_ban" class="pe-input" min="0" value="0" required>
                                    <span class="pe-input-suffix">₫</span>
                                </div>
                            </div>
                        </div>

                        <label class="pe-label">Giảm giá</label>
                        <div class="pe-input-suffix-wrap">
                            <input type="number" name="giam_gia" id="giam_gia" class="pe-input" min="0" max="100" value="0">
                            <span class="pe-input-suffix">%</span>
                        </div>

                        <div class="price-preview" style="margin-top: 14px; font-size: 13.5px; color: var(--text-muted);">
                            Giá sau giảm: <strong id="gia_sau_giam_preview" style="color: var(--purple-primary); font-size: 16px;">0 ₫</strong>
                        </div>

                        <label class="pe-label" style="margin-top: 18px;">Tồn kho số lượng</label>
                        <div class="pe-input-suffix-wrap">
                            <input type="number" name="so_luong" class="pe-input" min="0" value="0">
                            <span class="pe-input-suffix">SP</span>
                        </div>
                    </div>

                    <!-- Images Upload Card -->
                    <div class="pe-card">
                        <div class="pe-card-header">
                            <i class="fa-regular fa-image pe-card-icon"></i>
                            <h3 class="pe-card-title">Hình ảnh</h3>
                        </div>

                        <div class="pe-image-dropzone" id="peDropzone">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <p>Chọn tệp hoặc kéo thả vào đây</p>
                            <span>JPG, PNG, WEBP tối đa 5MB</span>
                            <input type="file" name="hinh_anh_files[]" id="peFileInput" class="pe-file-input" accept=".jpg,.jpeg,.png,.webp,.gif" multiple style="display:none;">
                        </div>

                        <div class="pe-preview-list" id="pePreviewList"></div>

                        <label class="pe-label">Hoặc nhập URL ảnh (cách nhau bằng dấu phẩy)</label>
                        <input type="text" name="hinh_anh" class="pe-input" placeholder="VD: assets/image/sanpham1.png">
                    </div>
                </div>
            </div>

            <!-- Sticky/Bottom Save button -->
            <div class="pe-footer-actions">
                <a href="danh-sach-san-pham.php" class="pe-btn-secondary" style="border-radius:10px; padding:12px 24px; text-decoration:none; font-size:14px; font-weight:600; display:inline-flex; align-items:center;">Hủy bỏ</a>
                <button type="submit" class="pe-btn-submit">
                    <i class="fa-regular fa-floppy-disk"></i> Cập nhật sản phẩm
                </button>
            </div>
        </form>
    </main>
</div>

<script>
    tinymce.init({
        selector: '#mo_ta, #thong_so',
        height: 340,
        menubar: false,
        plugins: 'link image media lists table code',
        toolbar: 'undo redo | blocks | bold italic underline strikethrough | bullist numlist | alignleft aligncenter alignright alignjustify | link image media table | code'
    });

    // Price calculator preview
    (function() {
        var giaBan = document.getElementById('gia_ban');
        var giamGia = document.getElementById('giam_gia');
        var preview = document.getElementById('gia_sau_giam_preview');

        function updatePreview() {
            var gb = parseInt(giaBan.value) || 0;
            var gg = parseInt(giamGia.value) || 0;
            var finalPrice = gg > 0 ? Math.round(gb * (100 - gg) / 100) : gb;
            preview.textContent = finalPrice.toLocaleString('vi-VN') + ' ₫';
        }

        if (giaBan && giamGia && preview) {
            giaBan.addEventListener('input', updatePreview);
            giamGia.addEventListener('input', updatePreview);
            updatePreview();
        }
    })();

    // Image Upload Drag & Drop Preview
    (function() {
        var dropzone = document.getElementById('peDropzone');
        var fileInput = document.getElementById('peFileInput');
        var previewList = document.getElementById('pePreviewList');

        if (!dropzone || !fileInput) return;

        dropzone.addEventListener('click', function() {
            fileInput.click();
        });

        fileInput.addEventListener('change', function() {
            handleFiles(fileInput.files);
        });

        dropzone.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropzone.style.borderColor = 'var(--purple-primary)';
            dropzone.style.backgroundColor = '#f5f7ff';
        });

        dropzone.addEventListener('dragleave', function() {
            dropzone.style.borderColor = '#cbd5e1';
            dropzone.style.backgroundColor = 'var(--bg-light)';
        });

        dropzone.addEventListener('drop', function(e) {
            e.preventDefault();
            dropzone.style.borderColor = '#cbd5e1';
            dropzone.style.backgroundColor = 'var(--bg-light)';
            
            var files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFiles(files);
            }
        });

        function handleFiles(files) {
            previewList.innerHTML = '';
            Array.from(files).forEach(function(file) {
                if (!file.type.startsWith('image/')) return;
                
                var reader = new FileReader();
                reader.onload = function(e) {
                    var item = document.createElement('div');
                    item.className = 'pe-preview-item';
                    
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    item.appendChild(img);
                    
                    var removeBtn = document.createElement('button');
                    removeBtn.className = 'pe-preview-remove';
                    removeBtn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
                    removeBtn.type = 'button';
                    removeBtn.addEventListener('click', function() {
                        item.remove();
                    });
                    item.appendChild(removeBtn);
                    
                    previewList.appendChild(item);
                };
                reader.readAsDataURL(file);
            });
        }
    })();
</script>
</body>
</html>