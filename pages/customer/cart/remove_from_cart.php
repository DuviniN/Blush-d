<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../index.php");
    exit();
}

require_once "../../../server/config/db.php";

$cartID = intval($_GET['id']);
$userID = $_SESSION['user_id'];

$sql = "DELETE FROM cart WHERE cart_id=? AND user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $cartID, $userID);
$stmt->execute();

header("Location: view_cart.php");
exit();
