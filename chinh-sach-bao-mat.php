<?php
require_once __DIR__ . '/admin/config/config.php';

$page_title = 'Bảo mật và tuân thủ - Viết Sơn Achieva';
$meta_robots = 'index, follow';
$extra_css = ['assets/css/chinh-sach-bao-mat.css'];
require 'head.php';
include 'header.php';
?>
<main class="security-page">
    <section class="security-hero">
        <div class="security-container security-hero__content">
            <span class="security-kicker"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i> Minh bạch bảo mật</span>
            <h1>Bảo mật và tuân thủ</h1>
            <p>Thông tin rõ ràng về cách Việt Sơn Achieva bảo vệ dữ liệu khách hàng, phạm vi kiểm soát hiện có và những nội dung chưa được công bố.</p>
            <p class="security-updated">Cập nhật lần cuối: 04/09/2026</p>
        </div>
    </section>

    <div class="security-container security-body">
        <section class="security-section" id="tom-tat">
            <div class="security-section__heading"><span class="security-index">00</span><div><p class="security-label">Tóm tắt dễ hiểu</p><h2>Bạn cần biết gì?</h2></div></div>
            <p>Chúng tôi thu thập thông tin cần thiết để tạo tài khoản, xử lý đơn hàng, giao hàng, bảo hành và hỗ trợ khách hàng. Chúng tôi không bán dữ liệu cá nhân. Bạn có thể yêu cầu xem, chỉnh sửa hoặc xóa dữ liệu bằng cách liên hệ <a href="mailto:support@vietsontdc.com">support@vietsontdc.com</a>.</p>
        </section>

        <section class="security-section" id="du-lieu">
            <div class="security-section__heading"><span class="security-index">00</span><div><p class="security-label">Quyền riêng tư</p><h2>Dữ liệu nào được thu thập?</h2></div></div>
            <div class="security-info-grid">
                <article class="security-info-card"><i class="fa-solid fa-address-card" aria-hidden="true"></i><div><h3>Dữ liệu bạn cung cấp</h3><p>Họ tên, email, số điện thoại, địa chỉ giao hàng, nội dung liên hệ, thông tin tài khoản và lịch sử đơn hàng.</p></div></article>
                <article class="security-info-card"><i class="fa-solid fa-bullseye" aria-hidden="true"></i><div><h3>Mục đích sử dụng</h3><p>Xác thực tài khoản, xử lý đơn hàng, giao hàng, bảo hành, hỗ trợ và cải thiện dịch vụ. Chỉ dùng trong phạm vi cần thiết.</p></div></article>
                <article class="security-info-card"><i class="fa-solid fa-hourglass-half" aria-hidden="true"></i><div><h3>Thời gian lưu giữ</h3><p>Lưu trong thời gian cần thiết cho giao dịch, bảo hành, kế toán và nghĩa vụ pháp lý; sau đó sẽ xóa hoặc ẩn danh khi không còn nhu cầu.</p></div></article>
                <article class="security-info-card" id="yeu-cau-xoa"><i class="fa-solid fa-user-minus" aria-hidden="true"></i><div><h3>Yêu cầu xóa dữ liệu</h3><p>Gửi yêu cầu đến <a href="mailto:support@vietsontdc.com">support@vietsontdc.com</a>. Chúng tôi có thể cần xác minh danh tính và giữ lại phần dữ liệu bắt buộc theo pháp luật.</p></div></article>
            </div>
        </section>

        <section class="security-section" id="compliance">
            <div class="security-section__heading">
                <span class="security-index">01</span>
                <div><p class="security-label">Tiêu chuẩn và phạm vi</p><h2>Chứng nhận và tuân thủ</h2></div>
            </div>
            <p class="security-intro">Hiện tại, Việt Sơn Achieva chưa công bố chứng nhận SOC 2, ISO 27001, GDPR hoặc HIPAA. Các tên dưới đây là phạm vi tham chiếu để bạn biết một chứng nhận sẽ kiểm soát điều gì, không phải tuyên bố rằng chúng tôi đang sở hữu chứng nhận đó.</p>
            <div class="security-compliance-grid">
                <article class="security-compliance-card"><div class="security-mark">SOC 2</div><div><h3>Kiểm soát dịch vụ</h3><p>Đánh giá kiểm soát về bảo mật, tính sẵn sàng, toàn vẹn xử lý, bảo mật thông tin và quyền riêng tư.</p></div><span class="security-status">Chưa công bố</span></article>
                <article class="security-compliance-card"><div class="security-mark">ISO<br>27001</div><div><h3>Quản lý an toàn thông tin</h3><p>Khung quản lý rủi ro và quy trình bảo vệ tài sản thông tin trong toàn tổ chức.</p></div><span class="security-status">Chưa công bố</span></article>
                <article class="security-compliance-card"><div class="security-mark">GDPR</div><div><h3>Quyền riêng tư dữ liệu</h3><p>Quy định về quyền của cá nhân và trách nhiệm khi xử lý dữ liệu cá nhân tại phạm vi áp dụng.</p></div><span class="security-status">Đánh giá theo phạm vi</span></article>
                <article class="security-compliance-card"><div class="security-mark">HIPAA</div><div><h3>Dữ liệu y tế</h3><p>Yêu cầu bảo vệ thông tin sức khỏe được quản lý tại Hoa Kỳ; không phải phạm vi dịch vụ cốt lõi hiện được công bố.</p></div><span class="security-status">Không áp dụng</span></article>
            </div>
        </section>

        <section class="security-section" id="encryption">
            <div class="security-section__heading"><span class="security-index">02</span><div><p class="security-label">Bảo vệ dữ liệu</p><h2>Mã hóa dữ liệu</h2></div></div>
            <div class="security-info-grid">
                <article class="security-info-card"><i class="fa-solid fa-lock" aria-hidden="true"></i><div><h3>Khi truyền tải</h3><p>Khi website được vận hành qua HTTPS, dữ liệu giữa trình duyệt và máy chủ được bảo vệ bằng TLS. Điều này giúp người khác khó đọc hoặc thay đổi dữ liệu trên đường truyền.</p></div></article>
                <article class="security-info-card"><i class="fa-solid fa-database" aria-hidden="true"></i><div><h3>Khi lưu trữ</h3><p>Mật khẩu tài khoản được lưu dưới dạng băm một chiều bằng cơ chế chuẩn của PHP, không lưu mật khẩu gốc. Việt Sơn chưa công bố việc mã hóa toàn bộ cơ sở dữ liệu hoặc bản sao lưu ở trạng thái lưu trữ.</p></div></article>
            </div>
        </section>

        <section class="security-section" id="storage">
            <div class="security-section__heading"><span class="security-index">03</span><div><p class="security-label">Vị trí xử lý</p><h2>Nơi lưu trữ dữ liệu</h2></div></div>
            <p>Thông tin khách hàng được lưu trữ và xử lý trên hạ tầng máy chủ phục vụ website và hệ thống quản lý đơn hàng của Việt Sơn Achieva. Khu vực địa lý cụ thể của máy chủ, bản sao lưu và tùy chọn chọn vùng chưa được công bố. Vui lòng liên hệ <a href="mailto:support@vietsontdc.com">support@vietsontdc.com</a> nếu cần xác nhận cho một yêu cầu tuân thủ cụ thể.</p>
        </section>

        <section class="security-section" id="access">
            <div class="security-section__heading"><span class="security-index">04</span><div><p class="security-label">Quyền hạn nội bộ</p><h2>Kiểm soát truy cập</h2></div></div>
            <div class="security-access-list">
                <div><i class="fa-solid fa-user-lock" aria-hidden="true"></i><p><strong>Ai được truy cập</strong><br>Chỉ nhân sự cần dữ liệu để xử lý đơn hàng, hỗ trợ khách hàng, bảo hành hoặc quản trị hệ thống.</p></div>
                <div><i class="fa-solid fa-key" aria-hidden="true"></i><p><strong>Trong điều kiện nào</strong><br>Truy cập phục vụ công việc, theo tài khoản được cấp và phạm vi trách nhiệm tương ứng; không sử dụng dữ liệu cho mục đích ngoài công việc.</p></div>
                <div><i class="fa-solid fa-list-check" aria-hidden="true"></i><p><strong>Biện pháp hạn chế</strong><br>Phân quyền theo vai trò quản trị, yêu cầu đăng nhập và ghi nhật ký hoạt động quản trị. Quyền truy cập cần được rà soát khi vai trò thay đổi.</p></div>
            </div>
        </section>

        <section class="security-section" id="reporting">
            <div class="security-section__heading"><span class="security-index">05</span><div><p class="security-label">Hãy giúp chúng tôi cải thiện</p><h2>Tiết lộ lỗ hổng bảo mật</h2></div></div>
            <p>Nếu phát hiện vấn đề bảo mật, vui lòng gửi mô tả, đường dẫn hoặc thành phần bị ảnh hưởng, bước tái hiện và bằng chứng an toàn đến <a href="mailto:support@vietsontdc.com">support@vietsontdc.com</a>. Không gửi dữ liệu khách hàng thật, mật khẩu hoặc mã khai thác có thể gây gián đoạn dịch vụ.</p>
            <div class="security-callout"><i class="fa-solid fa-envelope" aria-hidden="true"></i><div><strong>Quy trình tiếp nhận</strong><span>Chúng tôi sẽ xác nhận khi tiếp nhận, đánh giá mức độ ảnh hưởng, khắc phục theo ưu tiên và trao đổi kết quả phù hợp. Hiện chưa có chương trình bounty công khai hoặc chính sách thời hạn phản hồi cố định.</span></div></div>
        </section>

        <section class="security-section" id="incidents">
            <div class="security-section__heading"><span class="security-index">06</span><div><p class="security-label">Minh bạch sự cố</p><h2>Lịch sử sự cố</h2></div></div>
            <div class="security-empty"><i class="fa-solid fa-clipboard-check" aria-hidden="true"></i><div><strong>Chưa có sự cố nào được công bố trên trang này.</strong><p>Nếu có sự cố ảnh hưởng đáng kể đến khách hàng, chúng tôi sẽ cập nhật ngày xảy ra, phạm vi ảnh hưởng và biện pháp khắc phục tại đây.</p></div></div>
        </section>

        <section class="security-section" id="testing">
            <div class="security-section__heading"><span class="security-index">07</span><div><p class="security-label">Đánh giá độc lập</p><h2>Kiểm thử xâm nhập</h2></div></div>
            <p>Việt Sơn chưa công bố cuộc kiểm thử xâm nhập hoặc kiểm toán bảo mật độc lập gần nhất. Báo cáo kiểm thử của bên thứ ba, nếu có và được phép chia sẻ, có thể được yêu cầu qua <a href="mailto:support@vietsontdc.com?subject=Y%C3%AAu%20c%E1%BA%A7u%20th%C3%B4ng%20tin%20b%E1%BA%A3o%20m%E1%BA%ADt">support@vietsontdc.com</a>; việc cung cấp phụ thuộc vào phạm vi, tính bảo mật và thỏa thuận liên quan.</p>
        </section>

        <section class="security-section" id="lich-su-phien-ban">
            <div class="security-section__heading"><span class="security-index">08</span><div><p class="security-label">Theo dõi thay đổi</p><h2>Lịch sử phiên bản</h2></div></div>
            <div class="security-empty"><i class="fa-solid fa-clock-rotate-left" aria-hidden="true"></i><div><strong>04/09/2026 · Phiên bản 1.0</strong><p>Phát hành lần đầu: bổ sung tóm tắt quyền riêng tư, dữ liệu được thu thập, thời gian lưu giữ, quyền yêu cầu xóa và thông tin bảo mật kỹ thuật.</p></div></div>
        </section>
    </div>
</main>
<?php include 'footer.php'; ?>
</body>
</html>