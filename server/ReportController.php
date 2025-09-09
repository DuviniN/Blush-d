<?php
header('Content-Type: application/json');
require_once __DIR__ . '/config/db.php';


// Inventory table data
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'inventory') {
	$sql = "SELECT p.product_id, p.name as product_name, c.name as category, p.price, p.stock, 
			CASE WHEN p.stock <= 20 THEN 'Low Stock' ELSE 'In Stock' END as status
			FROM Product p
			LEFT JOIN Category c ON p.category_id = c.category_id";
	$result = $conn->query($sql);
	$data = [];
	if ($result && $result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
	}
	echo json_encode(['success' => true, 'data' => $data]);
	exit;
}

// Total products
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'total_products') {
	$sql = "SELECT COUNT(*) as total FROM Product";
	$result = $conn->query($sql);
	$row = $result ? $result->fetch_assoc() : ['total' => 0];
	echo json_encode(['success' => true, 'total' => $row['total']]);
	exit;
}

// Low stock count
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'low_stock') {
	$sql = "SELECT COUNT(*) as low_stock FROM Product WHERE stock <= 20";
	$result = $conn->query($sql);
	$row = $result ? $result->fetch_assoc() : ['low_stock' => 0];
	echo json_encode(['success' => true, 'low_stock' => $row['low_stock']]);
	exit;
}

// Total orders
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'total_orders') {
	$sql = "SELECT COUNT(*) as total_orders FROM `Order`";
	$result = $conn->query($sql);
	$row = $result ? $result->fetch_assoc() : ['total_orders' => 0];
	echo json_encode(['success' => true, 'total_orders' => $row['total_orders']]);
	exit;
}

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'popular_products' ) {
    // Get top selling product in each category
    $sql = "SELECT 
                p.name as product_name, 
                c.name as category, 
                SUM(oi.quantity) as sold,
                p.price,
                (SUM(oi.quantity) * p.price) as total_revenue
            FROM OrderItem oi
            JOIN Product p ON oi.product_id = p.product_id
            JOIN Category c ON p.category_id = c.category_id
            WHERE oi.product_id IN (
                SELECT product_id 
                FROM (
                    SELECT p2.product_id, p2.category_id, SUM(oi2.quantity) as qty,
                           ROW_NUMBER() OVER (PARTITION BY p2.category_id ORDER BY SUM(oi2.quantity) DESC) as rn
                    FROM OrderItem oi2
                    JOIN Product p2 ON oi2.product_id = p2.product_id
                    GROUP BY p2.product_id, p2.category_id
                ) ranked
                WHERE rn = 1
            )
            GROUP BY p.product_id, c.category_id
            ORDER BY c.name, sold DESC";
    
    $result = $conn->query($sql);
    $data = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'product_name' => $row['product_name'],
                'category' => $row['category'],
                'sold' => (int)$row['sold'],
                'price' => (float)$row['price'],
                'total_revenue' => (float)$row['total_revenue']
            ];
        }
    }
    echo json_encode(['success' => true, 'data' => $data]);
    exit;
}


echo json_encode(['success' => false, 'message' => 'Invalid request']);
exit;
?>
