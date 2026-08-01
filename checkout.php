<?php
session_start();
require_once 'db.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: sell.php");
    exit();
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {
    $customer_name = trim($_POST['customer_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $quantity = (int)$_POST['quantity'];
    $discount_code = trim($_POST['discount_code']);
    $payment_method = $_POST['payment_method'];
    $installment_info = $_POST['installment_info'];

    $original_total = $product['price'] * $quantity;
    $final_price = $original_total;

    // Xử lý mã ưu đãi
    if ($discount_code === 'GIAM10') {
        $final_price = $original_total * 0.9; // Giảm 10%
    } elseif ($discount_code === 'GIAM500K') {
        $final_price = max(0, $original_total - 500000); // Giảm 500k
    }

    if (!empty($customer_name) && !empty($phone) && !empty($address)) {
        $sql = "INSERT INTO orders (customer_name, phone, address, product_name, quantity, original_price, discount_code, final_price, payment_method, installment_info, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Chờ xử lý')";
        $stmt_insert = $pdo->prepare($sql);
        if ($stmt_insert->execute([$customer_name, $phone, $address, $product['name'], $quantity, $original_total, $discount_code, $final_price, $payment_method, $installment_info])) {
            $message = "Đặt hàng thành công! Chúng tôi sẽ liên hệ xác nhận sớm.";
        } else {
            $message = "Có lỗi xảy ra, vui lòng thử lại.";
        }
    } else {
        $message = "Vui lòng điền đầy đủ họ tên, số điện thoại và địa chỉ nhận hàng!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán đơn hàng</title>
    <link href="sell.css" rel="stylesheet">
    <style>
        .checkout-box { max-width: 600px; margin: 30px auto; background: #2b2b2b; padding: 30px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); }
        .product-summary { display: flex; gap: 15px; align-items: center; border-bottom: 1px solid #444; padding-bottom: 15px; margin-bottom: 20px; }
        .product-summary img { width: 80px; height: 80px; object-fit: contain; background: #fff; border-radius: 8px; }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="sell.php">Trang chủ</a>
        <a href="support.php">Hỗ trợ</a>
    </nav>

    <div class="checkout-box">
        <h2>Xác Nhận Đặt Hàng</h2>
        
        <div class="product-summary">
            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="phone">
            <div>
                <h4 style="color:#fff; margin-bottom:5px;"><?php echo htmlspecialchars($product['name']); ?></h4>
                <p style="color:#E53935; font-weight:bold;"><?php echo number_format($product['price'], 0, ',', '.'); ?>₫</p>
            </div>
        </div>

        <?php if(!empty($message)): ?>
            <p style="text-align:center; color:#4CAF50; font-weight:bold; margin-bottom:15px;"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Họ và tên người nhận (*)</label>
                <input type="text" name="customer_name" placeholder="Ví dụ: Nguyễn Văn A" required>
            </div>
            <div class="form-group">
                <label>Số điện thoại liên hệ (*)</label>
                <input type="text" name="phone" placeholder="Ví dụ: 0901234567" required>
            </div>
            <div class="form-group">
                <label>Địa chỉ nhận hàng chi tiết (*)</label>
                <textarea name="address" placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố..." required></textarea>
            </div>
            <div class="form-group">
                <label>Số lượng</label>
                <input type="number" name="quantity" value="1" min="1" required>
            </div>
            <div class="form-group">
                <label>Mã ưu đãi (Nhập <code>GIAM10</code> giảm 10% hoặc <code>GIAM500K</code> giảm 500k)</label>
                <input type="text" name="discount_code" placeholder="Nhập mã ưu đãi (nếu có)">
            </div>
            <div class="form-group">
                <label>Phương thức thanh toán</label>
                <select name="payment_method" style="padding:10px; border-radius:8px; background:#121212; color:#fff; border:1px solid #444;">
                    <option value="COD">Thanh toán khi nhận hàng (COD)</option>
                    <option value="Chuyển khoản Ngân hàng">Chuyển khoản ngân hàng trước</option>
                    <option value="Ví điện tử">Ví điện tử (Momo / ZaloPay)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Hình thức mua hàng / Trả góp</label>
                <select name="installment_info" style="padding:10px; border-radius:8px; background:#121212; color:#fff; border:1px solid #444;">
                    <option value="Thanh toán 100% tiền mặt/chuyển khoản">Thanh toán toàn bộ (Không trả góp)</option>
                    <option value="Trả góp 0% - Lộ trình 6 tháng">Trả góp 0% (Trả trước 30% - kỳ hạn 6 tháng)</option>
                    <option value="Trả góp qua thẻ tín dụng">Trả góp qua Thẻ tín dụng ngân hàng</option>
                </select>
            </div>
            <button type="submit" name="place_order" class="btn-submit">Xác Nhận Đặt Hàng Ngay</button>
        </form>
    </div>
</body>
</html>