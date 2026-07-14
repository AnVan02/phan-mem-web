document.addEventListener("DOMContentLoaded", function () {

    // ==========================
    // 0. Bộ lọc trên di động (ẩn/hiện sidebar khi bấm nút "Bộ lọc")
    // ==========================
    const filterToggleBtn = document.querySelector("[data-mobile-filter-toggle]");
    if (filterToggleBtn) {
        const targetEl = document.querySelector(filterToggleBtn.dataset.mobileFilterToggle);
        if (targetEl) {
            filterToggleBtn.addEventListener("click", function () {
                const isOpen = targetEl.classList.toggle("is-open");
                filterToggleBtn.classList.toggle("is-active", isOpen);
                filterToggleBtn.setAttribute("aria-expanded", isOpen ? "true" : "false");
            });
        }
    }

    // ==========================
    // 1. Đếm ngược khuyến mãi
    // ==========================
    const countdownEls = document.querySelectorAll("[data-countdown-endofday]");

    if (countdownEls.length > 0) {
        function padSo(n) {
            return String(n).padStart(2, "0");
        }

        function capNhat() {
            const now = new Date();
            const hetHan = new Date(
                now.getFullYear(),
                now.getMonth(),
                now.getDate(),
                23, 59, 59
            );
            const conLai = Math.max(0, hetHan - now);

            const gio = Math.floor(conLai / 3600000);
            const phut = Math.floor((conLai % 3600000) / 60000);
            const giay = Math.floor((conLai % 60000) / 1000);

            const text = `${padSo(gio)}:${padSo(phut)}:${padSo(giay)}`;

            countdownEls.forEach(function (el) {
                el.textContent = text;
            });
        }

        capNhat();
        setInterval(capNhat, 1000);
    }

    // ==========================
    // 2. Xem thêm / Thu gọn (ĐÃ SỬA)
    // ==========================
    // Gán hàm vào window để HTML có thể gọi qua onclick
    window.toggleShowMore = function (btn) {
        const sidebarBlock = btn.closest('.sidebar-block');
        const hiddenItems = sidebarBlock.querySelectorAll('.filter-item.hidden-item');

        // Kiểm tra trạng thái hiện tại dựa vào class active của nút
        const isExpanded = btn.classList.contains('active');
        const newDisplay = isExpanded ? 'none' : 'list-item';

        // Ẩn hoặc hiện các item
        hiddenItems.forEach(item => {
            item.style.display = newDisplay;
        });

        // Đổi trạng thái nút
        btn.classList.toggle('active');
        const spanText = btn.querySelector('span');

        if (btn.classList.contains('active')) {
            spanText.textContent = 'Thu gọn';
        } else {
            spanText.textContent = 'Xem thêm';
        }
    };

    // ==========================
    // 3. Chuyển đổi xem dạng lưới / danh sách
    // ==========================
    const viewToggle = document.querySelector("[data-view-toggle]");
    const viewTarget = document.querySelector("[data-view-target]");

    if (viewToggle && viewTarget) {
        viewToggle.addEventListener("click", function (e) {
            const btn = e.target.closest(".view-toggle-btn");
            if (!btn) return;

            viewToggle.querySelectorAll(".view-toggle-btn").forEach(function (b) {
                b.classList.remove("active");
            });
            btn.classList.add("active");

            viewTarget.classList.toggle("list-view", btn.dataset.view === "list");
        });
    }

    // ==========================
    // 4. Yêu thích / Chia sẻ trên thẻ sản phẩm (danh sách)
    // ==========================
    document.addEventListener("click", function (e) {
        const btnWishlist = e.target.closest(".btn-wishlist");
        if (btnWishlist) {
            e.preventDefault();
            e.stopPropagation();

            const maSp = btnWishlist.dataset.maSanPham;
            const formData = new FormData();
            formData.append("action", "toggle");
            formData.append("ma_san_pham", maSp);

            fetch("yeu-thich-ajax.php", { method: "POST", body: formData })
                .then((res) => res.json())
                .then((data) => {
                    if (data.success) {
                        const icon = btnWishlist.querySelector("i");
                        if (data.status === "added") {
                            btnWishlist.classList.add("active");
                            icon.classList.remove("fa-regular");
                            icon.classList.add("fa-solid");
                        } else {
                            btnWishlist.classList.remove("active");
                            icon.classList.remove("fa-solid");
                            icon.classList.add("fa-regular");
                        }
                    } else {
                        alert(data.message);
                        if (data.message.includes("đăng nhập")) {
                            window.location.href = "tai-khoan.php";
                        }
                    }
                })
                .catch((err) => console.error("Lỗi khi thêm yêu thích:", err));
            return;
        }

        const btnShare = e.target.closest("[data-share-product]");
        if (btnShare) {
            e.preventDefault();
            e.stopPropagation();

            const url = new URL(btnShare.dataset.shareUrl || window.location.href, window.location.href).href;
            const shareData = {
                title: btnShare.dataset.shareTitle || document.title,
                url: url,
            };

            if (navigator.share) {
                navigator.share(shareData).catch(() => {
                    /* người dùng huỷ chia sẻ */
                });
            } else if (navigator.clipboard) {
                navigator.clipboard
                    .writeText(shareData.url)
                    .then(() => alert("Đã sao chép liên kết sản phẩm!"))
                    .catch(() => alert(shareData.url));
            } else {
                alert(shareData.url);
            }
        }
    });
});