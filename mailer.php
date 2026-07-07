<?php 
    require 'header';
    
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
                <p>Tài khoản của bạn với email <strong>{$email_nhan}</strong> của bạn đã kích hoạt thành công</p>
                <p>Bây giờ bạn có thể đăng nhập để mua sắm và theo dõi đơn hàng dễ dàng hơn.</p>
                <br>
                <p>Trân trọng,<br>Đội ngũ Viết Sơn Achieva</p>
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

?>