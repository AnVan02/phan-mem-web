<?php
// Cấu hình đăng nhập mạng xã hội (Facebook, Google).
// Điền App ID / App Secret sau khi đăng ký ứng dụng tại:
//   - Facebook: https://developers.facebook.com/apps
//   - Google:   https://console.cloud.google.com/apis/credentials
//
// Redirect URI cần khai báo trên hai nền tảng trên (thay domain cho đúng môi trường):
//   Facebook: http://localhost/VietSon-Achieva/social-callback.php?provider=facebook
//   Google:   http://localhost/VietSon-Achieva/social-callback.php?provider=google

return [
    'facebook' => [
        'app_id'     => '',
        'app_secret' => '',
        'redirect_uri' => asset_url('social-callback.php') . '?provider=facebook',
    ],
    'google' => [
        'client_id'     => '',
        'client_secret' => '',
        'redirect_uri'  => asset_url('social-callback.php') . '?provider=google',
    ],
];
