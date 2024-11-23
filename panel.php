<div class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="admin.php" style="color:white; padding: 10px; background-color: <?php echo basename($_SERVER['PHP_SELF']) == 'admin.php' ? '#073845' : '#0c6980'; ?>;">Dashboard</a></li>
        <li><a href="manage_users.php" style="color:white; padding: 10px; background-color: <?php echo basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? '#073845' : '#0c6980'; ?>;">Manage Users</a></li>
    </ul>
</div>

<style>
    .sidebar {
        width: 20%;
        background-color: #0c6980;
        color: white;
        float: left;
        padding: 15px;
        height: 100vh; /* Đảm bảo sidebar chiếm toàn bộ chiều cao */
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .sidebar ul {
        list-style-type: none;
        padding: 0;
    }

    .sidebar ul li {
        margin: 10px 0;
    }

    .sidebar ul li a {
        display: block;
        text-decoration: none;
        color: white;
        border-radius: 5px;
        padding: 10px;
        background-color: #0c6980;
        text-align: center;
    }

    .sidebar ul li a:hover {
        background-color: #073845;
    }
</style>
