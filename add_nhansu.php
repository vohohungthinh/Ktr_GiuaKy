<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// Kiểm tra vai trò
if ($_SESSION['role'] !== 'admin') {
    header("Location: main.php"); // Chuyển hướng người dùng về trang chính
    exit();
}

// Xử lý form nếu được gửi đi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Xử lý dữ liệu từ form
    $maNV = $_POST['maNV'];
    $tenNV = $_POST['tenNV'];
    $phai = $_POST['phai'];
    $noiSinh = $_POST['noiSinh'];
    $maPhong = $_POST['maPhong'];
    $luong = $_POST['luong'];

    // Thực hiện thêm nhân viên vào CSDL (cần thay đổi query dựa trên cấu trúc của CSDL)
    $conn = mysqli_connect("localhost", "root", "", "ql_nhansu");

    if (!$conn) {
        die("Kết nối thất bại: " . mysqli_connect_error());
    }

    $sql = "INSERT INTO NhanVien (Ma_NV, Ten_NV, Phai, Noi_Sinh, Ma_Phong, Luong)
            VALUES ('$maNV', '$tenNV', '$phai', '$noiSinh', '$maPhong', '$luong')";

    if (mysqli_query($conn, $sql)) {
        // Nếu thêm thành công, chuyển hướng người dùng về trang chính
        header("Location: index.php");
        exit();
    } else {
        echo "Lỗi: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
</head>
<body>
    <h2>Add Employee</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Mã Nhân Viên: <input type="text" name="maNV" required><br><br>
        Tên Nhân Viên: <input type="text" name="tenNV" required><br><br>
        Giới tính: 
        <input type="radio" name="phai" value="NAM" checked> Nam
        <input type="radio" name="phai" value="NU"> Nữ<br><br>
        Nơi Sinh: <input type="text" name="noiSinh" required><br><br>
        Mã Phòng: <input type="text" name="maPhong" required><br><br>
        Lương: <input type="number" name="luong" required><br><br>
        <input type="submit" value="Add Employee">
    </form>
</body>
</html>

