<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

require_once "../../../server/config/db.php";

if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    header("Location: ../dashboard/dashboard.php?cat=All");
    exit();
}

$orderID = intval($_GET['order_id']);

$sql = "SELECT o.*, p.product_name, p.image_id, u.full_name 
        FROM orders o
        JOIN products p ON o.product_id = p.productID
        JOIN users u ON o.user_id = u.id
        WHERE o.order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orderID);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo "<script>alert('Order not found.'); window.location.href='../dashboard/dashboard.php?cat=All';</script>";
    exit();
}

$shipping_address = $order['house_no'] . ", " . $order['street1'] . 
                    (!empty($order['street2']) ? ", " . $order['street2'] : "") . 
                    ", " . $order['city'] . " - " . $order['postal_code'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Success | Blush’d Cosmetics</title>
<link rel="stylesheet" href="dashboard.css">
<link rel="stylesheet" href="order_success.css">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="success-container">
    <div class="success-card">
        <img src="../../../assets/images/checkmark.png" alt="Success" class="success-icon">
        <h1>Thank You for Your Order!</h1>
        <p class="subtitle">We’ve received your order and will start processing it soon.</p>

        <div class="order-info">
            <h3>Order Details</h3>
            <p><strong>Order ID:</strong> #<?php echo $order['order_id']; ?></p>
            <p><strong>Customer:</strong> <?php echo htmlspecialchars($order['full_name']); ?></p>
            <p><strong>Product:</strong> <?php echo htmlspecialchars($order['product_name']); ?></p>
            <p><strong>Quantity:</strong> <?php echo $order['quantity']; ?></p>
            <p><strong>Total:</strong> $<?php echo number_format($order['total'], 2); ?></p>
            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
            <p><strong>Shipping Address:</strong> <?php echo htmlspecialchars($shipping_address); ?></p>
        </div>

        <div class="actions">
            <a href="../dashboard/dashboard.php?cat=All" class="btn discover-more">Discover More Products</a>
        </div>
    </div>
</div>

</body>
</html>
