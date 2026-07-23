<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI, VAI_TRO_DON_HANG], '../dang-nhap.php');

    $trang_thai_loc = isset($_GET['trang_thai']) && $_GET['trang_thai'] !== '' ? (int) $_GET['trang_thai'] : -1;

    $sql = "SELECT ht.*, kh.customer_name, kh.customer_email, kh.customer_phone, kh.customer_address
            FROM ho_tro_khach_hang ht
            JOIN khach_hang_lien_he kh ON kh.ma_lien_he = ht.ma_khach_hang
            WHERE 1=1";
    $tham_so = [];
    if ($trang_thai_loc !== -1) {
        $sql .= " AND ht.trang_thai = :trang_thai";
        $tham_so[':trang_thai'] = $trang_thai_loc;
    }
    $sql .= " ORDER BY ht.ngay_gui DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($tham_so);
    $danh_sach = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $so_chua_xu_ly = (int) $pdo->query("SELECT COUNT(*) FROM ho_tro_khach_hang WHERE trang_thai = 0")->fetchColumn();

    $thong_bao = [
        'da_xu_ly'    => ['success', 'Đã đánh dấu yêu cầu là đã xử lý.'],
        'chua_xu_ly'  => ['success', 'Đã chuyển yêu cầu về trạng thái chưa xử lý.'],
        'da_xoa'      => ['success', 'Đã xoá yêu cầu hỗ trợ.'],
    ];
    $msg = isset($_GET['msg']) && isset($thong_bao[$_GET['msg']]) ? $thong_bao[$_GET['msg']] : null;

    $ADMIN_ROOT = '../';
    $active_page = 'khach-hang';
    $active_sub = 'ho-tro';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yêu cầu hỗ trợ khách hàng - Admin</title>
    <link rel="shortcut icon" href="../../assets/images/icon/logo VS_icon.jpg"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-layout.css">   
    <link rel="stylesheet" href="../assets/css/article.css">
    <link rel="stylesheet" href="../assets/css/tai-khoan.css">
</head>

<body>
    <div class="admin-shell">
        <?php include '../includes/sidebar.php'; ?>

        <main class="admin-main">
            <div class="admin-main-header">
                <h1><i class="fa-solid fa-headset"></i> Yêu cầu hỗ trợ khách hàng
                    <?php if ($so_chua_xu_ly > 0): ?>
                        <span style="background:#dc2626; color:#fff; font-size:12px; font-weight:700; border-radius:999px; padding:2px 10px; margin-left:8px; vertical-align:middle;"><?php echo $so_chua_xu_ly; ?> chưa xử lý</span>
                    <?php endif; ?>
                </h1>
            </div>

            <?php if ($msg): ?>
                <div class="admin-flash <?php echo $msg[0]; ?>"><?php echo htmlspecialchars($msg[1]); ?></div>
            <?php endif; ?>

            <div class="admin-panel">
                <div class="admin-panel-header" style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                    <h2>Danh sách yêu cầu (<?php echo count($danh_sach); ?>)</h2>
                    <div style="display:flex; gap:8px;">
                        <a href="ho-tro.php" class="btn-admin <?php echo $trang_thai_loc === -1 ? 'btn-admin-primary' : 'btn-admin-secondary'; ?>">Tất cả</a>
                        <a href="ho-tro.php?trang_thai=0" class="btn-admin <?php echo $trang_thai_loc === 0 ? 'btn-admin-primary' : 'btn-admin-secondary'; ?>">Chưa xử lý</a>
                        <a href="ho-tro.php?trang_thai=1" class="btn-admin <?php echo $trang_thai_loc === 1 ? 'btn-admin-primary' : 'btn-admin-secondary'; ?>">Đã xử lý</a>
                    </div>
                </div>

                <?php if (count($danh_sach) === 0): ?>
                    <div class="admin-empty">Chưa có yêu cầu hỗ trợ nào.</div>
                <?php else: ?>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Khách hàng</th>
                                <th>Chủ đề</th>
                                <th>Nội dung</th>
                                <th>Ngày gửi</th>
                                <th>Trạng thái</th>
                                <th>Phản hồi</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($danh_sach as $ht): ?>
                                <tr>
                                    <td class="title-cell">
                                        <div class="account-name-cell">
                                            <span class="account-avatar"><?php echo htmlspecialchars(mb_substr($ht['customer_name'], 0, 1)); ?></span>
                                            <div>
                                                <div><?php echo htmlspecialchars($ht['customer_name']); ?></div>
                                                <div style="font-size:12px; color:#6b7280;"><?php echo htmlspecialchars($ht['customer_email']); ?> · <?php echo htmlspecialchars($ht['customer_phone']); ?></div>
                                                <div style="font-size:12px; color:#6b7280;"><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($ht['customer_address'] ?? ''); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($ht['chu_de']); ?></td>
                                    <td style="max-width:320px; white-space:pre-wrap;"><?php echo htmlspecialchars($ht['noi_dung']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($ht['ngay_gui'])); ?></td>
                                    <td>
                                        <?php if ((int) $ht['trang_thai'] === 1): ?>
                                            <span class="admin-badge on">Đã xử lý</span>
                                        <?php else: ?>
                                            <span class="admin-badge off">Chưa xử lý</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="min-width:260px;">
                                        <?php if (!empty($ht['phan_hoi'])): ?>
                                            <div style="white-space:pre-wrap; font-size:13px; margin-bottom:4px;"><?php echo htmlspecialchars($ht['phan_hoi']); ?></div>
                                            <div style="font-size:12px; color:#6b7280;">Phản hồi lúc <?php echo date('d/m/Y H:i', strtotime($ht['ngay_phan_hoi'])); ?></div>
                                        <?php else: ?>
                                            <span style="color:#9ca3af; font-style:italic; font-size:13px;">Chưa có phản hồi</span>
                                        <?php endif; ?>
                                        <details style="margin-top:6px;">
                                            <summary style="cursor:pointer; font-size:12px; color:#2563eb;"><?php echo empty($ht['phan_hoi']) ? 'Viết phản hồi' : 'Sửa phản hồi'; ?></summary>
                                            <form method="POST" action="xuly-ho-tro.php" style="margin-top:8px; display:flex; flex-direction:column; gap:6px;">
                                                <input type="hidden" name="action" value="xu_ly">
                                                <input type="hidden" name="id" value="<?php echo (int) $ht['ma_ho_tro']; ?>">
                                                <textarea name="phan_hoi" rows="3" placeholder="Nhập nội dung phản hồi cho khách hàng..."
                                                    style="padding:8px; border:1px solid #d1d5db; border-radius:6px; font-family:inherit; font-size:13px; resize:vertical;"><?php echo htmlspecialchars($ht['phan_hoi'] ?? ''); ?></textarea>
                                                <button type="submit" class="btn-admin btn-admin-primary" style="align-self:flex-start; font-size:12px; padding:6px 12px;">Gửi & đánh dấu đã xử lý</button>
                                            </form>
                                        </details>
                                    </td>
                                    <td>
                                        <div class="admin-actions">
                                            <?php if ((int) $ht['trang_thai'] === 1): ?>
                                                <a class="edit" href="xuly-ho-tro.php?action=chua_xu_ly&id=<?php echo (int) $ht['ma_ho_tro']; ?>">Chưa xử lý</a>
                                            <?php endif; ?>
                                            <a class="delete" href="xuly-ho-tro.php?action=xoa&id=<?php echo (int) $ht['ma_ho_tro']; ?>"
                                                onclick="return confirm('Xoá yêu cầu hỗ trợ này?');">Xoá</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>

</html>
