<?php
session_start();
require_once 'db.php';

// Lấy danh sách sản phẩm từ CSDL
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();

// Phân nhóm sản phẩm theo category
$samsung_products = array_filter($products, fn($p) => $p['category'] === 'samsung');
$iphone_products = array_filter($products, fn($p) => $p['category'] === 'iphone');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bán hàng</title>
    <link href="sell.css" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="navbar">
            <?php if (isset($_SESSION['currentUser'])): ?>
                <a href="quanly.php">Quản lý (<?php echo htmlspecialchars($_SESSION['currentUser']); ?>)</a>
                <a href="login.php?logout=true">Đăng xuất</a>
            <?php else: ?>
                <a href="login.php">Đăng nhập</a>
            <?php endif; ?>
            <a href="sell.php">Mua hàng</a>
            <a href="support.php">Liên hệ hỗ trợ</a>
        </nav>

        <div class="image-slider">
            <img src="banner.png" alt="ảnh 1" class="banner">
            <img src="banner2.jpg" alt="ảnh 2" class="banner">
            <img src="banner3.jpg" alt="ảnh 3" class="banner">
            <img src="banner4.jpg" alt="ảnh 4" class="banner">
        </div>
    </header>
    <main class="smp">
        <p class="title">SAMSUNG</p>
        <div class="ss">
            <?php foreach ($samsung_products as $row): ?>
            <div class="box">
                <div class="img-container">
    <a href="checkout.php?id=<?php echo $row['id']; ?>"><img src="<?php echo htmlspecialchars($row['image']); ?>" alt="ss"></a>
</div>
                <p class="name"><?php echo htmlspecialchars($row['name']); ?></p>
                <p class="price"><?php echo number_format($row['price'], 0, ',', '.'); ?>₫</p>   
            </div>
            <?php endforeach; ?>
        </div>

        <p class="title">iPhone</p>
        <div class="ip"> 
            <?php foreach ($iphone_products as $row): ?>
            <div class="box">
                <div class="img-container">
                    <a href="<?php echo htmlspecialchars($row['link']); ?>" target="_blank"><img src="<?php echo htmlspecialchars($row['image']); ?>" alt="ss"></a>
                </div>
                <p class="name"><?php echo htmlspecialchars($row['name']); ?></p>
                <p class="price"><?php echo number_format($row['price'], 0, ',', '.'); ?>₫</p>   
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>