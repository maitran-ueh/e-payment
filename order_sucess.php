<?php
session_start();

$orderCode = $_GET['order'] ?? null;

if (!$orderCode) {
    echo '<div class="alert alert-warning" style="text-align: center; margin-top: 50px; font-size: 1.2rem; color: red;">❌ Không tìm thấy thông tin đơn hàng</div>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt hàng thành công</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <div class="alert alert-success text-center">
        <h3>✅ Đơn hàng của bạn đã được thanh toán thành công!</h3>
        <p>Mã đơn hàng: <strong><?= htmlspecialchars($orderCode) ?></strong></p>
        <a href="index.php" class="btn btn-primary mt-3">Tiếp tục mua sắm</a>
    </div>
</body>
</html>
