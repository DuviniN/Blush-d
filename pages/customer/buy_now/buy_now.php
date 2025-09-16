<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: ../login/login.php");
    exit();
}

require_once "../../../server/config/db.php";

$userID = $_SESSION['user_id'];

// Fetch user info
$sql_user = "SELECT * FROM User WHERE user_id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $userID);
$stmt_user->execute();
$user = $stmt_user->get_result()->fetch_assoc();

// Fetch all cart items
$sql_cart = "SELECT c.cart_id, c.quantity, p.product_id, p.product_name, p.price, p.stock
             FROM Cart c
             JOIN Products p ON c.product_id = p.product_id
             WHERE c.user_id = ?";
$stmt_cart = $conn->prepare($sql_cart);
$stmt_cart->bind_param("i", $userID);
$stmt_cart->execute();
$cart_result = $stmt_cart->get_result();

if($cart_result->num_rows == 0){
    echo "<script>alert('Your cart is empty.'); window.location.href='../dashboard/dashboard.php?cat=All';</script>";
    exit();
}

$cart_items = [];
$total = 0;
while($row = $cart_result->fetch_assoc()){
    $cart_items[] = $row;
    $total += $row['price'] * $row['quantity'];
}

// Handle POST: Place Order
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $house_no = trim($_POST['house_no']);
    $street1 = trim($_POST['street1']);
    $street2 = trim($_POST['street2']);
    $city = trim($_POST['city']);
    $postal_code = trim($_POST['postal_code']);
    $payment_method = $_POST['payment_method'];

    if(!empty($house_no) && !empty($street1) && !empty($city) && !empty($postal_code) && !empty($payment_method)){
        $conn->begin_transaction();
        try {
            // Insert into Order
            $stmt_order = $conn->prepare("INSERT INTO `Order` (order_date, total_price, house_no, street1, street2, city, postal_code, payment_method, user_id) VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_order->bind_param("dssssssi", $total, $house_no, $street1, $street2, $city, $postal_code, $payment_method, $userID);
            $stmt_order->execute();
            $orderID = $stmt_order->insert_id;

            // Insert each cart item
            foreach($cart_items as $item){
                // Insert order item
                $stmt_item = $conn->prepare("INSERT INTO Order_Item (quantity, price, order_id, product_id) VALUES (?, ?, ?, ?)");
                $stmt_item->bind_param("idii", $item['quantity'], $item['price'], $orderID, $item['product_id']);
                $stmt_item->execute();

                // Reduce stock
                $stmt_update = $conn->prepare("UPDATE Products SET stock = stock - ? WHERE product_id = ?");
                $stmt_update->bind_param("ii", $item['quantity'], $item['product_id']);
                $stmt_update->execute();
            }

            // Clear cart
            $conn->query("DELETE FROM Cart WHERE user_id = $userID");

            $conn->commit();
            header("Location: ../order_success/order_success.php?order_id=".$orderID);
            exit();
        } catch(Exception $e){
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
<title>Checkout | Blush’d Cosmetics</title>
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
            <input type="text" value="<?php echo htmlspecialchars($user['first_name']." ".$user['last_name']); ?>" disabled>
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
        <?php foreach($cart_items as $item): ?>
            <div class="cart-item">
                <img src="../../../assets/products/<?php echo $item['product_id']; ?>.png" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                <p><?php echo htmlspecialchars($item['product_name']); ?></p>
                <p>Price: $<?php echo number_format($item['price'], 2); ?></p>
                <p>Quantity: <?php echo $item['quantity']; ?></p>
                <p>Subtotal: $<?php echo number_format($item['price']*$item['quantity'], 2); ?></p>
            </div>
            <hr>
        <?php endforeach; ?>
        <p><strong>Grand Total: $<?php echo number_format($total, 2); ?></strong></p>
    </div>
</div>

</body>
</html>
