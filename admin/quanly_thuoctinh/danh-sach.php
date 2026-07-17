<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_NOI_DUNG], '../dang-nhap.php');

    function dem_san_pham_dung($pdo, $cot_khoa, $id) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM san_pham WHERE `$cot_khoa` = :id");
        $stmt->execute([':id' => $id]);
        return (int) $stmt->fetchColumn();
    }

    $panels = [
        [
            'loai' => 'danh_muc',
            'nhan' => 'Danh mục',
            'khoa' => 'ma_danh_muc',
            'cot'  => 'ten_danh_muc',
            'anh'  => false, // chưa có cột hinh_anh trong bảng danh_muc
            'ds'   => $pdo->query("SELECT * FROM danh_muc ORDER BY ten_danh_muc ASC")->fetchAll(PDO::FETCH_ASSOC),
        ],
         [
            'loai' => 'dung_luong',
            'nhan' => 'Dung lượng',
            'khoa' => 'ma_dung_luong',
            'cot'  => 'ten_dung_luong',
            'anh'  => true, // có cột hinh_anh
            'ds'   => $pdo->query("SELECT * FROM dung_luong ORDER BY ten_dung_luong ASC")->fetchAll(PDO::FETCH_ASSOC),
        ],
        [
            'loai' => 'thuong_hieu',
            'nhan' => 'Thương hiệu',
            'khoa' => 'ma_thuong_hieu',
            'cot'  => 'ten_thuong_hieu',
            'anh'  => false, // chưa có cột hinh_anh trong bảng thuong_hieu
            'ds'   => $pdo->query("SELECT * FROM thuong_hieu ORDER BY ten_thuong_hieu ASC")->fetchAll(PDO::FETCH_ASSOC),
        ],
      
    ];

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
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh mục / Thương hiệu / Dung lượng - Admin</title>
    <link rel="shortcut icon" href="../../assets/images/icon/logo VS_icon.jpg"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-layout.css">
    <link rel="stylesheet" href="../assets/css/article.css">
    <link rel="stylesheet" href="../assets/css/thuoc-tinh.css">
</head>

<body>
    <div class="admin-shell">
        <?php include '../includes/sidebar.php'; ?>

        <main class="admin-main">
            <div class="admin-main-header">
                <h1>Danh mục / Thương hiệu / Dung lượng</h1>
                <a href="../quanly_sanpham/danh-sach-san-pham.php" class="link-out">← Danh sách sản phẩm</a>
            </div>

            <?php if ($msg): ?>
                <div class="admin-flash <?php echo $msg[0]; ?>"><?php echo htmlspecialchars($msg[1]); ?></div>
            <?php endif; ?>

            <div class="attr-grid">
                <?php foreach ($panels as $p): ?>
                    <div class="admin-panel attr-panel" id="<?php echo $p['loai']; ?>">
                        <h2><?php echo htmlspecialchars($p['nhan']); ?> (<?php echo count($p['ds']); ?>)</h2>

                        <form action="xuly.php#<?php echo $p['loai']; ?>" method="POST" class="attr-add-form"
                              <?php echo !empty($p['anh']) ? 'enctype="multipart/form-data"' : ''; ?>>
                            <input type="hidden" name="action" value="them">
                            <input type="hidden" name="loai" value="<?php echo $p['loai']; ?>">
                            <input type="text" name="ten" placeholder="Tên <?php echo mb_strtolower($p['nhan'], 'UTF-8'); ?> mới..." required>

                            <?php if (!empty($p['anh'])): ?>
                                <input type="file" name="hinh_anh" accept="image/png,image/jpeg,image/webp" class="attr-file-input">
                            <?php endif; ?>

                            <button type="submit" class="btn-admin btn-admin-primary" title="Thêm"><i class="fa-solid fa-plus"></i></button>
                        </form>

                        <?php if (empty($p['ds'])): ?>
                            <div class="admin-empty">Chưa có dữ liệu.</div>
                        <?php else: ?>
                            <ul class="attr-list">
                                <?php foreach ($p['ds'] as $row):
                                    $id = (int) $row[$p['khoa']];
                                    $so_dung = dem_san_pham_dung($pdo, $p['khoa'], $id);
                                ?>
                                    <li class="attr-row">

                                        <?php if (!empty($p['anh'])): ?>
                                            <span class="attr-thumb">
                                                <img src="<?php echo htmlspecialchars(!empty($row['hinh_anh']) ? '../../' . $row['hinh_anh'] : '../../assets/image/pc.webp'); ?>"
                                                    loading="lazy"
                                                    onerror="this.onerror=null;this.src='../../assets/image/pc.webp';"
                                                    width="36" height="36" alt="">
                                            </span>
                                        <?php endif; ?>

                                        <form action="xuly.php#<?php echo $p['loai']; ?>" method="POST" class="attr-edit-form"
                                              <?php echo !empty($p['anh']) ? 'enctype="multipart/form-data"' : ''; ?>>
                                            <input type="hidden" name="action" value="sua">
                                            <input type="hidden" name="loai" value="<?php echo $p['loai']; ?>">
                                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                                            <input type="text" name="ten" value="<?php echo htmlspecialchars($row[$p['cot']]); ?>">

                                            <?php if (!empty($p['anh'])): ?>
                                                <input type="file" name="hinh_anh" accept="image/png,image/jpeg,image/webp"
                                                       class="attr-file-input" title="Chọn ảnh mới (bỏ trống để giữ ảnh cũ)">
                                            <?php endif; ?>

                                            <button type="submit" class="attr-save-btn" title="Lưu">
                                                <img width="30" height="30" src="https://img.icons8.com/color/48/save--v1.png" alt="save--v1"/>
                                            </button>
                                        </form>

                                        <span class="attr-usage"><?php echo $so_dung; ?> SP</span>
                                        <a class="attr-delete-btn" title="Xoá"
                                           href="xuly.php?action=xoa&loai=<?php echo $p['loai']; ?>&id=<?php echo $id; ?>"
                                           onclick="return confirm('Xoá <?php echo htmlspecialchars(mb_strtolower($p['nhan'], 'UTF-8')); ?> này?<?php echo $so_dung > 0 ? ' Đang có ' . $so_dung . ' sản phẩm sử dụng.' : ''; ?>');">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</body>

</html>