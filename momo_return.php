<?php
session_start();

if ($_GET['resultCode'] == 0) {
    // Thành công
    unset($_SESSION['cart']);
    echo "<h2>🎉 Thanh toán thành công! Mã đơn hàng #" . $_GET['orderId'] . "</h2>";
} else {
    echo "<h2>❌ Thanh toán thất bại hoặc bị hủy.</h2>";
}
?>
<a href='<?php echo dirname($_SERVER['PHP_SELF']) . "/index.php"; ?>'>← Quay lại trang mua hàng</a>

