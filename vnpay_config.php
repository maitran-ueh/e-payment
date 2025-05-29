<?php
define("BASE_URL", "http://localhost:3000/");

$vnp_TmnCode = "NJJ0R8FS"; // Website ID in VNPAY System (Test)
$vnp_HashSecret = "BYKJBHPPZKQMKBIBGGXIYKWYFAYSJXCW"; // Secret key from VNPAY
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl = BASE_URL . "vnpay_return.php"; // URL để nhận kết quả thanh toán
?>