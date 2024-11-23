<?php
include '../utils/database.php';

// Lấy dữ liệu từ AJAX
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['deviceId']) && isset($data['state'])) {
    $deviceId = $data['deviceId'];
    $state = $data['state'];

    try {
        // Kết nối cơ sở dữ liệu
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Cập nhật trạng thái thiết bị
        $sql = "UPDATE devices SET status = :state WHERE name = :device_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':state' => $state, ':device_id' => $deviceId]);

        // Trả về JSON thông báo thành công
        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        // Trả về JSON thông báo lỗi
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    } finally {
        Database::disconnect();
    }
} else {
    echo json_encode(["success" => false, "error" => "Dữ liệu không hợp lệ."]);
}
