<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: ../login/login.php");
    exit();
}

require_once "../../../server/config/db.php";

$userID = $_SESSION['user_id'];

$sql = "SELECT c.cart_id, c.quantity, p.product_id, p.product_name, p.price, p.stock
        FROM Cart c
        JOIN Products p ON c.product_id = p.product_id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>My Cart | Blush’d Cosmetics</title>
<link rel="stylesheet" href="../dashboard/dashboard.css">
<link rel="stylesheet" href="cart.css">
</head>
<body>
<?php include "../dashboard/navbar.php"; ?>

<h2>My Cart</h2>
<table>
    <tr>
        <th>Product</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Total</th>
        <th>Action</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): 
        $lineTotal = $row['price'] * $row['quantity'];
        $total += $lineTotal;
    ?>
    <tr>
        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
        <td><?php echo $row['quantity']; ?></td>
        <td>$<?php echo number_format($row['price'],2); ?></td>
        <td>$<?php echo number_format($lineTotal,2); ?></td>
        <td><a href="remove_from_cart.php?id=<?php echo $row['cart_id']; ?>">Remove</a></td>
    </tr>
    <?php endwhile; ?>
</table>

<h3>Grand Total: $<?php echo number_format($total,2); ?></h3>

<a href="../buy_now/buy_now.php" class="btn">Proceed to Checkout</a>

</body>
</html>
