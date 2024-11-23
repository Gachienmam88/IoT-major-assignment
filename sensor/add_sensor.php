<?php
require '../utils/database.php'; // Import file kết nối cơ sở dữ liệu

// Xử lý khi biểu mẫu được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $measurement_range = $_POST['measurement_range'];
    $accuracy = $_POST['accuracy'];
    $description = $_POST['description'];
    $node = $_POST['node'];
    $link = $_POST['link'];

    // Kết nối cơ sở dữ liệu
    $pdo = Database::connect();

    // Chuẩn bị câu truy vấn
    $sql = "INSERT INTO sensors (name, measurement_range, accuracy, description, node, link) VALUES (:name, :measurement_range, :accuracy, :description, :node, :link)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':measurement_range', $measurement_range);
    $stmt->bindParam(':accuracy', $accuracy);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':node', $node);
    $stmt->bindParam(':link', $link);

    if ($stmt->execute()) {
        // Thêm thành công, chuyển hướng về trang chính
        header("Location: ../admin/admin.php?message=Thêm thành công");
        exit;
    } else {
        $error = "Lỗi: Không thể thêm cảm biến.";
    }

    // Ngắt kết nối
    Database::disconnect();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Cảm Biến</title>
    <!-- Thêm Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Thêm Cảm Biến Mới</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="add_sensor.php">
            <div class="mb-3">
                <label for="name" class="form-label">Tên Cảm Biến</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="measurement_range" class="form-label">Khoảng Đo</label>
                <input type="text" class="form-control" id="measurement_range" name="measurement_range" required>
            </div>
            <div class="mb-3">
                <label for="accuracy" class="form-label">Độ Chính Xác</label>
                <input type="text" class="form-control" id="accuracy" name="accuracy" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Mô Tả</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="node" class="form-label">Node</label>
                <input type="text" class="form-control" id="node" name="node" required>
            </div>
            <div class="mb-3">
                <label for="link" class="form-label">Link</label>
                <input type="url" class="form-control" id="link" name="link">
            </div>
            <button type="submit" class="btn btn-primary">Thêm</button>
            <a href="admin.php" class="btn btn-secondary">Quay Lại</a>
        </form>
    </div>

    <!-- Thêm JavaScript Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>