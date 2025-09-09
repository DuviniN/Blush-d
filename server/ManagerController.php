<?php
require_once 'config/db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

class ManagerController {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $action = $_GET['action'] ?? '';
        
        try {
            switch ($method) {
                case 'GET':
                    $this->handleGetRequests($action);
                    break;
                case 'POST':
                    $this->handlePostRequests($action);
                    break;
                case 'PUT':
                    $this->handlePutRequests($action);
                    break;
                case 'DELETE':
                    $this->handleDeleteRequests($action);
                    break;
                default:
                    $this->sendResponse(false, 'Method not allowed', null, 405);
            }
        } catch (Exception $e) {
            $this->sendResponse(false, 'Server error: ' . $e->getMessage(), null, 500);
        }
    }
    
    private function handleGetRequests($action) {
        switch ($action) {
            case 'dashboard_stats':
                $this->getDashboardStats();
                break;
            case 'products':
                $this->getAllProducts();
                break;
            case 'product_by_id':
                $this->getProductById($_GET['id'] ?? 0);
                break;
            case 'categories':
                $this->getAllCategories();
                break;
            case 'orders':
                $this->getAllOrders();
                break;
            case 'order_details':
                $this->getOrderDetails($_GET['id'] ?? 0);
                break;
            case 'users':
                $this->getAllUsers();
                break;
            case 'user_profile':
                $this->getUserProfile($_GET['id'] ?? 0);
                break;
            case 'reviews':
                $this->getAllReviews();
                break;
            case 'low_stock_products':
                $this->getLowStockProducts();
                break;
            case 'sales_report':
                $this->getSalesReport($_GET['period'] ?? 'month');
                break;
            case 'popular_products':
                $this->getPopularProducts();
                break;
            case 'inventory_report':
                $this->getInventoryReport($_GET['category_id'] ?? null);
                break;
            case 'revenue_trends':
                $this->getRevenueTrends();
                break;
            case 'customer_insights':
                $this->getCustomerInsights();
                break;
            default:
                $this->sendResponse(false, 'Action not found', null, 404);
        }
    }
    
    private function handlePostRequests($action) {
        $input = json_decode(file_get_contents('php://input'), true);
        
        switch ($action) {
            case 'add_product':
                $this->addProduct($input);
                break;
            case 'add_category':
                $this->addCategory($input);
                break;
            case 'update_stock':
                $this->updateStock($input);
                break;
            case 'process_order':
                $this->processOrder($input);
                break;
            case 'add_user':
                $this->addUser($input);
                break;
            default:
                $this->sendResponse(false, 'Action not found', null, 404);
        }
    }
    
    private function handlePutRequests($action) {
        $input = json_decode(file_get_contents('php://input'), true);
        
        switch ($action) {
            case 'update_product':
                $this->updateProduct($input);
                break;
            case 'update_user':
                $this->updateUser($input);
                break;
            case 'update_order_status':
                $this->updateOrderStatus($input);
                break;
            default:
                $this->sendResponse(false, 'Action not found', null, 404);
        }
    }
    
    private function handleDeleteRequests($action) {
        switch ($action) {
            case 'delete_product':
                $this->deleteProduct($_GET['id'] ?? 0);
                break;
            case 'delete_user':
                $this->deleteUser($_GET['id'] ?? 0);
                break;
            default:
                $this->sendResponse(false, 'Action not found', null, 404);
        }
    }
    
    // Dashboard Statistics
    private function getDashboardStats() {
        $stats = array();
        
        // Total Products
        $sql = "SELECT COUNT(*) as total FROM Product";
        $result = $this->conn->query($sql);
        $stats['total_products'] = $result->fetch_assoc()['total'];
        
        // Low Stock Products (stock < 20)
        $sql = "SELECT COUNT(*) as total FROM Product WHERE stock < 20";
        $result = $this->conn->query($sql);
        $stats['low_stock_count'] = $result->fetch_assoc()['total'];
        
        // Total Orders (this month)
        $sql = "SELECT COUNT(*) as total FROM `Order` WHERE MONTH(order_date) = MONTH(CURRENT_DATE()) AND YEAR(order_date) = YEAR(CURRENT_DATE())";
        $result = $this->conn->query($sql);
        $stats['monthly_orders'] = $result->fetch_assoc()['total'];
        
        // Total Revenue (this month)
        $sql = "SELECT COALESCE(SUM(total_price), 0) as total FROM `Order` WHERE MONTH(order_date) = MONTH(CURRENT_DATE()) AND YEAR(order_date) = YEAR(CURRENT_DATE())";
        $result = $this->conn->query($sql);
        $stats['monthly_revenue'] = number_format($result->fetch_assoc()['total'], 2);
        
        // Total Customers
        $sql = "SELECT COUNT(*) as total FROM User WHERE role = 'CUSTOMER'";
        $result = $this->conn->query($sql);
        $stats['total_customers'] = $result->fetch_assoc()['total'];
        
        // Average Order Value
        $sql = "SELECT COALESCE(AVG(total_price), 0) as avg FROM `Order` WHERE MONTH(order_date) = MONTH(CURRENT_DATE()) AND YEAR(order_date) = YEAR(CURRENT_DATE())";
        $result = $this->conn->query($sql);
        $stats['avg_order_value'] = number_format($result->fetch_assoc()['avg'], 2);
        
        // Top Category by Sales
        $sql = "SELECT c.name, COALESCE(SUM(oi.quantity * oi.price), 0) as total_sales 
                FROM Category c 
                LEFT JOIN Product p ON c.category_id = p.category_id 
                LEFT JOIN Order_Item oi ON p.product_id = oi.product_id 
                LEFT JOIN `Order` o ON oi.order_id = o.order_id 
                WHERE MONTH(o.order_date) = MONTH(CURRENT_DATE()) AND YEAR(o.order_date) = YEAR(CURRENT_DATE())
                GROUP BY c.category_id, c.name 
                ORDER BY total_sales DESC 
                LIMIT 1";
        $result = $this->conn->query($sql);
        $top_category = $result->fetch_assoc();
        $stats['top_category'] = $top_category ? $top_category['name'] : 'N/A';
        $stats['top_category_sales'] = $top_category ? number_format($top_category['total_sales'], 2) : '0.00';
        
        $this->sendResponse(true, 'Dashboard stats retrieved successfully', $stats);
    }
    
    // Product Management
    private function getAllProducts() {
        $sql = "SELECT p.*, c.name as category_name 
                FROM Product p 
                LEFT JOIN Category c ON p.category_id = c.category_id 
                ORDER BY p.product_id DESC";
        $result = $this->conn->query($sql);
        
        $products = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        
        $this->sendResponse(true, 'Products retrieved successfully', $products);
    }
    
    private function getProductById($id) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM Product p 
                LEFT JOIN Category c ON p.category_id = c.category_id 
                WHERE p.product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $this->sendResponse(true, 'Product retrieved successfully', $product);
        } else {
            $this->sendResponse(false, 'Product not found', null, 404);
        }
    }
    
    private function addProduct($data) {
        $required = ['name', 'description', 'price', 'stock', 'category_id'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $this->sendResponse(false, "Field '$field' is required", null, 400);
                return;
            }
        }
        
        $sql = "INSERT INTO Product (name, description, price, stock, category_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssdii", $data['name'], $data['description'], $data['price'], $data['stock'], $data['category_id']);
        
        if ($stmt->execute()) {
            $product_id = $this->conn->insert_id;
            $this->sendResponse(true, 'Product added successfully', ['product_id' => $product_id]);
        } else {
            $this->sendResponse(false, 'Failed to add product', null, 500);
        }
    }
    
    private function updateProduct($data) {
        $required = ['product_id', 'name', 'description', 'price', 'stock', 'category_id'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                $this->sendResponse(false, "Field '$field' is required", null, 400);
                return;
            }
        }
        
        $sql = "UPDATE Product SET name = ?, description = ?, price = ?, stock = ?, category_id = ? WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssdiis", $data['name'], $data['description'], $data['price'], $data['stock'], $data['category_id'], $data['product_id']);
        
        if ($stmt->execute()) {
            $this->sendResponse(true, 'Product updated successfully');
        } else {
            $this->sendResponse(false, 'Failed to update product', null, 500);
        }
    }
    
    private function deleteProduct($id) {
        $sql = "DELETE FROM Product WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $this->sendResponse(true, 'Product deleted successfully');
        } else {
            $this->sendResponse(false, 'Failed to delete product', null, 500);
        }
    }
    
    private function updateStock($data) {
        if (!isset($data['product_id']) || !isset($data['quantity'])) {
            $this->sendResponse(false, 'Product ID and quantity are required', null, 400);
            return;
        }
        
        $sql = "UPDATE Product SET stock = stock + ? WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $data['quantity'], $data['product_id']);
        
        if ($stmt->execute()) {
            $this->sendResponse(true, 'Stock updated successfully');
        } else {
            $this->sendResponse(false, 'Failed to update stock', null, 500);
        }
    }
    
    // Category Management
    private function getAllCategories() {
        $sql = "SELECT * FROM Category ORDER BY name";
        $result = $this->conn->query($sql);
        
        $categories = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
        }
        
        $this->sendResponse(true, 'Categories retrieved successfully', $categories);
    }
    
    private function addCategory($data) {
        if (!isset($data['name']) || empty($data['name'])) {
            $this->sendResponse(false, 'Category name is required', null, 400);
            return;
        }
        
        $sql = "INSERT INTO Category (name) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $data['name']);
        
        if ($stmt->execute()) {
            $category_id = $this->conn->insert_id;
            $this->sendResponse(true, 'Category added successfully', ['category_id' => $category_id]);
        } else {
            $this->sendResponse(false, 'Failed to add category', null, 500);
        }
    }
    
    // Order Management
    private function getAllOrders() {
        $sql = "SELECT o.*, u.first_name, u.last_name, u.email 
                FROM `Order` o 
                LEFT JOIN User u ON o.user_id = u.user_id 
                ORDER BY o.order_date DESC";
        $result = $this->conn->query($sql);
        
        $orders = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }
        }
        
        $this->sendResponse(true, 'Orders retrieved successfully', $orders);
    }
    
    private function getOrderDetails($order_id) {
        $sql = "SELECT oi.*, p.name as product_name, p.description 
                FROM Order_Item oi 
                LEFT JOIN Product p ON oi.product_id = p.product_id 
                WHERE oi.order_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $order_items = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $order_items[] = $row;
            }
        }
        
        $this->sendResponse(true, 'Order details retrieved successfully', $order_items);
    }
    
    // User Management
    private function getAllUsers() {
        $sql = "SELECT user_id, first_name, last_name, email, address, phone_number, role 
                FROM User 
                ORDER BY user_id DESC";
        $result = $this->conn->query($sql);
        
        $users = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        
        $this->sendResponse(true, 'Users retrieved successfully', $users);
    }
    
    private function getUserProfile($user_id) {
        $sql = "SELECT user_id, first_name, last_name, email, address, phone_number, role 
                FROM User WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $this->sendResponse(true, 'User profile retrieved successfully', $user);
        } else {
            $this->sendResponse(false, 'User not found', null, 404);
        }
    }
    
    // Reports and Analytics
    private function getLowStockProducts() {
        $sql = "SELECT p.*, c.name as category_name 
                FROM Product p 
                LEFT JOIN Category c ON p.category_id = c.category_id 
                WHERE p.stock < 20 
                ORDER BY p.stock ASC";
        $result = $this->conn->query($sql);
        
        $products = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        
        $this->sendResponse(true, 'Low stock products retrieved successfully', $products);
    }
    
    private function getPopularProducts() {
        $sql = "SELECT 
                    c.name as category_name,
                    p.name as product_name,
                    SUM(oi.quantity) as total_sold,
                    SUM(oi.quantity * oi.price) as total_revenue,
                    p.price as current_price
                FROM Category c
                LEFT JOIN Product p ON c.category_id = p.category_id
                LEFT JOIN Order_Item oi ON p.product_id = oi.product_id
                LEFT JOIN `Order` o ON oi.order_id = o.order_id
                WHERE p.product_id IS NOT NULL
                GROUP BY c.category_id, p.product_id
                ORDER BY c.category_id, total_sold DESC";
        
        $result = $this->conn->query($sql);
        
        $popular_products = array();
        $categories_seen = array();
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                // Only get the top product per category
                if (!in_array($row['category_name'], $categories_seen)) {
                    $popular_products[] = array(
                        'category' => $row['category_name'],
                        'product_name' => $row['product_name'],
                        'total_sold' => $row['total_sold'] ?: 0,
                        'total_revenue' => number_format($row['total_revenue'] ?: 0, 2),
                        'current_price' => number_format($row['current_price'], 2)
                    );
                    $categories_seen[] = $row['category_name'];
                }
            }
        }
        
        $this->sendResponse(true, 'Popular products retrieved successfully', $popular_products);
    }
    
    private function getInventoryReport($category_id = null) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM Product p 
                LEFT JOIN Category c ON p.category_id = c.category_id";
        
        if ($category_id && $category_id !== 'all') {
            $sql .= " WHERE p.category_id = " . intval($category_id);
        }
        
        $sql .= " ORDER BY c.name, p.name";
        
        $result = $this->conn->query($sql);
        
        $inventory = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $inventory[] = $row;
            }
        }
        
        $this->sendResponse(true, 'Inventory report retrieved successfully', $inventory);
    }
    
    private function getSalesReport($period = 'month') {
        $date_condition = '';
        switch ($period) {
            case 'week':
                $date_condition = "WHERE o.order_date >= DATE_SUB(CURRENT_DATE(), INTERVAL 1 WEEK)";
                break;
            case 'month':
                $date_condition = "WHERE MONTH(o.order_date) = MONTH(CURRENT_DATE()) AND YEAR(o.order_date) = YEAR(CURRENT_DATE())";
                break;
            case 'year':
                $date_condition = "WHERE YEAR(o.order_date) = YEAR(CURRENT_DATE())";
                break;
        }
        
        $sql = "SELECT 
                    DATE(o.order_date) as sale_date,
                    COUNT(DISTINCT o.order_id) as total_orders,
                    SUM(o.total_price) as total_revenue,
                    AVG(o.total_price) as avg_order_value
                FROM `Order` o 
                $date_condition
                GROUP BY DATE(o.order_date)
                ORDER BY sale_date DESC";
        
        $result = $this->conn->query($sql);
        
        $sales_data = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $sales_data[] = $row;
            }
        }
        
        $this->sendResponse(true, 'Sales report retrieved successfully', $sales_data);
    }
    
    private function getRevenueTrends() {
        $sql = "SELECT 
                    DATE(o.order_date) as date,
                    SUM(o.total_price) as revenue
                FROM `Order` o 
                WHERE o.order_date >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)
                GROUP BY DATE(o.order_date)
                ORDER BY date ASC";
        
        $result = $this->conn->query($sql);
        
        $trends = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $trends[] = $row;
            }
        }
        
        $this->sendResponse(true, 'Revenue trends retrieved successfully', $trends);
    }
    
    private function getCustomerInsights() {
        $insights = array();
        
        // Top customers by spending
        $sql = "SELECT 
                    u.first_name, u.last_name, u.email,
                    COUNT(o.order_id) as total_orders,
                    SUM(o.total_price) as total_spent
                FROM User u
                JOIN `Order` o ON u.user_id = o.user_id
                WHERE u.role = 'CUSTOMER'
                GROUP BY u.user_id
                ORDER BY total_spent DESC
                LIMIT 5";
        
        $result = $this->conn->query($sql);
        $top_customers = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $top_customers[] = $row;
            }
        }
        $insights['top_customers'] = $top_customers;
        
        // Customer acquisition (new customers this month)
        $sql = "SELECT COUNT(*) as new_customers 
                FROM User 
                WHERE role = 'CUSTOMER' 
                AND MONTH(user_id) = MONTH(CURRENT_DATE()) 
                AND YEAR(user_id) = YEAR(CURRENT_DATE())";
        $result = $this->conn->query($sql);
        $insights['new_customers_this_month'] = $result->fetch_assoc()['new_customers'];
        
        $this->sendResponse(true, 'Customer insights retrieved successfully', $insights);
    }
    
    // Reviews Management
    private function getAllReviews() {
        $sql = "SELECT r.*, u.first_name, u.last_name, p.name as product_name 
                FROM Review r 
                LEFT JOIN User u ON r.user_id = u.user_id 
                LEFT JOIN Product p ON r.product_id = p.product_id 
                ORDER BY r.review_date DESC";
        $result = $this->conn->query($sql);
        
        $reviews = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $reviews[] = $row;
            }
        }
        
        $this->sendResponse(true, 'Reviews retrieved successfully', $reviews);
    }
    
    // User Management Methods
    private function addUser($data) {
        $required = ['first_name', 'last_name', 'email', 'password'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $this->sendResponse(false, "Field '$field' is required", null, 400);
                return;
            }
        }
        
        // Hash password
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO User (first_name, last_name, email, password, address, phone_number, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $role = $data['role'] ?? 'CUSTOMER';
        $address = $data['address'] ?? '';
        $phone = $data['phone_number'] ?? '';
        
        $stmt->bind_param("sssssss", $data['first_name'], $data['last_name'], $data['email'], $hashed_password, $address, $phone, $role);
        
        if ($stmt->execute()) {
            $user_id = $this->conn->insert_id;
            $this->sendResponse(true, 'User added successfully', ['user_id' => $user_id]);
        } else {
            if ($this->conn->errno == 1062) {
                $this->sendResponse(false, 'Email already exists', null, 400);
            } else {
                $this->sendResponse(false, 'Failed to add user', null, 500);
            }
        }
    }
    
    private function updateUser($data) {
        $required = ['user_id', 'first_name', 'last_name', 'email'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                $this->sendResponse(false, "Field '$field' is required", null, 400);
                return;
            }
        }
        
        $sql = "UPDATE User SET first_name = ?, last_name = ?, email = ?, address = ?, phone_number = ?, role = ? WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $address = $data['address'] ?? '';
        $phone = $data['phone_number'] ?? '';
        $role = $data['role'] ?? 'CUSTOMER';
        
        $stmt->bind_param("ssssssi", $data['first_name'], $data['last_name'], $data['email'], $address, $phone, $role, $data['user_id']);
        
        if ($stmt->execute()) {
            $this->sendResponse(true, 'User updated successfully');
        } else {
            if ($this->conn->errno == 1062) {
                $this->sendResponse(false, 'Email already exists', null, 400);
            } else {
                $this->sendResponse(false, 'Failed to update user', null, 500);
            }
        }
    }
    
    private function deleteUser($id) {
        $sql = "DELETE FROM User WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $this->sendResponse(true, 'User deleted successfully');
        } else {
            $this->sendResponse(false, 'Failed to delete user', null, 500);
        }
    }
    
    // Order Processing Methods
    private function processOrder($data) {
        if (!isset($data['user_id']) || !isset($data['items']) || empty($data['items'])) {
            $this->sendResponse(false, 'User ID and order items are required', null, 400);
            return;
        }
        
        $this->conn->begin_transaction();
        
        try {
            // Calculate total price
            $total_price = 0;
            foreach ($data['items'] as $item) {
                $total_price += $item['price'] * $item['quantity'];
            }
            
            // Create order
            $sql = "INSERT INTO `Order` (order_date, total_price, user_id) VALUES (NOW(), ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("di", $total_price, $data['user_id']);
            $stmt->execute();
            $order_id = $this->conn->insert_id;
            
            // Add order items
            foreach ($data['items'] as $item) {
                $sql = "INSERT INTO Order_Item (quantity, price, order_id, product_id) VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("idii", $item['quantity'], $item['price'], $order_id, $item['product_id']);
                $stmt->execute();
                
                // Update product stock
                $sql = "UPDATE Product SET stock = stock - ? WHERE product_id = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("ii", $item['quantity'], $item['product_id']);
                $stmt->execute();
            }
            
            $this->conn->commit();
            $this->sendResponse(true, 'Order processed successfully', ['order_id' => $order_id]);
            
        } catch (Exception $e) {
            $this->conn->rollback();
            $this->sendResponse(false, 'Failed to process order: ' . $e->getMessage(), null, 500);
        }
    }
    
    private function updateOrderStatus($data) {
        if (!isset($data['order_id']) || !isset($data['status'])) {
            $this->sendResponse(false, 'Order ID and status are required', null, 400);
            return;
        }
        
        // Note: The current schema doesn't have an order status field
        // This would require adding a status column to the Order table
        $this->sendResponse(false, 'Order status update not implemented (requires schema modification)', null, 501);
    }
    
    // Utility method to send JSON response
    private function sendResponse($success, $message, $data = null, $status_code = 200) {
        http_response_code($status_code);
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit;
    }
}

// Initialize and handle request
try {
    $manager_controller = new ManagerController($conn);
    $manager_controller->handleRequest();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage(),
        'data' => null,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}
?>
