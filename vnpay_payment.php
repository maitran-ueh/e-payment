<?php
session_start();
require_once("vnpay_config.php");

if (!isset($_SESSION['last_order'])) {
    echo '<div class="alert alert-warning" style="text-align: center; margin-top: 50px; font-size: 1.2rem; color: red;">❌ Không tìm thấy thông tin đơn hàng</div>';
    exit;
}

$order = $_SESSION['last_order'];
$orderId = $order['code'] ?? ('OD' . time());
$amount = $order['amount'] ?? 0;

if ($amount <= 0) {
    echo '<div class="alert alert-warning" style="text-align: center; margin-top: 50px; font-size: 1.2rem; color: red;">❌ Tổng tiền không hợp lệ</div>';
    exit;
}

date_default_timezone_set('Asia/Ho_Chi_Minh');

$vnp_TxnRef = $orderId;
$vnp_OrderInfo = "Thanh toán đơn hàng #" . $vnp_TxnRef;
$vnp_OrderType = "billpayment";
$vnp_Amount = $amount * 100; // nhân 100 theo yêu cầu của VNPAY
$vnp_Locale = "vn";
$vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

$inputData = array(
    "vnp_Version"    => "2.1.0",
    "vnp_TmnCode"    => $vnp_TmnCode,
    "vnp_Amount"     => $vnp_Amount,
    "vnp_Command"    => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode"   => "VND",
    "vnp_IpAddr"     => $vnp_IpAddr,
    "vnp_Locale"     => $vnp_Locale,
    "vnp_OrderInfo"  => $vnp_OrderInfo,
    "vnp_OrderType"  => $vnp_OrderType,
    "vnp_ReturnUrl"  => $vnp_Returnurl,
    "vnp_TxnRef"     => $vnp_TxnRef
);

// Tạo chuỗi hash
ksort($inputData);
$query = '';
$hashdata = '';

foreach ($inputData as $key => $value) {
    $query .= urlencode($key) . '=' . urlencode($value) . '&';
    $hashdata .= urlencode($key) . '=' . urlencode($value) . '&';
}

$hashdata = rtrim($hashdata, '&');
$query = rtrim($query, '&');

$vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
$vnp_Url = $vnp_Url . "?" . $query . "&vnp_SecureHash=" . $vnpSecureHash;

// Chuyển hướng người dùng đến VNPAY
header("Location: " . $vnp_Url);
exit;
?>