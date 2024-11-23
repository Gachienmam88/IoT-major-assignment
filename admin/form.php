<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhập dữ liệu cảm biến</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Nhập Dữ Liệu Cảm Biến</h2>
        <form action="http://localhost/nongnghiep/utils/postdata.php" method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="temperature" class="form-label">Nhiệt độ (°C):</label>
                <input type="number" step="0.1" name="temperature" id="temperature" class="form-control" required>
                <div class="invalid-feedback">Vui lòng nhập nhiệt độ hợp lệ.</div>
            </div>

            <div class="mb-3">
                <label for="humidity" class="form-label">Độ ẩm không khí (%):</label>
                <input type="number" step="0.1" name="humidity" id="humidity" class="form-control" required>
                <div class="invalid-feedback">Vui lòng nhập độ ẩm không khí hợp lệ.</div>
            </div>

            <div class="mb-3">
                <label for="soil" class="form-label">Độ ẩm đất (%):</label>
                <input type="number" step="0.1" name="soil" id="soil" class="form-control" required>
                <div class="invalid-feedback">Vui lòng nhập độ ẩm đất hợp lệ.</div>
            </div>

            <div class="mb-3">
                <label for="light" class="form-label">Cường độ ánh sáng (lux):</label>
                <input type="number" step="1" name="light" id="light" class="form-control" required>
                <div class="invalid-feedback">Vui lòng nhập cường độ ánh sáng hợp lệ.</div>
            </div>

            <div class="mb-3">
                <label for="concentration" class="form-label">Nồng độ CO₂ (ppm):</label>
                <input type="number" step="0.1" name="concentration" id="concentration" class="form-control" required>
                <div class="invalid-feedback">Vui lòng nhập nồng độ CO₂ hợp lệ.</div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Gửi</button>
        </form>
    </div>

    <!-- Bootstrap JS (Validation Script) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script để kích hoạt form validation của Bootstrap
        (function() {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>

</html>