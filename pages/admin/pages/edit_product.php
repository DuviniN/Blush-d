<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../index.php");
    exit();
}

require_once __DIR__ . '/../../../server/config/db.php';

$errors = [];

// Validate GET id
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    die("Invalid product ID.");
}
$id = (int) $_GET['id'];

// Fetch product
$stmt = $conn->prepare("SELECT product_id, product_name, category_id, price, stock FROM product WHERE product_id = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
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

        $update = $conn->prepare("UPDATE product SET product_name = ?, category_id = ?, price = ?, stock = ? WHERE product_id = ?");
        if (!$update) {
            $errors[] = "Prepare failed: " . $conn->error;
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
    <link rel="stylesheet" href="../assets/css/edit_product.css?v=<?php echo time(); ?>"> <!-- Your CSS -->
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

    <form method="POST" action="product.php?id=<?php echo $id; ?>">
        <label>Product Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>

        <label>Category ID:</label>
        <input type="text" name="category_id" value="<?php echo htmlspecialchars($product['category_id']); ?>" required>

        <label>Price:</label>
        <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>

        <label>Stock:</label>
        <input type="number" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required>

        <div class="buttons">
            <button type="submit">Update</button>
            <a href="product.php" class="btn-cancel">Cancel</a>
        </div>
    </form>
    <script src="../assets/js/edit_product.js?v=<?php echo time(); ?>"></script>
</body>
</html>
