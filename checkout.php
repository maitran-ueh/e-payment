<?php
session_start();
require 'db.php'; // file chứa $pdo

if (!isset($_POST['gateway']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$products = [
    1 => [
        "name" => "Bánh Gấu Matcha",
        "price" => 10000,
        "image" => "images/banh-gau-matcha.jpg",
    ],
    2 => [
        "name" => "Hạt Sấy & Đậu Mix",
        "price" => 7000,
        "image" => "images/hat-say-va-dau-mix.jpg", // ✅ Đã đổi tên
    ],
    3 => [
        "name" => "T-Rexx Vị Vải",
        "price" => 5000,
        "image" => "images/nuoc-vai.jpg",
    ],
];

$orderCode = 'OD' . rand(1000, 9999);
$total = 0;
$gateway = $_POST['gateway'];

// Lưu đơn hàng vào DB
foreach ($_SESSION['cart'] as $id => $qty) {
    if (isset($products[$id])) {
        $product = $products[$id];
        $price = $product['price'];
        $subtotal = $price * $qty;
        $total += $subtotal;

        $stmt = $pdo->prepare("INSERT INTO orders (order_code, product_id, product_name, quantity, price, total_price, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $orderCode,
            $id,
            $product['name'],
            $qty,
            $price,
            $subtotal,
            $gateway
        ]);
    }
}

// Lưu thông tin đơn vào session
$_SESSION['last_order'] = [
    'code' => $orderCode,
    'amount' => $total,
    'gateway' => $gateway,
    'created_at' => date('Y-m-d H:i:s')
];

// Không xoá cart nếu là PayPal vì cần giá trị trong bước xử lý tiếp theo
if ($gateway !== 'paypal') {
    unset($_SESSION['cart']);
}

// Điều hướng
switch ($gateway) {
    case 'momo':
        header("Location: momo_payment.php");
        break;
    case 'vnpay':
        header("Location: vnpay_payment.php");
        break;
    case 'paypal':
        header("Location: paypal_payment.php");
        break;
    default:
        header("Location: cart.php?error=invalid_gateway");
        break;
}
exit;