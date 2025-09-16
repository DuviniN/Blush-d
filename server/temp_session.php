<?php
session_start();

// Simple session management for development
function setTempManagerSession($managerId = 4) {
    $_SESSION['user_id'] = $managerId;
    $_SESSION['role'] = 'MANAGER';
    $_SESSION['is_logged_in'] = true;
    return true;
}

function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

function isLoggedIn() {
    return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
}

function getCurrentUserRole() {
    return $_SESSION['role'] ?? null;
}

// Initialize temp session if not already set
if (!isLoggedIn()) {
    setTempManagerSession(4); // Using Diana Williams (ID: 4) as temp manager
}
?>
