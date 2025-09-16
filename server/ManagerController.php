<?php
require_once 'config/db.php';
require_once 'BaseController.php';
require_once 'ProductController.php';
require_once 'DashboardController.php';
require_once 'ProfileController.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

class ManagerController extends BaseController {
    private $productController;
    private $dashboardController;
    private $profileController;
    
    public function __construct($connection) {
        parent::__construct($connection);
        $this->productController = new ProductController($connection);
        $this->dashboardController = new DashboardController($connection);
        $this->profileController = new ProfileController($connection);
    }
    
    public function handleRequest() {
        $action = $_GET['action'] ?? '';
        
        // Route to appropriate controller based on action
        try {
            // Profile actions
            if (in_array($action, ['manager_profile', 'user_profile', 'update_manager_profile', 'update_user'])) {
                $this->profileController->handleRequest();
                return;
            }
            
            // Product actions
            if (in_array($action, ['products', 'product_by_id', 'add_product', 'update_product', 'delete_product', 'categories', 'add_category', 'update_stock', 'low_stock_products'])) {
                $this->productController->handleRequest();
                return;
            }
            
            // Dashboard actions
            if (in_array($action, ['dashboard_stats', 'sales_report', 'popular_products', 'inventory_report', 'revenue_trends', 'customer_insights'])) {
                $this->dashboardController->handleRequest();
                return;
            }
            
            // Legacy actions that still need to be handled here
            $method = $_SERVER['REQUEST_METHOD'];
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
            case 'orders':
                $this->getAllOrders();
                break;
            case 'order_details':
                $this->getOrderDetails($_GET['id'] ?? 0);
                break;
            case 'users':
                $this->getAllUsers();
                break;
            case 'reviews':
                $this->getAllReviews();
                break;
            default:
                $this->sendResponse(false, 'Action not found', null, 404);
        }
    }
    
    private function handlePostRequests($action) {
        $input = json_decode(file_get_contents('php://input'), true);
        
        switch ($action) {
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
            case 'update_order_status':
                $this->updateOrderStatus($input);
                break;
            default:
                $this->sendResponse(false, 'Action not found', null, 404);
        }
    }
    
    private function handleDeleteRequests($action) {
        switch ($action) {
            case 'delete_user':
                $this->deleteUser($_GET['id'] ?? 0);
                break;
            default:
                $this->sendResponse(false, 'Action not found', null, 404);
        }
    }
    
    // Order Management Methods
    private function getAllOrders() {
        $sql = "SELECT o.*, u.first_name, u.last_name, u.email 
                FROM `Order` o 
                JOIN User u ON o.user_id = u.user_id 
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
        $sql = "SELECT o.*, u.first_name, u.last_name, u.email,
                       oi.quantity, oi.price as item_price, p.product_name
                FROM `Order` o
                JOIN User u ON o.user_id = u.user_id
                LEFT JOIN Order_Item oi ON o.order_id = oi.order_id
                LEFT JOIN Product p ON oi.product_id = p.product_id
                WHERE o.order_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $order = $result->fetch_all(MYSQLI_ASSOC);
            $this->sendResponse(true, 'Order details retrieved successfully', $order);
        } else {
            $this->sendResponse(false, 'Order not found', null, 404);
        }
    }
    
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
            $sql = "INSERT INTO `Order` (user_id, order_date, total_price) VALUES (?, NOW(), ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("id", $data['user_id'], $total_price);
            $stmt->execute();
            
            $order_id = $this->conn->insert_id;
            
            // Add order items
            foreach ($data['items'] as $item) {
                $sql = "INSERT INTO Order_Item (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
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
        
        // Note: You might need to add a status column to your Order table
        $sql = "UPDATE `Order` SET status = ? WHERE order_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $data['status'], $data['order_id']);
        
        if ($stmt->execute()) {
            $this->sendResponse(true, 'Order status updated successfully');
        } else {
            $this->sendResponse(false, 'Failed to update order status', null, 500);
        }
    }
    
    // User Management Methods
    private function getAllUsers() {
        $sql = "SELECT user_id, first_name, last_name, email, address, phone_number, role 
                FROM User ORDER BY role, last_name, first_name";
        $result = $this->conn->query($sql);
        
        $users = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        
        $this->sendResponse(true, 'Users retrieved successfully', $users);
    }
    
    private function addUser($data) {
        $required = ['first_name', 'last_name', 'email', 'password'];
        $missing = $this->validateRequired($data, $required);
        if ($missing) {
            $this->sendResponse(false, "Field '$missing' is required", null, 400);
            return;
        }
        
        $sql = "INSERT INTO User (first_name, last_name, email, password, address, phone_number, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $address = $data['address'] ?? '';
        $phone = $data['phone_number'] ?? '';
        $role = $data['role'] ?? 'CUSTOMER';
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt->bind_param("sssssss", $data['first_name'], $data['last_name'], $data['email'], $hashedPassword, $address, $phone, $role);
        
        if ($stmt->execute()) {
            $userId = $this->conn->insert_id;
            $this->sendResponse(true, 'User added successfully', ['user_id' => $userId]);
        } else {
            if ($this->conn->errno == 1062) {
                $this->sendResponse(false, 'Email already exists', null, 400);
            } else {
                $this->sendResponse(false, 'Failed to add user', null, 500);
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
    
    private function getAllReviews() {
        $sql = "SELECT r.*, u.first_name, u.last_name, p.product_name 
                FROM Review r 
                JOIN User u ON r.user_id = u.user_id 
                JOIN Product p ON r.product_id = p.product_id 
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
}

// Handle the request if this file is called directly
if (basename($_SERVER['PHP_SELF']) == 'ManagerController.php') {
    $controller = new ManagerController($conn);
    $controller->handleRequest();
}
?>
