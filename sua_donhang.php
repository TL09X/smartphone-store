<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['currentUser'])) { header("Location: login.php"); exit(); }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$id]);
$order = $stmt->fetch();
if(!$order) { header("Location: quanly_donhang.php"); exit(); }

$msg = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = trim($_POST['customer_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $product_name = trim($_POST['product_name']);
    $quantity = (int)$_POST['quantity'];
    $original_price = (float)$_POST['original_price'];
    $discount_code = trim($_POST['discount_code']);
    $payment_method = trim($_POST['payment_method']);
    $installment_info = trim($_POST['installment_info']);
    $status = trim($_POST['status']);
    
    // Tính lại giá sau ưu đãi tự động
    $original_total = $original_price * $quantity;
    $final_price = $original_total;
    if ($discount_code === 'GIAM10') {
        $final_price = $original_total * 0.9;
    } elseif ($discount_code === 'GIAM500K') {
        $final_price = max(0, $original_total - 500000);
    }

    $up = $pdo->prepare("UPDATE orders SET customer_name = ?, phone = ?, address = ?, product_name = ?, quantity = ?, original_price = ?, discount_code = ?, final_price = ?, payment_method = ?, installment_info = ?, status = ? WHERE id = ?");
    if($up->execute([$customer_name, $phone, $address, $product_name, $quantity, $original_total, $discount_code, $final_price, $payment_method, $installment_info, $status, $id])) {
        $msg = "Cập nhật đơn hàng thành công!";
        $stmt->execute([$id]);
        $order = $stmt->fetch();
    } else {
        $msg = "Lỗi cập nhật đơn hàng!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8"><title>Sửa đơn hàng</title>
    <link href="sell.css" rel="stylesheet">
</head>
<body>
    <div class="checkout-box" style="margin:30px auto; max-width:650px;">
        <h2>Chỉnh Sửa Chi Tiết Đơn Hàng #<?php echo $order['id']; ?></h2>
        <?php if($msg): ?><p style="color:#4CAF50; text-align:center; font-weight:bold; margin-bottom:15px;"><?php echo $msg; ?></p><?php endif; ?>
        <form method="POST">
            <div class="form-group"><label>Tên khách hàng</label><input type="text" name="customer_name" value="<?php echo htmlspecialchars($order['customer_name']); ?>" required></div>
            <div class="form-group"><label>Số điện thoại</label><input type="text" name="phone" value="<?php echo htmlspecialchars($order['phone']); ?>" required></div>
            <div class="form-group"><label>Địa chỉ nhận hàng</label><textarea name="address" required><?php echo htmlspecialchars($order['address']); ?></textarea></div>
            <div class="form-group"><label>Tên sản phẩm</label><input type="text" name="product_name" value="<?php echo htmlspecialchars($order['product_name']); ?>" required></div>
            <div class="form-group"><label>Số lượng</label><input type="number" name="quantity" min="1" value="<?php echo $order['quantity']; ?>" required></div>
            <div class="form-group"><label>Đơn giá gốc (1 sản phẩm)</label><input type="number" name="original_price" value="<?php echo $order['original_price'] / max(1, $order['quantity']); ?>" required></div>
            <div class="form-group"><label>Mã ưu đãi (GIAM10 hoặc GIAM500K)</label><input type="text" name="discount_code" value="<?php echo htmlspecialchars($order['discount_code']); ?>"></div>
            
            <div class="form-group">
                <label>Phương thức thanh toán</label>
                <select name="payment_method" style="padding:10px; border-radius:8px; background:#121212; color:#fff; border:1px solid #444;">
                    <option value="COD" <?php if($order['payment_method']=='COD') echo 'selected'; ?>>Thanh toán khi nhận hàng (COD)</option>
                    <option value="Chuyển khoản Ngân hàng" <?php if($order['payment_method']=='Chuyển khoản Ngân hàng') echo 'selected'; ?>>Chuyển khoản ngân hàng trước</option>
                    <option value="Ví điện tử" <?php if($order['payment_method']=='Ví điện tử') echo 'selected'; ?>>Ví điện tử (Momo / ZaloPay)</option>
                </select>
            </div>

            <div class="form-group">
                <label>Hình thức trả góp</label>
                <select name="installment_info" style="padding:10px; border-radius:8px; background:#121212; color:#fff; border:1px solid #444;">
                    <option value="Thanh toán 100% tiền mặt/chuyển khoản" <?php if($order['installment_info']=='Thanh toán 100% tiền mặt/chuyển khoản') echo 'selected'; ?>>Thanh toán toàn bộ (Không trả góp)</option>
                    <option value="Trả góp 0% - Lộ trình 6 tháng" <?php if($order['installment_info']=='Trả góp 0% - Lộ trình 6 tháng') echo 'selected'; ?>>Trả góp 0% (Trả trước 30% - kỳ hạn 6 tháng)</option>
                    <option value="Trả góp qua thẻ tín dụng" <?php if($order['installment_info']=='Trả góp qua thẻ tín dụng') echo 'selected'; ?>>Trả góp qua Thẻ tín dụng ngân hàng</option>
                </select>
            </div>

            <div class="form-group">
                <label>Trạng thái đơn hàng</label>
                <select name="status" style="padding:10px; border-radius:8px; background:#121212; color:#fff; border:1px solid #444;">
                    <option value="Chờ xử lý" <?php if($order['status']=='Chờ xử lý') echo 'selected'; ?>>Chờ xử lý</option>
                    <option value="Đang giao hàng" <?php if($order['status']=='Đang giao hàng') echo 'selected'; ?>>Đang giao hàng</option>
                    <option value="Đã hoàn thành" <?php if($order['status']=='Đã hoàn thành') echo 'selected'; ?>>Đã hoàn thành</option>
                    <option value="Đã hủy" <?php if($order['status']=='Đã hủy') echo 'selected'; ?>>Đã hủy</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">Lưu Thay Đổi Đơn Hàng</button>
            <a href="quanly_donhang.php" style="display:block; text-align:center; margin-top:15px; color:#aaa; text-decoration:none;">Quay lại danh sách đơn hàng</a>
        </form>
    </div>
</body>
</html>