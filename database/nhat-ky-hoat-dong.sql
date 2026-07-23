-- Bảng ghi nhật ký hoạt động của tài khoản quản trị (audit log)
CREATE TABLE IF NOT EXISTS `nhat_ky_hoat_dong` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `account_name` varchar(100) NOT NULL,
  `account_type` tinyint(4) DEFAULT NULL,
  `hanh_dong` varchar(20) NOT NULL,
  `doi_tuong` varchar(50) NOT NULL,
  `doi_tuong_id` int(11) DEFAULT NULL,
  `mo_ta` varchar(255) DEFAULT NULL,
  `dia_chi_ip` varchar(45) DEFAULT NULL,
  `thoi_gian` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_account_id` (`account_id`),
  KEY `idx_doi_tuong` (`doi_tuong`),
  KEY `idx_thoi_gian` (`thoi_gian`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
