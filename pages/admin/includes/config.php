<?php
// includes/config.php
// session_start();


$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = ''; // change if you use a password
$DB_NAME = 'blush-d';

// mysqli connection
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    die('DB Connect Error: ' . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");
// after $mysqli->set_charset("utf8mb4");
$conn = $mysqli; // <-- alias for legacy code that uses $conn
