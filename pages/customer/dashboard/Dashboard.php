<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}
include("includes/header.php");
include("includes/sidebar.php");
?>
<div class="content">
    <h1>Welcome, Admin!</h1>
    <p>Use the sidebar to manage products, users, and orders.</p>
</div>
<?php include("includes/footer.php"); ?>
