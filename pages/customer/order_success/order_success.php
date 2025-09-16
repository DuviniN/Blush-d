<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

require_once "../../../server/config/db.php";

// Validate order_id
if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    header("Location: ../dashboard/dashboard.php?cat=All");
    exit();
}

$orderID = intval($_GET['order_id']);

// Fetch order details along with order items
$sql = "
SELECT o.order_id, o.order_date, o.total_price, o.house_no, o.street1, o.street2, o.city, o.postal_code, o.payment_method,
       u.first_name, u.last_name, p.product_name, p.image_id, oi.quantity, oi.price
FROM `Order` o
JOIN User u ON o.user_id = u.user_id
JOIN Order_Item oi ON o.order_id = oi.order_id
JOIN Products p ON oi.product_id = p.product_id
WHERE o.order_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orderID);
$stmt->execute();
$result = $stmt->get_result();
$order_items = $result->fetch_all(MYSQLI_ASSOC);

if (!$order_items) {
    echo "<script>alert('Order not found.'); window.location.href='../dashboard/dashboard.php?cat=All';</script>";
    exit();
}

// Get shipping info from first order item (all items share the same shipping address)
$first_order = $order_items[0];
$shipping_address = $first_order['house_no'] . ", " . $first_order['street1'] .
                    (!empty($first_order['street2']) ? ", " . $first_order['street2'] : "") .
                    ", " . $first_order['city'] . " - " . $first_order['postal_code'];

// Build full customer name
$customer_name = $first_order['first_name'] . ' ' . $first_order['last_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Success | Blush’d Cosmetics</title>
<link rel="stylesheet" href="../dashboard/dashboard.css">
<link rel="stylesheet" href="order_success.css">
</head>
<body>

<?php include "../dashboard/navbar.php"; ?>

<div class="success-container">
    <div class="success-card">
        <img src="../../../assets/images/checkmark.png" alt="Success" class="success-icon">
        <h1>Thank You for Your Order!</h1>
        <p class="subtitle">We’ve received your order and will start processing it soon.</p>

        <div class="order-info">
            <h3>Order Details</h3>
            <p><strong>Order ID:</strong> #<?php echo $first_order['order_id']; ?></p>
            <p><strong>Customer:</strong> <?php echo htmlspecialchars($customer_name); ?></p>
            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($first_order['payment_method']); ?></p>
            <p><strong>Shipping Address:</strong> <?php echo htmlspecialchars($shipping_address); ?></p>
            <p><strong>Order Date:</strong> <?php echo date("F j, Y, g:i a", strtotime($first_order['order_date'])); ?></p>

            <h3>Products Ordered</h3>
            <ul>
                <?php foreach ($order_items as $item): ?>
                    <li>
                        <img src="../../../assets/products/<?php echo $item['product_id']; ?>.png" alt="<?php echo htmlspecialchars($item['product_name']); ?>" style="width:50px; height:50px; vertical-align:middle;">
                        <?php echo htmlspecialchars($item['product_name']); ?> - Quantity: <?php echo $item['quantity']; ?> - Price: $<?php echo number_format($item['price'], 2); ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <p><strong>Total Amount:</strong> $<?php echo number_format($first_order['total_price'], 2); ?></p>
        </div>

        <div class="actions">
            <a href="../dashboard/dashboard.php?cat=All" class="btn discover-more">Discover More Products</a>
        </div>
    </div>
</div>

</body>
</html>
