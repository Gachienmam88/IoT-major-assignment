<?php
include '../utils/database.php';
require '../vendor/autoload.php'; // Gọi thư viện PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendEmail($recipientEmail, $message)
{
    $mail = new PHPMailer(true);

    try {
        // Cấu hình SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'chipchip7a2@gmail.com'; // Gmail của bạn
        $mail->Password = 'vzue ijsf bxhe ooko';   // Mật khẩu ứng dụng của bạn
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Người gửi và người nhận
        $mail->setFrom('chipchip7a2@gmail.com', 'Hệ thống cảnh báo');
        $mail->addAddress($recipientEmail); // Email người nhận

        // Tiêu đề email
        $mail->Subject = 'Cảnh báo từ hệ thống IoT';

        // Nội dung HTML email (Bootstrap style)
        $mailContent = "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <title>Email thông báo</title>
</head>
<body>
    <div class='container' style='max-width: 600px; margin: 20px auto; border: 1px solid #ddd; border-radius: 8px; padding: 20px; background-color: #f9f9f9;'>
        <h3 class='text-center text-primary'>Thông báo từ hệ thống</h3>
        <p style='font-size: 16px; color: #555;'>
            Xin chào,<br>
            Hệ thống của bạn đã phát hiện ra một sự kiện quan trọng:
        </p>
        <p style='font-size: 18px; font-weight: bold; color: #d9534f;'>{$message}</p>
        <div class='text-center'>
            <a href='http://yourwebsite.com/detail_page.php' class='btn btn-primary' style='padding: 10px 20px; font-size: 16px; text-decoration: none;'>Xem chi tiết</a>
        </div>
        <p style='font-size: 14px; color: #777; margin-top: 20px;'>
            Đây là email tự động từ hệ thống IoT của bạn. Vui lòng không trả lời email này.
        </p>
    </div>
</body>
</html>
";

        // Thiết lập nội dung email
        $mail->isHTML(true);
        $mail->Body = $mailContent;

        // Gửi email
        $mail->send();
        echo "Email đã được gửi thành công!";
    } catch (Exception $e) {
        echo "Không thể gửi email. Lỗi: {$mail->ErrorInfo}";
    }
}
if (!empty($_POST)) {
    // Nhận dữ liệu từ form
    $temperature = $_POST['temperature'];
    $humidity = $_POST['humidity'];
    $soil = $_POST['soil'];
    $light = $_POST['light'];
    $concentration = $_POST['concentration'];

    // Tạo ID ngẫu nhiên 9 ký tự cho bảng recorddata
    $recordId = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"), 0, 9);
    $boardId = "esp8266_01";
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $currentDateTime = date('Y-m-d H:i:s'); // Lấy thời gian hiện tại

    try {
        // Kết nối cơ sở dữ liệu
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Thêm dữ liệu vào bảng "recorddata"
        $sqlInsertRecord = "INSERT INTO recorddata (id, board, temperature, humidity, soil, light, concentration, time, date) 
                            VALUES (:id, :board, :temperature, :humidity, :soil, :light, :concentration, :time, :date)";
        $stmt = $pdo->prepare($sqlInsertRecord);
        $stmt->execute([
            ':id' => $recordId,
            ':board' => $boardId,
            ':temperature' => $temperature,
            ':humidity' => $humidity,
            ':soil' => $soil,
            ':light' => $light,
            ':concentration' => $concentration,
            ':time' => $currentDateTime,
            ':date' => $currentDateTime,
        ]);

        // Kiểm tra xem ID đã tồn tại trong bảng "updatesdata" hay chưa
        $sqlCheck = "SELECT COUNT(*) FROM updatesdata WHERE id = :id";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute([':id' => $boardId]);
        $rowCount = $stmtCheck->fetchColumn();

        if ($rowCount > 0) {
            // Nếu đã tồn tại, tiến hành cập nhật
            $sqlUpdate = "UPDATE updatesdata 
                          SET temperature = :temperature, 
                              humidity = :humidity, 
                              soil = :soil, 
                              light = :light, 
                              concentration = :concentration, 
                              time = :time, 
                              date = :date 
                          WHERE id = :id";
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $stmtUpdate->execute([
                ':id' => $boardId,
                ':temperature' => $temperature,
                ':humidity' => $humidity,
                ':soil' => $soil,
                ':light' => $light,
                ':concentration' => $concentration,
                ':time' => $currentDateTime,
                ':date' => $currentDateTime,
            ]);
            echo "Cập nhật dữ liệu thành công trong updatesdata.";
        } else {
            // Nếu chưa tồn tại, thêm mới
            $sqlInsert = "INSERT INTO updatesdata (id, temperature, humidity, soil, light, concentration, time, date) 
                          VALUES (:id, :temperature, :humidity, :soil, :light, :concentration, :time, :date)";
            $stmtInsert = $pdo->prepare($sqlInsert);
            $stmtInsert->execute([
                ':id' => $boardId,
                ':temperature' => $temperature,
                ':humidity' => $humidity,
                ':soil' => $soil,
                ':light' => $light,
                ':concentration' => $concentration,
                ':time' => $currentDateTime,
                ':date' => $currentDateTime,
            ]);
            echo "Thêm mới dữ liệu thành công trong updatesdata.";
        }

        $deviceStatesQuery = $pdo->prepare("SELECT name, status FROM devices WHERE name IN ('fan', 'pump', 'shade')");
        $deviceStatesQuery->execute();
        $deviceStates = $deviceStatesQuery->fetchAll(PDO::FETCH_ASSOC);


        $deviceStateMap = [];
        foreach ($deviceStates as $device) {
            $deviceStateMap[$device['name']] = $device['status'];
        }


        if ($temperature >= 30 && $deviceStateMap['fan'] === 'OFF') {
            $message = 'Nhiệt độ lớn hơn 30 độ C, đã bật quạt';
            $updateFan = $pdo->prepare("UPDATE devices SET status = 'ON', last_activated = NOW() WHERE name = 'fan'");
            $updateFan->execute();
            $insertNotification = $pdo->prepare("INSERT INTO notification (content) VALUES (?)");
            $insertNotification->execute([$message]);
            sendEmail('chipchip9a5@gmail.com', $message);
        } elseif ($temperature < 30 && $deviceStateMap['fan'] === 'ON') {
            $message = 'Nhiệt độ đã giảm, quạt đã được tắt';
            $updateFan = $pdo->prepare("UPDATE devices SET status = 'OFF',last_activated = NOW() WHERE name = 'fan'");
            $updateFan->execute();
            $insertNotification = $pdo->prepare("INSERT INTO notification (content) VALUES (?)");
            $insertNotification->execute([$message]);
            sendEmail('chipchip9a5@gmail.com', $message);
        }


        if ($soil < 80 && $deviceStateMap['pump'] === 'OFF') {
            $message = 'Độ ẩm đất nhỏ hơn 80%, đã bật máy bơm';
            $updatePump = $pdo->prepare("UPDATE devices SET status = 'ON', last_activated = NOW() WHERE name = 'pump'");
            $updatePump->execute();
            $insertNotification = $pdo->prepare("INSERT INTO notification (content) VALUES (?)");
            $insertNotification->execute([$message]);
            sendEmail('chipchip9a5@gmail.com', $message);
        } elseif ($soil >= 80 && $deviceStateMap['pump'] === 'ON') {
            $message = 'Độ ẩm đất đã đạt ngưỡng, máy bơm đã được tắt';
            $updatePump = $pdo->prepare("UPDATE devices SET status = 'OFF',last_activated = NOW() WHERE name = 'pump'");
            $updatePump->execute();
            $insertNotification = $pdo->prepare("INSERT INTO notification (content) VALUES (?)");
            $insertNotification->execute([$message]);
            sendEmail('chipchip9a5@gmail.com', $message);
        }


        if ($light > 2000 && $deviceStateMap['shade'] === 'OFF') {
            $message = 'Cường độ ánh sáng lớn hơn 2000, đã bật mái che';
            $updateShade = $pdo->prepare("UPDATE devices SET status = 'ON', last_activated = NOW() WHERE name = 'shade'");
            $updateShade->execute();
            $insertNotification = $pdo->prepare("INSERT INTO notification (content) VALUES (?)");
            $insertNotification->execute([$message]);
            sendEmail('chipchip9a5@gmail.com', $message);
        } elseif ($light <= 2000 && $deviceStateMap['shade'] === 'ON') {
            $message = 'Cường độ ánh sáng đã giảm, mái che đã được tắt';
            $updateShade = $pdo->prepare("UPDATE devices SET status = 'OFF',last_activated = NOW() WHERE name = 'shade'");
            $updateShade->execute();
            $insertNotification = $pdo->prepare("INSERT INTO notification (content) VALUES (?)");
            $insertNotification->execute([$message]);
            sendEmail('chipchip9a5@gmail.com', $message);
        }
    } catch (PDOException $e) {
        echo "Lỗi: " . $e->getMessage();
    }

    // Đóng kết nối
    Database::disconnect();
} else {
    echo "Không có dữ liệu được gửi.";
}
$sql = "SELECT name, last_activated FROM devices";
foreach ($pdo->query($sql) as $device) {
    $lastActivated = strtotime($device['last_activated']);
    $currentTime = time();
    $elapsedTime = ($currentTime - $lastActivated) / 60;

    if ($elapsedTime >= 30) {
        if ($device['name'] === 'fan' && $temperature >= 30) {
            $insertNotification = $pdo->prepare("INSERT INTO notification (content) VALUES (?)");
            $insertNotification->execute(['Nhiệt độ không giảm, quạt có thể bị hỏng.']);
        }
        if ($device['name'] === 'pump' && $soil < 80) {
            $insertNotification = $pdo->prepare("INSERT INTO notification (content) VALUES (?)");
            $insertNotification->execute(['Độ ẩm đất không tăng, máy bơm có thể bị hỏng.']);
        }
        if ($device['name'] === 'shade' && $light > 2000) {
            $insertNotification = $pdo->prepare("INSERT INTO notification (content) VALUES (?)");
            $insertNotification->execute(['Cường độ ánh sáng không giảm, mái che có thể bị hỏng.']);
        }
    }
}
