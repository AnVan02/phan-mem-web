-- Biến bảng khach_hang_lien_he (hiện chỉ dùng lưu liên hệ) thành bảng tài khoản khách hàng thật:
-- thêm mật khẩu và ràng buộc email duy nhất để có thể đăng nhập.
ALTER TABLE `khach_hang_lien_he`
    ADD COLUMN `mat_khau` VARCHAR(255) NULL AFTER `customer_address`,
    ADD UNIQUE KEY `uniq_customer_email` (`customer_email`);

-- Liên kết đơn hàng với tài khoản khách hàng (khách vãng lai vẫn đặt được, cột để NULL)
ALTER TABLE `don_hang`
    ADD COLUMN `ma_khach_hang` INT NULL AFTER `session_id`,
    ADD CONSTRAINT `fk_don_hang_khach_hang` FOREIGN KEY (`ma_khach_hang`) REFERENCES `khach_hang_lien_he` (`ma_lien_he`) ON DELETE SET NULL;
