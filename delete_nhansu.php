<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// Kiểm tra vai trò của người dùng
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Kiểm tra xem id của nhân viên cần xóa có được gửi đến từ trang index.php không
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

// Kết nối CSDL
$conn = mysqli_connect("localhost", "root", "", "ql_nhansu");

if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Lấy id của nhân viên cần xóa từ tham số GET
$id = $_GET['id'];

// Lấy thông tin của nhân viên từ CSDL
$sql = "SELECT * FROM NhanVien WHERE Ma_NV = '$id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "Không tìm thấy nhân viên!";
    mysqli_close($conn);
    exit();
}

$row = mysqli_fetch_assoc($result);

// Hiển thị thông báo xác nhận
echo "<p>Bạn có muốn xóa nhân viên <strong>{$row['Ten_NV']}</strong>?</p>";

// Nút Xác nhận xóa
echo "<form method='post' action=''>";
echo "<input type='hidden' name='id' value='{$row['Ma_NV']}'>";
echo "<input type='submit' name='delete' value='Xác Nhận'>";
echo "</form>";

// Nút Hủy bỏ
echo "<a href='index.php'>Hủy</a>";

// Xử lý khi người dùng nhấn nút Xác nhận xóa
if (isset($_POST['delete'])) {
    // Xóa nhân viên từ CSDL
    $sql_delete = "DELETE FROM NhanVien WHERE Ma_NV = '$id'";

    if (mysqli_query($conn, $sql_delete)) {
        // Đã xóa thành công, chuyển hướng về trang index.php
        header("Location: index.php");
    } else {
        echo "Lỗi khi xóa nhân viên: " . mysqli_error($conn);
    }
}

// Đóng kết nối CSDL
mysqli_close($conn);
?>
