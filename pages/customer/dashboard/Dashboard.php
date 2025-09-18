<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../index.php");
    exit();
}

require_once "../../../server/config/db.php";

$category = isset($_GET['cat']) ? $_GET['cat'] : 'All';

if ($category === 'All') {
    $sql = "SELECT p.product_id, p.product_name, p.mini_description, p.price, p.image_id, p.stock, c.name AS category_name
            FROM product p
            LEFT JOIN category c ON p.category_id = c.category_id";
    $stmt = $conn->prepare($sql);
} else {
    $sql = "SELECT p.product_id, p.product_name, p.mini_description, p.price, p.image_id, p.stock, c.name AS category_name
            FROM product p
            LEFT JOIN category c ON p.category_id = c.category_id
            WHERE c.name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Blush'd </title>
    <link rel="stylesheet" href="Dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../header/header.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php include "../header/header.php"; ?>

    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</h1>
    <p class="subheading">
        <?php echo ($category === 'All') ? 'Explore our latest arrivals and bestsellers' : 'Discover premium ' . htmlspecialchars($category) . ' products curated for you'; ?>
    </p>

    <div class="product-grid">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product-card">
                <a href="../product/product.php?id=<?php echo $row['product_id']; ?>" class="product-link">
                    <img src="../../../assets/products/product_<?php echo htmlspecialchars($row['image_id'] ?? 'default'); ?>.png"
                        alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                </a>
                <div class="product-info">
                    <a href="../product/product.php?id=<?php echo $row['product_id']; ?>" class="product-link">
                        <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                    </a>
                    <p class="mini-desc"><?php echo htmlspecialchars($row['mini_description']); ?></p>
                    <p class="price">Rs. <?php echo number_format($row['price'], 2); ?></p>
                    <p class="stock <?php echo ($row['stock'] > 0) ? 'in-stock' : 'out-of-stock'; ?>">
                        <?php echo ($row['stock'] > 0) ? "In Stock" : "Out of Stock"; ?>
                    </p>

                    <div class="actions">
                        <!-- Add to Cart -->
                        <form class="cart-form" onsubmit="addToCart(event, <?php echo $row['product_id']; ?>)">
                            <div class="quantity-wrapper" style="display: flex; align-items: center; justify-content: center;">
                                <label for="quantity-<?php echo $row['product_id']; ?>">Qty:</label>
                                <input type="number" id="quantity-<?php echo $row['product_id']; ?>" value="1" min="1" max="<?php echo $row['stock']; ?>">
                            </div>
                            <button type="submit" class="btn add-to-cart">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <div id="notification" class="notification">
        <span id="notificationMessage"></span>
        <button id="closeNotification">&times;</button>
    </div>

    <script>
        // Notification
        const notification = document.getElementById('notification');
        const notificationMessage = document.getElementById('notificationMessage');
        const closeBtn = document.getElementById('closeNotification');
        closeBtn.onclick = () => {
            notification.style.display = 'none';
        };

        // Add to Cart without redirect
        function addToCart(event, productID) {
            event.preventDefault();
            const qty = document.getElementById('quantity-' + productID).value;

            // AJAX request
            fetch('../cart/add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `product_id=${productID}&quantity=${qty}`
                })
                .then(res => 
                res.text())
                .then(data => {
                    notificationMessage.textContent = "Product added to cart!";
                    notification.style.backgroundColor = '#71f175ff';
                    notification.style.display = 'block';

                    // Update cart count badge
                    updateCartCount(parseInt(qty));
                })
                .catch(err => alert('Failed to add to cart.'));

            setTimeout(() => {
                notification.style.display = 'none';
            }, 1000);
        }
    </script>

</body>

</html>