<?php
session_start();
require_once 'config/db.php';

class PasswordController {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function changePassword($managerId, $currentPassword, $newPassword) {
        try {
            // First, verify the current password
            $stmt = $this->conn->prepare("SELECT password FROM managers WHERE id = ?");
            $stmt->bind_param("i", $managerId);
            $stmt->execute();
            $result = $stmt->get_result();
            $manager = $result->fetch_assoc();
            
            if (!$manager) {
                return [
                    'success' => false,
                    'message' => 'Manager not found'
                ];
            }
            
            // Verify current password (assuming passwords are hashed)
            if (!password_verify($currentPassword, $manager['password'])) {
                return [
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ];
            }
            
            // Validate new password strength
            $passwordValidation = $this->validatePasswordStrength($newPassword);
            if (!$passwordValidation['valid']) {
                return [
                    'success' => false,
                    'message' => $passwordValidation['message']
                ];
            }
            
            // Hash the new password
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Update the password in database
            $updateStmt = $this->conn->prepare("
                UPDATE managers 
                SET password = ?, password_changed_at = NOW() 
                WHERE id = ?
            ");
            
            $updateStmt->bind_param("si", $hashedNewPassword, $managerId);
            $result = $updateStmt->execute();
            
            if ($result) {
                // Log the password change for security
                $this->logPasswordChange($managerId);
                
                return [
                    'success' => true,
                    'message' => 'Password changed successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to update password'
                ];
            }
            
        } catch (Exception $e) {
            error_log("Password change error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while changing password'
            ];
        }
    }
    
    private function validatePasswordStrength($password) {
        $errors = [];
        
        // Check minimum length
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long";
        }
        
        // Check for uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter";
        }
        
        // Check for lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter";
        }
        
        // Check for number
        if (!preg_match('/\d/', $password)) {
            $errors[] = "Password must contain at least one number";
        }
        
        // Check for special character
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            $errors[] = "Password must contain at least one special character";
        }
        
        // Check if password is too common (basic check)
        $commonPasswords = [
            'password', '123456789', 'qwerty123', 'admin123', 
            'password123', '12345678', 'welcome123'
        ];
        
        if (in_array(strtolower($password), $commonPasswords)) {
            $errors[] = "Password is too common, please choose a stronger password";
        }
        
        return [
            'valid' => empty($errors),
            'message' => empty($errors) ? 'Password is strong' : implode(', ', $errors)
        ];
    }
    
    private function logPasswordChange($managerId) {
        try {
            // Create password change log entry
            $logStmt = $this->conn->prepare("
                INSERT INTO password_change_log (manager_id, changed_at, ip_address, user_agent) 
                VALUES (?, NOW(), ?, ?)
            ");
            
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
            
            $logStmt->bind_param("iss", $managerId, $ipAddress, $userAgent);
            $logStmt->execute();
        } catch (Exception $e) {
            // Log error but don't fail the password change
            error_log("Failed to log password change: " . $e->getMessage());
        }
    }
    
    public function getPasswordHistory($managerId, $limit = 5) {
        try {
            $stmt = $this->conn->prepare("
                SELECT changed_at, ip_address 
                FROM password_change_log 
                WHERE manager_id = ? 
                ORDER BY changed_at DESC 
                LIMIT ?
            ");
            
            $stmt->bind_param("ii", $managerId, $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $history = [];
            while ($row = $result->fetch_assoc()) {
                $history[] = $row;
            }
            
            return [
                'success' => true,
                'data' => $history
            ];
            
        } catch (Exception $e) {
            error_log("Password history error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to retrieve password history'
            ];
        }
    }
    
    public function checkPasswordExpiry($managerId) {
        try {
            $stmt = $this->conn->prepare("
                SELECT password_changed_at, 
                       DATEDIFF(NOW(), password_changed_at) as days_since_change
                FROM managers 
                WHERE id = ?
            ");
            
            $stmt->bind_param("i", $managerId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            $daysSinceChange = $row['days_since_change'] ?? 0;
            $passwordExpiryDays = 90; // Password expires after 90 days
            
            $isExpired = $daysSinceChange >= $passwordExpiryDays;
            $daysUntilExpiry = $passwordExpiryDays - $daysSinceChange;
            
            return [
                'success' => true,
                'data' => [
                    'is_expired' => $isExpired,
                    'days_since_change' => $daysSinceChange,
                    'days_until_expiry' => max(0, $daysUntilExpiry),
                    'should_warn' => $daysUntilExpiry <= 7 && $daysUntilExpiry > 0
                ]
            ];
            
        } catch (Exception $e) {
            error_log("Password expiry check error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to check password expiry'
            ];
        }
    }
}

// Handle API requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? '';
    
    $passwordController = new PasswordController();
    
    header('Content-Type: application/json');
    
    switch ($action) {
        case 'change_password':
            // Get manager ID from session (in real app)
            $managerId = $_SESSION['manager_id'] ?? 1; // Default for demo
            
            $currentPassword = $input['currentPassword'] ?? '';
            $newPassword = $input['newPassword'] ?? '';
            
            if (empty($currentPassword) || empty($newPassword)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Current password and new password are required'
                ]);
                exit;
            }
            
            $result = $passwordController->changePassword($managerId, $currentPassword, $newPassword);
            echo json_encode($result);
            break;
            
        case 'password_history':
            $managerId = $_SESSION['manager_id'] ?? 1;
            $result = $passwordController->getPasswordHistory($managerId);
            echo json_encode($result);
            break;
            
        case 'check_expiry':
            $managerId = $_SESSION['manager_id'] ?? 1;
            $result = $passwordController->checkPasswordExpiry($managerId);
            echo json_encode($result);
            break;
            
        default:
            echo json_encode([
                'success' => false,
                'message' => 'Invalid action'
            ]);
            break;
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Only POST requests are allowed'
    ]);
}
?>
