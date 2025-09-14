<?php
require_once 'config/db.php';
require_once 'BaseController.php';

class ReportController extends BaseController {
    
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
                default:
                    $this->sendResponse(false, 'Method not allowed', null, 405);
            }
        } catch (Exception $e) {
            $this->sendResponse(false, 'Server error: ' . $e->getMessage(), null, 500);
        }
    }
    
    private function handleGetRequests($action) {
        switch ($action) {
            case 'total_products':
                $this->getTotalProducts();
                break;
            case 'low_stock':
                $this->getLowStockItems();
                break;
            case 'total_orders':
                $this->getTotalOrders();
                break;
            case 'monthly_revenue':
                $this->getMonthlyRevenue();
                break;
            case 'category_distribution':
                $this->getCategoryDistribution();
                break;
            case 'sales_by_category':
                $this->getSalesByCategory();
                break;
            case 'monthly_sales_chart':
                $this->getMonthlySalesChart();
                break;
            case 'popular_products':
                $this->getPopularProducts();
                break;
            default:
                $this->sendResponse(false, 'Action not found', null, 404);
        }
    }
    
    private function getTotalProducts() {
        $sql = "SELECT COUNT(*) as total FROM product";
        $result = $this->conn->query($sql);
        $data = $result->fetch_assoc();
        
        $this->sendResponse(true, 'Total products retrieved successfully', $data);
    }
    
    private function getLowStockItems() {
        $sql = "SELECT COUNT(*) as count FROM product WHERE stock < 20";
        $result = $this->conn->query($sql);
        $data = $result->fetch_assoc();
        
        $this->sendResponse(true, 'Low stock items retrieved successfully', $data);
    }
    
    private function getTotalOrders() {
        $sql = "SELECT COUNT(*) as total FROM `order` WHERE MONTH(order_date) = MONTH(CURRENT_DATE()) AND YEAR(order_date) = YEAR(CURRENT_DATE())";
        $result = $this->conn->query($sql);
        $data = $result->fetch_assoc();
        
        $this->sendResponse(true, 'Total orders retrieved successfully', $data);
    }
    
    private function getMonthlyRevenue() {
        $sql = "SELECT COALESCE(SUM(total_price), 0) as revenue FROM `order` WHERE MONTH(order_date) = MONTH(CURRENT_DATE()) AND YEAR(order_date) = YEAR(CURRENT_DATE())";
        $result = $this->conn->query($sql);
        $data = $result->fetch_assoc();
        
        $this->sendResponse(true, 'Monthly revenue retrieved successfully', $data);
    }
    
    private function getCategoryDistribution() {
        $sql = "SELECT 
                    c.name as category_name,
                    COUNT(p.product_id) as product_count,
                    COALESCE(SUM(p.stock), 0) as total_stock
                FROM category c
                LEFT JOIN product p ON c.category_id = p.category_id
                GROUP BY c.category_id, c.name
                ORDER BY product_count DESC";
        
        $result = $this->conn->query($sql);
        $categories = array();
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
        }
        
        $this->sendResponse(true, 'Category distribution retrieved successfully', $categories);
    }
    
    private function getSalesByCategory() {
        $sql = "SELECT 
                    c.name as category_name,
                    COUNT(oi.order_item_id) as items_sold,
                    SUM(oi.quantity) as total_quantity,
                    SUM(oi.quantity * oi.price) as total_revenue
                FROM category c
                LEFT JOIN product p ON c.category_id = p.category_id
                LEFT JOIN order_item oi ON p.product_id = oi.product_id
                LEFT JOIN `order` o ON oi.order_id = o.order_id
                WHERE o.order_date >= DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH) OR o.order_date IS NULL
                GROUP BY c.category_id, c.name
                ORDER BY total_revenue DESC";
        
        $result = $this->conn->query($sql);
        $sales = array();
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $sales[] = array(
                    'category_name' => $row['category_name'],
                    'items_sold' => $row['items_sold'] ?? 0,
                    'total_quantity' => $row['total_quantity'] ?? 0,
                    'total_revenue' => $row['total_revenue'] ?? 0
                );
            }
        }
        
        $this->sendResponse(true, 'Sales by category retrieved successfully', $sales);
    }
    
    private function getMonthlySalesChart() {
        $sql = "SELECT 
                    DATE_FORMAT(order_date, '%Y-%m') as month,
                    COUNT(*) as order_count,
                    SUM(total_price) as revenue
                FROM `order`
                WHERE order_date >= DATE_SUB(CURRENT_DATE(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(order_date, '%Y-%m')
                ORDER BY month ASC";
        
        $result = $this->conn->query($sql);
        $chartData = array();
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $chartData[] = array(
                    'month' => $row['month'],
                    'month_name' => date('M Y', strtotime($row['month'] . '-01')),
                    'order_count' => $row['order_count'],
                    'revenue' => $row['revenue']
                );
            }
        }
        
        $this->sendResponse(true, 'Monthly sales chart data retrieved successfully', $chartData);
    }
    
    private function getPopularProducts() {
        $sql = "SELECT 
                    p.product_name,
                    p.price,
                    p.stock,
                    c.name as category_name,
                    COALESCE(SUM(oi.quantity), 0) as total_sold,
                    COALESCE(SUM(oi.quantity * oi.price), 0) as revenue
                FROM product p
                LEFT JOIN category c ON p.category_id = c.category_id
                LEFT JOIN order_item oi ON p.product_id = oi.product_id
                GROUP BY p.product_id
                ORDER BY total_sold DESC, revenue DESC
                LIMIT 10";
        
        $result = $this->conn->query($sql);
        $products = array();
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        
        $this->sendResponse(true, 'Popular products retrieved successfully', $products);
    }
}
?>
