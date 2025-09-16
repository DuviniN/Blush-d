<?php
session_start();

// Check if user confirmed logout
if(isset($_POST['confirm_logout'])){
    // Destroy session
    $_SESSION = array();
    session_destroy();
    
    // Redirect after logout
    header("Location: ../login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Logout Confirmation</title>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #f8d7e3, #f6f2f7);
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
    }

    /* Modal Container */
    .modal {
        background: #fff;
        padding: 40px 60px;
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        text-align: center;
        max-width: 400px;
        animation: slideDown 0.5s ease;
    }

    .modal h2 {
        color: #ff6b81;
        margin-bottom: 15px;
    }

    .modal p {
        color: #555;
        margin-bottom: 25px;
        font-size: 16px;
    }

    /* Buttons */
    .btn {
        padding: 10px 25px;
        margin: 0 10px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        transition: 0.3s;
    }

    .btn-confirm {
        background: #ff6b81;
        color: #fff;
    }

    .btn-confirm:hover {
        background: #ff3b61;
    }

    .btn-cancel {
        background: #eee;
        color: #555;
    }

    .btn-cancel:hover {
        background: #ddd;
    }

    @keyframes slideDown {
        from { transform: translateY(-50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>
</head>
<body>

<div class="modal">
    <h2>Confirm Logout</h2>
    <p>Are you sure you want to log out from your account?</p>
    <form method="post">
        <button type="submit" name="confirm_logout" class="btn btn-confirm">Yes, Logout</button>
        <a href="../dashboard.php" class="btn btn-cancel">Cancel</a>
    </form>
</div>

</body>
</html>
