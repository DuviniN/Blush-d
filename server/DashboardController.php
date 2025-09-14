<?php
require_once 'config/db.php';
require_once 'BaseController.php';

class DashboardController extends BaseController {
    
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
            case 'dashboard_stats':
                $this->getDashboardStats();
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
        $stats['monthly_revenue'] = $result->fetch_assoc()['total'];
        
        // Total Users
        $sql = "SELECT COUNT(*) as total FROM User";
        $result = $this->conn->query($sql);
        $stats['total_users'] = $result->fetch_assoc()['total'];
        
        // Total Categories
        $sql = "SELECT COUNT(*) as total FROM Category";
        $result = $this->conn->query($sql);
        $stats['total_categories'] = $result->fetch_assoc()['total'];
        
        $this->sendResponse(true, 'Dashboard statistics retrieved successfully', $stats);
    }
    
    private function getSalesReport($period) {
        switch ($period) {
            case 'week':
                $dateCondition = "WHERE order_date >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY)";
                break;
            case 'year':
                $dateCondition = "WHERE YEAR(order_date) = YEAR(CURRENT_DATE())";
                break;
            default: // month
                $dateCondition = "WHERE MONTH(order_date) = MONTH(CURRENT_DATE()) AND YEAR(order_date) = YEAR(CURRENT_DATE())";
        }
        
        $sql = "SELECT 
                    DATE(order_date) as date,
                    COUNT(*) as total_orders,
                    SUM(total_price) as daily_revenue
                FROM `Order` 
                $dateCondition
                GROUP BY DATE(order_date)
                ORDER BY DATE(order_date) DESC";
        
        $result = $this->conn->query($sql);
        $salesData = array();
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $salesData[] = $row;
            }
        }
        
        $this->sendResponse(true, 'Sales report retrieved successfully', $salesData);
    }
    
    private function getPopularProducts() {
        $sql = "SELECT 
                    p.product_name,
                    p.price,
                    c.name as category_name,
                    COUNT(oi.product_id) as times_ordered,
                    SUM(oi.quantity) as total_quantity_sold,
                    SUM(oi.quantity * oi.price) as total_revenue
                FROM Order_Item oi
                JOIN Product p ON oi.product_id = p.product_id
                LEFT JOIN Category c ON p.category_id = c.category_id
                GROUP BY oi.product_id
                ORDER BY times_ordered DESC, total_quantity_sold DESC
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
    
    private function getInventoryReport($category_id = null) {
        $sql = "SELECT 
                    p.product_id,
                    p.product_name,
                    p.stock,
                    p.price,
                    c.name as category_name,
                    CASE 
                        WHEN p.stock < 10 THEN 'Critical'
                        WHEN p.stock < 20 THEN 'Low'
                        WHEN p.stock < 50 THEN 'Medium'
                        ELSE 'Good'
                    END as stock_status
                FROM Product p
                LEFT JOIN Category c ON p.category_id = c.category_id";
        
        if ($category_id) {
            $sql .= " WHERE p.category_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $category_id);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $sql .= " ORDER BY p.stock ASC";
            $result = $this->conn->query($sql);
        }
        
        $inventory = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $inventory[] = $row;
            }
        }
        
        $this->sendResponse(true, 'Inventory report retrieved successfully', $inventory);
    }
    
    private function getRevenueTrends() {
        $sql = "SELECT 
                    YEAR(order_date) as year,
                    MONTH(order_date) as month,
                    SUM(total_price) as monthly_revenue,
                    COUNT(*) as monthly_orders
                FROM `Order`
                WHERE order_date >= DATE_SUB(CURRENT_DATE(), INTERVAL 12 MONTH)
                GROUP BY YEAR(order_date), MONTH(order_date)
                ORDER BY year DESC, month DESC";
        
        $result = $this->conn->query($sql);
        $trends = array();
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $row['month_name'] = date('F', mktime(0, 0, 0, $row['month'], 1));
                $trends[] = $row;
            }
        }
        
        $this->sendResponse(true, 'Revenue trends retrieved successfully', $trends);
    }
    
    private function getCustomerInsights() {
        // Top customers by order count
        $sql = "SELECT 
                    u.first_name,
                    u.last_name,
                    u.email,
                    COUNT(o.order_id) as total_orders,
                    SUM(o.total_price) as total_spent
                FROM User u
                JOIN `Order` o ON u.user_id = o.user_id
                WHERE u.role = 'CUSTOMER'
                GROUP BY u.user_id
                ORDER BY total_orders DESC, total_spent DESC
                LIMIT 10";
        
        $result = $this->conn->query($sql);
        $customers = array();
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $customers[] = $row;
            }
        }
        
        $this->sendResponse(true, 'Customer insights retrieved successfully', $customers);
    }
}
?>
