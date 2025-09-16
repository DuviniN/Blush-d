<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once "../../../server/config/db.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'not_logged_in']);
    exit;
}

$user_id = intval($_SESSION['user_id']);
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
$comments = isset($_POST['comments']) ? trim($_POST['comments']) : '';

if ($product_id <= 0 || $rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Insert review
$sql = "INSERT INTO Review (rating, comments, review_date, user_id, product_id) VALUES (?, ?, NOW(), ?, ?)";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("isii", $rating, $comments, $user_id, $product_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Review submitted']);
    } else {
        echo json_encode(['success' => false, 'message' => 'DB error: ' . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'DB prepare error: ' . $conn->error]);
}
exit;
