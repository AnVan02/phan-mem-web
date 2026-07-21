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
│   ├── quanly_taikhoan/    Quản lý tài khoản quản trị (danh sách, thêm/sửa/xoá, phân quyền)
│   ├── dang-nhap.php       Đăng nhập quản trị
│   ├── dang-xuat.php       Đăng xuất quản trị
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
├── mailer.php                               Gửi email (đơn hàng, liên hệ...)
├── robots.txt                               Chỉ dẫn crawl cho search engine
└── sitemap.php                              Sitemap XML tự sinh từ database (xem mục SEO bên dưới)
```

## Quy ước link nội bộ

Toàn bộ link/href/src nội bộ trong site dùng đường dẫn **tương đối, không có `/` ở đầu** (vd: `san-pham.php`, `assets/image/logo.png`), không dùng đường dẫn tuyệt đối từ gốc domain (`/san-pham.php`). Lý do: site có thể chạy ở domain gốc (production) hoặc trong thư mục con của `htdocs` (XAMPP local, vd `http://localhost/VietSon-Achieva/`) — link tương đối luôn đúng ở cả hai trường hợp, còn link tuyệt đối `/...` sẽ vỡ khi chạy trong thư mục con.

## SEO: robots.txt & sitemap.php

- [robots.txt](robots.txt) — chặn crawl `/admin/`, trang riêng tư (`tai-khoan.php`, `gio-hang.php`, `don-hang-chi-tiet.php`), các file include không phải trang thật (`head.php`, `header.php`, `footer.php`...), script AJAX/xử lý/migrate/debug, thư mục `database/`, `scratch/`, `_shot/`. Dòng `Sitemap:` ở cuối file cần điền domain thật khi lên production (đang để placeholder `<domain-cua-ban>`).
- [sitemap.php](sitemap.php) — sinh sitemap XML động: liệt kê sẵn các trang tĩnh chính (`index.php`, `san-pham.php`, `mo-ta-linh-kien.php`, `tin-tuc-moi.php`, `bao-hanh.php`), đồng thời tự truy vấn bảng `san_pham` và `article` để thêm URL từng sản phẩm/bài viết đang active — có dữ liệu mới là sitemap tự cập nhật, không cần sửa code. URL gốc (domain + thư mục con nếu có) được tự nhận diện qua `$_SERVER['HTTP_HOST']`/`SCRIPT_NAME`, không hard-code domain.

## Quy ước dùng head.php

Các trang phía người dùng không tự viết `<!DOCTYPE html>...<head>...<body>` nữa, mà set vài biến rồi `require 'head.php';`:

```php
$page_title = 'Tiêu đề trang - Viết Sơn Achieva';
$extra_css  = ['assets/css/xxx.css'];   // CSS riêng của trang
require 'head.php';
```

Biến tuỳ chọn khác: `$canonical_url`, `$html_lang` (mặc định `vi`), `$meta_robots`, `$pre_css_scripts` (script không defer, render trước CSS), `$post_css_scripts` (script defer, render sau CSS).

## Microsite landing-page/ (ROSA AI)

Thư mục [landing-page/](landing-page/) là một microsite marketing riêng (sản phẩm "ROSA AI"), không dùng chung DB/config với site bán hàng — mỗi trang tự chứa HTML/CSS/JS của nó.

- `landing.php` + `landing.css` / `landing.js` — trang landing chính
- `ROSA-AI-CONNECT.php` + `ROSA-AI-CONNECT.css` — landing sản phẩm "ROSA AI Connect" (AI local không giới hạn cho doanh nghiệp), có link từ menu trang landing chính
- `ROSA-AI-Workspace.php` + `ROSA-AI-Workspace.css` — landing sản phẩm "ROSA AI Workspace" (trợ lý AI cho đội ngũ doanh nghiệp)
- `test.php` — bản nháp/thử nghiệm một hướng thiết kế khác, chưa gắn vào luồng chính
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
- Đăng nhập/đăng xuất quản trị, dashboard tổng quan
- Quản lý sản phẩm (CRUD), thuộc tính sản phẩm
- Quản lý bài viết/tin tức (CRUD)
- Quản lý banner trang chủ
- Quản lý đơn hàng (danh sách, chi tiết, cập nhật trạng thái)
- Quản lý bảo hành, kể cả nhập hàng loạt số serial từ file Excel/CSV (tải file mẫu, đọc `.xlsx` bằng `ZipArchive`/`SimpleXMLElement` không cần thư viện ngoài)
- Quản lý tài khoản quản trị (danh sách, thêm/sửa/xoá, phân quyền); mật khẩu được mã hoá bcrypt (script [migrate-phan-quyen-admin.php](migrate-phan-quyen-admin.php) dùng để chuyển các mật khẩu cũ dạng plain text sang bcrypt, an toàn khi chạy lại nhiều lần)

## Ghi chú tiến độ

Xem [note.txt](note.txt) để biết các đầu việc gần nhất đã hoàn thành (luồng giỏ hàng → đặt hàng → admin xem đơn).
