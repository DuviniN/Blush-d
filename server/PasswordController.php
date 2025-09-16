<?php
/**
 * Password Controller for Blush-d Application
 * Handles password change operations with proper validation and security
 */

require_once 'BaseController.php';
require_once 'config/db.php';

class PasswordController extends BaseController {
    
    public function __construct($connection) {
        parent::__construct($connection);
        session_start();
    }
    
    public function handleRequest() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->sendResponse(false, 'User not authenticated', null, 401);
            return;
        }
        
        $method = $_SERVER['REQUEST_METHOD'];
        $input = json_decode(file_get_contents('php://input'), true);
        
        if ($method === 'POST') {
            $action = $input['action'] ?? '';
            
            switch ($action) {
                case 'change_password':
                    $this->changePassword($input);
                    break;
                case 'validate_current_password':
                    $this->validateCurrentPassword($input);
                    break;
                default:
                    $this->sendResponse(false, 'Invalid action', null, 400);
            }
        } else {
            $this->sendResponse(false, 'Method not allowed', null, 405);
        }
    }
    
    /**
     * Change user password with validation
     */
    private function changePassword($input) {
        $currentPassword = $input['currentPassword'] ?? '';
        $newPassword = $input['newPassword'] ?? '';
        $userId = $_SESSION['user_id'];
        
        // Validate input
        if (empty($currentPassword) || empty($newPassword)) {
            $this->sendResponse(false, 'Current password and new password are required');
            return;
        }
        
        // Get current user data
        $stmt = $this->conn->prepare("SELECT password FROM User WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $this->sendResponse(false, 'User not found');
            return;
        }
        
        $user = $result->fetch_assoc();
        
        // Verify current password
        if (!password_verify($currentPassword, $user['password'])) {
            $this->sendResponse(false, 'Current password is incorrect');
            return;
        }
        
        // Validate new password strength (server-side validation)
        if (!$this->validatePasswordStrength($newPassword)) {
            $this->sendResponse(false, 'New password does not meet security requirements');
            return;
        }
        
        // Check if new password is different from current
        if (password_verify($newPassword, $user['password'])) {
            $this->sendResponse(false, 'New password must be different from current password');
            return;
        }
        
        // Hash new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Update password in database
        $updateStmt = $this->conn->prepare("UPDATE User SET password = ?, updated_at = NOW() WHERE user_id = ?");
        $updateStmt->bind_param("si", $hashedPassword, $userId);
        
        if ($updateStmt->execute()) {
            $this->sendResponse(true, 'Password changed successfully');
        } else {
            $this->sendResponse(false, 'Failed to update password');
        }
    }
    
    /**
     * Validate current password without changing it
     */
    private function validateCurrentPassword($input) {
        $currentPassword = $input['currentPassword'] ?? '';
        $userId = $_SESSION['user_id'];
        
        if (empty($currentPassword)) {
            $this->sendResponse(false, 'Current password is required');
            return;
        }
        
        $stmt = $this->conn->prepare("SELECT password FROM User WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $this->sendResponse(false, 'User not found');
            return;
        }
        
        $user = $result->fetch_assoc();
        
        if (password_verify($currentPassword, $user['password'])) {
            $this->sendResponse(true, 'Current password is valid');
        } else {
            $this->sendResponse(false, 'Current password is incorrect');
        }
    }
    
    /**
     * Validate password strength
     */
    private function validatePasswordStrength($password) {
        // At least 8 characters
        if (strlen($password) < 8) {
            return false;
        }
        
        // At least one uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }
        
        // At least one lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }
        
        // At least one number
        if (!preg_match('/\d/', $password)) {
            return false;
        }
        
        // At least one special character
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            return false;
        }
        
        return true;
    }
}

// Handle direct access to this file
if (basename($_SERVER['PHP_SELF']) === 'PasswordController.php') {
    require_once 'config/db.php';
    $controller = new PasswordController($conn);
    $controller->handleRequest();
}
?>
