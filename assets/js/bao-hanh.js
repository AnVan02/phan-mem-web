document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.warranty-search-form');
    const resultsArea = document.querySelector('.warranty-results-area');
    const searchInput = document.querySelector('#serial-search');
    const clearBtn = document.querySelector('#clear-search');

    function toggleClearBtn() {
        if (!clearBtn || !searchInput) return;
        clearBtn.classList.toggle('is-visible', searchInput.value.length > 0);
    }

    if (searchInput) {
        toggleClearBtn();
        searchInput.addEventListener('input', toggleClearBtn);
    }

    if (clearBtn && searchInput) {
        clearBtn.addEventListener('click', function () {
            searchInput.value = '';
            toggleClearBtn();
            searchInput.focus();
        });
    }

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Ngăn chặn load lại trang

            const searchValue = searchInput ? searchInput.value : '';

            // Hiển thị trạng thái đang tải mượt mà
            resultsArea.innerHTML = `
                <div style="text-align:center; padding: 40px; animation: fadeIn 0.3s ease;">
                    <div style="display:inline-block; width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #DC2626; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                    <p style="margin-top: 15px; color: #64748b; font-weight: 500;">Đang tra cứu hệ thống vui lòng đợi...</p>
                </div>
                <style>@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }</style>
            `;

            // Tạo dữ liệu gửi đi
            const formData = new FormData();
            formData.append('search', searchValue);

            // Fetch dữ liệu ngầm không load lại trang
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(html => {
                    // Cắt lấy mỗi phần hiển thị kết quả để thay thế
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newResults = doc.querySelector('.warranty-results-area');

                    if (newResults) {
                        resultsArea.innerHTML = newResults.innerHTML;
                    } else {
                        resultsArea.innerHTML = '<div class="search-error-msg">Có lỗi hiển thị, vui lòng tải lại website!</div>';
                    }
                })
                .catch(error => {
                    resultsArea.innerHTML = '<div class="search-error-msg">Mất kết nối mạng, vui lòng thử lại!</div>';
                });
        });
    }
});
