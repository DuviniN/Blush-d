<?php
require_once 'config/db.php';
require_once 'BaseController.php';

class ProductController extends BaseController {
    
    public function __construct($connection) {
        parent::__construct($connection);
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
            case 'products':
                $this->getAllProducts();
                break;
            case 'product_by_id':
                $this->getProductById($_GET['id'] ?? 0);
                break;
            case 'categories':
                $this->getAllCategories();
                break;
            case 'low_stock_products':
                $this->getLowStockProducts();
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
            case 'update_stock':
                $this->updateStock($input);
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
            default:
                $this->sendResponse(false, 'Action not found', null, 404);
        }
    }
    
    private function getAllProducts() {
        $sql = "SELECT p.*, c.name as category, c.name as category_name 
                FROM product p 
                LEFT JOIN category c ON p.category_id = c.category_id 
                ORDER BY p.product_name";
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
        $sql = "SELECT p.*, c.name as category, c.name as category_name 
                FROM product p 
                LEFT JOIN category c ON p.category_id = c.category_id 
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
    
    private function addProduct($data = null) {
        // Handle both form data and JSON data
        if ($data === null) {
            $data = $_POST;
        }
        
        $required = ['product_name', 'price', 'stock', 'category_id'];
        $missing = $this->validateRequired($data, $required);
        if ($missing) {
            $this->sendResponse(false, "Field '$missing' is required", null, 400);
            return;
        }
        
        $sql = "INSERT INTO product (product_name, description, price, stock, category_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $description = $data['description'] ?? '';
        
        $stmt->bind_param("ssdii", $data['product_name'], $description, $data['price'], $data['stock'], $data['category_id']);
        
        if ($stmt->execute()) {
            $productId = $this->conn->insert_id;
            $this->sendResponse(true, 'Product added successfully', ['product_id' => $productId]);
        } else {
            $this->sendResponse(false, 'Failed to add product', null, 500);
        }
    }
    
    private function updateProduct($data) {
        $required = ['product_id', 'product_name', 'price', 'stock'];
        $missing = $this->validateRequired($data, $required);
        if ($missing) {
            $this->sendResponse(false, "Field '$missing' is required", null, 400);
            return;
        }
        
        $sql = "UPDATE product SET product_name = ?, description = ?, price = ?, stock = ?, category_id = ? WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $description = $data['description'] ?? '';
        $category_id = $data['category_id'] ?? null;
        
        $stmt->bind_param("ssdiii", $data['product_name'], $description, $data['price'], $data['stock'], $category_id, $data['product_id']);
        
        if ($stmt->execute()) {
            $this->sendResponse(true, 'Product updated successfully');
        } else {
            $this->sendResponse(false, 'Failed to update product', null, 500);
        }
    }
    
    private function deleteProduct($id) {
        $sql = "DELETE FROM product WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $this->sendResponse(true, 'Product deleted successfully');
        } else {
            $this->sendResponse(false, 'Failed to delete product', null, 500);
        }
    }
    
    private function updateStock($data) {
        $required = ['product_id', 'quantity'];
        $missing = $this->validateRequired($data, $required);
        if ($missing) {
            $this->sendResponse(false, "Field '$missing' is required", null, 400);
            return;
        }
        
        // Add to existing stock instead of replacing it
        $sql = "UPDATE product SET stock = stock + ? WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $data['quantity'], $data['product_id']);
        
        if ($stmt->execute()) {
            $this->sendResponse(true, 'Stock updated successfully');
        } else {
            $this->sendResponse(false, 'Failed to update stock', null, 500);
        }
    }
    
    private function getAllCategories() {
        $sql = "SELECT * FROM category ORDER BY name";
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
        $required = ['name'];
        $missing = $this->validateRequired($data, $required);
        if ($missing) {
            $this->sendResponse(false, "Field '$missing' is required", null, 400);
            return;
        }
        
        $sql = "INSERT INTO category (name) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $data['name']);
        
        if ($stmt->execute()) {
            $categoryId = $this->conn->insert_id;
            $this->sendResponse(true, 'Category added successfully', ['category_id' => $categoryId]);
        } else {
            if ($this->conn->errno == 1062) {
                $this->sendResponse(false, 'Category already exists', null, 400);
            } else {
                $this->sendResponse(false, 'Failed to add category', null, 500);
            }
        }
    }
    
    private function getLowStockProducts() {
        $sql = "SELECT p.*, c.name as category, c.name as category_name 
                FROM product p 
                LEFT JOIN category c ON p.category_id = c.category_id 
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
}
?>
