# VietSon-Achieva

Website bán linh kiện máy tính, xây dựng bằng PHP thuần + MySQL (XAMPP).

## Yêu cầu môi trường

- XAMPP (Apache + MySQL/MariaDB), PHP 7.4+
- Import database: `database/vietson-achieva.sql` (bảng chính) và các file bổ sung `database/tai-khoan-khach-hang.sql`, `database/gio-hang-don-hang.sql`
- Cấu hình kết nối DB tại [admin/config/config.php](admin/config/config.php) (mặc định `host=localhost`, `dbname=vietson-achieva`, `user=root`, không mật khẩu)

## Cấu trúc thư mục

```
├── admin/                  Khu vực quản trị
│   ├── config/             Kết nối DB, hàm dùng chung (tao_slug, mbstring fallback...)
│   ├── includes/           sidebar.php dùng chung cho các trang quản trị
│   ├── quanly/             Quản lý banner, đơn hàng
│   ├── quanly_sanpham/     Quản lý sản phẩm (thêm/sửa/xoá/danh sách)
│   ├── quanly_baiviet/     Quản lý bài viết/tin tức
│   ├── quanly_baohanh/     Quản lý bảo hành (kể cả nhập hàng loạt số serial từ file Excel/CSV)
│   ├── quanly_thuoctinh/   Quản lý thuộc tính sản phẩm
│   ├── dang-nhap.php       Đăng nhập quản trị
│   ├── dashboad.php        Trang tổng quan
│   └── index.php
├── assets/                 css, js, image, uploads dùng chung cho site
├── database/                Các file SQL
├── landing-page/             Microsite marketing ROSA AI, độc lập với site chính (xem mục riêng bên dưới)
├── head.php                  Khối <head> (meta, title, CSS/JS chung) dùng chung, các trang `require` thay vì lặp lại
├── header.php / footer.php  Layout dùng chung cho trang người dùng
├── index.php                 Trang chủ
├── san-pham.php               Danh sách sản phẩm
├── chi-tiet-san-pham.php      Chi tiết sản phẩm (giá, số lượng, thêm giỏ hàng)
├── mo-ta-linh-kien.php        Mô tả/so sánh linh kiện
├── gio-hang.php                Trang giỏ hàng (sửa số lượng, xoá, đặt hàng)
├── gio-hang-ajax.php          API AJAX cho giỏ hàng (thêm/cập nhật/xoá/đếm)
├── so-sanh-ajax.php            API AJAX so sánh sản phẩm
├── don-hang-chi-tiet.php      Chi tiết đơn hàng của khách
├── tai-khoan.php / xuly-tai-khoan.php   Đăng ký/đăng nhập/quản lý tài khoản khách hàng
├── tin-tuc-moi.php / chi-tiet-tin-tuc.php  Tin tức
├── bao-hanh.php / cam-ket-khach-hang.php   Trang chính sách/cam kết
├── thuong-hieu.php / danh-muc.php          Trang thương hiệu / danh mục
└── mailer.php                              Gửi email (đơn hàng, liên hệ...)
```

## Quy ước dùng head.php

Các trang phía người dùng không tự viết `<!DOCTYPE html>...<head>...<body>` nữa, mà set vài biến rồi `require 'head.php';`:

```php
$page_title = 'Tiêu đề trang - Viết Sơn Achieva';
$extra_css  = ['assets/css/xxx.css'];   // CSS riêng của trang
require 'head.php';
```

Biến tuỳ chọn khác: `$canonical_url`, `$html_lang` (mặc định `vi`), `$meta_robots`, `$pre_css_scripts` (script không defer, render trước CSS), `$post_css_scripts` (script defer, render sau CSS).

## Microsite landing-page/ (ROSA AI)

Thư mục [landing-page/](landing-page/) là một microsite marketing riêng (sản phẩm "ROSA AI Bridge"), không dùng chung DB/config với site bán hàng — mỗi trang tự chứa HTML/CSS/JS của nó.

- `landing.php` + `landing.css` / `landing.js` — trang landing chính
- `khach-hang.php` + `khach-hang.css` — landing dành cho nhóm "người tích hợp" (công ty phần mềm, đội IT dùng Claude Code/Cursor/ComfyUI...)
- `van-hanh-ems.php` + `van-hanh-ems.css` — landing dành cho nhóm "người vận hành/SME" (đang là file trống, chưa dựng nội dung)
- `test.php` — bản nháp/thử nghiệm một hướng thiết kế khác cho nhóm SME, chưa gắn vào luồng chính
- `luu-lien-he.php` — endpoint AJAX nhận form đăng ký demo, ghi vào `data/lien-he.txt`
- `images/` — hình ảnh minh hoạ (kể cả các ảnh sơ đồ AI-generated dùng cho hero/step section)

## Chức năng đã hoàn thành

**Người dùng**
- Xem trang chủ, danh mục, thương hiệu, tin tức
- Xem chi tiết sản phẩm, chọn số lượng, thêm vào giỏ hàng (AJAX)
- So sánh sản phẩm (AJAX)
- Quản lý giỏ hàng: sửa số lượng, xoá sản phẩm, đặt hàng
- Đăng ký/đăng nhập tài khoản, xem chi tiết đơn hàng đã đặt
- Xem chính sách bảo hành, cam kết khách hàng

**Quản trị**
- Đăng nhập quản trị, dashboard tổng quan
- Quản lý sản phẩm (CRUD), thuộc tính sản phẩm
- Quản lý bài viết/tin tức (CRUD)
- Quản lý banner trang chủ
- Quản lý đơn hàng (danh sách, chi tiết, cập nhật trạng thái)
- Quản lý bảo hành, kể cả nhập hàng loạt số serial từ file Excel/CSV (tải file mẫu, đọc `.xlsx` bằng `ZipArchive`/`SimpleXMLElement` không cần thư viện ngoài)

## Ghi chú tiến độ

Xem [note.txt](note.txt) để biết các đầu việc gần nhất đã hoàn thành (luồng giỏ hàng → đặt hàng → admin xem đơn).
