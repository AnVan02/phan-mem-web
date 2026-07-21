-- Bảng lưu form "Đăng ký tư vấn giải pháp AI" (landing.php, ROSA-AI-Workspace.php, ROSA-AI-CONNECT.php)
CREATE TABLE IF NOT EXISTS `dang_ky_tu_van` (
  `ma_dang_ky` int(11) NOT NULL AUTO_INCREMENT,
  `ho_ten` varchar(100) NOT NULL,
  `so_dien_thoai` varchar(20) NOT NULL,
  `email` varchar(150) NOT NULL,
  `ten_cong_ty` varchar(150) DEFAULT NULL,
  `nhu_cau` text DEFAULT NULL,
  `trang_nguon` varchar(50) DEFAULT NULL COMMENT 'Trang gửi form: landing, ai-connect, ai-workspace',
  `ngay_gui` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ma_dang_ky`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
