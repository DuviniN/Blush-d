<?php
require_once 'config/db.php';
require_once 'BaseController.php';

class ReviewsController extends BaseController {
    public function handleRequest() { 
        $method = $_SERVER['REQUEST_METHOD'];
        try {
            switch ($method) {
                case 'GET':
                    $this->getReviews();
                    break;
                default:
                    $this->sendResponse(false, 'Method not allowed', null, 405);
            }
        } catch (Exception $e) {
            $this->sendResponse(false, 'Server error: ' . $e->getMessage(), null, 500);
        }
    }

    public function getReviews() {
        $sql = "SELECT r.review_id as id, r.user_id, r.product_id, r.rating, r.comments as comment, r.review_date as created_at, 
                       CONCAT(u.first_name, ' ', u.last_name) as username, p.product_name as product_name
                FROM Review r
                JOIN User u ON r.user_id = u.user_id
                JOIN Product p ON r.product_id = p.product_id
                ORDER BY r.review_date DESC
                LIMIT 3";
        $result = $this->conn->query($sql);

        if ($result) {
            $reviews = [];
            while ($row = $result->fetch_assoc()) {
                $reviews[] = $row;
            }
            $this->sendResponse(true, 'Reviews fetched successfully', $reviews);
        } else {
            $this->sendResponse(false, 'Failed to fetch reviews', null, 500);
        }
    }
}

?>