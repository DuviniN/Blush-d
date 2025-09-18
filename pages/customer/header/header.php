<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../index.php");
    exit();
}

require_once "../../../server/config/db.php";

// Initialize cart session if not exists
require_once "../../../server/config/db.php";

// Function to calculate cart item count from database
function getCartItemCount()
{
    global $conn;
    if (!isset($_SESSION['user_id'])) {
        return 0;
    }

    $userID = $_SESSION['user_id'];
    $sql = "SELECT SUM(quantity) as total_count FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    return $result['total_count'] ? (int)$result['total_count'] : 0;
}

// Get current page category for active link
$category = isset($_GET['cat']) ? $_GET['cat'] : 'All';

$cartCount = getCartItemCount();
?>
<!-- Announcement Bar -->
<div class="announcement-bar">
    <div class="announcement-wrapper">
        <span class="announcement">🚚 Deliver within 3 days</span>
        <span class="announcement">💄 New arrivals are live now!</span>
        <span class="announcement">✨ Free shipping on orders over $50</span>
    </div>
</div>


<nav class="header-navbar">
    <div class="logo">
        <a href="../dashboard/dashboard.php">Blush’d</a>
    </div>

    <ul class="nav-links">
        <li><a href="../dashboard/dashboard.php?cat=All" class="<?php echo ($category === 'All') ? 'active' : ''; ?>">All</a></li>
        <li><a href="../dashboard/dashboard.php?cat=Skincare" class="<?php echo ($category === 'Skincare') ? 'active' : ''; ?>">Skin</a></li>
        <li><a href="../dashboard/dashboard.php?cat=Haircare" class="<?php echo ($category === 'Haircare') ? 'active' : ''; ?>">Hair</a></li>
        <li><a href="../dashboard/dashboard.php?cat=Makeup" class="<?php echo ($category === 'Makeup') ? 'active' : ''; ?>">Makeup</a></li>
        <li><a href="../dashboard/dashboard.php?cat=Tools" class="<?php echo ($category === 'Tools') ? 'active' : ''; ?>">Tools</a></li>
    </ul>

    <div class="nav-user">
        <a href="../cart/view_cart.php" class="cart-link">
            🛒 Cart
            <?php if ($cartCount > 0): ?>
                <span class="cart-count"><?php echo $cartCount; ?></span>
            <?php endif; ?>
        </a>
        <a href="#" class="logout-btn" id="logoutBtn">Logout</a>
    </div>
</nav>
<!-- Logout Confirmation Modal -->
<div class="logout-modal" id="logoutModal">
    <div class="modal">
        <h2>Confirm Logout</h2>
        <p>Are you sure you want to log out from your account?</p>
        <form id="logoutForm">
            <button type="button" name="confirm_logout" class="btn btn-confirm" id="confirmLogout">Yes, Logout</button>
            <button type="button" class="btn btn-cancel" id="cancelLogout">Cancel</button>
        </form>
    </div>
</div>
<script>
    const logoutBtn = document.getElementById('logoutBtn');
    const logoutModal = document.getElementById('logoutModal');
    const cancelLogout = document.getElementById('cancelLogout');
    const confirmLogout = document.getElementById('confirmLogout');

    logoutBtn.addEventListener('click', () => {
        logoutModal.style.display = 'flex';
    });

    cancelLogout.addEventListener('click', () => {
        logoutModal.style.display = 'none';
    });

    confirmLogout.addEventListener('click', () => {
        // Call the API endpoint for logout
        fetch('../../../server/api.php?endpoint=auth&action=logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                // Redirect to login page regardless of API response
                window.location.href = '../../../index.php';
            })
            .catch(error => {
                console.error('Logout error:', error);
                // Still redirect to login page even if there's an error
                window.location.href = '../../../index.php';
            });
    });

    // Click outside modal to close
    window.addEventListener('click', (e) => {
        if (e.target == logoutModal) {
            logoutModal.style.display = 'none';
        }
    });

    // Function to update cart count badge
    function updateCartCount(addedQuantity) {
        let cartBadge = document.querySelector('.cart-count');
        if (cartBadge) {
            cartBadge.textContent = parseInt(cartBadge.textContent) + parseInt(addedQuantity);
        } else {
            const badge = document.createElement('span');
            badge.className = 'cart-count';
            badge.textContent = addedQuantity;
            document.querySelector('.cart-link').appendChild(badge);
        }
    }
</script>