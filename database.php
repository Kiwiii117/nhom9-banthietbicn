<?php
declare(strict_types=1);

$host = 'localhost';
$port = 3306;
$database = 'nhom9_banthietbilaptop'; // Tên CSDL mới của nhóm bạn
$username = 'root'; // Tên đăng nhập mặc định của XAMPP
$password = ''; // Mật khẩu mặc định của XAMPP (để trống)

$dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $exception) {
    // Nếu kết nối lỗi, chương trình sẽ dừng và báo lỗi
    exit('Không thể kết nối cơ sở dữ liệu. Vui lòng kiểm tra MySQL và file cấu hình.');
}
?>