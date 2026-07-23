<?php
    require_once 'config/config.php';

    // Đã đăng nhập rồi thì vào thẳng dashboard, khỏi đăng nhập lại
    if (isset($_SESSION['account_id_admin'])) {
        header('Location: dashboad.php');
        exit;
    }

    if (isset($_POST['login'])) {
        $account_email = trim($_POST['account_email'] ?? '');
        $account_password = trim($_POST['account_password'] ?? '');

        $stmt = $pdo->prepare("SELECT * FROM account WHERE account_email = :email AND account_type IN (0, 1, 2) LIMIT 1");
        $stmt->execute([':email' => $account_email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($account_password, $row['account_password'])) {
            $_SESSION['login'] = $row['account_email'];
            $_SESSION['account_id_admin'] = $row['account_id'];
            $_SESSION['account_name'] = $row['account_name'];
            $_SESSION['account_type'] = (int) $row['account_type'];
            ghi_nhat_ky($pdo, 'dang_nhap', 'tai_khoan', (int) $row['account_id'], 'Đăng nhập thành công.');
            header('Location: dashboad.php');
            exit;
        } else {
            ghi_nhat_ky($pdo, 'dang_nhap_that_bai', 'tai_khoan', $row['account_id'] ?? null, 'Đăng nhập thất bại với email: ' . $account_email);
            echo '<script>alert("Tài khoản hoặc mật khẩu không chính xác, vui lòng nhập lại");</script>';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="assets/css/dang-nhap.css">
  <title>Login Admin</title>
  <!-- <link rel="shortcut icon" href="assets/images/icon/logo VS_icon.jpg"/> -->
</head>
<body>
    <section class="login">
        <div class="form-box">
            <div class="form-value">
                <form action="" autocomplete="on" method="POST">
                    <h2 >Hệ Thông Admin</h2>
                  
                    <div class="inputbox">
                        <img class="icon-img" src="https://img.icons8.com/color/48/gmail-login.png" alt="Email Open" width="50" height="50">
                        <input type="email" name="account_email" placeholder=" " required>
                        <label for="">Email</label>
                    </div>
                    <div class="inputbox">
                        <img class="icon-img" src="https://img.icons8.com/fluency/48/password-window.png" alt="Email Open" width="50" height="50">
                        <input type="password" name="account_password" placeholder=" " required>
                        <label for="">Password</label>
                    </div>

                    <div class="forget">
                        <label for=""><input type="checkbox">Remember Me  <a href="#">Forget Password</a></label>
                    </div>
                    <button type="submit" name="login">Log in</button>
                    <div class="register">
                        <p>Don't have a account <a href="#">Register</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>