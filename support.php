<?php
session_start();
require_once 'db.php';
$message_status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $content = trim($_POST['content']);
    
    if(!empty($name) && !empty($email) && !empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO supports (name, email, content) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $content])) {
            $message_status = "Gửi yêu cầu hỗ trợ thành công! Chúng tôi sẽ phản hồi sớm.";
        } else {
            $message_status = "Có lỗi xảy ra, vui lòng thử lại.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ hỗ trợ</title>
    <link href="sell.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <a href="sell.php">Mua hàng</a>
        <a href="login.php">Đăng nhập</a>
        <a href="support.php">Liên hệ hỗ trợ</a>
    </nav>

    <div class="support-container">
        <h2>Trung tâm Hỗ trợ Khách hàng</h2>
        <p class="desc">Gửi yêu cầu thắc mắc hoặc phản hồi của bạn cho chúng tôi.</p>
        <?php if (!empty($message_status)) { echo "<p style='color:#4CAF50; text-align:center; margin-bottom:15px;'>$message_status</p>"; } ?>
        <form method="POST" action="">
            <div class="form-group">
                <label>Họ và tên</label>
                <input type="text" name="name" placeholder="Nhập họ và tên..." required>
            </div>
            <div class="form-group">
                <label>Email liên hệ</label>
                <input type="email" name="email" placeholder="Nhập email của bạn..." required>
            </div>
            <div class="form-group">
                <label>Nội dung cần hỗ trợ</label>
                <textarea name="content" placeholder="Mô tả chi tiết vấn đề của bạn..." required></textarea>
            </div>
            <button type="submit" class="btn-submit">Gửi Yêu Cầu</button>
        </form>
    </div>
</body>
</html>