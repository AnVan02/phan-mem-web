<?php
require_once __DIR__ . '/admin/config/config.php';
$page_title        = $page_title ?? 'Viết Sơn Achieva';
$html_lang         = $html_lang ?? 'vi';
$canonical_url     = $canonical_url ?? '';
$meta_robots       = $meta_robots ?? '';
$extra_css         = $extra_css ?? [];
$pre_css_scripts   = $pre_css_scripts ?? [];
$post_css_scripts  = $post_css_scripts ?? [];
$site_root         = $site_root ?? '';
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($html_lang); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <?php if ($meta_robots !== ''): ?>
    <meta name="robots" content="<?php echo htmlspecialchars($meta_robots); ?>">
    <?php endif; ?>
    <?php if ($canonical_url !== ''): ?>
    <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>">
    <?php endif; ?>
    <link rel="shortcut icon" href="<?php echo htmlspecialchars(asset_url('assets/images/icon/logo VS_icon.jpg')); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Montserrat:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script>window.SITE_ROOT = <?php echo json_encode(rtrim($site_root, '/')); ?>;</script>
    <script src="<?php echo htmlspecialchars(asset_url('assets/js/header.js')); ?>"></script>
    <?php foreach ($pre_css_scripts as $src): ?>
    <script src="<?php echo htmlspecialchars(asset_url($src)); ?>"></script>
    <?php endforeach; ?>

    <link rel="stylesheet" href="<?php echo htmlspecialchars(asset_url('assets/vendor/fontawesome/css/all.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(asset_url('assets/css/header.css')); ?>">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(asset_url('assets/css/footer.css')); ?>">
    <?php foreach ($extra_css as $css): ?>
    <link rel="stylesheet" href="<?php echo htmlspecialchars(asset_url($css)); ?>">
    <?php endforeach; ?>
    <?php foreach ($post_css_scripts as $src): ?>
    <script src="<?php echo htmlspecialchars(asset_url($src)); ?>" defer></script>
    <?php endforeach; ?>
</head>

<body>
