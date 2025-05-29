<?php
session_start();

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

// Giỏ hàng lấy từ session
$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <title>Giỏ hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/style.css" />
    <script>
        function confirmDelete() {
            return confirm("Bạn có chắc chắn muốn xoá sản phẩm này?");
        }

        function confirmCheckout() {
            return confirm("Xác nhận đặt hàng?");
        }
    </script>
</head>

<body class="container py-4">
    <h1 class="mb-4" style="text-align: center;">🛒 GIỎ HÀNG</h1>
    <?php if (empty($cart)): ?>
        <div class="alert alert-warning" style="text-align: center;"><strong>GIỎ HÀNG ĐANG TRỐNG</strong></div>
    <?php else: ?>
        <div class="alert alert-warning" style="box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-radius: 10px; overflow: hidden;">
        <table class="table" style="box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-radius: 10px; overflow: hidden;">
    <thead>
        <tr style="text-align: center;">
            <th>Sản phẩm</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Thành tiền</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $total = 0; // Khởi tạo tổng
        foreach ($cart as $id => $qty):
            if (!isset($products[$id])) continue; // Tránh lỗi nếu id không tồn tại
            $p = $products[$id];
            $subtotal = $p['price'] * $qty;
            $total += $subtotal;
        ?>
        <tr style="text-align: center;">
            <td style="display: flex; align-items: center; gap: 10px;">
        <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" style="height: 50px; width: 50px; object-fit: cover; border-radius: 5px;">
        <span><?= htmlspecialchars($p['name']) ?></span>
</td>
            <td><?= (int)$qty ?></td>
            <td><?= number_format($p['price']) ?> VNĐ</td>
            <td><?= number_format($subtotal) ?> VNĐ</td>
            <td>
                <a href="remove_from_cart.php?id=<?= urlencode($id) ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete()">
                    <i class="bi bi-trash"></i> Xoá
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3"><strong>TỔNG CỘNG:</strong></td>
            <td style="text-align: center;"><strong><?= number_format($total) ?> VNĐ</strong></td>
            <td></td>
        </tr>
    </tbody>
</table>

        <form action="checkout.php" method="POST" onsubmit="return confirmCheckout()" class="mt-3">
            <input type="hidden" name="amount" value="<?= htmlspecialchars($total) ?>" />
            <div class="mb-3">
                <label class="form-label fw-bold">Chọn cổng thanh toán:</label><br />
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gateway" value="momo" id="payMomo" checked required />
                    <label class="form-check-label" for="payMomo">
                        <img src="images/momo-logo.png" alt="MoMo" style="height: 30px;">
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gateway" value="vnpay" id="payVnpay" />
                    <label class="form-check-label" for="payVnpay">
                        <img src="images/vnpay-logo.png" alt="VNPay" style="height: 30px;">
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gateway" value="paypal" id="payPaypal" />
                    <label class="form-check-label" for="payPaypal">
                        <img src="images/paypal-logo.png" alt="PayPal" style="height: 30px;">
                    </label>
                </div>
            </div>
            <button type="submit" class="btn btn-success">
                <i class="bi bi-credit-card"></i> Thanh toán
            </button>
        </form>
    </div>
    <?php endif; ?>
    <div class="mt-4">
        <a href="index.php" class="btn btn-secondary">← Tiếp tục mua hàng</a>
    </div>
</body>

</html>