<?php
session_start(); // Start the session
include_once("connections/connection.php");
$con = connection();

// Define the product image variable at the top
$product_image = "";

if (isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];
    // Get product details including the image
    $query = "SELECT * FROM Products WHERE product_id='$productId'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $product_image = $row['product_image'] ?? "";
}

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


// Fetch products from the database
$query = "SELECT * FROM Products";
$products_result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="css/products.css">
</head>
<body>
    <div class="dashboard-container">
    <div class="sidebar">
            <div class="logo">
                <img src="img/logo.png" alt="Logo">
            </div>
            <div class="toggle-arrow">
                <span class="arrow"></span> <!-- Arrow when collapsed -->
            </div>
            <nav>
                <ul>
                    <li><a href="staff_dashboard.php"><img src="img/dashboard.png" alt="Dashboard"><span>Dashboard</span></a></li>
                    <li><a href="cashier_pos.php"><img src="img/pos.png" alt="POS"><span>POS</span></a></li>
                    <li><a href="staff_products.php"><img src="img/products.png" alt="Products"><span>Products</span></a></li>
                    <li><a href="staff_sales.php"><img src="img/sales.png" alt="Sales"><span>Sales</span></a></li>
                </ul>
            </nav>
            <div class="logout">
                <a href="logout.php" onclick="return confirm('Do you want to logout from this page?');">
                    <img src="img/logout.png" alt="Logout">
                    <span>Logout</span>
                </a>
            </div>
    </div>
    <div class="main-content">
            <header class="dashboard-header">
                <h1>Product Management</h1>
            </header>
            <section class="dashboard-content">
                <div class="product-management">
                    <h2>Manage Products</h2>

                    <!-- Add Product Button -->
                    <button id="addProductBtn" class="btn-add">Add Product</button>

                    <div class="search-sort-container">
                        <input type="text" id="searchBar" placeholder="Search products...">
                        <select id="categorySort">
                            <option value="">All Categories</option>
                            <option value="Frappe">Frappe</option>
                            <option value="Milk Shake">Milk Shake</option>
                            <option value="Slushie">Slushie</option>
                            <option value="Cream Cheese">Cream Cheese</option>
                        </select>
                        <select id="sizeSort">
                            <option value="">All Sizes</option>
                            <option value="16oz">16oz</option>
                            <option value="22oz">22oz</option>
                        </select>
                    </div>

                    <!-- Product Table -->
                    <table id="productTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Size</th>
                                <th>Price</th>
                                <th>Stock Quantity</th>
                                <th>Cups</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = $products_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['product_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['product_category']); ?></td>
                                    <td><?php echo htmlspecialchars($row['product_size']); ?></td>
                                    <td><?php echo htmlspecialchars($row['product_selling_price']); ?></td>
                                    <td><?php echo htmlspecialchars($row['product_stock_quantity']); ?></td>
                                    <td><?php echo htmlspecialchars($row['product_cups']); ?></td>
                                    <td><?php echo htmlspecialchars($row['product_status']); ?></td>
                                    <td>
                                        <button class="edit-btn"
                                            data-id="<?php echo $row['product_id']; ?>"
                                            data-name="<?php echo htmlspecialchars($row['product_name']); ?>"
                                            data-category="<?php echo htmlspecialchars($row['product_category']); ?>"
                                            data-size="<?php echo htmlspecialchars($row['product_size']); ?>"
                                            data-price="<?php echo htmlspecialchars($row['product_selling_price']); ?>"
                                            data-invested_price="<?php echo htmlspecialchars($row['invested_price']); ?>"
                                            data-stock="<?php echo htmlspecialchars($row['product_stock_quantity']); ?>"
                                            data-cups="<?php echo htmlspecialchars($row['product_cups']); ?>"
                                            data-status="<?php echo htmlspecialchars($row['product_status']); ?>"
                                            data-image="<?php echo htmlspecialchars($row['product_image']); ?>">
                                            Edit
                                        </button>
                                        <button class="delete-btn" data-id="<?php echo $row['product_id']; ?>">Delete</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                    <!-- Add/Edit Product Modal -->
                    <div id="productModal" class="modal" style="display:none;">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <h2 id="modalTitle">Add Product</h2>
                            <form id="productForm" method="POST" enctype="multipart/form-data">
                                <input type="hidden" id="product_id" name="product_id">

                                <label for="product_image">Product Image</label>
                                <img id="productImagePreview" style="display:none; width:150px;" alt="Product Image Preview">
                                <input type="file" id="product_image" name="product_image" accept="image/*">

                                <label for="productName">Product Name</label>
                                <input type="text" id="productName" name="product_name" required>

                                <label for="category">Category</label>
                                <input type="text" id="category" name="category" required>

                                <label for="size">Size</label>
                                <input type="text" id="size" name="size" required>

                                <label for="price">Selling Price</label>
                                <input type="number" id="price" name="price" step="0.01" required>

                                <label for="invested_price">Invested Price</label>
                                <input type="number" id="invested_price" name="invested_price" step="0.01" required>

                                <label for="stock">Stock Quantity</label>
                                <input type="number" id="stock" name="stock" required>

                                <label for="cups">Cups</label>
                                <input type="number" id="cups" name="cups" required>

                                <label for="status">Status</label>
                                <select id="status" name="status">
                                    <option value="Available">Available</option>
                                    <option value="Not Available">Not Available</option>
                                </select>

                                <button type="submit" class="btn-save">Save</button>
                            </form>

                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="js/products.js"></script>
    <script>
    // Detect if the sidebar toggle button is clicked on mobile devices
    const sidebarToggle = document.querySelector('.toggle-arrow');
    const sidebar = document.querySelector('.sidebar');

    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('sidebar-expanded'); // Toggle the expanded state of the sidebar
    });
</script>
</body>
</html>
