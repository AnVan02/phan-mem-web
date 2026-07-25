<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function gui_email_dang_ky_thanh_cong($email_nhan, $ten_khach_hang) {
    $mail = new PHPMailer(true);
    try {
        // Cấu hình SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tvdell789@gmail.com';      // đổi thành email gửi
        $mail->Password   = 'app_password_16_ky_tu';     // App Password của Gmail (không phải mật khẩu thường)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('tvdell789@gmail.com', 'Viết Sơn Achieva');
        $mail->addAddress($email_nhan, $ten_khach_hang);

        $mail->isHTML(true);
        $mail->Subject = 'Đăng ký tài khoản thành công - Viết Sơn Achieva';
        $mail->Body    = "
            <div style='font-family: Arial, sans-serif; max-width:600px; margin:auto;'>
                <h2 style='color:#2563eb;'>Xin chào {$ten_khach_hang},</h2>
                <p>Cảm ơn bạn đã đăng ký tài khoản tại <strong>Viết Sơn Achieva </strong>.</p>
                <p>Tài khoản của bạn với email <strong>{$email_nhan}</strong> của bạn đã kích hoạt</p>
                <p>Bây giờ bạn có thể đăng nhập để mua sắm và theo dõi đơn hàng dễ dàng hơn.</p>
                <br>
                <p>Trân trọng,<br>Đội ngũ Achieva</p>
            </div>
        ";
        $mail->AltBody = "Xin chào {$ten_khach_hang}, tài khoản của bạn đã đăng ký thành công tại Viết Sơn Achieva.";
        

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Loi gui mail dang ky: ' . $mail->ErrorInfo);
        return false;
    }
}

function gui_email_dat_lai_mat_khau($email_nhan, $ten_khach_hang, $link_dat_lai) {
    $tieu_de_goc = 'Yêu cầu đặt lại mật khẩu - Viết Sơn Achieva';
    $tieu_de     = '=?UTF-8?B?' . base64_encode($tieu_de_goc) . '?=';

    $noi_dung = "
        <div style='font-family: Arial, sans-serif; max-width:600px; margin:auto;'>
            <h2 style='color:#1e3c72;'>Xin chào " . htmlspecialchars($ten_khach_hang) . ",</h2>
            <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản <strong>{$email_nhan}</strong> tại Viết Sơn Achieva.</p>
            <p>Vui lòng bấm vào nút bên dưới để đặt mật khẩu mới. Liên kết có hiệu lực trong <strong>30 phút</strong>:</p>
            <p style='text-align:center; margin:28px 0;'>
                <a href='{$link_dat_lai}' style='background:#1e3c72; color:#ffd700; padding:12px 28px; border-radius:999px; text-decoration:none; font-weight:700; display:inline-block;'>Đặt lại mật khẩu</a>
            </p>
            <p>Nếu nút trên không hoạt động, hãy sao chép liên kết sau vào trình duyệt:<br>
            <a href='{$link_dat_lai}'>{$link_dat_lai}</a></p>
            <p style='color:#6b7280; font-size:14px;'>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này. Mật khẩu của bạn sẽ không thay đổi.</p>
            <br>
            <p>Trân trọng,<br>Đội ngũ Viết Sơn Achieva</p>
        </div>
    ";

    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Viet Son Achieva <support@vietsontdc.com>\r\n";

    return @mail($email_nhan, $tieu_de, $noi_dung, $headers);
}

?>