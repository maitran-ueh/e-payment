$data = json_decode(file_get_contents('php://input'), true);

if ($data['resultCode'] == 0) {
    $orderId = $data['orderId'];
    $transId = $data['transId'];

    $sql = "UPDATE orders
            SET status='success', momo_trans_id='$transId'
            WHERE order_id='$orderId'";

    $conn->query($sql);
} else {
    // Giao dịch thất bại
    $orderId = $data['orderId'];
    $sql = "UPDATE orders SET status='failed' WHERE order_id='$orderId'";
    $conn->query($sql);
}
