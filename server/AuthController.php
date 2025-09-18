<?php
session_start();
require_once 'config/db.php';
require_once 'BaseController.php';

class AuthController extends BaseController {
    
    public function __construct($connection) {
        parent::__construct($connection);
    }
    
    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $action = $_GET['action'] ?? '';
        
        try {
            switch ($method) {
                case 'POST':
                    $this->handlePostRequests($action);
                    break;
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
            case 'check':
                $this->checkSession();
                break;
            default:
                $this->sendResponse(false, 'Action not found', null, 404);
        }
    }
    
    private function handlePostRequests($action) {
        switch ($action) {
            case 'login':
                $this->login();
                break;
            case 'register':
                $this->register();
                break;
            case 'logout':
                $this->logout();
                break;
            default:
                $this->sendResponse(false, 'Action not found', null, 404);
        }
    }
    
    private function login() {
        $data = json_decode(file_get_contents('php://input'), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $this->sendResponse(false, 'Email and password are required', null, 400);
            return;
        }
        
        $stmt = $this->conn->prepare("SELECT user_id, first_name, last_name, email, password, role FROM User WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $this->sendResponse(false, 'Invalid email or password', null, 401);
            return;
        }
        
        $user = $result->fetch_assoc();
        
        if (!password_verify($password, $user['password'])) {
            $this->sendResponse(false, 'Invalid email or password', null, 401);
            return;
        }
        
        // Set session data
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['is_logged_in'] = true;
        
        $this->sendResponse(true, 'Login successful', [
            'user_id' => $user['user_id'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
            'role' => $user['role']
        ]);
    }

    private function register() {
        $data = json_decode(file_get_contents('php://input'), true);
        $firstName = $data['firstName'] ?? '';
        $lastName = $data['lastName'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        
        if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
            $this->sendResponse(false, 'First name, last name, email, and password are required', null, 400);
            return;
        }
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->sendResponse(false, 'Invalid email format', null, 400);
            return;
        }
        
        // Check if email already exists
        $stmt = $this->conn->prepare("SELECT user_id FROM User WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $this->sendResponse(false, 'Email already exists', null, 409);
            return;
        }
        
        // Hash the password
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        
        // Insert new user (only customers can register)
        $role = 'CUSTOMER';
        $stmt = $this->conn->prepare("INSERT INTO User (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $firstName, $lastName, $email, $password_hash, $role);
        
        if ($stmt->execute()) {
            $userId = $stmt->insert_id;
            
            // Set session data for the newly registered user
            $_SESSION['user_id'] = $userId;
            $_SESSION['email'] = $email;
            $_SESSION['first_name'] = $firstName;
            $_SESSION['last_name'] = $lastName;
            $_SESSION['role'] = $role;
            $_SESSION['is_logged_in'] = true;
            
            $this->sendResponse(true, 'Registration successful', [
                'user_id' => $userId,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'role' => $role
            ]);
        } else {
            $this->sendResponse(false, 'Registration failed', null, 500);
        }
    }

    private function logout() {
        // Clear all session data
        session_unset();
        session_destroy();
        
        $this->sendResponse(true, 'Logout successful', null);
    }
    
    public function checkSession() {
        if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
            $this->sendResponse(true, 'Session valid', [
                'user_id' => $_SESSION['user_id'],
                'email' => $_SESSION['email'],
                'first_name' => $_SESSION['first_name'],
                'last_name' => $_SESSION['last_name'],
                'role' => $_SESSION['role']
            ]);
        } else {
            $this->sendResponse(false, 'No valid session', null, 401);
        }
    }
}