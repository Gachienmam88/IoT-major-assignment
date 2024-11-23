<?php
session_start();

// Kiểm tra nếu người dùng chưa đăng nhập hoặc không phải admin, chuyển hướng về trang đăng nhập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Kết nối cơ sở dữ liệu
require '../utils/database.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 15%;
            background-color: #0c6980;
            padding: 20px;
            color: white;
            height: 100vh;
        }

        .sidebar h2 {
            text-align: center;
            font-size: 1.5rem;
        }

        .sidebar a {
            text-decoration: none;
            color: white;
            display: block;
            padding: 10px 0;
            background-color: #094c5d;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 10px;
        }

        .sidebar a:hover {
            background-color: #073845;
        }

        .main-content {
            width: 85%;
            padding: 20px;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #0c6980;
            color: white;
            padding: 10px 20px;
            position: relative;
        }

        .header h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        .header .actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .main-content h1 {
            text-align: center;
            font-size: 2rem;
        }

        .logout-btn {
            position: absolute;
            right: 20px;
            top: 20px;
            background-color: #f44336;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            font-size: 1rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }

        .notification-btn {
            position: absolute;
            right: 120px;
            top: 20px;
            background-color: transparent;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .notification-btn:hover {
            color: #f0f0f0;
        }

        .notification-dropdown {
            position: absolute;
            top: 50px;
            /* Đặt cách nút chuông một khoảng */
            right: 20px;
            /* Canh phải */
            width: 500px;
            /* Chiều rộng cố định */
            height: 70vh;
            /* Chiều cao tối đa bằng 1/2 chiều cao màn hình */
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow-y: auto;
            /* Hiển thị thanh cuộn nếu nội dung vượt quá */
            z-index: 1000;
            display: none;
            /* Ẩn ban đầu */
        }

        .notification-dropdown.active {
            display: flex;
            flex-direction: column;
        }

        .notification-item {
            height: 7vh;
            /* Chiều cao dòng */
            padding: 0;
            /* Loại bỏ padding để nội dung căn giữa chính xác */
            text-align: center;
            /* Căn giữa theo chiều ngang */
            display: flex;
            /* Dùng flexbox để căn giữa */
            align-items: center;
            /* Căn giữa theo chiều dọc */
            justify-content: center;
            /* Căn giữa theo chiều ngang */
            font-size: 18px;
            /* Kích thước chữ */
            font-weight: 500;
            /* Độ đậm của chữ */
            border-bottom: 1px solid #ddd;
            /* Đường viền dưới */
            text-decoration: none;
            /* Bỏ gạch chân */
            color: #333;
            border: 1px solid black !important;
            /* Màu chữ */
        }

        .notification-item:hover {
            background-color: #f5f5f5;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .logout-btn:hover {
            background-color: #d32f2f;
        }

        .device-grid {
            display: flex;
            justify-content: space-evenly;
            width: 100%;
            margin-top: 20px;
        }

        .device {
            background-color: #f5f5f5;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 18%;
        }

        .device button {
            padding: 10px 20px;
            font-size: 1rem;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .on-btn {
            background-color: #4CAF50;
            color: white;
        }

        .off-btn {
            background-color: #f44336;
            color: white;
        }

        .device-state {
            margin-top: 10px;
            font-weight: bold;
        }

        /* CSS cho phần thông số kỹ thuật */
        .specifications {
            margin-top: 40px;
            width: 100%;
        }

        .specifications h2 {
            text-align: center;
            font-size: 1.8rem;
        }

        .specs-table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        .specs-table th,
        .specs-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .specs-table th {
            background-color: #0c6980;
            color: white;
        }
    </style>
</head>

<body>
    <script>
        function toggleDeviceState(deviceId, state) {
            // Gửi yêu cầu AJAX để cập nhật trạng thái
            fetch("../devices/update_device.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        deviceId,
                        state
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Cập nhật trạng thái trên giao diện
                        const deviceStateElement = document.getElementById(`state-${deviceId}`);
                        deviceStateElement.textContent = `State: ${state}`;
                    } else {
                        alert("Cập nhật trạng thái thất bại!");
                    }
                })
                .catch(error => console.error("Lỗi:", error));
        }
    </script>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="../admin/admin.php">Dashboard</a>
        <a href="../users/manage_users.php">Manage Users</a>
        <a href="../home.php">Home</a>
    </div>

    <div class="main-content">
        <h1>Admin Dashboard</h1>
        <button class="notification-btn" onclick="toggleNotifications()">🔔</button>
        <a href="logout.php" class="logout-btn">Logout</a>

        <!-- Phần bật tắt thiết bị -->
        <div id="notificationDropdown" class="notification-dropdown">
            <?php
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            function timeElapsedString($datetime, $full = false)
            {
                $now = new DateTime(); // Thời gian hiện tại
                $ago = new DateTime($datetime); // Thời gian thông báo được tạo
                $diff = $now->diff($ago); // Sự khác biệt giữa hai thời gian

                $string = [
                    'year' => $diff->y,
                    'month' => $diff->m,
                    'week' => floor($diff->d / 7),
                    'day' => $diff->d,
                    'hour' => $diff->h,
                    'minute' => $diff->i,
                    'second' => $diff->s,
                ];

                foreach ($string as $k => &$v) {
                    if ($v) {
                        $v = $v . ' ' . $k . ($v > 1 ? 's' : '');
                    } else {
                        unset($string[$k]);
                    }
                }

                if (!$full) $string = array_slice($string, 0, 1);
                return $string ? implode(', ', $string) . ' ago' : 'just now';
            }

            $pdo = Database::connect();
            $sql = 'SELECT content, created_at FROM notification ORDER BY created_at DESC';
            foreach ($pdo->query($sql) as $row) {
                $timeElapsed = timeElapsedString($row['created_at']);
                echo '<a href="admin.php" class="notification-item">';
                echo htmlspecialchars($row['content']) . ' <small class="text-muted">(' . $timeElapsed . ')</small>';
                echo '</a>';
            }
            Database::disconnect();
            ?>
        </div>
        <div class="device-grid">
            <div class="device">
                <h3>Quạt</h3>
                <h3>Node 1</h3>
                <button class="on-btn" onclick="toggleDeviceState('fan', 'ON')">ON</button>
                <button class="off-btn" onclick="toggleDeviceState('fan', 'OFF')">OFF</button>
                <div class="device-state" id="state-fan">State: OFF</div>
            </div>
            <div class="device">
                <h3>Máy bơm</h3>
                <h3>Node 1</h3>
                <button class="on-btn" onclick="toggleDeviceState('pump', 'ON')">ON</button>
                <button class="off-btn" onclick="toggleDeviceState('pump', 'OFF')">OFF</button>
                <div class="device-state" id="state-pump">State: OFF</div>
            </div>
            <div class="device">
                <h3>Điều khiển mái che</h3>
                <h3>Node 2</h3>
                <button class="on-btn" onclick="toggleDeviceState('shade', 'ON')">ON</button>
                <button class="off-btn" onclick="toggleDeviceState('shade', 'OFF')">OFF</button>
                <div class="device-state" id="state-shade">State: OFF</div>
            </div>
        </div>

        <!-- Phần hiển thị thông số kỹ thuật dưới dạng bảng -->
        <div class="specifications">
            <h2>Thông số kỹ thuật của các cảm biến Node 1</h2>

            <!-- Nút thêm cảm biến -->
            <div class="add-sensor">
                <a style="padding:10px;font-size:30px; color:blue;" href="../sensor/add_sensor.php" class="add-btn">
                    <i class="fa-solid fa-plus"></i> Thêm cảm biến
                </a>
            </div>

            <table class="specs-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Model</th>
                        <th>Measurement Range</th>
                        <th>Accuracy</th>
                        <th>Description</th>
                        <th>Action</th>
                        <th>State</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Kết nối cơ sở dữ liệu
                    $pdo = Database::connect();
                    $sql = "SELECT * FROM sensors where node = 'Node 1'";
                    foreach ($pdo->query($sql) as $row) {
                        echo '<tr>';
                        echo '<td>' . $row['name'] . '</td>';
                        echo '<td>' . $row['model'] . '</td>';
                        echo '<td>' . $row['measurement_range'] . '</td>';
                        echo '<td>' . $row['accuracy'] . '</td>';
                        echo '<td>' . $row['description'] . '</td>';
                        echo '<td>';
                        echo '<a style="padding:10px;font-size:30px; color:blue;" href="' . $row['link'] . '" title="Xem chi tiết"><i class="fas fa-eye"></i></a> ';
                        echo '<a style="padding:10px;font-size:30px; color:yellow;" href="../sensor/edit_sensor.php?id=' . $row['id'] . '" title="Sửa"><i class="fas fa-edit"></i></a> ';
                        echo '<a style="padding:10px;font-size:30px; color:red;" href="../sensor/delete_sensor.php?id=' . $row['id'] . '" title="Xóa" onclick="return confirm(\'Bạn có chắc muốn xóa?\')"><i class="fas fa-trash"></i></a>';;
                        echo '</td>';
                        echo '<td>';
                        if ($row['state'] === 'online') {
                            echo '<span style="display: inline-block; width: 10px; height: 10px; background-color: green; border-radius: 50%;"></span> Online';
                        } else {
                            echo '<span style="display: inline-block; width: 10px; height: 10px; background-color: red; border-radius: 50%;"></span> Offline';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                    Database::disconnect();
                    ?>
                </tbody>
            </table>
        </div>
        <div class="specifications">
            <h2>Thông số kỹ thuật của các cảm biến Node 2</h2>

            <!-- Nút thêm cảm biến -->
            <div class="add-sensor">
                <a style="padding:10px;font-size:30px; color:blue;" href="../sensor/add_sensor.php" class="add-btn">
                    <i class="fa-solid fa-plus"></i> Thêm cảm biến
                </a>
            </div>

            <table class="specs-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Model</th>
                        <th>Measurement Range</th>
                        <th>Accuracy</th>
                        <th>Description</th>
                        <th>Action</th>
                        <th>State</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Kết nối cơ sở dữ liệu
                    $pdo = Database::connect();
                    $sql = "SELECT * FROM sensors where node = 'Node 2'";
                    foreach ($pdo->query($sql) as $row) {
                        echo '<tr>';
                        echo '<td>' . $row['name'] . '</td>';
                        echo '<td>' . $row['model'] . '</td>';
                        echo '<td>' . $row['measurement_range'] . '</td>';
                        echo '<td>' . $row['accuracy'] . '</td>';
                        echo '<td>' . $row['description'] . '</td>';
                        echo '<td>';
                        echo '<a style="padding:10px;font-size:30px; color:blue;" href="' . $row['link'] . '" title="Xem chi tiết"><i class="fas fa-eye"></i></a> ';
                        echo '<a style="padding:10px;font-size:30px; color:yellow;" href="../sensor/edit_sensor.php?id=' . $row['id'] . '" title="Sửa"><i class="fas fa-edit"></i></a> ';
                        echo '<a style="padding:10px;font-size:30px; color:red;" href="../sensor/delete_sensor.php?id=' . $row['id'] . '" title="Xóa" onclick="return confirm(\'Bạn có chắc muốn xóa?\')"><i class="fas fa-trash"></i></a>';
                        echo '</td>';
                        echo '<td>';
                        if ($row['state'] === 'online') {
                            echo '<span style="display: inline-block; width: 10px; height: 10px; background-color: green; border-radius: 50%;"></span> Online';
                        } else {
                            echo '<span style="display: inline-block; width: 10px; height: 10px; background-color: red; border-radius: 50%;"></span> Offline';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                    Database::disconnect();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('active');
        }
    </script>
</body>

</html>