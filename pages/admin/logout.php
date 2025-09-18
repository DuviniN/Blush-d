<?php
session_start();
require_once __DIR__ . '/../../server/config/db.php';

// Clear all session data
session_unset();
session_destroy();

// Redirect to login page
header('Location: ../auth/login/login.php');
exit;
