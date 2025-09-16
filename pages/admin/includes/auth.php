<?php
// includes/auth.php
// simple admin check. In production, use hashed passwords and proper roles
if (!isset($_SESSION)) session_start();

function require_login() {
    if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: /admin/login.php');
        exit;
    }
}
