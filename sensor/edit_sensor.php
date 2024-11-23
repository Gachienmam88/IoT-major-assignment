<?php
require '../utils/database.php'; // Import file kết nối cơ sở dữ liệu

// Lấy ID từ URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Kết nối cơ sở dữ liệu
$pdo = Database::connect();

// Lấy thông tin cảm biến từ CSDL
$sql = "SELECT * FROM sensors WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$sensor = $stmt->fetch(PDO::FETCH_ASSOC);

// Nếu không tìm thấy cảm biến, hiển thị lỗi
if (!$sensor) {
    die("Không tìm thấy cảm biến với ID $id.");
}

// Xử lý khi biểu mẫu được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $measurement_range = $_POST['measurement_range'];
    $accuracy = $_POST['accuracy'];
    $description = $_POST['description'];
    $node = $_POST['node'];
    $link = $_POST['link'];

    // Cập nhật thông tin cảm biến trong CSDL
    $updateSql = "UPDATE sensors SET name = :name, measurement_range = :measurement_range, accuracy = :accuracy, description = :description, node = :node, link = :link WHERE id = :id";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->bindParam(':name', $name);
    $updateStmt->bindParam(':measurement_range', $measurement_range);
    $updateStmt->bindParam(':accuracy', $accuracy);
    $updateStmt->bindParam(':description', $description);
    $updateStmt->bindParam(':node', $node);
    $updateStmt->bindParam(':link', $link);
    $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($updateStmt->execute()) {
        // Cập nhật thành công, chuyển hướng về trang chính
        header("Location: index.php?message=Cập nhật thành công");
        exit;
    } else {
        $error = "Lỗi: Không thể cập nhật cảm biến.";
    }
}

// Ngắt kết nối
Database::disconnect();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập Nhật Cảm Biến</title>
    <!-- Thêm Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Cập Nhật Cảm Biến</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="update_sensor.php?id=<?php echo $id; ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Tên Cảm Biến</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($sensor['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="measurement_range" class="form-label">Khoảng Đo</label>
                <input type="text" class="form-control" id="measurement_range" name="measurement_range" value="<?php echo htmlspecialchars($sensor['measurement_range']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="accuracy" class="form-label">Độ Chính Xác</label>
                <input type="text" class="form-control" id="accuracy" name="accuracy" value="<?php echo htmlspecialchars($sensor['accuracy']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Mô Tả</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($sensor['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="node" class="form-label">Node</label>
                <input type="text" class="form-control" id="node" name="node" value="<?php echo htmlspecialchars($sensor['node']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="link" class="form-label">Link</label>
                <input type="url" class="form-control" id="link" name="link" value="<?php echo htmlspecialchars($sensor['link']); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Cập Nhật</button>
            <a href="index.php" class="btn btn-secondary">Quay Lại</a>
        </form>
    </div>

    <!-- Thêm JavaScript Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>