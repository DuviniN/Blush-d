<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../index.php");
    exit();
}

require_once __DIR__ . '/../../../server/config/db.php';

if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit;
}
$id = intval($_GET['id']);
$stmt = $conn->prepare("DELETE FROM product WHERE product_id=?");
$stmt->bind_param('i',$id);
$stmt->execute();
$stmt->close();

header('Location: products.php?msg=Product+deleted');
exit;
