<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['currentUser'])) { header("Location: login.php"); exit(); }

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM supports WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header("Location: quanly_supports.php");
    exit();
}

$supports = $pdo->query("SELECT * FROM supports ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý hỗ trợ khách hàng</title>
    <link href="sell.css" rel="stylesheet">
    <style>
        .container-box { max-width: 1100px; margin: 30px auto; padding: 0 15px; }
        .table-custom { width: 100%; border-collapse: collapse; background: #2b2b2b; border-radius: 12px; overflow: hidden; margin-top: 15px; }
        .table-custom th, .table-custom td { padding: 12px; text-align: left; border-bottom: 1px solid #444; font-size: 0.9rem; }
        .table-custom th { background: #1f1f1f; color: #E53935; }
        .btn-del { padding: 5px 10px; border-radius: 4px; text-decoration: none; font-size: 0.8rem; color: #fff; background: #E53935; }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="quanly.php">Quản lý chung</a>
        <a href="sell.php">Trang chủ</a>
        <a href="login.php?logout=true">Đăng xuất</a>
    </nav>

    <div class="container-box">
        <h2>Danh Sách Yêu Cầu Hỗ Trợ Từ Khách Hàng</h2>
        <div style="overflow-x:auto;">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Nội dung cần hỗ trợ</th>
                        <th>Thời gian gửi</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($supports) > 0): foreach($supports as $s): ?>
                    <tr>
                        <td><?php echo $s['id']; ?></td>
                        <td><b><?php echo htmlspecialchars($s['name']); ?></b></td>
                        <td><?php echo htmlspecialchars($s['email']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($s['content'])); ?></td>
                        <td><small style="color:#aaa;"><?php echo $s['created_at']; ?></small></td>
                        <td><a href="quanly_supports.php?action=delete&id=<?php echo $s['id']; ?>" class="btn-del" onclick="return confirm('Xóa phản hồi này?');">Xóa</a></td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="6" style="text-align:center; color:#aaa; padding:20px;">Chưa có yêu cầu hỗ trợ nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>