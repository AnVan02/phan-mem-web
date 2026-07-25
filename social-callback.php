<?php
require_once 'admin/config/config.php';

$provider = $_GET['provider'] ?? '';
$code     = $_GET['code'] ?? '';
$state    = $_GET['state'] ?? '';

if (!in_array($provider, ['facebook', 'google'], true) || $code === ''
    || !isset($_SESSION['oauth_state']) || !hash_equals($_SESSION['oauth_state'], $state)) {
    header('Location: tai-khoan.php?msg=loi_dang_nhap&tab=dang-nhap');
    exit;
}
unset($_SESSION['oauth_state']);

$cfg = require 'admin/config/social-login.php';

function goi_http_json($url, $params = null, $headers = []) {
    $ch = curl_init();
    if ($params !== null) {
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    } else {
        curl_setopt($ch, CURLOPT_URL, $url);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge(['Accept: application/json'], $headers));
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res ? json_decode($res, true) : null;
}

$provider_id  = null;
$ten          = null;
$email        = null;

if ($provider === 'facebook') {
    $fb = $cfg['facebook'];
    $token_res = goi_http_json('https://graph.facebook.com/v19.0/oauth/access_token', [
        'client_id'     => $fb['app_id'],
        'client_secret' => $fb['app_secret'],
        'redirect_uri'  => $fb['redirect_uri'],
        'code'          => $code,
    ]);
    $access_token = $token_res['access_token'] ?? null;

    if (!$access_token) {
        header('Location: tai-khoan.php?msg=loi_dang_nhap&tab=dang-nhap');
        exit;
    }

    $profile = goi_http_json('https://graph.facebook.com/me?fields=id,name,email&access_token=' . urlencode($access_token));
    if (!$profile || empty($profile['id'])) {
        header('Location: tai-khoan.php?msg=loi_dang_nhap&tab=dang-nhap');
        exit;
    }

    $provider_id = $profile['id'];
    $ten         = $profile['name'] ?? 'Khách hàng Facebook';
    $email       = $profile['email'] ?? null;
}

if ($provider === 'google') {
    $gg = $cfg['google'];
    $token_res = goi_http_json('https://oauth2.googleapis.com/token', [
        'client_id'     => $gg['client_id'],
        'client_secret' => $gg['client_secret'],
        'redirect_uri'  => $gg['redirect_uri'],
        'code'          => $code,
        'grant_type'    => 'authorization_code',
    ]);
    $access_token = $token_res['access_token'] ?? null;

    if (!$access_token) {
        header('Location: tai-khoan.php?msg=loi_dang_nhap&tab=dang-nhap');
        exit;
    }

    $profile = goi_http_json('https://www.googleapis.com/oauth2/v3/userinfo', null, [
        'Authorization: Bearer ' . $access_token,
    ]);
    if (!$profile || empty($profile['sub'])) {
        header('Location: tai-khoan.php?msg=loi_dang_nhap&tab=dang-nhap');
        exit;
    }

    $provider_id = $profile['sub'];
    $ten         = $profile['name'] ?? 'Khách hàng Google';
    $email       = $profile['email'] ?? null;
}

if (!$provider_id) {
    header('Location: tai-khoan.php?msg=loi_dang_nhap&tab=dang-nhap');
    exit;
}

// 1) Đã từng đăng nhập bằng chính provider này trước đó -> đăng nhập luôn.
$stmt = $pdo->prepare("SELECT * FROM khach_hang_lien_he WHERE login_provider = :p AND provider_id = :pid LIMIT 1");
$stmt->execute([':p' => $provider, ':pid' => $provider_id]);
$kh = $stmt->fetch(PDO::FETCH_ASSOC);

// 2) Chưa có, nhưng email trùng với tài khoản đã đăng ký bằng mật khẩu -> liên kết vào tài khoản đó.
if (!$kh && $email) {
    $stmt = $pdo->prepare("SELECT * FROM khach_hang_lien_he WHERE customer_email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $kh = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($kh) {
        $pdo->prepare("UPDATE khach_hang_lien_he SET login_provider = :p, provider_id = :pid WHERE ma_lien_he = :id")
            ->execute([':p' => $provider, ':pid' => $provider_id, ':id' => $kh['ma_lien_he']]);
    }
}

// 3) Chưa từng có tài khoản -> tạo mới. Nếu Facebook không trả email, dùng email giả duy nhất theo provider_id.
if (!$kh) {
    $email_luu = $email ?: ($provider . '_' . $provider_id . '@khong-co-email.local');

    $ins = $pdo->prepare("INSERT INTO khach_hang_lien_he (customer_name, customer_email, login_provider, provider_id)
        VALUES (:ten, :email, :p, :pid)");
    $ins->execute([
        ':ten'   => $ten,
        ':email' => $email_luu,
        ':p'     => $provider,
        ':pid'   => $provider_id,
    ]);

    $stmt = $pdo->prepare("SELECT * FROM khach_hang_lien_he WHERE ma_lien_he = :id LIMIT 1");
    $stmt->execute([':id' => $pdo->lastInsertId()]);
    $kh = $stmt->fetch(PDO::FETCH_ASSOC);
}

$_SESSION['khach_hang_id']  = (int) $kh['ma_lien_he'];
$_SESSION['khach_hang_ten'] = $kh['customer_name'];

header('Location: tai-khoan.php?msg=dang_nhap_thanh_cong');
exit;
