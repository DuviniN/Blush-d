<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_login();
header('Location: pages/dashboard.php');
exit;
