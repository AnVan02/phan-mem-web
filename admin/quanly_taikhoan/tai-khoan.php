<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI], '../dang-nhap.php');

    $danh_sach = $pdo->query("SELECT * FROM account ORDER BY account_id ASC")->fetchAll(PDO::FETCH_ASSOC);

    // Nếu có ?sua=id thì nạp dữ liệu để sửa, ngược lại là form thêm mới
    $sua_id = isset($_GET['sua']) ? (int) $_GET['sua'] : 0;
    $dang_sua = null;
    if ($sua_id > 0) {
        foreach ($danh_sach as $tk) {
            if ((int) $tk['account_id'] === $sua_id) {
                $dang_sua = $tk;
                break;
            }
        }
    }

    $thong_bao = [
        'da_them'          => ['success', 'Đã thêm tài khoản quản trị mới.'],
        'da_sua'           => ['success', 'Đã cập nhật tài khoản.'],
        'da_xoa'           => ['success', 'Đã xoá tài khoản.'],
        'loi_thieu_du_lieu'=> ['error', 'Vui lòng nhập đầy đủ tên, email và mật khẩu.'],
        'loi_email_ton_tai'=> ['error', 'Email này đã được dùng cho tài khoản khác.'],
        'loi_mat_khau_ngan'=> ['error', 'Mật khẩu phải có ít nhất 6 ký tự.'],
        'loi_tu_xoa'       => ['error', 'Không thể xoá chính tài khoản đang đăng nhập.'],
        'loi_xoa_quan_tri_cuoi' => ['error', 'Không thể xoá — hệ thống cần ít nhất 1 tài khoản Quản trị viên.'],
    ];
    $msg = isset($_GET['msg']) && isset($thong_bao[$_GET['msg']]) ? $thong_bao[$_GET['msg']] : null;

    $ADMIN_ROOT = '../';
    $active_page = 'tai-khoan';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài khoản quản trị - Admin</title>   
    <link rel="shortcut icon" href="../../assets/images/icon/logo VS_icon.jpg"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-layout.css">
    <link rel="stylesheet" href="../assets/css/article.css">
    <link rel="stylesheet" href="../assets/css/post-editor.css">
    <link rel="stylesheet" href="../assets/css/tai-khoan.css">
</head>

<body>
    <div class="admin-shell">
        <?php include '../includes/sidebar.php'; ?>

        <main class="admin-main">
            <div class="admin-main-header">
                <h1><i class="fa-solid fa-users-gear"></i> Tài khoản quản trị</h1>
            </div>

            <?php if ($msg): ?>
                <div class="admin-flash <?php echo $msg[0]; ?>"><?php echo htmlspecialchars($msg[1]); ?></div>
            <?php endif; ?>

            <div class="post-box" id="form-tai-khoan">
                <h3><?php echo $dang_sua ? 'Sửa tài khoản' : 'Thêm tài khoản quản trị mới'; ?></h3>

                <form action="xuly-tai-khoan.php" method="POST" class="banner-form">
                    <input type="hidden" name="action" value="<?php echo $dang_sua ? 'sua' : 'them'; ?>">
                    <?php if ($dang_sua): ?>
                        <input type="hidden" name="id" value="<?php echo (int) $dang_sua['account_id']; ?>">
                    <?php endif; ?>

                    <label class="field-label">Họ tên</label>
                    <input type="text" name="account_name" placeholder="Vd: Nguyễn Văn A"
                        value="<?php echo $dang_sua ? htmlspecialchars($dang_sua['account_name']) : ''; ?>" required>

                    <div class="field-row">
                        <div>
                            <label class="field-label">Email đăng nhập</label>
                            <input type="email" name="account_email" placeholder="admin@vietsontdc.com"
                                value="<?php echo $dang_sua ? htmlspecialchars($dang_sua['account_email']) : ''; ?>" required>
                        </div>
                                
                        <!-- /** @var array<int,string> $DS_VAI_TRO */ -->
                        <div>
                            <label class="field-label">Vai trò</label>
                            <select name="account_type" required>
                                <?php foreach ($DS_VAI_TRO as $ma_vt => $ten_vt): ?>
                                    <option value="<?php echo $ma_vt; ?>" <?php echo ($dang_sua && (int) $dang_sua['account_type'] === $ma_vt) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($ten_vt); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


                    <label class="field-label">Mật khẩu<?php echo $dang_sua ? ' (bỏ trống nếu không đổi)' : ''; ?></label>
                    <input type="password" name="account_password" placeholder="Ít nhất 6 ký tự" <?php echo $dang_sua ? '' : 'required'; ?>>

                    <div class="banner-form-actions">
                        <button type="submit" class="btn-admin btn-admin-primary">
                            <i class="fa-solid <?php echo $dang_sua ? 'fa-floppy-disk' : 'fa-plus'; ?>"></i>
                            <?php echo $dang_sua ? 'Lưu thay đổi' : 'Thêm tài khoản'; ?>
                        </button>
                        <?php if ($dang_sua): ?>
                            <a href="tai-khoan.php" class="btn-admin btn-admin-secondary">Huỷ</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <div class="admin-panel">
                <h2>Danh sách tài khoản (<?php echo count($danh_sach); ?>)</h2>

                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Vai trò</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($danh_sach as $tk):
                                $la_chinh_minh = isset($_SESSION['account_id_admin']) && (int) $_SESSION['account_id_admin'] === (int) $tk['account_id'];
                            ?>
                                <tr>
                                    <td class="title-cell">
                                        <div class="account-name-cell">
                                            <span class="account-avatar"><?php echo htmlspecialchars(mb_substr($tk['account_name'], 0, 1)); ?></span>
                                            <span><?php echo htmlspecialchars($tk['account_name']); ?></span>
                                            <?php echo $la_chinh_minh ? '<span class="admin-badge on">Bạn</span>' : ''; ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($tk['account_email']); ?></td>
                                    <td><span class="role-badge role-<?php echo (int) $tk['account_type']; ?>"><?php echo htmlspecialchars($DS_VAI_TRO[(int) $tk['account_type']] ?? 'Không xác định'); ?></span></td>
                                    <td><?php echo date('d/m/Y', strtotime($tk['created_at'])); ?></td>
                                    <td>
                                        <div class="admin-actions">
                                            <a class="edit" href="tai-khoan.php?sua=<?php echo (int) $tk['account_id']; ?>#form-tai-khoan">Sửa</a>
                                            <a class="delete" href="xuly-tai-khoan.php?action=xoa&id=<?php echo (int) $tk['account_id']; ?>"
                                                onclick="return confirm('Xoá tài khoản này?');">Xoá</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>