<?php
require '../utils/database.php'; // Import file kết nối cơ sở dữ liệu

// Kiểm tra xem tham số id có được truyền qua URL không
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id']; // Lấy id từ URL

    // Kết nối cơ sở dữ liệu
    $pdo = Database::connect();

    // Chuẩn bị câu truy vấn xóa
    $sql = "DELETE FROM sensors WHERE id = :id";

    // Thực thi truy vấn
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Xóa thành công, quay lại trang trước hoặc thông báo
        header("Location: ../admin/admin.php?message=Xóa thành công"); // Điều hướng về trang chính
        exit;
    } else {
        echo "Lỗi: Không thể xóa bản ghi.";
    }

    // Ngắt kết nối
    Database::disconnect();
} else {
    echo "Tham số id không hợp lệ.";
}
