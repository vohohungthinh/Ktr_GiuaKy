<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Nhân Viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            color: #333;
            padding: 8px 12px;
            text-decoration: none;
            border: 1px solid #ddd;
            margin: 0 4px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .pagination a.active {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }
        .pagination a:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>THÔNG TIN NHÂN VIÊN</h1>
        <table>
            <tr>
                <th>Mã Nhân Viên</th>
                <th>Tên Nhân Viên</th>
                <th>Giới Tính</th>
                <th>Nơi Sinh</th>
                <th>Tên Phòng</th>
                <th>Lương</th>
                <th>Actions</th>
            </tr>
            <?php
            $conn = mysqli_connect("localhost", "root", "", "ql_nhansu");

            if (!$conn) {
                die("Kết nối thất bại: " . mysqli_connect_error());
            }

            $employees_per_page = 5;
            $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
            $offset = ($current_page - 1) * $employees_per_page;

            $sql = "SELECT n.*, p.Ten_Phong FROM NhanVien n JOIN Phongban p ON n.Ma_Phong = p.Ma_Phong LIMIT $offset, $employees_per_page";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$row["Ma_NV"]."</td>";
                    echo "<td>".$row["Ten_NV"]."</td>";
                    echo "<td>";
                    if ($row["Phai"] == "NU") {
                        echo '<img src="img/woman.jpg" alt="Woman" style="height: 60px; width: 60px;">';
                    } else {
                        echo '<img src="img/man.jpg" alt="Man" style="height: 60px; width: 60px;">';
                    }
                    echo "</td>";
                    echo "<td>".$row["Noi_Sinh"]."</td>";
                    echo "<td>".$row["Ten_Phong"]."</td>";
                    echo "<td>".$row["Luong"]."</td>";
                    echo "<td class='actions'>";
                    echo "<a href='edit_nhansu.php?id=".$row['Ma_NV']."'>Sửa</a> | ";
                    echo "<a href='delete_nhansu.php?id=".$row['Ma_NV']."'>Xóa</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "Không có dữ liệu";
            }

            $sql_count = "SELECT COUNT(*) AS total FROM NhanVien";
            $result_count = mysqli_query($conn, $sql_count);
            $row_count = mysqli_fetch_assoc($result_count);
            $total_records = $row_count['total'];
            $total_pages = ceil($total_records / $employees_per_page);

            echo "<div class='pagination'>";
            for ($i = 1; $i <= $total_pages; $i++) {
                echo "<a href='?page=".$i."' class='".($current_page == $i ? "active" : "")."'>".$i."</a> ";
            }
            echo "</div>";

            mysqli_close($conn);
            ?>
        </table>
    </div>
    <?php
        session_start();

        // Kiểm tra đăng nhập
        if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
            header("Location: login.php");
            exit();
        }
        ?>
        
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin Dashboard</title>
        </head>
        <body>
            <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>

        
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <!-- Add, delete, edit functionalities here -->
                <p>Admin </p>
                <!-- Ví dụ: -->
                <ul>
                    <li><a href="add_nhansu.php">THÊM NHÂN VIÊN</a></li>
                </ul>
            <?php else: ?>
                <p>User</p>
            <?php endif; ?>
        
            <!-- Nút logout -->
            <?php

        // Xử lý logout
        if(isset($_POST['logout'])) {
            // Xóa tất cả các biến session
            session_unset();
            
            // Hủy session
            session_destroy();
            
            // Chuyển hướng về trang đăng nhập
            header("Location: login.php");
            exit();
        }
        ?>
            <form method="post" action="">
                <input type="submit" name="logout" value="Logout">
            </form>
        </body>
        </html>
</body>
</html>


