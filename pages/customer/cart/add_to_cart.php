<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: ../login/login.php");
    exit();
}

require_once "../../../server/config/db.php";

$userID = $_SESSION['user_id'];
$productID = intval($_POST['product_id']);
$quantity = intval($_POST['quantity']);

// Check if product exists and stock is enough
$sql = "SELECT stock FROM Products WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productID);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if(!$product || $product['stock'] < $quantity){
    $_SESSION['error'] = "Not enough stock available.";
    header("Location: ../dashboard/dashboard.php?cat=All");
    exit();
}

// Check if product already in cart
$sql = "SELECT * FROM Cart WHERE user_id=? AND product_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $userID, $productID);
$stmt->execute();
$existing = $stmt->get_result()->fetch_assoc();

if($existing){
    // Update quantity
    $sql = "UPDATE Cart SET quantity = quantity + ? WHERE cart_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $quantity, $existing['cart_id']);
    $stmt->execute();
} else {
    // Insert new
    $sql = "INSERT INTO Cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $userID, $productID, $quantity);
    $stmt->execute();
}

$_SESSION['success'] = "Item added to cart!";
header("Location: ../cart/view_cart.php");
exit();
?>
