<?php
header('Content-Type: application/json; charset=utf-8');
// Adjust path if your project uses a different include pattern.
// This path matches the one used in your product.php
require_once "../../../server/config/db.php";

$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
if ($product_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Missing or invalid product_id']);
    exit;
}

// Fetch reviews with user name
$sql = "SELECT r.review_id, r.rating, r.comments, r.review_date, u.first_name, u.last_name
        FROM Review r
        LEFT JOIN `User` u ON r.user_id = u.user_id
        WHERE r.product_id = ?
        ORDER BY r.review_date DESC";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $reviews = [];
    while ($row = $res->fetch_assoc()) {
        $reviews[] = $row;
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => $conn->error]);
    exit;
}

// Stats: count + average
$sql2 = "SELECT COUNT(*) AS cnt, IFNULL(ROUND(AVG(rating),2),0) AS avg_rating FROM Review WHERE product_id = ?";
$stats = ['cnt' => 0, 'avg_rating' => 0];
if ($stmt2 = $conn->prepare($sql2)) {
    $stmt2->bind_param("i", $product_id);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    if ($r = $res2->fetch_assoc()) {
        $stats = $r;
    }
    $stmt2->close();
}

echo json_encode([
    'success' => true,
    'reviews' => $reviews,
    'count' => (int)$stats['cnt'],
    'avg' => (float)$stats['avg_rating']
]);
exit;
