<?php
    require_once '../config/config.php';
    yeu_cau_dang_nhap([VAI_TRO_QUAN_TRI], '../dang-nhap.php');

    $tu_khoa = isset($_GET['q']) ? trim($_GET['q']) : '';
    $vai_tro = isset($_GET['role']) && $_GET['role'] !== '' ? $_GET['role'] : 'all';

    $sql_where = "WHERE 1=1";
    $tham_so = [];
    
    if ($tu_khoa !== '') {
        $sql_where .= " AND (account_name LIKE :q OR account_email LIKE :q)";
        $tham_so[':q'] = '%' . $tu_khoa . '%';
    }
    
    if ($vai_tro !== 'all') {
        $sql_where .= " AND account_type = :role";
        $tham_so[':role'] = (int) $vai_tro;
    }

    $dem_stmt = $pdo->prepare("SELECT COUNT(*) FROM account $sql_where");
    $dem_stmt->execute($tham_so);
    $tong_so = (int) $dem_stmt->fetchColumn();

    $so_dong = 10;
    $tong_so_trang = max(1, (int) ceil($tong_so / $so_dong));
    $trang_hien_tai = isset($_GET['trang']) ? (int) $_GET['trang'] : 1;
    if ($trang_hien_tai < 1) $trang_hien_tai = 1;
    if ($trang_hien_tai > $tong_so_trang) $trang_hien_tai = $tong_so_trang;
    $bat_dau = ($trang_hien_tai - 1) * $so_dong;

    $danh_sach_stmt = $pdo->prepare("SELECT * FROM account $sql_where ORDER BY account_id DESC LIMIT :gioi_han OFFSET :bat_dau");
    foreach ($tham_so as $k => $v) {
        $danh_sach_stmt->bindValue($k, $v);
    }
    $danh_sach_stmt->bindValue(':gioi_han', $so_dong, PDO::PARAM_INT);
    $danh_sach_stmt->bindValue(':bat_dau', $bat_dau, PDO::PARAM_INT);
    $danh_sach_stmt->execute();
    $danh_sach = $danh_sach_stmt->fetchAll(PDO::FETCH_ASSOC);

    $sua_id = isset($_GET['sua']) ? (int) $_GET['sua'] : 0;
    $dang_sua = null;
    if ($sua_id > 0) {
        $stmt_sua = $pdo->prepare("SELECT * FROM account WHERE account_id = :id");
        $stmt_sua->execute([':id' => $sua_id]);
        $dang_sua = $stmt_sua->fetch(PDO::FETCH_ASSOC);
    }

    function xay_url_trang($trang) {
        $params = $_GET;
        $params['trang'] = $trang;
        return 'tai-khoan.php?' . http_build_query($params);
    }

    $thong_bao = [
        'da_them'               => ['success', 'Đã thêm tài khoản quản trị mới.'],
        'da_sua'                => ['success', 'Đã cập nhật tài khoản.'],
        'da_xoa'                => ['success', 'Đã xoá tài khoản.'],
        'loi_thieu_du_lieu'     => ['error',   'Vui lòng nhập đầy đủ tên, email và mật khẩu.'],
        'loi_email_ton_tai'     => ['error',   'Email này đã được dùng cho tài khoản khác.'],
        'loi_mat_khau_ngan'     => ['error',   'Mật khẩu phải có ít nhất 6 ký tự.'],
        'loi_tu_xoa'            => ['error',   'Không thể xoá chính tài khoản đang đăng nhập.'],
        'loi_xoa_quan_tri_cuoi' => ['error',   'Không thể xoá — hệ thống cần ít nhất 1 tài khoản Quản trị viên.'],
        'loi_anh'               => ['error',   'Ảnh đại diện không hợp lệ (chỉ nhận jpg, jpeg, png, webp, gif).'],
    ];
    $msg = isset($_GET['msg']) && isset($thong_bao[$_GET['msg']]) ? $thong_bao[$_GET['msg']] : null;

    $ADMIN_ROOT  = '../';
    $active_page = 'tai-khoan';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quan tri tai khoan - Admin</title>
    <link rel="shortcut icon" href="../../assets/images/icon/logo VS_icon.jpg" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-layout.css">
    <link rel="stylesheet" href="../assets/css/tai-khoan.css">
</head>
<body>
<div class="admin-shell">
    <?php include '../includes/sidebar.php'; ?>
    <main class="admin-main">

        <div class="tk-page-header">
            <div>
                <h1 class="tk-page-title">Quản trị tài khoản</h1>
                <p class="tk-page-subtitle"><span class="tk-dot"></span> Quản lý tài khoản quản trị hệ thống</p>
            </div>
        </div>

        <?php if ($msg): ?>
            <div class="tk-flash tk-flash-<?php echo $msg[0]; ?>">
                <i class="fa-solid <?php echo $msg[0] === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'; ?>"></i>
                <?php echo htmlspecialchars($msg[1]); ?>
            </div>
        <?php endif; ?>

        <div class="tk-card" id="form-tai-khoan">
            <div class="tk-card-header">
                <div class="tk-card-icon"><i class="fa-solid fa-user-plus"></i></div>
                <h2 class="tk-card-title"><?php echo $dang_sua ? 'SỬA TÀI KHOẢN' : 'THÊM TÀI KHOẢN QUẢN TRỊ MỚI'; ?></h2>
            </div>
            <div class="tk-form-layout">
                <div class="tk-form-main">
                    <form action="xuly-tai-khoan.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="<?php echo $dang_sua ? 'sua' : 'them'; ?>">
                        <?php if ($dang_sua): ?>
                            <input type="hidden" name="id" value="<?php echo (int)$dang_sua['account_id']; ?>">
                        <?php endif; ?>

                        <div class="tk-field-group">
                            <label class="tk-label">Ảnh đại diện</label>
                            <div class="tk-dropzone" id="tkDropzone">
                                <img src="<?php echo ($dang_sua && !empty($dang_sua['account_avatar'])) ? '../../'.htmlspecialchars($dang_sua['account_avatar']) : ''; ?>"
                                     class="tk-dropzone-preview" id="tkPreview" alt=""
                                     style="<?php echo ($dang_sua && !empty($dang_sua['account_avatar'])) ? '' : 'display:none'; ?>">
                                <div class="tk-dropzone-placeholder" id="tkPlaceholder"
                                     style="<?php echo ($dang_sua && !empty($dang_sua['account_avatar'])) ? 'display:none' : ''; ?>">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    <p>Kéo thả ảnh vào đây</p>
                                    <span>hoặc click để chọn tệp<br>JPG, PNG tối đa 2MB</span>
                                </div>
                                <input type="file" name="account_avatar_file" id="tkAvatarInput"
                                       accept=".jpg,.jpeg,.png,.webp,.gif" class="tk-file-input">
                            </div>
                        </div>

                        <div class="tk-field-group">
                            <label class="tk-label" for="tkName">Họ tên</label>
                            <input type="text" id="tkName" name="account_name" class="tk-input"
                                placeholder="Nhập họ và tên"
                                value="<?php echo $dang_sua ? htmlspecialchars($dang_sua['account_name']) : ''; ?>" required>
                        </div>

                        <div class="tk-field-row">
                            <div class="tk-field-group">
                                <label class="tk-label" for="tkEmail">Email đăng nhập</label>
                                <input type="email" id="tkEmail" name="account_email" class="tk-input"
                                    placeholder="Nhập email"
                                    value="<?php echo $dang_sua ? htmlspecialchars($dang_sua['account_email']) : ''; ?>" required>
                            </div>
                            <div class="tk-field-group">
                                <label class="tk-label" for="tkRole">Vai tr&ograve;</label>
                                <select id="tkRole" name="account_type" class="tk-select">
                                    <option value="" disabled <?php echo !$dang_sua ? 'selected' : ''; ?>>Chọn vai trò</option>
                                    <?php foreach ($DS_VAI_TRO as $ma_vt => $ten_vt): ?>
                                        <option value="<?php echo $ma_vt; ?>"
                                            <?php echo ($dang_sua && (int)$dang_sua['account_type'] === $ma_vt) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($ten_vt); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="tk-field-group">
                            <label class="tk-label" for="tkPass">
                                Mật khẩu
                                <?php if ($dang_sua): ?><span class="tk-label-hint">(bỏ trống nếu không đổi)</span><?php endif; ?>
                            </label>
                            <div class="tk-input-wrap">
                                <input type="password" id="tkPass" name="account_password" class="tk-input"
                                    placeholder="Nh&#7853;p Mật khẩu (&#237;t nh&#7845;t 6 k&#253; t&#7921;)"
                                    <?php echo $dang_sua ? '' : 'required'; ?>>
                                <button type="button" class="tk-eye-btn" id="tkEyeBtn">
                                    <i class="fa-regular fa-eye" id="tkEyeIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="tk-form-actions">
                            <button type="submit" class="tk-btn-primary">
                                <i class="fa-solid <?php echo $dang_sua ? 'fa-floppy-disk' : 'fa-plus'; ?>"></i>
                                <?php echo $dang_sua ? 'Lưu thay đổi' : 'Thêm tài khoản'; ?>
                            </button>
                            <?php if ($dang_sua): ?>
                                <a href="tai-khoan.php" class="tk-btn-secondary"><i class="fa-solid fa-xmark"></i> Hủy</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <div class="tk-security-panel">
                    <div class="tk-security-icon-wrap">
                        <div class="tk-security-shield"><i class="fa-solid fa-shield-halved"></i></div>
                        <div class="tk-security-lock"><i class="fa-solid fa-lock"></i></div>
                    </div>
                    <h3 class="tk-security-title">Quản lý an toàn</h3>
                    <p class="tk-security-desc">Th&#234;m v&#224; Quản lý tài khoản quản trị hệ thống m&#7897;t c&#225;ch d&#7877; d&#224;ng v&#224; b&#7843;o m&#7853;t.</p>
                </div>
            </div>
        </div>

        <div class="tk-card tk-table-card">
            <div class="tk-table-header">
                <div class="tk-table-title-wrap">
                    <div class="tk-card-icon tk-card-icon--list"><i class="fa-solid fa-list"></i></div>
                    <h2 class="tk-card-title">DANH SÁCH TÀI KHOẢN (<?php echo $tong_so; ?>)</h2>
                </div>
                <div class="tk-table-tools">
                    <div class="tk-search-wrap">
                        <form action="" method="GET" style="display: flex; width: 100%;">
                            <input type="text" name="q" class="tk-search-input" placeholder="Tìm kiếm theo Họ tên hoặc email..." value="<?php echo htmlspecialchars($tu_khoa); ?>">
                            <input type="hidden" name="role" value="<?php echo htmlspecialchars($vai_tro); ?>">
                            <button type="submit" style="background:none; border:none; margin-left: -30px; cursor:pointer;"><i class="fa-solid fa-magnifying-glass tk-search-icon" style="position: static;"></i></button>
                        </form>
                    </div>
                    <button class="tk-filter-btn" id="tkFilterBtn">
                        <i class="fa-solid fa-sliders"></i> Bộ lọc
                    </button>
                </div>
            </div>

            <div class="tk-filter-panel" id="tkFilterPanel" style="<?php echo $vai_tro !== 'all' ? '' : 'display:none;'; ?>">
                <label class="tk-label">Lọc theo vai trò:</label>
                <div class="tk-filter-roles">
                    <a href="tai-khoan.php?q=<?php echo urlencode($tu_khoa); ?>&role=all" class="tk-filter-role <?php echo $vai_tro === 'all' ? 'active' : ''; ?>" style="text-decoration: none;">Tất cả</a>
                    <?php foreach ($DS_VAI_TRO as $ma_vt => $ten_vt): ?>
                        <a href="tai-khoan.php?q=<?php echo urlencode($tu_khoa); ?>&role=<?php echo $ma_vt; ?>" class="tk-filter-role <?php echo (string)$vai_tro === (string)$ma_vt ? 'active' : ''; ?>" style="text-decoration: none;"><?php echo htmlspecialchars($ten_vt); ?></a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="tk-table-wrap">
                <table class="tk-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Vai tr&ograve;</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="tkTbody">
                        <?php foreach ($danh_sach as $i => $tk):
                            $la_chinh_minh = isset($_SESSION['account_id_admin']) && (int)$_SESSION['account_id_admin'] === (int)$tk['account_id'];
                            $initial = mb_strtoupper(mb_substr($tk['account_name'], 0, 1, 'UTF-8'), 'UTF-8');
                        ?>
                            <tr class="tk-row"
                                data-name="<?php echo htmlspecialchars(mb_strtolower($tk['account_name'],'UTF-8')); ?>"
                                data-email="<?php echo htmlspecialchars(strtolower($tk['account_email'])); ?>"
                                data-role="<?php echo (int)$tk['account_type']; ?>">
                                <td class="tk-td-num"><?php echo $i + 1; ?></td>
                                <td>
                                    <div class="tk-name-cell">
                                        <?php if (!empty($tk['account_avatar'])): ?>
                                            <span class="tk-avatar tk-avatar-img"><img src="../../<?php echo htmlspecialchars($tk['account_avatar']); ?>" alt=""></span>
                                        <?php else: ?>
                                            <span class="tk-avatar"><?php echo $initial; ?></span>
                                        <?php endif; ?>
                                        <span><?php echo htmlspecialchars($tk['account_name']); ?></span>
                                        <?php if ($la_chinh_minh): ?><span class="tk-badge-you">Bạn</span><?php endif; ?>
                                    </div>
                                </td>
                                <td class="tk-td-email"><?php echo htmlspecialchars($tk['account_email']); ?></td>
                                <td>
                                    <span class="tk-role-badge tk-role-<?php echo (int)$tk['account_type']; ?>">
                                        <i class="fa-solid fa-crown"></i>
                                        <?php echo htmlspecialchars($DS_VAI_TRO[(int)$tk['account_type']] ?? 'Khong xac dinh'); ?>
                                    </span>
                                </td>
                                <td class="tk-td-date"><?php echo date('d/m/Y', strtotime($tk['created_at'])); ?></td>
                                <td>
                                    <div class="tk-actions">
                                        <a href="tai-khoan.php?sua=<?php echo (int)$tk['account_id']; ?>#form-tai-khoan"
                                           class="tk-action-btn tk-action-edit" title="Sua">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <a href="xuly-tai-khoan.php?action=xoa&id=<?php echo (int)$tk['account_id']; ?>"
                                           class="tk-action-btn tk-action-delete" title="Xoa"
                                           onclick="return confirm('Xoa tai khoan nay?');">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php if (count($danh_sach) === 0): ?>
                <div class="tk-empty" id="tkEmpty" style="display:flex">
                    <i class="fa-solid fa-users-slash"></i><p>Không tìm thấy tài khoản nào.</p>
                </div>
                <?php endif; ?>
            </div>

            <?php if ($tong_so_trang > 1): ?>
            <div class="tk-pagination">
                <span class="tk-paging-info">Hiển thị <?php echo $bat_dau + 1; ?> đến <?php echo min($bat_dau + $so_dong, $tong_so); ?> của <?php echo $tong_so; ?> kết quả</span>
                <div class="tk-paging-controls">
                    <a href="<?php echo xay_url_trang(max(1, $trang_hien_tai - 1)); ?>" class="tk-page-btn <?php echo $trang_hien_tai <= 1 ? 'disabled' : ''; ?>" style="text-decoration:none;"><i class="fa-solid fa-chevron-left"></i></a>
                    <div class="tk-page-numbers">
                        <?php for ($p = 1; $p <= $tong_so_trang; $p++): ?>
                            <a href="<?php echo xay_url_trang($p); ?>" class="tk-page-num <?php echo $p === $trang_hien_tai ? 'active' : ''; ?>" style="text-decoration:none;"><?php echo $p; ?></a>
                        <?php endfor; ?>
                    </div>
                    <a href="<?php echo xay_url_trang(min($tong_so_trang, $trang_hien_tai + 1)); ?>" class="tk-page-btn <?php echo $trang_hien_tai >= $tong_so_trang ? 'disabled' : ''; ?>" style="text-decoration:none;"><i class="fa-solid fa-chevron-right"></i></a>
                </div>
            </div>
            <?php endif; ?>
        </div>

    </main>
</div>
<script>
(function(){
    var dz=document.getElementById('tkDropzone'),fi=document.getElementById('tkAvatarInput'),
        pv=document.getElementById('tkPreview'),ph=document.getElementById('tkPlaceholder');
    function showPv(f){if(!f||!f.type.startsWith('image/'))return;var r=new FileReader();r.onload=function(e){pv.src=e.target.result;pv.style.display='block';if(ph)ph.style.display='none';};r.readAsDataURL(f);}
    if(dz){dz.addEventListener('click',function(){fi.click();});fi.addEventListener('change',function(){showPv(fi.files[0]);});dz.addEventListener('dragover',function(e){e.preventDefault();dz.classList.add('tk-drag-over');});dz.addEventListener('dragleave',function(){dz.classList.remove('tk-drag-over');});dz.addEventListener('drop',function(e){e.preventDefault();dz.classList.remove('tk-drag-over');var f=e.dataTransfer.files[0];if(f){fi.files=e.dataTransfer.files;showPv(f);}});}
    var eb=document.getElementById('tkEyeBtn'),pi=document.getElementById('tkPass'),ei=document.getElementById('tkEyeIcon');
    if(eb){eb.addEventListener('click',function(){var s=pi.type==='password';pi.type=s?'text':'password';ei.className=s?'fa-regular fa-eye-slash':'fa-regular fa-eye';});}
    var fb=document.getElementById('tkFilterBtn'),fp=document.getElementById('tkFilterPanel');
    if(fb){fb.addEventListener('click',function(){var o=fp.style.display!=='none';fp.style.display=o?'none':'block';fb.classList.toggle('active',!o);});}
})();
</script>
</body>
</html>
