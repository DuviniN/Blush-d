<?php
// pages/admin/pages/edit_product.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/config.php';

$errors = [];






// Validate GET id
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    die("Invalid product ID.");
}
$id = (int) $_GET['id'];

// Fetch product
$stmt = $mysqli->prepare("SELECT product_id, name, category_id, price, stock FROM product WHERE product_id = ?");
if (!$stmt) {
    die("Prepare failed: " . $mysqli->error);
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    $stmt->close();
    die("Product not found.");
}
$product = $result->fetch_assoc();
$stmt->close();

// Handle POST (update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category = (int)($_POST['category_id'] ?? '');
    $price = $_POST['price'] ?? '';
    $stock = $_POST['stock'] ?? '';

    // Basic validation
    if ($name === '') $errors[] = "Product name is required.";
    if ($category === '') $errors[] = "Category ID is required.";
    if ($price === '' || !is_numeric($price)) $errors[] = "Valid price is required.";
    if ($stock === '' || filter_var($stock, FILTER_VALIDATE_INT) === false) $errors[] = "Valid stock quantity is required.";

    if (empty($errors)) {
        $price = (float)$price;
        $stock = (int)$stock;

        $update = $mysqli->prepare("UPDATE product SET name = ?, category_id = ?, price = ?, stock = ? WHERE product_id = ?");
        if (!$update) {
            $errors[] = "Prepare failed: " . $mysqli->error;
        } else {
            $update->bind_param("sidii", $name, $category, $price, $stock, $id);
            if ($update->execute()) {
                header("Location: manage_products.php?msg=" . urlencode("Product updated successfully"));
                exit();
            } else {
                $errors[] = "Update failed: " . $update->error;
            }
            $update->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../assets/css/edit_product.css"> <!-- Your CSS -->
</head>
<body>
    <h2>Edit Product</h2>

    <?php if (!empty($errors)): ?>
        <div class="error-box">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/Blush-d/pages/admin/pages/products.php?id=<?php echo $id; ?>">
        <label>Product Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

        <label>Category ID:</label>
        <input type="text" name="category_id" value="<?php echo htmlspecialchars($product['category_id']); ?>" required>

        <label>Price:</label>
        <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>

        <label>Stock:</label>
        <input type="number" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required>

        <div class="buttons">
            <button type="submit">Update</button>
            <a href="products.php" class="btn-cancel">Cancel</a>
        </div>
    </form>
    <script src="/Blush-d/pages/admin/assets/js/edit_product.js"></script>
</body>
</html>
