<?php
require_once 'admin/config/config.php';

$provider = $_GET['provider'] ?? '';
$cfg = require 'admin/config/social-login.php';

if (!in_array($provider, ['facebook', 'google'], true)) {
    header('Location: tai-khoan.php?msg=loi_dang_nhap&tab=dang-nhap');
    exit;
}

// Chống CSRF cho luồng OAuth: state ngẫu nhiên lưu vào session, đối chiếu lại ở bước callback.
$state = bin2hex(random_bytes(16));
$_SESSION['oauth_state'] = $state;

if ($provider === 'facebook') {
    $fb = $cfg['facebook'];
    if ($fb['app_id'] === '' || $fb['app_secret'] === '') {
        header('Location: tai-khoan.php?msg=loi_social_chua_cau_hinh&tab=dang-nhap');
        exit;
    }
    $params = [
        'client_id'     => $fb['app_id'],
        'redirect_uri'  => $fb['redirect_uri'],
        'state'         => $state,
        'scope'         => 'email,public_profile',
        'response_type' => 'code',
    ];
    header('Location: https://www.facebook.com/v19.0/dialog/oauth?' . http_build_query($params));
    exit;
}

if ($provider === 'google') {
    $gg = $cfg['google'];
    if ($gg['client_id'] === '' || $gg['client_secret'] === '') {
        header('Location: tai-khoan.php?msg=loi_social_chua_cau_hinh&tab=dang-nhap');
        exit;
    }
    $params = [
        'client_id'     => $gg['client_id'],
        'redirect_uri'  => $gg['redirect_uri'],
        'state'         => $state,
        'scope'         => 'openid email profile',
        'response_type' => 'code',
        'access_type'   => 'online',
        'prompt'        => 'select_account',
    ];
    header('Location: https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params));
    exit;
}
