<?php
// Start session and protect page
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

require_once "../../../server/config/db.php";

// Validate product ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Redirect if invalid
    header("Location: ../dashboard/dashboard.php?cat=All");
    exit();
}

$productID = intval($_GET['id']);

// Fetch product details
$sql = "SELECT * FROM products WHERE productID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productID);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// Stop if product not found
if (!$product) {
    echo "<h2>Product not found!</h2>";
    exit();
}

// Fetch related products
$category = $product['category'] ?? 'All';
$sql_related = "SELECT * FROM products WHERE category = ? AND productID != ? LIMIT 4";
$stmt_related = $conn->prepare($sql_related);
$stmt_related->bind_param("si", $category, $productID);
$stmt_related->execute();
$related_result = $stmt_related->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($product['product_name']); ?> | Blush’d Cosmetics</title>
<link rel="stylesheet" href="dashboard.css">
<link rel="stylesheet" href="product.css">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="product-detail">

  <?php // Product Image ?>
  <div class="product-image">
    <img src="../../../assets/images/<?php echo htmlspecialchars($product['image_id']); ?>.png" 
         alt="<?php echo htmlspecialchars($product['product_name']); ?>">
  </div>

  <?php // Product Info Card ?>
  <div class="product-info-card">
    <div class="product-info">

        <?php // Product Name ?>
        <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>

        <?php // Tabs: Description / Ingredients / Reviews ?>
        <div class="tabs">
            <button class="tab-button active" data-tab="desc">Description</button>
            <button class="tab-button" data-tab="ingredients">Ingredients</button>
            <button class="tab-button" data-tab="reviews">Reviews</button>
        </div>

        <?php // Tab Contents ?>
        <div class="tab-content active" id="desc">
            <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
        </div>
        <div class="tab-content" id="ingredients">
            <p><?php echo nl2br(htmlspecialchars($product['ingredients'] ?? 'No ingredients listed.')); ?></p>
        </div>
        <div class="tab-content" id="reviews">
            <p>Customer reviews will be shown here.</p>
        </div>

        <?php // Price & Discount ?>
        <p class="price">
            $<?php echo number_format($product['price'], 2); ?>
            <?php if (!empty($product['original_price']) && $product['original_price'] > $product['price']): ?>
                <span class="original-price">$<?php echo number_format($product['original_price'], 2); ?></span>
            <?php endif; ?>
        </p>

        <?php // Stock Availability ?>
        <p class="stock <?php echo ($product['quantity'] > 0) ? 'in-stock' : 'out-of-stock'; ?>">
            <?php echo ($product['quantity'] > 0) ? "In Stock" : "Out of Stock"; ?>
        </p>

        <?php // Quantity Selector ?>
        <div class="quantity">
            <label for="quantity">Qty:</label>
            <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['quantity']; ?>">
        </div>

        <?php // Action Buttons ?>
        <div class="actions">
            <button class="btn add-to-cart" onclick="handleAddToCart(<?php echo $product['quantity']; ?>)">Add to Cart</button>
            <button class="btn buy-now" onclick="handleBuyNow(<?php echo $product['productID']; ?>, <?php echo $product['quantity']; ?>)">Buy Now</button>
        </div>
    </div>
  </div>
</div>

<?php // Related Products ?>
<?php if ($related_result->num_rows > 0): ?>
<div class="related-products">
    <h2>Recommended Products</h2>
    <div class="products-grid">
        <?php while($related = $related_result->fetch_assoc()): ?>
        <div class="product-card">
            <a href="product.php?id=<?php echo $related['productID']; ?>">
                <img src="../../../assets/images/<?php echo htmlspecialchars($related['image_id']); ?>.png" 
                     alt="<?php echo htmlspecialchars($related['product_name']); ?>">
                <p class="product-name"><?php echo htmlspecialchars($related['product_name']); ?></p>
                <p class="product-price">$<?php echo number_format($related['price'], 2); ?></p>
            </a>
        </div>
        <?php endwhile; ?>
    </div>
</div>
<?php endif; ?>

<script>
// Tabs Switching Script
const tabButtons = document.querySelectorAll('.tab-button');
const tabContents = document.querySelectorAll('.tab-content');

tabButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        tabButtons.forEach(b => b.classList.remove('active'));
        tabContents.forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById(btn.dataset.tab).classList.add('active');
    });
});

// Add to Cart button handler
function handleAddToCart(stock) {
    const qty = parseInt(document.getElementById('quantity').value);
    if (stock <= 0) {
        alert("Sorry, this product is out of stock.");
        return;
    }
    if (qty > stock) {
        alert("Only " + stock + " items are available.");
        return;
    }
    alert("Product added to cart!");
}

// Buy Now button handler
function handleBuyNow(productID, stock) {
    const qty = parseInt(document.getElementById('quantity').value);
    if (stock <= 0) {
        alert("Sorry, this product is out of stock.");
        return;
    }
    if (qty > stock) {
        alert("Only " + stock + " items are available.");
        return;
    }
    window.location.href = '../buy_now/buy_now.php?id=' + productID + '&qty=' + qty;
}
</script>

</body>
</html>
