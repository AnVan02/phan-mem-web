<?php 

$GLOBALS['page_specific_title'] = "TRA CỨU BẢO HÀNH ACHIVA";
$GLOBALS['page_specific_description'] = "Tra cứu thông tin bảo hành sản phẩm theo số serial tại Công Ty Cổ Phần ACHIVA, giúp khách hàng nhanh chóng kiểm tra thời hạn bảo hành, tình trạng bảo hành và các thông tin liên quan đến sản phẩm";
$GLOBALS['page_specific_keywords'] = "tra cứu bảo hành ACHIVA, tra cứu số serial ACHIVA, bảo hành ACHIVA ";

?>

<?php
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
        $stmt = $mysqli->prepare("SELECT * FROM sanpham WHERE SOSERIAL = ?");
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

?>
<div class="warranty-page-wrapper">
    <!-- Header/Hero Section -->
    <div class="warranty-hero-section">
        <div class="container">
            <div class="hero-inner">
                <h1 class="hero-main-title">TRA CỨU BẢO HÀNH ACHIVA</h1>
                <div class="hero-breadcrumb">
                    <a href="index.php">TRANG CHỦ</a> / <span>TRA CỨU BẢO HÀNH ACHIVA</span>
                    <p>Tra cứu thông tin bảo hành sản phẩm theo số serial tại Công Ty Cổ Phần ACHIVA, giúp khách hàng nhanh chóng kiểm tra thời hạn bảo hành, tình trạng bảo hành và các thông tin liên quan đến sản phẩm</p>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="warranty-content-section">
        <div class="container">
            <div class="warranty-card-container">
                <div class="card-inner">
                    <h1 class="card-title-main">TRA CỨU BẢO HÀNH ACHIVA</h1>
                    <div class="title-divider"></div>

                    <div class="search-box-container">
                        <form name="test" action="#" method="POST" class="warranty-search-form">
                            <div class="search-input-wrapper">
                                <input type="text" name="search" id="serial-search"
                                    placeholder="Nhập số serial của sản phẩm để tra cứu" required
                                    value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">
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
                        ?>
                                <div class="results-layout">
                                    <div class="result-card-item">
                                        <div class="result-details">
                                            <div class="detail-line">
                                                <span class="label">Số Serial:</span>
                                                <span
                                                    class="value serial-number"><?php echo htmlspecialchars($data['serial']); ?></span>
                                                <?php if (isset($data['isSpecial']) && $data['isSpecial']): ?>
                                                    
                                                <?php endif; ?>
                                            </div>
                                            <div class="detail-line">
                                                <span class="label">Mã Hàng:</span>
                                                <span
                                                    class="value"><?php echo htmlspecialchars($data['maHang'] ?? '—'); ?></span>
                                            </div>
                                            <div class="detail-line">
                                                <span class="label">Tên sản phẩm:</span>
                                                <span
                                                    class="value"><?php echo htmlspecialchars($data['tenHang'] ?? '—'); ?></span>
                                            </div>
                                            <div class="detail-line">
                                                <span class="label">Ngày Xuất:</span>
                                                <span class="value"><?php echo htmlspecialchars($ngayXuat); ?></span>
                                            </div>
                                            <div class="detail-line">
                                                <span class="label">Thời hạn bảo hành:</span>
                                                <span
                                                    class="value warranty-value"><?php
                                                        $soThang = $data['soThangBH'] ?? '0';
                                                        echo $soThang == -1 ? 'Bảo hành trọn đời' : htmlspecialchars($soThang) . ' tháng';
                                                    ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            } else {
                                echo '<div class="search-error-msg">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10 0C4.48 0 0 4.48 0 10C0 15.52 4.48 20 10 20C15.52 20 20 15.52 20 10C20 4.48 15.52 0 10 0ZM11 15H9V13H11V15ZM11 11H9V5H11V11Z" fill="#ff4d4f"/>
                                        </svg>
                                        <span>Không tìm thấy thông tin cho mã serial: ' . htmlspecialchars($search) . '</span>
                                      </div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Look & Feel Setup */
    .warranty-page-wrapper {
        font-family: Montserrat;
        color: #1a1a1a;
        width: 100%;
        background-color: #f8f9fa;
        min-height: 100vh;
    }

    .warranty-page-wrapper .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    /* Hero Banner - matching screenshot precisely */
    .warranty-hero-section {
        /*background: #004691;*/
        background-image: url('assets/images/banner/bao-hanh.webp');
        background-size: cover;
        background-position: center right;
        padding: 100px 0;
        color: #ffffff;
    }

    .warranty-hero-section .hero-inner {
        text-align: left;
    }

    /* ... (and so on for other styles) ... */

    .hero-main-title {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 15px;
        letter-spacing: -0.5px;
        text-transform: uppercase;
    }

    .hero-breadcrumb {
        font-size: 16px;
        color: rgba(255, 255, 255, 0.75);
        font-weight: 500;
    }

    .hero-breadcrumb a {
        color: inherit;
        text-decoration: none;
        transition: color 0.2s;
    }

    .hero-breadcrumb a:hover {
        color: #fff;
    }

    /* Card Content Area */
    .warranty-content-section {
        padding: 60px 0;
    }

    .warranty-card-container {
        max-width: 1100px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
        padding: 60px 40px;
    }

    .card-title-main {
        font-size: 28px;
        font-weight: 700;
        color: #DC2626;
        text-align: center;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .title-divider {
        width: 100px;
        height: 3px;
        background-color: #DC2626;
        margin: 0 auto 40px;
    }

    /* Search Box - refined */
    .search-box-container {
        max-width: 750px;
        margin: 0 auto;
    }

    .search-input-wrapper {
        display: flex;
        background: #fdfdfd;
        border: 1px solid #e0e6ed;
        border-radius: 100px;
        padding: 8px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .search-input-wrapper:focus-within {
        border-color: #0068b5;
        box-shadow: 0 0 0 4px rgba(0, 104, 181, 0.1);
        background: #fff;
    }

    .search-input-wrapper input {
        flex: 1;
        border: none;
        background: transparent;
        padding: 15px 30px;
        font-size: 16px;
        font-family: Montserrat;
        color: #333;
        outline: none;
        font-weight: 500;

    }

    .search-input-wrapper input::placeholder {
        color: #aeb9c6;
        font-weight: 400;
    }

    .warranty-submit-btn {
        background: #DC2626;
        color: #fff;
        border: none;
        border-radius: 100px;
        padding: 0 40px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(0, 104, 181, 0.2);
    }

    /*.warranty-submit-btn:hover {*/
    /*    background: #005a9e;*/
    /*    transform: translateY(-1px);*/
    /*    box-shadow: 0 6px 15px rgba(0, 104, 181, 0.3);*/
    /*}*/

    /* Results layout - professional */
    .warranty-results-area {
        margin-top: 50px;
    }

    .results-layout {
        display: grid;
        gap: 20px;
    }

    .result-card-item {
        background: #fcfdfe;
        border: 1px solid #f0f4f8;
        border-radius: 16px;
        padding: 30px;
        transition: transform 0.2s;
        border-color: #0068b5;
    }

    .detail-line {
        display: grid;
        grid-template-columns: 180px 1fr;
        padding: 15px 0;
        border-bottom: 1px solid #f0f4f8;
        gap: 15px;
        align-items: flex-start;
        /* Ensure label stays at top if info wraps */
    }

    .detail-line:last-child {
        border-bottom: none;
    }

    .label {
        font-size: 15px;
        font-weight: 500;
        color: #64748b;
    }

    .value {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
        text-align: left;
        /* Values are now left-aligned in their column */
    }

    .serial-number {
        color: #FF3300;
        /* Crimson Red */
    }

    .special-badge {
        display: inline-block;
        background: #fff7e6;
        color: #d46b08;
        border: 1px solid #ffd591;
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 4px;
        margin-left: 10px;
        font-weight: 600;
        text-transform: uppercase;
        vertical-align: middle;
    }

    .warranty-value {
        color: #1d4ed8;
        /* Corporate Blue */
    }

    .search-error-msg {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        background: #fff1f0;
        border: 1px solid #ffa39e;
        color: #cf1322;
        padding: 20px;
        border-radius: 12px;
        font-weight: 500;
        animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .warranty-card-container {
            padding: 30px 15px;
        }

        .search-input-wrapper {
            flex-direction: column;
            border-radius: 20px;
            padding: 10px;
        }

        .search-input-wrapper input {
            padding: 12px 15px;
            text-align: center;
        }

        .warranty-submit-btn {
            width: 100%;
            height: 50px;
            margin-top: 10px;
        }

        /* Sửa chính: chuyển grid thành flex column */
        .detail-line {
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 12px 0;
            text-align: left;
        }

        .label {
            font-size: 12px;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .value {
            font-size: 15px;
            font-weight: 700;
        }

        .result-card-item {
            padding: 20px 15px;
        }

        .hero-main-title {
            font-size: 22px;
        }

        .card-title-main {
            font-size: 20px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.warranty-search-form');
        const resultsArea = document.querySelector('.warranty-results-area');

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Ngăn chặn load lại trang

                const searchInput = document.querySelector('#serial-search').value;

                // Hiển thị trạng thái đang tải mượt mà
                resultsArea.innerHTML = `
                <div style="text-align:center; padding: 40px; animation: fadeIn 0.3s ease;">
                    <div style="display:inline-block; width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #DC2626; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                    <p style="margin-top: 15px; color: #64748b; font-weight: 500;">Đang tra cứu hệ thống vui lòng đợi...</p>
                </div>
                <style>@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }</style>
            `;

                // Tạo dữ liệu gửi đi 
                const formData = new FormData();
                formData.append('search', searchInput);

                // Fetch dữ liệu ngầm không load lại trang
                fetch(window.location.href, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(html => {
                        // Cắt lấy mỗi phần hiển thị kết quả để thay thế
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newResults = doc.querySelector('.warranty-results-area');

                        if (newResults) {
                            resultsArea.innerHTML = newResults.innerHTML;
                        } else {
                            resultsArea.innerHTML = '<div class="search-error-msg">Có lỗi hiển thị, vui lòng tải lại website!</div>';
                        }
                    })
                    .catch(error => {
                        resultsArea.innerHTML = '<div class="search-error-msg">Mất kết nối mạng, vui lòng thử lại!</div>';
                    });
            });
        }
    });
</script>