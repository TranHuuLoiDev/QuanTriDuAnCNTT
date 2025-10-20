<?php
$host = "localhost";
$user = "root";     // tài khoản mặc định của XAMPP
$pass = "";          // mật khẩu (nếu có thì điền vào)
$dbname = "bookstore";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Kết nối thất bại: " . $conn->connect_error]));
}

$conn->set_charset("utf8");
?>
