<?php
session_start();
require_once 'db.php'; // Biến kết nối là $pdo
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user]);
    $row = $stmt->fetch();

    if ($row) {
        // Kiểm tra mật khẩu (lưu ý: nên dùng password_verify nếu mã hóa mật khẩu)
        if ($pass === $row['password']) {
            $_SESSION['currentUser'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            if ($row['role'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: quanly.php");
            }
            exit();
        } else {
            $error = "Mật khẩu không chính xác!";
        }
    } else {
        $error = "Tên đăng nhập không tồn tại!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link href="sell.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <a href="sell.php">Mua hàng</a>
        <a href="login.php">Đăng nhập</a>
        <a href="support.php">Liên hệ hỗ trợ</a>
    </nav>

    <div class="auth-container">
        <h2>Đăng nhập tài khoản</h2>
        <?php if (!empty($error)) { echo "<p style='color:#E53935; text-align:center; margin-bottom:15px;'>$error</p>"; } ?>
        <form method="POST" action="">
            <div class="form-group">
                <label>Tên đăng nhập hoặc Email</label>
                <input type="text" name="username" placeholder="Nhập tên đăng nhập..." required>
            </div>
            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" placeholder="Nhập mật khẩu..." required>
            </div>
            <button type="submit" class="btn-submit">Đăng Nhập</button>
        </form>
    </div>
</body>
</html>