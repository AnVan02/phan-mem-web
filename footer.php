<?php
$footer_lang = ($_COOKIE['site_lang'] ?? 'vi') === 'en' ? 'en' : 'vi';
$t = (require __DIR__ . '/lang/' . $footer_lang . '.php')['footer'];
$t['copyright'] = str_replace('{year}', date('Y'), $t['copyright']);
?>
<footer class="footer">
    <div class="footer__container">

        <div class="footer__info">

            <div class="footer__block">
                <h3 class="footer__heading"><?php echo $t['contact_heading']; ?></h3>
                <ul class="footer__list">
                    <li><a href="mailto:support@vietsontdc.com"><?php echo $t['contact_support']; ?></a></li>
                    <li><strong><?php echo $t['contact_sales']; ?></strong> (028) 39293770</li>
                    <li><strong><?php echo $t['contact_warranty_hn']; ?></strong> 0936699336</li>
                    <li><strong><?php echo $t['contact_warranty_hcm']; ?></strong> (028) 39260996</li>
                </ul>
            </div>

            <div class="footer__block">
                <h3 class="footer__heading"><?php echo $t['policy_heading']; ?></h3>
                <ul class="footer__list">
                    <li><a href="#"><?php echo $t['policy_about']; ?></a></li>
                    <li><a href="#"><?php echo $t['policy_return']; ?></a></li>
                    <li><a href="#"><?php echo $t['policy_shipping']; ?></a></li>
                    <li><a href="#"><?php echo $t['policy_warranty']; ?></a></li>

                </ul>
            </div>

            <div class="footer__block">
                <h3 class="footer__heading"><?php echo $t['branch_heading']; ?></h3>
                <ul class="footer__list">
                    <li><strong><?php echo $t['branch_hn_label']; ?></strong> <?php echo $t['branch_hn_address']; ?></li>
                    <li><strong><?php echo $t['branch_hcm_label']; ?></strong> <?php echo $t['branch_hcm_address']; ?></li>
                    <li><a href="<?php echo htmlspecialchars(asset_url('index.php?page=chinh-sach-bao-hanh')); ?>"><?php echo $t['branch_warranty_return']; ?></a></li>
                    <li><a href="<?php echo htmlspecialchars(asset_url('index.php?page=Tra-Cuu-Thong-Tin-Bao-Hanh-Viet-Son')); ?>"><?php echo $t['branch_warranty_lookup']; ?></a></li>
                </ul>
            </div>

            <div class="footer__block">
                <h3 class="footer__heading"><?php echo $t['shop_heading']; ?></h3>
                <ul class="footer__list">
                    <li><a href="#"><?php echo $t['shop_prebuilt']; ?></a></li>
                    <li><a href="#"><?php echo $t['shop_components']; ?></a></li>
                    <li><a href="#"><?php echo $t['shop_gaming']; ?></a></li>
                    <li><a href="#"><?php echo $t['shop_accessories']; ?></a></li>
                </ul>
            </div>

            <div class="footer__block footer__block--about">
                <h3 class="footer__heading"><?php echo $t['about_heading']; ?></h3>
                <p class="footer__description">
                    <strong><?php echo $t['about_description_strong']; ?></strong>
                    <?php echo $t['about_description']; ?>
                </p>
            </div>



        </div>

        <!-- Social icons -->
        <div class="footer__social">
            <a href="#" aria-label="Facebook"><img width="35" height="35"
                    src="https://img.icons8.com/color/48/facebook-new.png" alt="facebook-new" /></a>
            <a href="#" aria-label="Zalo"><img width="35" height="35" src="https://img.icons8.com/color/48/zalo.png"
                    alt="zalo" /></a>
            <a href="#" aria-label="Youtube"><img width="35" height="35"
                    src="https://img.icons8.com/color/48/youtube-play.png" alt="youtube-play" /></a>
            <a href="#" aria-label="TikTok"><img width="35" height="35"
                    src="https://img.icons8.com/color/48/tiktok--v1.png" alt="tiktok--v1" /></a>
            <a href="#" aria-label="LinkedIn"><img width="35" height="35"
                    src="https://img.icons8.com/fluency/48/linkedin.png" alt="linkedin" /></a>
        </div>

        <!-- Language + copyright row -->
        <div class="footer__meta">
            <div class="footer__lang" id="footerLangToggle">
                <img width="20" height="20"
                    src="https://img.icons8.com/color/48/<?php echo $footer_lang === 'en' ? 'great-britain' : 'vietnam'; ?>.png"
                    alt="<?php echo $footer_lang === 'en' ? 'english' : 'vietnam'; ?>" />
                <span><?php echo $t['lang_label']; ?></span>
                <i class="fa-solid fa-chevron-down"></i>

                <div class="footer__lang-dropdown" id="footerLangDropdown">
                    <button type="button" class="footer__lang-option<?php echo $footer_lang === 'vi' ? ' is-active' : ''; ?>"
                        data-lang="vi">
                        <img width="20" height="20" src="https://img.icons8.com/color/48/vietnam.png" alt="vietnam" />
                        Tiếng Việt
                    </button>
                    <button type="button" class="footer__lang-option<?php echo $footer_lang === 'en' ? ' is-active' : ''; ?>"
                        data-lang="en">
                        <img width="20" height="20" src="https://img.icons8.com/color/48/great-britain.png"
                            alt="english" />
                        English
                    </button>
                </div>
            </div>
            <p class="footer__copyright"><?php echo $t['copyright']; ?></p>
        </div>

        <!-- Bottom legal bar -->
        <div class="footer__bottom">
            <div class="footer__legal">
                <a href="#"><?php echo $t['legal_terms']; ?></a>
                <a href="#"><?php echo $t['legal_privacy']; ?></a>
                <a href="#"><?php echo $t['legal_service_terms']; ?></a>
                <a href="#"><?php echo $t['legal_do_not_sell']; ?></a>
            </div>
        </div>

    </div>
</footer>
<script>
(function () {
    var toggle = document.getElementById('footerLangToggle');
    var dropdown = document.getElementById('footerLangDropdown');
    if (!toggle || !dropdown) return;

    toggle.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdown.classList.toggle('is-open');
        toggle.classList.toggle('is-open');
    });

    document.addEventListener('click', function () {
        dropdown.classList.remove('is-open');
        toggle.classList.remove('is-open');
    });

    dropdown.querySelectorAll('.footer__lang-option').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            var lang = btn.getAttribute('data-lang');
            document.cookie = 'site_lang=' + lang + ';path=/;max-age=' + (60 * 60 * 24 * 365);
            location.reload();
        });
    });
})();
</script>