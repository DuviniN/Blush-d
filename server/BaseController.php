<?php
class BaseController {
    protected $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    protected function sendResponse($success, $message, $data = null, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
        exit();
    }
    
    protected function validateRequired($data, $required) {
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                return $field;
            }
        }
        return null;
    }
    
    protected function sanitizeInput($input) {
        if (is_array($input)) {
            return array_map([$this, 'sanitizeInput'], $input);
        }
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }
}
?>
