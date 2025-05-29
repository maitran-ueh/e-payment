<?php
session_start();

// Kiểm tra nếu không có đơn hàng trong session
if (!isset($_SESSION['last_order'])) {
    echo "Không tìm thấy đơn hàng để thanh toán.";
    exit;
}

$endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

// Thông tin tài khoản MoMo Sandbox
$partnerCode = "MOMO4MUD20240115_TEST";
$accessKey = "Ekj9og2VnRfOuIys";
$secretKey = "PseUbm2s8QVJEbexsh8H3Jz2qa9tDqoa";

// Lấy thông tin đơn hàng
$order = $_SESSION['last_order'];
$orderId = $order['code'] ?? ('OD' . time()); // Sử dụng 'code', hoặc tạo mã tạm
$amount = $order['amount'] ?? 0;

$requestId = time() . "";
$orderInfo = "Thanh toán đơn hàng #" . $orderId;
$redirectUrl = "http://localhost/momo_return.php";
$ipnUrl = "http://localhost/momo_ipn.php";

$requestType = "captureWallet";
$extraData = "";

// Tạo chuỗi ký
$rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
$signature = hash_hmac("sha256", $rawHash, $secretKey);

// Dữ liệu gửi đến MoMo
$data = [
    'partnerCode' => $partnerCode,
    'accessKey' => $accessKey,
    'requestId' => $requestId,
    'amount' => (string)$amount,
    'orderId' => $orderId,
    'orderInfo' => $orderInfo,
    'redirectUrl' => $redirectUrl,
    'ipnUrl' => $ipnUrl,
    'extraData' => $extraData,
    'requestType' => $requestType,
    'signature' => $signature,
    'lang' => 'vi'
];

// Gửi yêu cầu đến MoMo
$ch = curl_init($endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$result = curl_exec($ch);
curl_close($ch);

$response = json_decode($result, true);

// Nếu thành công → chuyển hướng đến trang thanh toán
if (isset($response['payUrl'])) {
    header('Location: ' . $response['payUrl']);
    exit;
} else {
    echo "Lỗi khi tạo liên kết thanh toán MoMo.<br>";
    echo "<pre>";
    print_r($response);
    echo "</pre>";
}
?>