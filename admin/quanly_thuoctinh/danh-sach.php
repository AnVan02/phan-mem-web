<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_NOI_DUNG], '../dang-nhap.php');

    function dem_san_pham_dung($pdo, $cot_khoa, $id) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM san_pham WHERE `$cot_khoa` = :id");
        $stmt->execute([':id' => $id]);
        return (int) $stmt->fetchColumn();
    }

    $danh_muc_ds  = $pdo->query("SELECT * FROM danh_muc ORDER BY ten_danh_muc ASC")->fetchAll(PDO::FETCH_ASSOC);
    $dung_luong_ds = $pdo->query("SELECT * FROM dung_luong ORDER BY ten_dung_luong ASC")->fetchAll(PDO::FETCH_ASSOC);
    $thuong_hieu_ds = $pdo->query("SELECT * FROM thuong_hieu ORDER BY ten_thuong_hieu ASC")->fetchAll(PDO::FETCH_ASSOC);

    $thong_bao = [
        'da_them'          => ['success', 'Đã thêm mới thành công.'],
        'da_sua'           => ['success', 'Đã cập nhật.'],
        'da_xoa'           => ['success', 'Đã xoá.'],
        'loi_thieu_ten'    => ['error', 'Vui lòng nhập tên.'],
        'loi_dang_su_dung' => ['error', 'Không thể xoá vì đang có sản phẩm sử dụng mục này.'],
        'loi_anh'          => ['error', 'Ảnh không hợp lệ. Chỉ chấp nhận jpg, jpeg, png, webp.'],
        'loi_khong_hop_le' => ['error', 'Yêu cầu không hợp lệ.'],
    ];
    $msg = isset($_GET['msg']) && isset($thong_bao[$_GET['msg']]) ? $thong_bao[$_GET['msg']] : null;

    $ADMIN_ROOT = '../';
    $active_page = 'san-pham';
    $active_sub = 'thuoc-tinh';

    // Màu icon ngẫu nhiên cho card
    $icon_colors = ['#6366f1','#f59e0b','#10b981','#ef4444','#3b82f6','#8b5cf6','#ec4899','#14b8a6'];
    $icon_list = ['fa-tag','fa-layer-group','fa-bookmark','fa-cube','fa-boxes-stacked','fa-list','fa-circle-dot','fa-star'];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh mục / Thương hiệu / Dung lượng - Admin</title>
    <link rel="shortcut icon" href="../../assets/images/icon/logo VS_icon.jpg"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-layout.css">
    <link rel="stylesheet" href="../assets/css/thuoc-tinh.css">
</head>

<body>
    <div class="admin-shell">
        <?php include '../includes/sidebar.php'; ?>

        <main class="admin-main">
            <!-- Page Header -->
            <div class="attr-page-header">
                <div class="attr-page-header-left">
                    <h1 class="attr-page-title">Danh mục / Thương hiệu / Dung lượng</h1>
                    <p class="attr-page-subtitle">Quản lý danh mục, thương hiệu và dung lượng sản phẩm</p>
                </div>
                <a href="../quanly_sanpham/danh-sach-san-pham.php" class="attr-back-btn">
                    <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách sản phẩm
                </a>
            </div>

            <?php if ($msg): ?>
                <div class="attr-flash attr-flash-<?php echo $msg[0]; ?>">
                    <i class="fa-solid <?php echo $msg[0] === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'; ?>"></i>
                    <?php echo htmlspecialchars($msg[1]); ?>
                </div>
            <?php endif; ?>

            <!-- ===== PANEL 1: DANH MỤC ===== -->
            <div class="attr-section" id="danh_muc">
                <div class="attr-section-header">
                    <div class="attr-section-title-wrap">
                        <div class="attr-section-icon" style="background:#eef2ff;">
                            <i class="fa-solid fa-layer-group" style="color:#6366f1;"></i>
                        </div>
                        <h2 class="attr-section-title">DANH MỤC <span class="attr-count">(<?php echo count($danh_muc_ds); ?>)</span></h2>
                    </div>
                    <div class="attr-section-actions">
                        <div class="attr-search-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" id="search-danh-muc" placeholder="Tìm danh mục..." oninput="filterCards('danh-muc-grid', this.value)">
                        </div>
                        <button class="attr-add-btn" onclick="toggleAddModal('modal-them-danh-muc')">
                            <i class="fa-solid fa-plus"></i> Thêm danh mục
                        </button>
                    </div>
                </div>

                <?php if (empty($danh_muc_ds)): ?>
                    <div class="attr-empty">
                        <i class="fa-solid fa-inbox"></i>
                        <p>Chưa có danh mục nào. Hãy thêm danh mục đầu tiên!</p>
                    </div>
                <?php else: ?>
                    <div class="attr-card-grid" id="danh-muc-grid">
                        <?php foreach ($danh_muc_ds as $i => $row):
                            $id = (int) $row['ma_danh_muc'];
                            $so_dung = dem_san_pham_dung($pdo, 'ma_danh_muc', $id);
                            $color = $icon_colors[$i % count($icon_colors)];
                            $icon = $icon_list[$i % count($icon_list)];
                            $bg = 'rgba(' . implode(',', sscanf(substr($color,1), '%02x%02x%02x')) . ',0.12)';
                        ?>
                            <div class="attr-card" data-name="<?php echo htmlspecialchars(strtolower($row['ten_danh_muc'])); ?>">
                                <div class="attr-card-icon" style="background:<?php echo $bg; ?>;">
                                    <i class="fa-solid <?php echo $icon; ?>" style="color:<?php echo $color; ?>;"></i>
                                </div>
                                <div class="attr-card-body">
                                    <span class="attr-card-name"><?php echo htmlspecialchars($row['ten_danh_muc']); ?></span>
                                    <span class="attr-card-count"><?php echo $so_dung; ?> SP</span>
                                </div>
                                <div class="attr-card-menu">
                                    <button class="attr-card-menu-btn" onclick="toggleMenu(this)" title="Tùy chọn">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <div class="attr-card-dropdown">
                                        <button onclick="openEditModal('danh_muc','<?php echo $id; ?>','<?php echo htmlspecialchars(addslashes($row['ten_danh_muc'])); ?>')">
                                            <i class="fa-solid fa-pen"></i> Sửa
                                        </button>
                                        <a href="xuly.php?action=xoa&loai=danh_muc&id=<?php echo $id; ?>"
                                           onclick="return confirm('Xoá danh mục này?<?php echo $so_dung > 0 ? ' Đang có ' . $so_dung . ' sản phẩm sử dụng.' : ''; ?>')"
                                           class="danger">
                                            <i class="fa-solid fa-trash"></i> Xoá
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- ===== PANEL 2: DUNG LƯỢNG ===== -->
            <?php
            // Pagination
            $dl_page = max(1, (int)($_GET['dl_page'] ?? 1));
            $dl_limit = 10;
            $dl_total = count($dung_luong_ds);
            $dl_pages = max(1, ceil($dl_total / $dl_limit));
            $dl_offset = ($dl_page - 1) * $dl_limit;
            $dl_show = array_slice($dung_luong_ds, $dl_offset, $dl_limit);
            ?>
            <div class="attr-section" id="dung_luong">
                <div class="attr-section-header">
                    <div class="attr-section-title-wrap">
                        <div class="attr-section-icon" style="background:#ecfdf5;">
                            <i class="fa-solid fa-hard-drive" style="color:#10b981;"></i>
                        </div>
                        <h2 class="attr-section-title">DUNG LƯỢNG <span class="attr-count">(<?php echo $dl_total; ?>)</span></h2>
                    </div>
                    <div class="attr-section-actions">
                        <div class="attr-search-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" id="search-dung-luong" placeholder="Tìm dung lượng...">
                        </div>
                        <div class="attr-sort-select">
                            <select id="sort-dung-luong" onchange="sortTable('dung-luong-table', this.value)">
                                <option value="az">Sắp xếp: A → Z</option>
                                <option value="za">Sắp xếp: Z → A</option>
                                <option value="usage-desc">Nhiều SP nhất</option>
                                <option value="usage-asc">Ít SP nhất</option>
                            </select>
                        </div>
                        <button class="attr-add-btn" style="background:#10b981;" onclick="toggleAddModal('modal-them-dung-luong')">
                            <i class="fa-solid fa-plus"></i> Thêm dung lượng
                        </button>
                    </div>
                </div>

                <?php if (empty($dung_luong_ds)): ?>
                    <div class="attr-empty">
                        <i class="fa-solid fa-inbox"></i>
                        <p>Chưa có dung lượng nào.</p>
                    </div>
                <?php else: ?>
                    <div class="attr-table-wrap">
                        <table class="attr-table" id="dung-luong-table">
                            <thead>
                                <tr>
                                    <th style="width:60px;">STT</th>
                                    <th>DUNG LƯỢNG</th>
                                    <th>HÌNH ẢNH</th>
                                    <th style="width:120px;">SỐ SẢN PHẨM</th>
                                    <th style="width:120px;">THAO TÁC</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dl_show as $i => $row):
                                    $id = (int) $row['ma_dung_luong'];
                                    $so_dung = dem_san_pham_dung($pdo, 'ma_dung_luong', $id);
                                ?>
                                    <tr data-name="<?php echo htmlspecialchars(strtolower($row['ten_dung_luong'])); ?>">
                                        <td class="attr-td-num"><?php echo str_pad($dl_offset + $i + 1, 2, '0', STR_PAD_LEFT); ?></td>
                                        <td>
                                            <div class="attr-td-name-wrap">
                                                <div class="attr-td-icon" style="background:#ecfdf5;">
                                                    <i class="fa-solid fa-hard-drive" style="color:#10b981;font-size:13px;"></i>
                                                </div>
                                                <span class="attr-td-name" data-id="<?php echo $id; ?>" data-loai="dung_luong"><?php echo htmlspecialchars($row['ten_dung_luong']); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <img src="<?php echo htmlspecialchars(!empty($row['hinh_anh']) ? '../../' . $row['hinh_anh'] : '../../assets/image/pc.webp'); ?>"
                                                 loading="lazy"
                                                 onerror="this.onerror=null;this.src='../../assets/image/pc.webp';"
                                                 width="40" height="40"
                                                 class="attr-td-thumb" alt="<?php echo htmlspecialchars($row['ten_dung_luong']); ?>">
                                        </td>
                                        <td>
                                            <span class="attr-usage-badge"><?php echo $so_dung; ?> SP</span>
                                        </td>
                                        <td>
                                            <div class="attr-action-btns">
                                                <button class="attr-action-edit"
                                                        onclick="openEditModal('dung_luong','<?php echo $id; ?>','<?php echo htmlspecialchars(addslashes($row['ten_dung_luong'])); ?>')"
                                                        title="Sửa">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <a href="xuly.php?action=xoa&loai=dung_luong&id=<?php echo $id; ?>"
                                                   onclick="return confirm('Xoá dung lượng này?<?php echo $so_dung > 0 ? ' Đang có ' . $so_dung . ' sản phẩm sử dụng.' : ''; ?>')"
                                                   class="attr-action-delete" title="Xoá">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination dung luong -->
                    <?php if ($dl_pages > 1): ?>
                    <div class="attr-pagination">
                        <span class="attr-pagi-info">Hiển thị <?php echo $dl_offset+1; ?> đến <?php echo min($dl_offset+$dl_limit, $dl_total); ?> của <?php echo $dl_total; ?> kết quả</span>
                        <div class="attr-pagi-btns">
                            <?php if ($dl_page > 1): ?>
                                <a href="?dl_page=<?php echo $dl_page-1; ?>#dung_luong" class="attr-pagi-btn"><i class="fa-solid fa-chevron-left"></i></a>
                            <?php endif; ?>
                            <?php for ($p = 1; $p <= $dl_pages; $p++): ?>
                                <a href="?dl_page=<?php echo $p; ?>#dung_luong" class="attr-pagi-btn <?php echo $p == $dl_page ? 'active' : ''; ?>"><?php echo $p; ?></a>
                            <?php endfor; ?>
                            <?php if ($dl_page < $dl_pages): ?>
                                <a href="?dl_page=<?php echo $dl_page+1; ?>#dung_luong" class="attr-pagi-btn"><i class="fa-solid fa-chevron-right"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- ===== PANEL 3: THƯƠNG HIỆU ===== -->
            <?php
            $th_page = max(1, (int)($_GET['th_page'] ?? 1));
            $th_limit = 10;
            $th_total = count($thuong_hieu_ds);
            $th_pages = max(1, ceil($th_total / $th_limit));
            $th_offset = ($th_page - 1) * $th_limit;
            $th_show = array_slice($thuong_hieu_ds, $th_offset, $th_limit);
            ?>
            <div class="attr-section" id="thuong_hieu">
                <div class="attr-section-header">
                    <div class="attr-section-title-wrap">
                        <div class="attr-section-icon" style="background:#fef3c7;">
                            <i class="fa-solid fa-medal" style="color:#f59e0b;"></i>
                        </div>
                        <h2 class="attr-section-title">THƯƠNG HIỆU <span class="attr-count">(<?php echo $th_total; ?>)</span></h2>
                    </div>
                    <div class="attr-section-actions">
                        <div class="attr-search-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" id="search-thuong-hieu" placeholder="Tìm thương hiệu...">
                        </div>
                        <button class="attr-add-btn" style="background:#f59e0b;" onclick="toggleAddModal('modal-them-thuong-hieu')">
                            <i class="fa-solid fa-plus"></i> Thêm thương hiệu
                        </button>
                    </div>
                </div>

                <?php if (empty($thuong_hieu_ds)): ?>
                    <div class="attr-empty">
                        <i class="fa-solid fa-inbox"></i>
                        <p>Chưa có thương hiệu nào.</p>
                    </div>
                <?php else: ?>
                    <div class="attr-card-grid" id="thuong-hieu-grid">
                        <?php foreach ($thuong_hieu_ds as $i => $row):
                            $id = (int) $row['ma_thuong_hieu'];
                            $so_dung = dem_san_pham_dung($pdo, 'ma_thuong_hieu', $id);
                            $color = $icon_colors[$i % count($icon_colors)];
                            $icon = $icon_list[$i % count($icon_list)];
                            $bg = 'rgba(' . implode(',', sscanf(substr($color,1), '%02x%02x%02x')) . ',0.12)';
                        ?>
                            <div class="attr-card" data-name="<?php echo htmlspecialchars(strtolower($row['ten_thuong_hieu'])); ?>">
                                <div class="attr-card-icon" style="background:<?php echo $bg; ?>;">
                                    <i class="fa-solid <?php echo $icon; ?>" style="color:<?php echo $color; ?>;"></i>
                                </div>
                                <div class="attr-card-body">
                                    <span class="attr-card-name"><?php echo htmlspecialchars($row['ten_thuong_hieu']); ?></span>
                                    <span class="attr-card-count"><?php echo $so_dung; ?> SP</span>
                                </div>
                                <div class="attr-card-menu">
                                    <button class="attr-card-menu-btn" onclick="toggleMenu(this)" title="Tùy chọn">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <div class="attr-card-dropdown">
                                        <button onclick="openEditModal('thuong_hieu','<?php echo $id; ?>','<?php echo htmlspecialchars(addslashes($row['ten_thuong_hieu'])); ?>')">
                                            <i class="fa-solid fa-pen"></i> Sửa
                                        </button>
                                        <a href="xuly.php?action=xoa&loai=thuong_hieu&id=<?php echo $id; ?>"
                                           onclick="return confirm('Xoá thương hiệu này?<?php echo $so_dung > 0 ? ' Đang có ' . $so_dung . ' sản phẩm sử dụng.' : ''; ?>')"
                                           class="danger">
                                            <i class="fa-solid fa-trash"></i> Xoá
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </main>
    </div>

    <!-- ===== MODAL THÊM DANH MỤC ===== -->
    <div class="attr-modal-overlay" id="modal-them-danh-muc" onclick="closeModalOnOverlay(event, this)">
        <div class="attr-modal">
            <div class="attr-modal-header">
                <h3><i class="fa-solid fa-layer-group"></i> Thêm danh mục</h3>
                <button class="attr-modal-close" onclick="closeModal('modal-them-danh-muc')"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="xuly.php#danh_muc" method="POST" class="attr-modal-form">
                <input type="hidden" name="action" value="them">
                <input type="hidden" name="loai" value="danh_muc">
                <div class="attr-form-group">
                    <label>Tên danh mục <span class="required">*</span></label>
                    <input type="text" name="ten" placeholder="VD: Ổ cứng SSD, RAM, CPU..." required autofocus>
                </div>
                <div class="attr-modal-footer">
                    <button type="button" class="attr-modal-cancel" onclick="closeModal('modal-them-danh-muc')">Huỷ</button>
                    <button type="submit" class="attr-modal-submit"><i class="fa-solid fa-plus"></i> Thêm</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===== MODAL THÊM DUNG LƯỢNG ===== -->
    <div class="attr-modal-overlay" id="modal-them-dung-luong" onclick="closeModalOnOverlay(event, this)">
        <div class="attr-modal">
            <div class="attr-modal-header">
                <h3><i class="fa-solid fa-hard-drive"></i> Thêm dung lượng</h3>
                <button class="attr-modal-close" onclick="closeModal('modal-them-dung-luong')"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="xuly.php#dung_luong" method="POST" enctype="multipart/form-data" class="attr-modal-form">
                <input type="hidden" name="action" value="them">
                <input type="hidden" name="loai" value="dung_luong">
                <div class="attr-form-group">
                    <label>Tên dung lượng <span class="required">*</span></label>
                    <input type="text" name="ten" placeholder="VD: 256GB, 512GB, 1TB..." required autofocus>
                </div>
                <div class="attr-form-group">
                    <label>Hình ảnh</label>
                    <div class="attr-file-drop" id="drop-them-dl" onclick="document.getElementById('file-them-dl').click()">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span>Kéo thả hoặc nhấn để chọn ảnh</span>
                        <small>PNG, JPG, WEBP</small>
                    </div>
                    <input type="file" name="hinh_anh" id="file-them-dl" accept="image/png,image/jpeg,image/webp" style="display:none" onchange="previewFile(this, 'drop-them-dl')">
                </div>
                <div class="attr-modal-footer">
                    <button type="button" class="attr-modal-cancel" onclick="closeModal('modal-them-dung-luong')">Huỷ</button>
                    <button type="submit" class="attr-modal-submit" style="background:#10b981;"><i class="fa-solid fa-plus"></i> Thêm</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===== MODAL THÊM THƯƠNG HIỆU ===== -->
    <div class="attr-modal-overlay" id="modal-them-thuong-hieu" onclick="closeModalOnOverlay(event, this)">
        <div class="attr-modal">
            <div class="attr-modal-header">
                <h3><i class="fa-solid fa-medal"></i> Thêm thương hiệu</h3>
                <button class="attr-modal-close" onclick="closeModal('modal-them-thuong-hieu')"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="xuly.php#thuong_hieu" method="POST" class="attr-modal-form">
                <input type="hidden" name="action" value="them">
                <input type="hidden" name="loai" value="thuong_hieu">
                <div class="attr-form-group">
                    <label>Tên thương hiệu <span class="required">*</span></label>
                    <input type="text" name="ten" placeholder="VD: Samsung, ASUS, Kingston..." required autofocus>
                </div>
                <div class="attr-modal-footer">
                    <button type="button" class="attr-modal-cancel" onclick="closeModal('modal-them-thuong-hieu')">Huỷ</button>
                    <button type="submit" class="attr-modal-submit" style="background:#f59e0b;"><i class="fa-solid fa-plus"></i> Thêm</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===== MODAL SỬA (dùng chung) ===== -->
    <div class="attr-modal-overlay" id="modal-sua" onclick="closeModalOnOverlay(event, this)">
        <div class="attr-modal">
            <div class="attr-modal-header">
                <h3 id="modal-sua-title"><i class="fa-solid fa-pen"></i> Sửa</h3>
                <button class="attr-modal-close" onclick="closeModal('modal-sua')"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="xuly.php" method="POST" id="modal-sua-form" enctype="multipart/form-data" class="attr-modal-form">
                <input type="hidden" name="action" value="sua">
                <input type="hidden" name="loai" id="modal-sua-loai">
                <input type="hidden" name="id" id="modal-sua-id">
                <div class="attr-form-group">
                    <label id="modal-sua-label">Tên <span class="required">*</span></label>
                    <input type="text" name="ten" id="modal-sua-ten" required>
                </div>
                <div class="attr-form-group" id="modal-sua-anh-wrap" style="display:none;">
                    <label>Hình ảnh mới</label>
                    <div class="attr-file-drop" id="drop-sua" onclick="document.getElementById('file-sua').click()">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span>Kéo thả hoặc nhấn để chọn ảnh mới</span>
                        <small>Bỏ trống để giữ ảnh cũ</small>
                    </div>
                    <input type="file" name="hinh_anh" id="file-sua" accept="image/png,image/jpeg,image/webp" style="display:none" onchange="previewFile(this, 'drop-sua')">
                </div>
                <div class="attr-modal-footer">
                    <button type="button" class="attr-modal-cancel" onclick="closeModal('modal-sua')">Huỷ</button>
                    <button type="submit" class="attr-modal-submit"><i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Toggle modal
    function toggleAddModal(id) {
        const el = document.getElementById(id);
        el.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
        document.body.style.overflow = '';
    }
    function closeModalOnOverlay(e, el) {
        if (e.target === el) closeModal(el.id);
    }
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.attr-modal-overlay.active').forEach(m => {
                m.classList.remove('active');
                document.body.style.overflow = '';
            });
        }
    });

    // Open edit modal
    function openEditModal(loai, id, ten) {
        const nhan = { danh_muc: 'Danh mục', dung_luong: 'Dung lượng', thuong_hieu: 'Thương hiệu' };
        document.getElementById('modal-sua-loai').value = loai;
        document.getElementById('modal-sua-id').value = id;
        document.getElementById('modal-sua-ten').value = ten;
        document.getElementById('modal-sua-title').innerHTML = '<i class="fa-solid fa-pen"></i> Sửa ' + (nhan[loai] || '');
        document.getElementById('modal-sua-label').innerHTML = 'Tên ' + (nhan[loai] || '') + ' <span class="required">*</span>';
        document.getElementById('modal-sua-anh-wrap').style.display = (loai === 'dung_luong') ? '' : 'none';
        document.getElementById('modal-sua-form').action = 'xuly.php#' + loai;
        toggleAddModal('modal-sua');
        // Close card dropdown if open
        document.querySelectorAll('.attr-card-dropdown.open').forEach(d => d.classList.remove('open'));
    }

    // Card dropdown menu
    function toggleMenu(btn) {
        const dd = btn.nextElementSibling;
        const isOpen = dd.classList.contains('open');
        document.querySelectorAll('.attr-card-dropdown.open').forEach(d => d.classList.remove('open'));
        if (!isOpen) dd.classList.add('open');
        event.stopPropagation();
    }
    document.addEventListener('click', () => {
        document.querySelectorAll('.attr-card-dropdown.open').forEach(d => d.classList.remove('open'));
    });

    // Filter cards by name
    function filterCards(gridId, val) {
        const grid = document.getElementById(gridId);
        if (!grid) return;
        const q = val.toLowerCase();
        grid.querySelectorAll('.attr-card').forEach(card => {
            card.style.display = card.dataset.name.includes(q) ? '' : 'none';
        });
    }

    // Filter table rows
    document.getElementById('search-dung-luong').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#dung-luong-table tbody tr').forEach(row => {
            row.style.display = row.dataset.name.includes(q) ? '' : 'none';
        });
    });
    document.getElementById('search-thuong-hieu') && document.getElementById('search-thuong-hieu').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#thuong-hieu-grid .attr-card').forEach(card => {
            card.style.display = card.dataset.name.includes(q) ? '' : 'none';
        });
    });

    // Sort table
    function sortTable(tableId, mode) {
        const tbody = document.querySelector('#' + tableId + ' tbody');
        if (!tbody) return;
        const rows = Array.from(tbody.querySelectorAll('tr'));
        rows.sort((a, b) => {
            const na = a.dataset.name || '';
            const nb = b.dataset.name || '';
            const ua = parseInt(a.querySelector('.attr-usage-badge')?.textContent) || 0;
            const ub = parseInt(b.querySelector('.attr-usage-badge')?.textContent) || 0;
            if (mode === 'az') return na.localeCompare(nb, 'vi');
            if (mode === 'za') return nb.localeCompare(na, 'vi');
            if (mode === 'usage-desc') return ub - ua;
            if (mode === 'usage-asc') return ua - ub;
            return 0;
        });
        rows.forEach(r => tbody.appendChild(r));
    }

    // Preview file in drop zone
    function previewFile(input, dropId) {
        const drop = document.getElementById(dropId);
        if (!drop || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = e => {
            drop.innerHTML = '<img src="' + e.target.result + '" style="max-height:80px;border-radius:8px;object-fit:cover;">';
        };
        reader.readAsDataURL(input.files[0]);
    }
    </script>
</body>

</html>