<?php
$page_title       = 'Sản phẩm - Viết Sơn Achieva';
$extra_css        = ['assets/css/bao-hanh.css'];
$post_css_scripts = ['assets/js/bao-hanh.js'];
require 'head.php';
?>

<?php
require_once 'admin/config/config.php';
include 'header.php';




function getWarrantyFromApi($serial)
{
    $url = 'https://baohanhvs.rosaoffice.com/api/warranty/serial/' . rawurlencode($serial);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $response) {
        return json_decode($response, true);
    }
    return null;
}

function getHinhAnhBySku($sku)
{
    global $pdo;
    if (!$pdo || !$sku)
        return null;

    try {
        $stmt = $pdo->prepare("SELECT hinh_anh FROM san_pham WHERE sku = ? LIMIT 1");
        $stmt->execute([$sku]);
        $hinhAnhRaw = $stmt->fetchColumn();

        if (!empty($hinhAnhRaw)) {
            $anhList = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', $hinhAnhRaw))));
            if (!empty($anhList)) {
                return preg_match('#^https?://#i', $anhList[0]) ? $anhList[0] : asset_url($anhList[0]);
            }
        }
    } catch (Exception $e) {
        return null;
    }

    return null;
}

function getWarrantyFromDb($serial)
{
    global $pdo;
    if (!$pdo)
        return null;

    try {
        $stmt = $pdo->prepare("
            SELECT bh.*, sp.hinh_anh AS SP_HINHANH
            FROM bao_hanh bh
            LEFT JOIN san_pham sp ON sp.sku = bh.MAHANG
            WHERE bh.SOSERIAL = ?
        ");
        $stmt->execute([$serial]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Ưu tiên ảnh từ bảng san_pham, fallback về hinh_anh trong bao_hanh
            $hinhAnhRaw = !empty($row['SP_HINHANH']) ? $row['SP_HINHANH'] : ($row['hinh_anh'] ?? null);

            // hinh_anh có thể chứa nhiều ảnh cách nhau bởi , hoặc ; -> lấy ảnh đầu tiên
            $hinhAnh = null;
            if (!empty($hinhAnhRaw)) {
                $anhList = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', $hinhAnhRaw))));
                if (!empty($anhList)) {
                    // Ảnh nội bộ (path tương đối) không cần qua proxy, chỉ ảnh URL tuyệt đối mới cần
                    $hinhAnh = preg_match('#^https?://#i', $anhList[0]) ? $anhList[0] : asset_url($anhList[0]);
                }
            }

            return [
                'serial' => $row['SOSERIAL'],
                'maHang' => $row['MAHANG'],
                'tenHang' => $row['TENHANG'],
                'ngayNhapKho' => $row['NGAYXUAT'],
                'soThangBH' => $row['THOIHANBH'],
                'hinhAnh' => $hinhAnh,
                'isSpecial' => true
            ];
        }
    } catch (Exception $e) {
        return null;
    }

    return null;
}

function parseVnDate($str)
{
    if (!$str || $str === '—')
        return null;

    foreach (['Y-m-d H:i:s', 'Y-m-d', 'd/m/Y H:i:s', 'd/m/Y'] as $format) {
        $date = DateTime::createFromFormat($format, $str);
        if ($date !== false) {
            $date->setTime(0, 0, 0); // ép về 00:00:00 để tính "ngày" chính xác
            return $date;
        }
    }

    $ts = strtotime($str);
    if ($ts !== false) {
        $date = (new DateTime())->setTimestamp($ts);
        $date->setTime(0, 0, 0); // đồng bộ, tránh lệch giờ
        return $date;
    }
    return null;
}
?>

<div class="warranty-page-wrapper">

    <!-- Hero -->
    <section class="about-hero">
        <div class="container about-hero-inner">
            <span class="about-hero-eyebrow">Bảo hành </span>
            <h1 class="about-hero-title">Tra cứu bảo hành ACHIVA </h1>
            <p class="about-hero-subtitle">Nhập số serial linh kiện để tra cứu tình trạng bảo hành chỉ trong vài giây.
                Được xây dựng từ nền tảng gần 40 năm kinh nghiệm, ACHIVA tự hào là thương hiệu công nghệ hàng đầu Việt Nam.</p>
            <div class="about-hero-arrows">
                <i class="fa-solid fa-play"></i>
                <i class="fa-solid fa-play"></i>
                <i class="fa-solid fa-play"></i>
            </div>
        </div>
        <span class="about-hero-year">1990</span>
    </section>

    <!-- Main Content Section -->
    <div class="warranty-content-section">
        <div class="container">
            <div class="warranty-card-container">
                <div class="card-inner">
                    <h2 class="card-title-main">TRA CỨU BẢO HÀNH ACHIVA</h2>
                    <div class="title-divider"></div>
                    <p class="card-subtitle">Nhập số Serial để kiểm tra thông tin bảo hành sản phẩm chính hãng</p>

                    <div class="search-box-container">
                        <form name="test" action="#" method="POST" class="warranty-search-form">
                            <div class="search-input-wrapper">
                                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                                <input type="text" name="search" id="serial-search"
                                    placeholder="Nhập số Serial..." required autocomplete="off"
                                    value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">
                                <button type="button" class="clear-search-btn" id="clear-search"
                                    aria-label="Xóa"><i class="fa-solid fa-xmark"></i></button>
                                <button type="submit" class="warranty-submit-btn">Kiểm tra</button>
                            </div>
                        </form>
                    </div>

                    <!-- Results Area -->
                    <div class="warranty-results-area">
                        <?php
                        if (isset($_POST['search'])) {
                            $search = trim($_POST['search']);

                            // 1. Ưu tiên kiểm tra DB (dành cho các trường hợp nhập thủ công / đặc biệt)
                            $data = getWarrantyFromDb($search);

                            // 2. Nếu không tìm thấy, gọi API của S1
                            if (!$data) {
                                $data = getWarrantyFromApi($search);
                                // API không trả ảnh -> tra thêm ảnh theo mã hàng trong DB nội bộ
                                if ($data && empty($data['hinhAnh'])) {
                                    $data['hinhAnh'] = getHinhAnhBySku($data['maHang'] ?? null);
                                }
                            }

                            if ($data) {
                                // Ngày xuất: ưu tiên ngày bán cho KH, fallback về ngày nhập kho
                                $ngayXuat = $data['baoHanhKH']['ngayBan'] ?? $data['ngayNhapKho'] ?? '—';
                                $ngayXuatDate = parseVnDate($ngayXuat);
                                $ngayXuatLabel = $ngayXuatDate ? $ngayXuatDate->format('d/m/Y') : htmlspecialchars($ngayXuat);

                                $soThang = $data['soThangBH'] ?? '0';
                                $isLifetime = ($soThang == -1);

                                $ngayHetHanLabel = '—';
                                $remainingBadge = '';
                                $expiryStateClass = 'is-lifetime';
                                if ($isLifetime) {
                                    $ngayHetHanLabel = 'Trọn đời';
                                } elseif ($ngayXuatDate) {
                                    $expiryDate = clone $ngayXuatDate;
                                    $expiryDate->modify('+' . intval($soThang) . ' months');
                                    $ngayHetHanLabel = $expiryDate->format('d/m/Y');

                                    $today = new DateTime('today');
                                    $diffDays = (int) $today->diff($expiryDate)->format('%r%a');

                                    if ($diffDays >= 0) {
                                        $expiryStateClass = 'is-active';
                                        $remainingBadge = '<span class="expiry-badge expiry-ok"><i class="fa-regular fa-clock"></i> Còn ' . $diffDays . ' ngày</span>';
                                    } else {
                                        $expiryStateClass = 'is-expired';
                                        $remainingBadge = '<span class="expiry-badge expiry-expired"><i class="fa-regular fa-clock"></i> Hết hạn ' . abs($diffDays) . ' ngày trước</span>';
                                    }
                                }
                        ?>
                                <div class="valid-msg">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>Serial hợp lệ! Đây là sản phẩm chính hãng.</span>
                                </div>

                                <div class="results-layout">
                                    <div class="result-card-item">
                                        <div class="result-media">
                                            <div class="result-media-box">
                                                <?php if (!empty($data['hinhAnh'])):
                                                    $isAbsoluteUrl = preg_match('#^https?://#i', $data['hinhAnh']);
                                                    $imgSrc = $isAbsoluteUrl
                                                        ? 'image-proxy.php?src=' . urlencode($data['hinhAnh'])
                                                        : $data['hinhAnh'];
                                                ?>
                                                    <img src="<?php echo htmlspecialchars($imgSrc); ?>"
                                                        alt="<?php echo htmlspecialchars($data['tenHang'] ?? 'Sản phẩm'); ?>"
                                                        class="product-img">
                                                <?php else: ?>
                                                    <i class="fa-solid fa-hard-drive"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="result-media-caption">
                                                <i class="fa-solid fa-circle-check"></i>
                                                <div>
                                                    <strong>Sản phẩm chính hãng</strong>
                                                    <span>Được phân phối chính hãng tại Việt Nam</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="result-details">
                                            <div class="detail-line">
                                                <span class="detail-"><img src= "https://img.icons8.com/external-flaticons-lineal-color-flat-icons/40/external-serial-number-gaming-ecommerce-flaticons-lineal-color-flat-icons-2.png"></span>
                                                <span class="label">Số Serial</span>
                                                <span
                                                    class="value serial-number"><?php echo htmlspecialchars($data['serial']); ?></span>
                                            </div>
                                            <div class="detail-line">
                                                <span class="detail-"><img src= "https://img.icons8.com/color/40/qr-code--v1.png"></span>
                                                <span class="label">Mã Hãng</span>
                                                <span
                                                    class="value"><?php echo htmlspecialchars($data['maHang'] ?? '—'); ?></span>
                                            </div>
                                            <div class="detail-line">
                                                <span class="detail"><img src= "https://img.icons8.com/fluency/40/product.png"></span>
                                                <span class="label">Tên sản phẩm</span>
                                                <span
                                                    class="value"><?php echo htmlspecialchars($data['tenHang'] ?? '—'); ?></span>
                                            </div>
                                            <div class="detail-line">
                                                <span class="detail"><img src= "https://img.icons8.com/color/40/overtime.png"></span>
                                                <span class="label">Ngày Xuất</span>
                                                <span class="value"><?php echo $ngayXuatLabel; ?></span>
                                            </div>
                                            <div class="detail-line">
                                                <span class="detail"><img src= "https://img.icons8.com/fluency/40/warranty--v1.png"></span>
                                                <span class="label">Thời hạn bảo hành</span>
                                                <span
                                                    class="value warranty-value"><?php
                                                                                    echo $isLifetime ? 'Bảo hành trọn đời' : htmlspecialchars($soThang) . ' tháng';
                                                                                    ?></span>
                                            </div>
                                            <div class="detail-line">
                                                <span class="detail"><img src= "https://img.icons8.com/external-flaticons-lineal-color-flat-icons/40/external-expiration-date-medical-ecommerce-flaticons-lineal-color-flat-icons.png"></span>
                                                <span class="label">Ngày hết hạn</span>
                                                <span class="value expiry-value <?php echo $expiryStateClass; ?>">
                                                    <?php echo htmlspecialchars($ngayHetHanLabel); ?>
                                                    <?php echo $remainingBadge; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="warranty-notice-bar">
                                    <i class="fa-solid fa-circle-info"></i>
                                    <span><strong>Lưu ý:</strong> Vui lòng giữ lại hóa đơn/phiếu mua hàng để được hỗ
                                        trợ bảo hành nhanh chóng.</span>
                                </div>
                        <?php
                            } else {
                                echo '<div class="search-error-msg">
                                        <i class="fa-solid fa-circle-exclamation"></i>
                                        <span>Mã serial: ' . htmlspecialchars($search) . ' không hợp lệ. Đây không phải là sản phẩm chính hãng</span>
                                      </div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="warranty-features-row">
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                    <div class="feature-text">
                        <h4>Bảo hành chính hãng</h4>
                        <p>Sản phẩm được bảo hành tại TT bảo hành chính hãng</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-headset"></i></div>
                    <div class="feature-text">
                        <h4>Hỗ trợ 24/7</h4>
                        <p>Đội ngũ hỗ trợ sẵn sàng giải đáp mọi thắc mắc</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-phone"></i></div>
                    <div class="feature-text">
                        <h4>Hotline</h4>
                        <p class="feature-highlight"> 0936699336 </p>
                        <p class="feature-highlight">(028)39260996 </p>
                        <p class="feature-sub">(8:00 - 17:30, T2 - T7)</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-globe"></i></div>
                    <div class="feature-text">
                        <h4>Website</h4>
                        <p class="feature-highlight"><a href="https://www.vietsontdc.com/"
                                target="_blank">www.vietsontdc.com</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require 'footer.php' ?>