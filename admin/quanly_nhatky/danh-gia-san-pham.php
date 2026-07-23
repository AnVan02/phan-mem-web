<?php
require_once '../config/config.php';
yeu_cau_dang_nhap();

// 1. XỬ LÝ XOÁ ĐÁNH GIÁ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'xoa') {
    $ma_danh_gia = (int) ($_POST['ma_danh_gia'] ?? 0);
    if ($ma_danh_gia > 0) {
        $del = $pdo->prepare("DELETE FROM danh_gia_san_pham WHERE ma_danh_gia = :id");
        $del->execute([':id' => $ma_danh_gia]);
    }
    header('Location: danh-sach.php' . (isset($_GET['q']) ? '?' . http_build_query($_GET) : ''));
    exit;
}

// 2. XỬ LÝ TRẢ LỜI ĐÁNH GIÁ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'tra_loi') {
    $ma_danh_gia = (int) $_POST['ma_danh_gia'];
    $noi_dung_tra_loi = trim($_POST['noi_dung_tra_loi']);

    if ($ma_danh_gia > 0) {
        $upd = $pdo->prepare("UPDATE danh_gia_san_pham SET noi_dung_tra_loi = :nd, ngay_tra_loi = NOW() WHERE ma_danh_gia = :id");
        $upd->execute([':nd' => $noi_dung_tra_loi, ':id' => $ma_danh_gia]);
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

// 3. BỘ LỌC TÌM KIẾM
$tu_khoa   = isset($_GET['q']) ? trim($_GET['q']) : '';
$loc_sao   = isset($_GET['sao']) && $_GET['sao'] !== '' ? (int) $_GET['sao'] : 0;
$loc_sp    = isset($_GET['sp']) && $_GET['sp'] !== '' ? (int) $_GET['sp'] : 0;

$dieu_kien = [];
$tham_so   = [];

if ($tu_khoa !== '') {
    $dieu_kien[] = "(dg.noi_dung LIKE :tu_khoa OR kh.customer_name LIKE :tu_khoa OR sp.ten_san_pham LIKE :tu_khoa)";
    $tham_so[':tu_khoa'] = '%' . $tu_khoa . '%';
}
if ($loc_sao >= 1 && $loc_sao <= 5) {
    $dieu_kien[] = "dg.so_sao = :sao";
    $tham_so[':sao'] = $loc_sao;
}
if ($loc_sp > 0) {
    $dieu_kien[] = "dg.ma_san_pham = :sp";
    $tham_so[':sp'] = $loc_sp;
}
$where_sql = !empty($dieu_kien) ? 'WHERE ' . implode(' AND ', $dieu_kien) : '';

// 4. THỐNG KÊ NHANH
$tong_danh_gia  = (int) $pdo->query("SELECT COUNT(*) FROM danh_gia_san_pham")->fetchColumn();
$diem_tb_chung  = (float) $pdo->query("SELECT COALESCE(AVG(so_sao), 0) FROM danh_gia_san_pham")->fetchColumn();
$danh_gia_xau   = (int) $pdo->query("SELECT COUNT(*) FROM danh_gia_san_pham WHERE so_sao <= 2")->fetchColumn();
$cho_phan_hoi   = (int) $pdo->query("SELECT COUNT(*) FROM danh_gia_san_pham WHERE noi_dung_tra_loi IS NULL OR noi_dung_tra_loi = ''")->fetchColumn();

// 5. LẤY DANH SÁCH ĐÁNH GIÁ
$sql = "SELECT dg.*, sp.ten_san_pham, kh.customer_name
            FROM danh_gia_san_pham dg
            JOIN san_pham sp ON dg.ma_san_pham = sp.ma_san_pham
            JOIN khach_hang_lien_he kh ON dg.ma_khach_hang = kh.ma_lien_he
            $where_sql
            ORDER BY dg.ngay_danh_gia DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($tham_so);
$danh_sach = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách sản phẩm để lọc
$sp_options = $pdo->query("SELECT DISTINCT sp.ma_san_pham, sp.ten_san_pham FROM danh_gia_san_pham dg JOIN san_pham sp ON dg.ma_san_pham = sp.ma_san_pham ORDER BY sp.ten_san_pham ASC")->fetchAll(PDO::FETCH_ASSOC);

$ADMIN_ROOT = '../';
$active_page = 'danh_gia';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đánh giá sản phẩm</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-layout.css">
    <link rel="stylesheet" href="../assets/css/danh-gia-san-pham.css">

</head>

<body>
    <div class="admin-shell">
        <?php include '../includes/sidebar.php'; ?>

        <main class="admin-main">
            <div class="admin-main-header">
                <div>
                    <h1><i class="fa-solid fa-star-half-stroke"></i> Quản lý đánh giá sản phẩm</h1>
                    <div class="subtitle">Xem phản hồi của khách hàng và gửi lời cảm ơn hoặc giải đáp.</div>
                </div>
            </div>

            <!-- 1. Thống kê nhanh -->
            <div class="dg-stat-grid">
                <div class="stat-card">
                    <div class="icon blue"><i class="fa-solid fa-comments"></i></div>
                    <div>
                        <span class="value"><?php echo $tong_danh_gia; ?></span>
                        <span class="label">Tổng đánh giá</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon orange"><i class="fa-solid fa-star"></i></div>
                    <div>
                        <span class="value"><?php echo number_format($diem_tb_chung, 1); ?>/5</span>
                        <span class="label">Điểm trung bình</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon red"><i class="fa-solid fa-circle-exclamation"></i></div>
                    <div>
                        <span class="value"><?php echo $danh_gia_xau; ?></span>
                        <span class="label">Đánh giá 1-2 sao</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon green"><i class="fa-solid fa-reply"></i></div>
                    <div>
                        <span class="value"><?php echo $cho_phan_hoi; ?></span>
                        <span class="label">Chưa phản hồi</span>
                    </div>
                </div>
            </div>

            <!-- 2. Bộ lọc -->
            <form method="GET" class="dg-filter-bar">
                <div class="dg-filter-field">
                    <label>Tìm kiếm</label>
                    <input type="text" name="q" placeholder="Khách hàng, sản phẩm..."
                        value="<?php echo htmlspecialchars($tu_khoa); ?>">
                </div>
                <div class="dg-filter-field">
                    <label>Số sao</label>
                    <select name="sao">
                        <option value="">Tất cả sao</option>
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <option value="<?php echo $i; ?>" <?php echo $loc_sao == $i ? 'selected' : ''; ?>>
                                <?php echo $i; ?> sao</option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="dg-filter-field">
                    <label>Sản phẩm</label>
                    <select name="sp">
                        <option value="">Tất cả sản phẩm</option>
                        <?php foreach ($sp_options as $opt): ?>
                            <option value="<?php echo $opt['ma_san_pham']; ?>"
                                <?php echo $loc_sp == $opt['ma_san_pham'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($opt['ten_san_pham']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="dg-btn-search">Lọc</button>
                <a href="danh-sach.php" class="dg-btn-reset">Xoá lọc</a>
            </form>

            <!-- 3. Bảng danh sách -->
            <div class="dash-panel dash-table-panel">
                <h2 style="font-size: 18px; margin-bottom: 20px;">Danh sách chi tiết</h2>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th width="20%">Sản phẩm</th>
                                <th width="15%">Khách hàng</th>
                                <th width="12%">Đánh giá</th>
                                <th width="35%">Nội dung & Phản hồi</th>
                                <th width="10%">Ngày</th>
                                <th width="8%">Xoá</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($danh_sach as $dg): ?>
                                <tr>
                                    <td>
                                        <a style="color:var(--primary); font-weight:600; text-decoration:none;"
                                            href="../quanly_sanpham/sua.php?id=<?php echo $dg['ma_san_pham']; ?>">
                                            <?php echo htmlspecialchars($dg['ten_san_pham']); ?>
                                        </a>
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($dg['customer_name']); ?></strong></td>
                                    <td>
                                        <div class="dg-stars">
                                            <?php for ($i = 1; $i <= 5; $i++) echo '<i class="fa-' . ($i <= $dg['so_sao'] ? 'solid' : 'regular') . ' fa-star"></i>'; ?>
                                        </div>
                                        <span class="<?php echo $dg['so_sao'] <= 2 ? 'dg-badge-low' : 'dg-badge-ok'; ?>">
                                            <?php echo $dg['so_sao'] <= 2 ? 'TIÊU CỰC' : 'TỐT'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <!-- Nội dung khách viết -->
                                        <div style="line-height: 1.5; color:#444;">
                                            <?php echo nl2br(htmlspecialchars($dg['noi_dung'])); ?>
                                        </div>

                                        <!-- Phản hồi của Admin (nếu có) -->
                                        <?php if (!empty($dg['noi_dung_tra_loi'])): ?>
                                            <div class="dg-admin-reply">
                                                <i class="fa-solid fa-reply fa-rotate-180"></i>
                                                <strong>Admin:</strong> <?php echo htmlspecialchars($dg['noi_dung_tra_loi']); ?>
                                                <div style="font-size:10px; color:#888; margin-top:5px;">
                                                    Đã gửi lúc: <?php echo date('d/m/Y H:i', strtotime($dg['ngay_tra_loi'])); ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Nút Trả lời -->
                                        <button type="button" class="dg-btn-reply-toggle"
                                            onclick="toggleReply(<?php echo $dg['ma_danh_gia']; ?>)">
                                            <?php echo empty($dg['noi_dung_tra_loi']) ? 'Trả lời ngay' : 'Sửa câu trả lời'; ?>
                                        </button>

                                        <!-- Form Trả lời ẩn -->
                                        <div id="form-reply-<?php echo $dg['ma_danh_gia']; ?>" style="display:none;"
                                            class="dg-reply-form">
                                            <form method="POST">
                                                <input type="hidden" name="action" value="tra_loi">
                                                <input type="hidden" name="ma_danh_gia"
                                                    value="<?php echo $dg['ma_danh_gia']; ?>">
                                                <textarea name="noi_dung_tra_loi" placeholder="Viết phản hồi của bạn..."
                                                    required><?php echo $dg['noi_dung_tra_loi']; ?></textarea>
                                                <div class="dg-reply-actions">
                                                    <button type="submit" class="btn-send">Gửi phản hồi</button>
                                                    <button type="button" class="btn-cancel"
                                                        onclick="toggleReply(<?php echo $dg['ma_danh_gia']; ?>)">Hủy</button>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                    <td style="font-size:12px; color:#888;">
                                        <?php echo date('d/m/Y', strtotime($dg['ngay_danh_gia'])); ?></td>
                                    <td>
                                        <form method="POST"
                                            onsubmit="return confirm('Xoá đánh giá này sẽ không thể khôi phục. Tiếp tục?');">
                                            <input type="hidden" name="action" value="xoa">
                                            <input type="hidden" name="ma_danh_gia"
                                                value="<?php echo $dg['ma_danh_gia']; ?>">
                                            <button type="submit" class="dg-btn-delete"><i
                                                    class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Hàm ẩn/hiện form trả lời
        function toggleReply(id) {
            const form = document.getElementById('form-reply-' + id);
            if (form.style.display === 'none') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
    </script>
</body>

</html>