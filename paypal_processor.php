<?php
session_start();
require 'db.php'; // kết nối PDO: $pdo

// Kiểm tra trạng thái thanh toán và tồn tại đơn hàng
if (!isset($_GET['status']) || $_GET['status'] !== 'success' || !isset($_SESSION['last_order'])) {
    header("Location: cart.php?paypal=fail");
    exit;
}

$order = $_SESSION['last_order'];
$orderCode = $order['code'];

try {
    // Cập nhật trạng thái đơn hàng là 'completed'
    $stmt = $pdo->prepare("UPDATE orders 
        SET payment_status = ? 
        WHERE order_code = ?");
    $stmt->execute(['completed', $orderCode]);

    // Xoá giỏ hàng và thông tin đơn sau khi xử lý
    unset($_SESSION['cart'], $_SESSION['last_order']);

    header("Location: order_success.php?order=$orderCode"); // hoặc trang cảm ơn
    exit;

} catch (Exception $e) {
    echo "Lỗi khi cập nhật đơn hàng: " . $e->getMessage();
}
