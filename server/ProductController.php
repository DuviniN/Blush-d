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
        // For file uploads, use $_POST and $_FILES directly
        $input = null;
        if (!isset($_FILES) || empty($_FILES)) {
            $input = json_decode(file_get_contents('php://input'), true);
        }
        
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
        
        // Initialize image variables
        $imagePath = null;
        $imageId = null;
        
        // Handle image upload
        if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->handleImageUpload($_FILES['productImage']);
            
            if ($uploadResult['success']) {
                $imagePath = $uploadResult['path'];
                $imageId = $uploadResult['id'];
            } else {
                $this->sendResponse(false, $uploadResult['message'], null, 400);
                return;
            }
        }
        
        $sql = "INSERT INTO product (product_name, description, price, stock, category_id, image_id, ingredients, mini_description, img_src) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        
        $description = $data['description'] ?? '';
        $ingredients = $data['ingredients'] ?? '';
        $mini_description = $data['mini_description'] ?? '';
        
        $stmt->bind_param("ssdiiisss", $data['product_name'], $description, $data['price'], $data['stock'], $data['category_id'], $imageId, $ingredients, $mini_description, $imagePath);
        
        if ($stmt->execute()) {
            $productId = $this->conn->insert_id;
            $this->sendResponse(true, 'Product added successfully', [
                'product_id' => $productId,
                'image_path' => $imagePath,
                'image_id' => $imageId
            ]);
        } else {
            // If database insert fails and image was uploaded, clean up the image file
            if ($imagePath && file_exists(__DIR__ . '/../' . $imagePath)) {
                unlink(__DIR__ . '/../' . $imagePath);
            }
            $this->sendResponse(false, 'Failed to add product: ' . $stmt->error, null, 500);
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
    
    private function handleImageUpload($file) {
        // Check if file is valid
        if (!isset($file) || !is_array($file)) {
            return ['success' => false, 'message' => 'No file provided'];
        }
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
                UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                UPLOAD_ERR_EXTENSION => 'Upload stopped by extension'
            ];
            
            $message = $errorMessages[$file['error']] ?? 'Unknown upload error';
            return ['success' => false, 'message' => $message];
        }
        
        // Define upload directory
        $uploadDir = __DIR__ . '/../assets/products/';
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                return ['success' => false, 'message' => 'Failed to create upload directory'];
            }
        }
        
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $fileType = $file['type'];
        
        if (!in_array($fileType, $allowedTypes)) {
            return ['success' => false, 'message' => 'Invalid file type. Only JPEG, PNG, and GIF are allowed.'];
        }
        
        // Validate file size (10MB max)
        $maxSize = 10 * 1024 * 1024; // 10MB in bytes
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'File size too large. Maximum 10MB allowed.'];
        }
        
        // Generate unique filename with safe ID that fits in INT column
        $baseTimestamp = 1700000000; // Nov 2023 as base
        $timeDiff = time() - $baseTimestamp;
        $randomId = mt_rand(1000, 9999);
        $imageId = $timeDiff * 10000 + $randomId;
        
        // Safety check - if too large, use simple random
        if ($imageId > 2000000000) {
            $imageId = mt_rand(100000, 999999);
        }
        
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $uniqueName = 'product_' . $imageId . '.' . $fileExtension;
        $fullPath = $uploadDir . $uniqueName;
        $relativePath = 'assets/products/' . $uniqueName;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $fullPath)) {
            return [
                'success' => true,
                'path' => $relativePath,
                'id' => $imageId,
                'filename' => $uniqueName
            ];
        } else {
            return ['success' => false, 'message' => 'Failed to move uploaded file'];
        }
    }
}
?>
