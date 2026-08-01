<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['currentUser'])) { header("Location: login.php"); exit(); }

$msg = "";

// Thêm sản phẩm mới
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $price = (float)$_POST['price'];
    $image = trim($_POST['image']);
    $category = trim($_POST['category']);
    
    if (!empty($name) && $price > 0 && !empty($image)) {
        $link = "checkout.php"; // Link mặc định khi bấm vào sản phẩm
        $stmt = $pdo->prepare("INSERT INTO products (name, price, image, category, link) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $price, $image, $category, $link])) {
            $msg = "Thêm sản phẩm thành công!";
        } else {
            $msg = "Lỗi khi thêm sản phẩm!";
        }
    } else {
        $msg = "Vui lòng nhập đầy đủ thông tin hợp lệ!";
    }
}

// Xóa sản phẩm
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    if ($stmt->execute([$_GET['id']])) {
        $msg = "Đã xóa sản phẩm thành công!";
    }
}

$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm</title>
    <link href="sell.css" rel="stylesheet">
    <style>
        .container-box { max-width: 1100px; margin: 30px auto; padding: 0 15px; }
        .form-box { background: #2b2b2b; padding: 20px; border-radius: 12px; margin-bottom: 30px; }
        .table-custom { width: 100%; border-collapse: collapse; background: #2b2b2b; border-radius: 12px; overflow: hidden; }
        .table-custom th, .table-custom td { padding: 12px; text-align: left; border-bottom: 1px solid #444; font-size: 0.9rem; }
        .table-custom th { background: #1f1f1f; color: #E53935; }
        .btn-act { padding: 5px 10px; border-radius: 4px; text-decoration: none; font-size: 0.8rem; color: #fff; background: #E53935; }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="quanly.php">Quản lý chung</a>
        <a href="sell.php">Trang chủ</a>
        <a href="login.php?logout=true">Đăng xuất</a>
    </nav>

    <div class="container-box">
        <h2>Quản Lý Sản Phẩm Cửa Hàng</h2>
        <?php if($msg): ?><p style="color:#4CAF50; text-align:center; margin:10px 0; font-weight:bold;"><?php echo $msg; ?></p><?php endif; ?>

        <div class="form-box">
            <h3>Thêm sản phẩm mới</h3>
            <form method="POST">
                <div class="form-group"><label>Tên điện thoại</label><input type="text" name="name" placeholder="VD: iPhone 17 Pro..." required></div>
                <div class="form-group"><label>Giá tiền (VNĐ)</label><input type="number" name="price" placeholder="VD: 25000000" required></div>
                <div class="form-group"><label>Tên file ảnh (Trong thư mục gốc)</label><input type="text" name="image" placeholder="VD: i17prm.jpg" required></div>
                <div class="form-group"><label>Danh mục hãng</label>
                    <select name="category" style="padding:10px; border-radius:8px; background:#121212; color:#fff; border:1px solid #444;">
                        <option value="samsung">Samsung</option>
                        <option value="iphone">iPhone</option>
                    </select>
                </div>
                <button type="submit" name="add_product" class="btn-submit">Thêm Sản Phẩm</button>
            </form>
        </div>

        <h3>Danh sách sản phẩm hiện tại</h3>
        <div style="overflow-x:auto; margin-top: 15px;">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Hãng</th>
                        <th>Giá bán</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($products as $p): ?>
                    <tr>
                        <td><?php echo $p['id']; ?></td>
                        <td><img src="<?php echo htmlspecialchars($p['image']); ?>" style="width:40px; height:40px; object-fit:contain; background:#fff; border-radius:4px;"></td>
                        <td><b><?php echo htmlspecialchars($p['name']); ?></b></td>
                        <td><?php echo strtoupper($p['category']); ?></td>
                        <td style="color:#E53935; font-weight:bold;"><?php echo number_format($p['price'], 0, ',', '.'); ?>₫</td>
                        <td><a href="quanly_sanpham.php?action=delete&id=<?php echo $p['id']; ?>" class="btn-act" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">Xóa</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>