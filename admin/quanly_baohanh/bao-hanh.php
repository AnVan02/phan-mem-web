<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_DON_HANG], '../dang-nhap.php');

    $import_gan_nhat = $_SESSION['bao_hanh_import'] ?? null;

    $thong_bao = [
        'da_import'     => ['success', $import_gan_nhat
            ? "Import xong: {$import_gan_nhat['thanh_cong']}/{$import_gan_nhat['tong']} dòng thành công" . ($import_gan_nhat['that_bai'] > 0 ? ", {$import_gan_nhat['that_bai']} dòng lỗi." : '.')
            : 'Import thành công.'],
        'loi_dinh_dang' => ['error', 'File không hợp lệ hoặc sai định dạng cột. Vui lòng dùng đúng file mẫu Excel (.xlsx) hoặc CSV.'],
        'loi_kich_thuoc' => ['error', 'File vượt quá kích thước cho phép (tối đa 20MB).'],
        'loi_chua_chon' => ['error', 'Vui lòng chọn file Excel trước khi import.'],
        'loi_khong_co_du_lieu' => ['error', 'File không có dòng dữ liệu nào để import.'],
    ];
    $msg = isset($_GET['msg']) && isset($thong_bao[$_GET['msg']]) ? $thong_bao[$_GET['msg']] : null;

    $ADMIN_ROOT = '../';
    $active_page = 'bao-hanh';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import file bảo hành - Admin</title>
    <link rel="shortcut icon" href="../../assets/images/icon/logo VS_icon.jpg"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-layout.css">
    <link rel="stylesheet" href="../assets/css/article.css">
    <link rel="stylesheet" href="../assets/css/import-baohanh.css">
</head>

<body>
    <div class="admin-shell">
        <?php include '../includes/sidebar.php'; ?>

        <main class="admin-main">
            <div class="import-bh-breadcrumb">
                <a href="../dashboad.php"><i class="fa-solid fa-house"></i> Trang chủ</a>
                <i class="fa-solid fa-chevron-right"></i>
                <span class="current">Import file bảo hành</span>
            </div>

            <?php if ($msg): ?>
                <div class="admin-flash <?php echo $msg[0]; ?>"><?php echo htmlspecialchars($msg[1]); ?></div>
            <?php endif; ?>

            <div class="import-bh-grid">
                <div class="admin-panel import-bh-main">
                    <h2>Import File bảo hành</h2>
                    <p class="subtitle">Nhập file Excel để import dữ liệu vào hệ thống</p>

                    <div class="import-bh-flow">
                        <div class="import-bh-flow-icon">
                            <img src="https://img.icons8.com/color/48/microsoft-excel-2019.png" alt="Excel">
                        </div>
                        <div class="import-bh-flow-arrow"><i class="fa-solid fa-arrow-right"></i></div>
                        <div class="import-bh-flow-icon">
                            <img src="https://img.icons8.com/fluency/48/database.png" alt="SQL">
                        </div>
                    </div>

                    <form action="xuly-import.php" method="POST" enctype="multipart/form-data" id="importForm">
                        <label class="import-bh-dropzone" id="dropzone" for="excelFile">
                            <div class="import-bh-dropzone-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                            <p class="import-bh-dropzone-title" id="dropzoneTitle">Kéo và thả file Excel vào đây</p>
                            <p class="import-bh-dropzone-sub" id="dropzoneSub">Hoặc chọn file từ máy tính của bạn</p>
                            <span class="import-bh-choose-btn"><i class="fa-solid fa-folder-open"></i> Chọn tệp Excel</span>
                            <input type="file" name="excel_file" id="excelFile" accept=".xlsx,.csv" style="display:none;">
                            <p class="import-bh-dropzone-hint">Định dạng hỗ trợ: .xlsx, .csv &nbsp;·&nbsp; Kích thước tối đa: 20MB</p>
                        </label>

                        <div class="import-bh-note">
                            <div class="import-bh-note-icon"><i class="fa-solid fa-circle-info"></i></div>
                            <div class="import-bh-note-body">
                                <h4>Lưu ý khi import dữ liệu</h4>
                                <ul>
                                    <li>File Excel phải đúng định dạng theo mẫu hệ thống</li>
                                    <li>Vui lòng kiểm tra dữ liệu trước khi import để đảm bảo tính chính xác</li>
                                    <li>Không được để trống các cột bắt buộc</li>
                                </ul>
                            </div>
                            <a href="mau-excel.php" class="import-bh-template-btn"><i class="fa-solid fa-download"></i> Tải mẫu Excel</a>
                        </div>

                        <button type="submit" class="import-bh-submit" id="submitBtn" disabled>
                            <i class="fa-solid fa-file-import"></i> Import bảo hành
                        </button>
                    </form>
                </div>

                <div class="import-bh-side">
                    <div class="admin-panel">
                        <h3><i class="fa-solid fa-circle-question"></i> Hướng dẫn import</h3>
                        <div class="import-bh-steps">
                            <div class="import-bh-step">
                                <div class="import-bh-step-num">1</div>
                                <div class="import-bh-step-text">
                                    <strong>Tải mẫu Excel</strong>
                                    <span>Tải file mẫu về và nhập dữ liệu đúng định dạng</span>
                                </div>
                            </div>
                            <div class="import-bh-step">
                                <div class="import-bh-step-num">2</div>
                                <div class="import-bh-step-text">
                                    <strong>Chuẩn bị dữ liệu</strong>
                                    <span>Kiểm tra dữ liệu đảm bảo đầy đủ, chính xác</span>
                                </div>
                            </div>
                            <div class="import-bh-step">
                                <div class="import-bh-step-num">3</div>
                                <div class="import-bh-step-text">
                                    <strong>Import dữ liệu</strong>
                                    <span>Kéo thả hoặc chọn file Excel đã chuẩn bị vào hệ thống</span>
                                </div>
                            </div>
                            <div class="import-bh-step">
                                <div class="import-bh-step-num">4</div>
                                <div class="import-bh-step-text">
                                    <strong>Kiểm tra kết quả</strong>
                                    <span>Xem kết quả import để biết dữ liệu thành công hay lỗi</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="admin-panel">
                        <h3><i class="fa-solid fa-clock-rotate-left"></i> Thông tin import gần nhất</h3>
                        <?php if (!$import_gan_nhat): ?>
                            <div class="import-bh-latest-empty"><i class="fa-regular fa-calendar"></i> Chưa có dữ liệu import</div>
                        <?php else: ?>
                            <div class="import-bh-latest-empty"><i class="fa-regular fa-calendar"></i> Lúc <?php echo htmlspecialchars($import_gan_nhat['thoi_gian']); ?></div>
                            <div class="import-bh-latest-row">
                                <span>Tổng số dòng</span>
                                <span class="value"><?php echo (int) $import_gan_nhat['tong']; ?></span>
                            </div>
                            <div class="import-bh-latest-row success">
                                <span>Thành công</span>
                                <span class="value"><?php echo (int) $import_gan_nhat['thanh_cong']; ?></span>
                            </div>
                            <div class="import-bh-latest-row fail">
                                <span>Thất bại</span>
                                <span class="value"><?php echo (int) $import_gan_nhat['that_bai']; ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        var dropzone = document.getElementById('dropzone');
        var fileInput = document.getElementById('excelFile');
        var dropzoneTitle = document.getElementById('dropzoneTitle');
        var dropzoneSub = document.getElementById('dropzoneSub');
        var submitBtn = document.getElementById('submitBtn');

        function hienThiFile(file) {
            if (!file) {
                return;
            }
            var duoiHopLe = /\.(xlsx|csv)$/i.test(file.name);
            if (!duoiHopLe) {
                alert('Chỉ hỗ trợ file .xlsx hoặc .csv');
                fileInput.value = '';
                return;
            }
            if (file.size > 20 * 1024 * 1024) {
                alert('File vượt quá kích thước cho phép (tối đa 20MB)');
                fileInput.value = '';
                return;
            }
            dropzone.classList.add('has-file');
            dropzoneTitle.textContent = file.name;
            dropzoneSub.textContent = 'Đã chọn file (' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)';
            submitBtn.disabled = false;
        }

        fileInput.addEventListener('click', function (e) {
            e.stopPropagation();
        });

        fileInput.addEventListener('change', function () {
            hienThiFile(fileInput.files[0]);
        });

        ['dragover', 'dragenter'].forEach(function (evt) {
            dropzone.addEventListener(evt, function (e) {
                e.preventDefault();
                dropzone.classList.add('is-dragover');
            });
        });

        ['dragleave', 'dragend'].forEach(function (evt) {
            dropzone.addEventListener(evt, function (e) {
                e.preventDefault();
                dropzone.classList.remove('is-dragover');
            });
        });

        dropzone.addEventListener('drop', function (e) {
            e.preventDefault();
            dropzone.classList.remove('is-dragover');
            var file = e.dataTransfer.files[0];
            if (file) {
                fileInput.files = e.dataTransfer.files;
                hienThiFile(file);
            }
        });
    </script>
</body>

</html>
