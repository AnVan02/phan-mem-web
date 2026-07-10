
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm - Viết Sơn Achieva</title>
    <link rel="shortcut icon" href="assets/images/icon/logo VS_icon.jpg" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script src="assets/js/header.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/bao-hanh.css">
    <script src="assets/js/bao-hanh.js" defer></script>

    <!--
        Ghi chú: khối CSS bên dưới dành riêng cho sidebar "Danh mục sản phẩm" vừa thêm.
        Bạn có thể copy phần này qua assets/css/san-pham.css rồi xoá thẻ <style> này đi,
        và chỉnh lại màu sắc (--vs-primary...) cho khớp theme hiện tại của site.
    -->
</head>

<body>
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

function getWarrantyFromDb($serial)
{
    global $mysqli;
    if (!$mysqli)
        return null;

    try {
        $stmt = $mysqli->prepare("SELECT * FROM bao_hanh WHERE SOSERIAL = ?");
        $stmt->bind_param("s", $serial);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            return [
                'serial' => $row['SOSERIAL'],
                'maHang' => $row['MAHANG'],
                'tenHang' => $row['TENHANG'],
                'ngayNhapKho' => $row['NGAYXUAT'],
                'soThangBH' => $row['THOIHANBH'],
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
            return $date;
        }
    }

    $ts = strtotime($str);
    return $ts !== false ? (new DateTime())->setTimestamp($ts) : null;
}
?>

<div class="warranty-page-wrapper">

    <!-- Main Content Section -->
    <div class="warranty-content-section">
        <div class="container">
            <div class="warranty-card-container">
                <div class="card-inner">
                    <h1 class="card-title-main">TRA CỨU BẢO HÀNH ACHIVA</h1>
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
                                                <i class="fa-solid fa-hard-drive"></i>
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
                                                <span class="detail-icon"><i class="fa-solid fa-barcode"></i></span>
                                                <span class="label">Số Serial</span>
                                                <span
                                                    class="value serial-number"><?php echo htmlspecialchars($data['serial']); ?></span>
                                            </div>
                                            <div class="detail-line">
                                                <span class="detail-icon"><i class="fa-solid fa-tag"></i></span>
                                                <span class="label">Mã Hãng</span>
                                                <span
                                                    class="value"><?php echo htmlspecialchars($data['maHang'] ?? '—'); ?></span>
                                            </div>
                                            <div class="detail-line">
                                                <span class="detail-icon"><i class="fa-solid fa-cube"></i></span>
                                                <span class="label">Tên sản phẩm</span>
                                                <span
                                                    class="value"><?php echo htmlspecialchars($data['tenHang'] ?? '—'); ?></span>
                                            </div>
                                            <div class="detail-line">
                                                <span class="detail-icon"><i class="fa-solid fa-calendar-days"></i></span>
                                                <span class="label">Ngày Xuất</span>
                                                <span class="value"><?php echo $ngayXuatLabel; ?></span>
                                            </div>
                                            <div class="detail-line">
                                                <span class="detail-icon"><i class="fa-solid fa-shield-halved"></i></span>
                                                <span class="label">Thời hạn bảo hành</span>
                                                <span
                                                    class="value warranty-value"><?php
                                                        echo $isLifetime ? 'Bảo hành trọn đời' : htmlspecialchars($soThang) . ' tháng';
                                                    ?></span>
                                            </div>
                                            <div class="detail-line">
                                                <span class="detail-icon"><i class="fa-solid fa-calendar-check"></i></span>
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
                        <p class="feature-highlight">1900 1234</p>
                        <p class="feature-sub">(8:00 - 18:00, T2 - CN)</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-globe"></i></div>
                    <div class="feature-text">
                        <h4>Website</h4>
                        <p class="feature-highlight"><a href="https://www.vietson.com.vn"
                                target="_blank">www.vietson.com.vn</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
