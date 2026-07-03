    <section class="partners-section">
        <div class="container">
            <div class="partners-header">
                <span class="partners-eyebrow">— ĐƯỢC XÂY DỰNG VỚI CÁC THƯƠNG HIỆU BẠN TIN TƯỞNG</span>
                <h2 class="partners-title">Các đối tác <span class="highlight">đáng tin cậy</span> của chúng tôi</h2>
            </div>
        </div>

        <div class="partners-marquee">
            <div class="partners-track">
                <?php
                    // Mỗi thương hiệu: name = tên hiển thị, src = đường dẫn ảnh
                    // (có thể là file local trong assets/image/doi-tac/ hoặc link ảnh online)
                    $doi_tac = [
                        'amd'      => ['name' => 'AMD',      'src' => 'https://img.icons8.com/ios-filled/50/amd.png'],
                        'intel'    => ['name' => 'Intel',    'src' => 'assets/image/doi-tac/intel.png'],
                        'kingston' => ['name' => 'Kingston', 'src' => 'assets/image/doi-tac/kingston.png'],
                        'nvidia'   => ['name' => 'NVIDIA',   'src' => 'https://img.icons8.com/color/48/nvidia.png'],
                        'asus'     => ['name' => 'ASUS',     'src' => 'assets/image/doi-tac/asus.png'],
                        'benq'     => ['name' => 'BENQ',     'src' => 'assets/image/doi-tac/benq.png'],
                        'Asrock'   => ['name' => 'Asrock',     'src' => 'assets/image/doi-tac/Asrock.png'],
                        'lexar'     => ['name' => 'LEXAR',     'src' => 'assets/image/doi-tac/lexar.png'],
                        'unv'   => ['name' => 'UNV',     'src' => 'assets/image/doi-tac/unv.png'],
                        'kingbank'   => ['name' => 'kingbank',     'src' => 'assets/image/doi-tac/Asrock.png'],
                        'agi'     => ['name' => 'AGI',     'src' => 'assets/image/doi-tac/agi.png'],
                        'palit'   => ['name' => 'PALIT',     'src' => 'assets/image/doi-tac/palit.png'],

                        
                    ];

                    // Lặp lại 2 lần để tạo hiệu ứng chạy vô tận không bị ngắt quãng
                    for ($lap = 0; $lap < 2; $lap++) {
                        foreach ($doi_tac as $brand) {
                            echo '<div class="partner-item">';
                            echo '<img src="' . $brand['src'] . '" alt="' . $brand['name'] . '" loading="lazy">';
                            echo '</div>';
                        }
                    }
                ?>
            </div>
        </div>
    </section>
