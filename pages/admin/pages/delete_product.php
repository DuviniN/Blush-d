<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit;
}
$id = intval($_GET['id']);
$stmt = $mysqli->prepare("DELETE FROM product WHERE product_id=?");
$stmt->bind_param('i',$id);
$stmt->execute();
$stmt->close();

header('Location: products.php?msg=Product+deleted');
exit;
