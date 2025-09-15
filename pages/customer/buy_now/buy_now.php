<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

require_once "../../../server/config/db.php";

// Validate product and quantity
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../dashboard/dashboard.php?cat=All");
    exit();
}

$productID = intval($_GET['id']);
$quantity = isset($_GET['qty']) ? intval($_GET['qty']) : 1;

// Fetch product
$sql = "SELECT * FROM Products WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productID);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "<script>alert('Product not found.'); window.location.href='../dashboard/dashboard.php?cat=All';</script>";
    exit();
}

if ($product['stock'] <= 0) {
    echo "<script>alert('Sorry, this product is out of stock.'); window.location.href='../dashboard/dashboard.php?cat=All';</script>";
    exit();
}

if ($quantity <= 0 || $quantity > $product['stock']) {
    echo "<script>alert('Only {$product['stock']} items are available.'); window.location.href='../product/product.php?id={$productID}';</script>";
    exit();
}

// Fetch user
$userID = $_SESSION['user_id'];
$sql_user = "SELECT * FROM User WHERE user_id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $userID);
$stmt_user->execute();
$user = $stmt_user->get_result()->fetch_assoc();

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $house_no = trim($_POST['house_no']);
    $street1 = trim($_POST['street1']);
    $street2 = trim($_POST['street2']);
    $city = trim($_POST['city']);
    $postal_code = trim($_POST['postal_code']);
    $payment_method = $_POST['payment_method'];

    $total = $product['price'] * $quantity;

    if (!empty($house_no) && !empty($street1) && !empty($city) && !empty($postal_code) && !empty($payment_method)) {
        // Start transaction
        $conn->begin_transaction();

        try {
            // Insert into Order
            $sql_order = "INSERT INTO `Order` 
                (order_date, total_price, house_no, street1, street2, city, postal_code, payment_method, user_id) 
                VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_order = $conn->prepare($sql_order);
            $stmt_order->bind_param("dssssssi", $total, $house_no, $street1, $street2, $city, $postal_code, $payment_method, $userID);
            $stmt_order->execute();
            $orderID = $stmt_order->insert_id;

            // Insert into Order_Item
            $sql_item = "INSERT INTO Order_Item (quantity, price, order_id, product_id) VALUES (?, ?, ?, ?)";
            $stmt_item = $conn->prepare($sql_item);
            $stmt_item->bind_param("idii", $quantity, $product['price'], $orderID, $productID);
            $stmt_item->execute();

            // Reduce stock
            $sql_update = "UPDATE Products SET stock = stock - ? WHERE product_id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ii", $quantity, $productID);
            $stmt_update->execute();

            // Commit transaction
            $conn->commit();

            header("Location: ../order_success/order_success.php?order_id=" . $orderID);
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            $error = "Failed to place order. Please try again.";
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Buy Now | Blush’d Cosmetics</title>
<link rel="stylesheet" href="dashboard.css">
<link rel="stylesheet" href="buy_now.css">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="checkout-container">
    <div class="checkout-form">
        <h2>Checkout</h2>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <label>Full Name</label>
            <input type="text" value="<?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>" disabled>
            <label>Email</label>
            <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
            <label>Phone</label>
            <input type="text" value="<?php echo htmlspecialchars($user['phone_number']); ?>" disabled>

            <h3>Shipping Address</h3>
            <label>House No *</label>
            <input type="text" name="house_no" required>
            <label>Street 1 *</label>
            <input type="text" name="street1" required>
            <label>Street 2</label>
            <input type="text" name="street2">
            <label>City *</label>
            <input type="text" name="city" required>
            <label>Postal Code *</label>
            <input type="text" name="postal_code" required>

            <label>Payment Method *</label>
            <select name="payment_method" required>
                <option value="">-- Select Payment Method --</option>
                <option value="Cash on Delivery">Cash on Delivery</option>
                <option value="Card Payment">Card Payment</option>
            </select>

            <button type="submit" class="btn">Confirm Order</button>
        </form>
    </div>

    <div class="order-summary">
        <h2>Order Summary</h2>
        <img src="../../../assets/products/<?php echo $product['product_id']; ?>.png" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
        <p><?php echo htmlspecialchars($product['product_name']); ?></p>
        <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
        <p>Quantity: <?php echo $quantity; ?></p>
        <p><strong>Total: $<?php echo number_format($product['price'] * $quantity, 2); ?></strong></p>
    </div>
</div>

</body>
</html>
