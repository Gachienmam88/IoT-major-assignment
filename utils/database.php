<?php
class Database {
    private static $dbName = 'totnghiep';  // Tên cơ sở dữ liệu của bạn
    private static $dbHost = 'localhost';  // Máy chủ cơ sở dữ liệu (localhost khi dùng XAMPP)
    private static $dbUsername = 'root';   // Tài khoản MySQL (mặc định là root)
    private static $dbUserPassword = '';   // Mật khẩu MySQL (để trống nếu không có)

    private static $cont  = null;

    public function __construct() {
        die('Init function is not allowed');
    }

    public static function connect() {
        // Một kết nối duy nhất cho toàn bộ ứng dụng
        if (null == self::$cont) {     
            try {
                self::$cont = new PDO("mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword); 
            } catch(PDOException $e) {
                die($e->getMessage()); 
            }
        }
        return self::$cont;
    }

    public static function disconnect() {
        self::$cont = null;
    }
}
?>