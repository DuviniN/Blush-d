<?php
/**
 * Main API Router for Blush-d Application
 * Routes requests to appropriate controllers based on endpoint
 */

require_once 'config/db.php';

// Set headers for API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Get the endpoint from the URL
$endpoint = $_GET['endpoint'] ?? '';
$action = $_GET['action'] ?? '';

try {
    switch ($endpoint) {
        case 'reviews':
            require_once 'ReviewsController.php';
            $controller = new ReviewsController($conn);
            $controller->handleRequest();
            break;
        case 'profile':
            require_once 'ProfileController.php';
            $controller = new ProfileController($conn);
            $controller->handleRequest();
            break;
            
        case 'products':
            require_once 'ProductController.php';
            $controller = new ProductController($conn);
            $controller->handleRequest();
            break;
            
        case 'dashboard':
            require_once 'DashboardController.php';
            $controller = new DashboardController($conn);
            $controller->handleRequest();
            break;
            
        case 'manager':
            require_once 'ManagerController.php';
            $controller = new ManagerController($conn);
            $controller->handleRequest();
            break;
            
        case 'password':
            require_once 'PasswordController.php';
            $controller = new PasswordController($conn);
            $controller->handleRequest();
            break;
            
        case 'reports':
            require_once 'ReportController.php';
            $controller = new ReportController($conn);
            $controller->handleRequest();
            break;
            
        default:
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Endpoint not found',
                'data' => null
            ]);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage(),
        'data' => null
    ]);
}
?>
