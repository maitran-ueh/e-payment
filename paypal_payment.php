<?php
session_start();

// Kiểm tra thông tin đơn hàng
if (!isset($_SESSION['last_order'])) {
    header("Location: cart.php");
    exit;
}

// Lấy thông tin đơn hàng
$order = $_SESSION['last_order'];
$amount = $order['amount']; // số tiền VNĐ
$orderCode = $order['code'];

// Chuyển VNĐ sang USD, giả sử tỉ giá 23000 VNĐ = 1 USD
$usdAmount = number_format($amount / 23000, 2, '.', '');

// Bạn cần thay client-id PayPal tương ứng sandbox hoặc live
$paypalClientId = "ActPpZUWz-RS670JzMs7zemeHbhPNb0mDCDTZ5V4DsmPye2VCmxPMd8UHjk_V9bD1qSWKF89_Ko-d_aN";

// URL redirect khi thanh toán thành công hoặc hủy (cần đổi theo domain thật của bạn)
$returnUrl = "http://localhost/paypal_processor.php?status=success";
$cancelUrl = "http://localhost/cart.php?payment=cancelled";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Thanh toán PayPal</title>
    <script src="https://www.paypal.com/sdk/js?client-id=<?= $paypalClientId ?>&currency=USD"></script>
</head>
<body>
    <h2>Thanh toán đơn hàng <?= htmlspecialchars($orderCode) ?></h2>
    <p>Số tiền: <?= number_format($amount) ?> VNĐ (~<?= $usdAmount ?> USD)</p>

    <div id="paypal-button-container"></div>

    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                // Tạo đơn hàng PayPal
                return actions.order.create({
                    purchase_units: [{
                        description: "Đơn hàng <?= addslashes($orderCode) ?>",
                        amount: {
                            currency_code: "USD",
                            value: "<?= $usdAmount ?>"
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                // Khi khách thanh toán thành công
                return actions.order.capture().then(function(details) {
                    alert("Thanh toán thành công bởi " + details.payer.name.given_name);
                    // Sau đó chuyển về server xử lý
                    window.location.href = "paypal_processor.php?status=success&orderCode=<?= urlencode($orderCode) ?>&transactionId=" + details.id;
                });
            },
            onCancel: function(data) {
                // Khi khách huỷ thanh toán
                alert('Bạn đã huỷ thanh toán.');
                window.location.href = "cart.php?payment=cancelled";
            },
            onError: function(err) {
                // Xử lý lỗi
                alert('Có lỗi xảy ra khi thanh toán. Vui lòng thử lại.');
                console.error(err);
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>
