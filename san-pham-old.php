<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đối tượng khách hàng</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <style>
    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(to bottom, #f3f6fb, #e9eef5);
    }

    .audience {
        padding: 80px 5%;
        max-width: 1500px;
        margin: auto;
    }

    .section-title {
        text-align: center;
        font-size: 42px;
        font-weight: 800;
        margin-bottom: 15px;
    }

    .section-desc {
        text-align: center;
        max-width: 750px;
        margin: 0 auto 60px;
        color: #555;
        font-size: 18px;
    }

    .audience-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 35px;
    }

    /* CARD */
    .a-card {
        position: relative;
        padding: 40px;
        border-radius: 28px;
        background: linear-gradient(135deg, #ffffff, #f4f7fc);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        transition: .3s ease;
    }

    .a-card:hover {
        transform: translateY(-8px);
    }

    /* Background soft shape */
    .a-card::after {
        content: "";
        position: absolute;
        right: -60px;
        bottom: -60px;
        width: 240px;
        height: 240px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.15), transparent 70%);
        border-radius: 50%;
    }

    /* Header */
    .a-top {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .icon-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        color: #fff;
    }

    /* Different colors */
    .blue .icon-circle {
        background: linear-gradient(135deg, #2563eb, #1e3a8a);
    }

    .green .icon-circle {
        background: linear-gradient(135deg, #16a34a, #065f46);
    }

    .orange .icon-circle {
        background: linear-gradient(135deg, #f97316, #c2410c);
    }

    .a-card h3 {
        font-size: 22px;
        font-weight: 700;
        margin: 0;
    }

    .a-card p {
        margin: 15px 0;
        color: #555;
        font-size: 15px;
    }

    /* checklist */
    .a-checklist {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .a-checklist li {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
    }

    .a-checklist i {
        color: #22c55e;
    }

    /* Illustration */
    .a-illustration {
        position: absolute;
        right: 25px;
        bottom: 25px;
        width: 120px;
        z-index: 2;
        transition: .3s;
    }

    .a-card:hover .a-illustration {
        transform: scale(1.05);
    }

    /* Feature strip */
    .audience-features {
        margin-top: 80px;
        padding: 40px;
        border-radius: 25px;
        background: #ffffff;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .af-item {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .af-icon {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 20px;
    }

    .af-icon.blue {
        background: #3b82f6;
    }

    .af-icon.green {
        background: #22c55e;
    }

    .af-icon.purple {
        background: #8b5cf6;
    }

    .af-icon.orange {
        background: #f97316;
    }

    .af-text h4 {
        margin: 0 0 5px;
        font-size: 16px;
    }

    .af-text p {
        margin: 0;
        font-size: 14px;
        color: #666;
    }

    /* Responsive */
    @media(max-width:1100px) {
        .audience-cards {
            grid-template-columns: 1fr;
        }

        .audience-features {
            grid-template-columns: 1fr 1fr;
        }
    }
    </style>
</head>

<body>

    <section class="audience">

        <h2 class="section-title">Đối tượng khách hàng phù hợp</h2>
        <p class="section-desc">
            Giải pháp phù hợp với các tổ chức, doanh nghiệp có khối lượng tài liệu lớn và thường xuyên cần tra cứu thông
            tin.
        </p>

        <div class="audience-cards">

            <!-- CARD 1 -->
            <div class="a-card blue">
                <div class="a-top">
                    <div class="icon-circle"><i class="fa-solid fa-landmark"></i></div>
                    <h3>Cơ quan hành chính</h3>
                </div>

                <p>Quản lý và tra cứu nhanh các văn bản, quy định, hồ sơ hành chính.</p>

                <ul class="a-checklist">
                    <li><i class="fa-solid fa-check"></i>Lưu trữ tập trung</li>
                    <li><i class="fa-solid fa-check"></i>Tra cứu nhanh chóng</li>
                    <li><i class="fa-solid fa-check"></i>Đảm bảo tính chính xác</li>
                    <li><i class="fa-solid fa-check"></i>Tuân thủ quy định</li>
                </ul>

                <img class="a-illustration" src="https://cdn-icons-png.flaticon.com/512/2942/2942076.png">
            </div>

            <!-- CARD 2 -->
            <div class="a-card green">
                <div class="a-top">
                    <div class="icon-circle"><i class="fa-solid fa-scale-balanced"></i></div>
                    <h3>Công ty luật</h3>
                </div>

                <p>Tìm kiếm nhanh hồ sơ vụ việc, hợp đồng và tài liệu pháp lý.</p>

                <ul class="a-checklist">
                    <li><i class="fa-solid fa-check"></i>Quản lý hồ sơ vụ việc</li>
                    <li><i class="fa-solid fa-check"></i>Tìm kiếm thông minh</li>
                    <li><i class="fa-solid fa-check"></i>Chia sẻ và cộng tác</li>
                    <li><i class="fa-solid fa-check"></i>Bảo mật tuyệt đối</li>
                </ul>

                <img class="a-illustration" src="https://cdn-icons-png.flaticon.com/512/3068/3068757.png">
            </div>

            <!-- CARD 3 -->
            <div class="a-card green">
                <div class="a-top">
                    <div class="icon-circle"><i class="fa-solid fa-scale-balanced"></i></div>
                    <h3>Công ty luật</h3>
                </div>

                <p>Tìm kiếm nhanh hồ sơ vụ việc, hợp đồng và tài liệu pháp lý.</p>

                <ul class="a-checklist">
                    <li><i class="fa-solid fa-check"></i>Quản lý hồ sơ vụ việc</li>
                    <li><i class="fa-solid fa-check"></i>Tìm kiếm thông minh</li>
                    <li><i class="fa-solid fa-check"></i>Chia sẻ và cộng tác</li>
                    <li><i class="fa-solid fa-check"></i>Bảo mật tuyệt đối</li>
                </ul>

                <img class="a-illustration" src="https://cdn-icons-png.flaticon.com/512/3068/3068757.png">
            </div>

            <!-- CARD 4 -->
            <div class="a-card orange">
                <div class="a-top">
                    <div class="icon-circle"><i class="fa-solid fa-truck"></i></div>
                    <h3>Ngân hàng</h3>
                </div>

                <p>Quản lý hồ sơ khách hàng, hợp đồng, tài liệu nghiệp vụ và tuân thủ.</p>

                <ul class="a-checklist">
                    <li><i class="fa-solid fa-check"></i>Quản lý hồ sơ khách hàng</li>
                    <li><i class="fa-solid fa-check"></i>Lưu trữ hợp đồng an toàn</li>
                    <li><i class="fa-solid fa-check"></i>Tuân thủ quy định tài chính</li>
                    <li><i class="fa-solid fa-check"></i>Tra cứu tức thì</li>
                </ul>

                <img class="a-illustration" src="https://cdn-icons-png.flaticon.com/512/2830/2830289.png">
            </div>

            <!-- CARD 5 -->
            <div class="a-card orange">
                <div class="a-top">
                    <div class="icon-circle"><i class="fa-solid fa-truck"></i></div>
                    <h3>Doanh nghiệp Logistics</h3>
                </div>

                <p>Quản lý chứng từ, quy trình vận hành và tài liệu nội bộ hiệu quả.</p>

                <ul class="a-checklist">
                    <li><i class="fa-solid fa-check"></i>Quản lý chứng từ</li>
                    <li><i class="fa-solid fa-check"></i>Quy trình vận hành</li>
                    <li><i class="fa-solid fa-check"></i>Dễ dàng truy xuất</li>
                    <li><i class="fa-solid fa-check"></i>Tối ưu hiệu suất</li>
                </ul>

                <img class="a-illustration" src="https://cdn-icons-png.flaticon.com/512/2830/2830289.png">
            </div>

            <!-- CARD 6 -->
            <div class="a-card orange">
                <div class="a-top">
                    <div class="icon-circle"><i class="fa-solid fa-truck"></i></div>
                    <h3>Doanh nghiệp sản xuất</h3>
                </div>

                <p>Quản lý tài liệu kỹ thuật, quy trình sản xuất và hồ sơ chất lượng.</p>

                <ul class="a-checklist">
                    <li><i class="fa-solid fa-check"></i>Quản lý tài liệu kỹ thuật</li>
                    <li><i class="fa-solid fa-check"></i>Kiểm soát quy trình sản xuất</li>
                    <li><i class="fa-solid fa-check"></i>Hồ sơ chất lượng rõ ràng</li>
                    <li><i class="fa-solid fa-check"></i>Truy xuất nhanh chóng</li>
                </ul>

                <img class="a-illustration" src="https://cdn-icons-png.flaticon.com/512/2830/2830289.png">
            </div>


            <!-- CARD 7 -->
            <div class="a-card orange">
                <div class="a-top">
                    <div class="icon-circle"><i class="fa-solid fa-truck"></i></div>
                    <h3>Trường học</h3>
                </div>

                <p>Quản lý hồ sơ học sinh, quy trình vận hành và tài liệu nội bộ hiệu quả</p>

                <ul class="a-checklist">
                    <li><i class="fa-solid fa-check"></i>Quản lý hồ sơ học sinh</li>
                    <li><i class="fa-solid fa-check"></i>Lưu trữ tài liệu giảng dạy</li>
                    <li><i class="fa-solid fa-check"></i>Tra cứu thông tin dễ dàng</li>
                    <li><i class="fa-solid fa-check"></i>Vận hành hiệu quả</li>
                </ul>

                <img class="a-illustration" src="https://cdn-icons-png.flaticon.com/512/2830/2830289.png">
            </div>

            <!-- CARD 8 -->
            <div class="a-card orange">
                <div class="a-top">
                    <div class="icon-circle"><i class="fa-solid fa-truck"></i></div>
                    <h3>Chứng khoán </h3>
                </div>

                <p>Quản lý hồ sơ khách hàng, báo cáo, tài liệu phân tích và giao dịch.</p>

                <ul class="a-checklist">
                    <li><i class="fa-solid fa-check"></i>Quản lý hồ sơ khách hàng</li>
                    <li><i class="fa-solid fa-check"></i>Lưu trữ báo cáo phân tích</li>
                    <li><i class="fa-solid fa-check"></i>Tra cứu giao dịch nhanh</li>
                    <li><i class="fa-solid fa-check"></i>Tuân thủ quy định tài chính</li>
                </ul>

                <img class="a-illustration" src="https://cdn-icons-png.flaticon.com/512/2830/2830289.png">
            </div>

        </div>

        <!-- Feature Strip -->
        <div class="audience-features">

            <div class="af-item">
                <div class="af-icon blue"><i class="fa-solid fa-bolt"></i></div>
                <div class="af-text">
                    <h4>Tìm kiếm nhanh chóng</h4>
                    <p>Tiết kiệm thời gian tra cứu.</p>
                </div>
            </div>

            <div class="af-item">
                <div class="af-icon green"><i class="fa-solid fa-shield-halved"></i></div>
                <div class="af-text">
                    <h4>Bảo mật an toàn</h4>
                    <p>Kiểm soát quyền truy cập.</p>
                </div>
            </div>

            <div class="af-item">
                <div class="af-icon purple"><i class="fa-solid fa-layer-group"></i></div>
                <div class="af-text">
                    <h4>Quản lý tập trung</h4>
                    <p>Lưu trữ và khai thác dễ dàng.</p>
                </div>
            </div>

            <div class="af-item">
                <div class="af-icon orange"><i class="fa-solid fa-chart-pie"></i></div>
                <div class="af-text">
                    <h4>Tối ưu chi phí</h4>
                    <p>Giảm chi phí vận hành.</p>
                </div>
            </div>

        </div>

    </section>

</body>

</html>