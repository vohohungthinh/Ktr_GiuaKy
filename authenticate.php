<?php
session_start();

// Kết nối vào cơ sở dữ liệu
$conn = mysqli_connect("localhost", "root", "", "ql_nhansu");

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Lấy dữ liệu từ form đăng nhập
$username = $_POST['username'];
$password = $_POST['password'];

// Truy vấn để kiểm tra tài khoản
$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = mysqli_query($conn, $sql);

// Kiểm tra kết quả truy vấn
if (mysqli_num_rows($result) == 1) {
    // Tài khoản đúng, đăng nhập thành công
    $row = mysqli_fetch_assoc($result);
    $_SESSION['username'] = $row['username'];
    $_SESSION['role'] = $row['role'];
    
    // Chuyển hướng tới trang chính của hệ thống
    header("Location: index.php");
} else {
    // Sai tên đăng nhập hoặc mật khẩu, đăng nhập thất bại
    echo "Sai tên đăng nhập hoặc mật khẩu. Vui lòng thử lại.";
}

// Đóng kết nối
mysqli_close($conn);
?>
