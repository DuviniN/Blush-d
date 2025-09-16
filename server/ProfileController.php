<?php
require_once 'config/db.php';
require_once 'temp_session.php';
require_once 'BaseController.php';

class ProfileController extends BaseController {
    
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
                case 'PUT':
                    $this->handlePutRequests($action);
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
            case 'manager_profile':
                $this->getManagerProfile();
                break;
            case 'user_profile':
                $this->getUserProfile($_GET['id'] ?? 0);
                break;
            default:
                $this->sendResponse(false, 'Action not found', null, 404);
        }
    }
    
    private function handlePutRequests($action) {
        $input = json_decode(file_get_contents('php://input'), true);
        
        switch ($action) {
            case 'update_manager_profile':
                $this->updateManagerProfile($input);
                break;
            case 'update_user':
                $this->updateUser($input);
                break;
            default:
                $this->sendResponse(false, 'Action not found', null, 404);
        }
    }
    
    private function getManagerProfile() {
        $user_id = getCurrentUserId();
        if (!$user_id) {
            $this->sendResponse(false, 'No session found', null, 401);
            return;
        }

        $sql = "SELECT user_id, first_name, last_name, email, phone_number, role, department, start_day 
                FROM User WHERE user_id = ? AND role = 'MANAGER'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Add employee ID as EMP + user_id
            $user['employee_id'] = 'EMP' . $user['user_id'];
            $this->sendResponse(true, 'Manager profile retrieved successfully', $user);
        } else {
            $this->sendResponse(false, 'Manager not found', null, 404);
        }
    }
    
    private function getUserProfile($user_id) {
        $sql = "SELECT user_id, first_name, last_name, email,  phone_number, role, department, start_day 
                FROM User WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Add employee ID as EMP + user_id
            $user['employee_id'] = 'EMP' . $user['user_id'];
            $this->sendResponse(true, 'User profile retrieved successfully', $user);
        } else {
            $this->sendResponse(false, 'User not found', null, 404);
        }
    }
    
    private function updateManagerProfile($data) {
        $user_id = getCurrentUserId();
        if (!$user_id) {
            $this->sendResponse(false, 'No session found', null, 401);
            return;
        }

        $required = ['first_name', 'last_name', 'email'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                $this->sendResponse(false, "Field '$field' is required", null, 400);
                return;
            }
        }

        // First, let's check if the user exists and is a manager
        $check_sql = "SELECT user_id FROM User WHERE user_id = ? AND role = 'MANAGER'";
        $check_stmt = $this->conn->prepare($check_sql);
        $check_stmt->bind_param("i", $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows === 0) {
            $this->sendResponse(false, 'Manager not found or user is not a manager', null, 404);
            return;
        }

        $sql = "UPDATE User SET first_name = ?, last_name = ?, email = ?, phone_number = ?
                WHERE user_id = ? AND role = 'MANAGER'";
        $stmt = $this->conn->prepare($sql);
        
        
        $phone = isset($data['phone_number']) && $data['phone_number'] !== '' ? $data['phone_number'] : null;

        $stmt->bind_param("ssssi", $data['first_name'], $data['last_name'], $data['email'], $phone, $user_id);

        if ($stmt->execute()) {
            $affected_rows = $stmt->affected_rows;
            
            if ($affected_rows > 0) {
                $this->sendResponse(true, 'Profile updated successfully');
            } else {
                $this->sendResponse(false, 'No changes made - data is identical to current values', null, 200);
            }
        } else {
            if ($this->conn->errno == 1062) {
                $this->sendResponse(false, 'Email already exists', null, 400);
            } else {
                $this->sendResponse(false, 'Failed to update profile: ' . $this->conn->error, null, 500);
            }
        }
    }    private function updateUser($data) {
        $required = ['user_id', 'first_name', 'last_name', 'email'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                $this->sendResponse(false, "Field '$field' is required", null, 400);
                return;
            }
        }
        
        $sql = "UPDATE User SET first_name = ?, last_name = ?, email = ?,  phone_number = ?, role = ? WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $phone = $data['phone_number'] ?? '';
        $role = $data['role'] ?? 'CUSTOMER';
        
        $stmt->bind_param("ssssi", $data['first_name'], $data['last_name'], $data['email'], $phone, $role, $data['user_id']);
        
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
}
?>
