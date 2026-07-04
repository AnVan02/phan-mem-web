// Xem trước ảnh đã chọn cho input[type=file][multiple], cộng dồn ảnh qua nhiều lần chọn.
(function () {
    function initAnhInput(input) {
        var selectedFiles = [];

        var preview = document.createElement('div');
        preview.className = 'anh-preview-list';
        input.insertAdjacentElement('afterend', preview);

        function render() {
            preview.innerHTML = '';
            selectedFiles.forEach(function (file, index) {
                var url = URL.createObjectURL(file);
                var item = document.createElement('div');
                item.className = 'anh-preview-item';
                item.innerHTML =
                    '<img src="' + url + '" alt="">' +
                    '<button type="button" class="anh-preview-remove" data-index="' + index + '" title="Bỏ ảnh này">&times;</button>';
                preview.appendChild(item);
            });
        }

        function syncInput() {
            var dt = new DataTransfer();
            selectedFiles.forEach(function (file) { dt.items.add(file); });
            input.files = dt.files;
        }

        input.addEventListener('change', function () {
            selectedFiles = selectedFiles.concat(Array.from(input.files));
            syncInput();
            render();
        });

        preview.addEventListener('click', function (e) {
            var btn = e.target.closest('.anh-preview-remove');
            if (!btn) return;
            selectedFiles.splice(parseInt(btn.dataset.index, 10), 1);
            syncInput();
            render();
        });
    }

    document.querySelectorAll('input[type="file"].js-anh-preview').forEach(initAnhInput);
})();
