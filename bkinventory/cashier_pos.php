<?php
session_start(); // Start the session
include_once("connections/connection.php");
$con = connection();

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
    <title>Basteakoy Products</title>
    <link href="https://fonts.googleapis.com/css2?family=General+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://use.fontawesome.com/0c7a3095b5.js"></script>
    <link rel="stylesheet" href="css/pos.css">
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
                <h1>Basteakoy Products</h1>
            </header>
            <section id="dashboardContent" class="dashboard-content home-background">
                <!-- Home section -->
                <div id="home" class="section active">
                    <h2>Order Product</h2>
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

                    <form action="checkout_process.php" method="POST" id="checkoutForm">
                    <table id="productTable">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Size</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Qty</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($products_result)): ?>
                            <?php
                            // Determine if product is out of stock
                            $isOutOfStock = ($row['product_stock_quantity'] == 0 || $row['product_cups'] == 0);
                            $productStatus = $row['product_status'];
                            ?>
                            <tr>
                                <td>
                                    <img src="<?php echo htmlspecialchars($row['product_image']); ?>" alt="Product Image" style="width:50px;">
                                </td>
                                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['product_category']); ?></td>
                                <td><?php echo htmlspecialchars($row['product_size']); ?></td>
                                <td><?php echo htmlspecialchars($row['product_selling_price']); ?></td>
                                <td>
                                    <span class="status <?php echo strtolower(str_replace(' ', '-', $productStatus)); ?>">
                                        <?php echo htmlspecialchars($productStatus); ?>
                                    </span>
                                </td>
                                <td class="<?php echo $isOutOfStock ? 'out-of-stock' : ''; ?>">
                                    <?php if (!$isOutOfStock): ?>
                                        <select name="quantity[<?php echo $row['product_id']; ?>]" onchange="calculateTotal(this)">
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                    <?php else: ?>
                                        Out of Stock
                                    <?php endif; ?>
                                </td>
                                <td class="product-total">0</td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" style="text-align: right; font-weight: bold;">Total:</td>
                            <td id="totalValue">0.00</td>
                        </tr>
                    </tfoot>
                </table>


                        <label for="payment_method">Choose Payment Method:</label>
                        <select name="payment_method" id="payment_method" onchange="toggleQRCode()">
                            <option value="cash">Cash</option>
                            <option value="gcash">G-Cash</option>
                        </select>

                        <div id="qrCodeSection" style="display:none;">
                            <h3>Scan the QR Code</h3>
                            <img src="img/qrcodeclient.png" alt="GCash QR Code">
                            <p>G-Cash Number: 09275436927</p>
                        </div>

                        <label for="customer_paid">Amount Paid:</label>
                        <input type="number" name="customer_paid" id="customer_paid" required>

                        <button type="submit" class="checkout-button">Checkout</button>
                    </form>

                    <!-- Add this HTML for the modal in your admin_pos.php -->
                    <div id="receiptModal" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <h2>Receipt</h2>
                            <div id="receiptDetails"></div>
                            <button id="printReceipt" class="styled-button">Print Receipt</button>
                            <button id="downloadExcel" class="styled-button">Download as Excel</button>
                        </div>
                    </div>

                </div>
            </section>
        </main>
    </div>
    

    <script src="js/pos.js"></script>
    <script>

        // Detect if the sidebar toggle button is clicked on mobile devices
    const sidebarToggle = document.querySelector('.toggle-arrow');
    const sidebar = document.querySelector('.sidebar');

    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('sidebar-expanded'); // Toggle the expanded state of the sidebar
    });
        function toggleQRCode() {
            var paymentMethod = document.getElementById('payment_method').value;
            var qrCodeSection = document.getElementById('qrCodeSection');
            if (paymentMethod === 'gcash') {
                qrCodeSection.style.display = 'block';
            } else {
                qrCodeSection.style.display = 'none';
            }
        }

        function calculateTotal(selectElement) {
            var row = selectElement.closest('tr');
            var price = parseFloat(row.cells[4].textContent); // Product price is in the 5th cell (index 4)
            var quantity = parseInt(selectElement.value); // Selected quantity
            var totalPrice = price * quantity; // Total price for this row
            row.cells[7].textContent = totalPrice.toFixed(2); // Set the total price in the last column

            // Recalculate the grand total
            var total = 0;
            document.querySelectorAll('.product-total').forEach(function(cell) {
                total += parseFloat(cell.textContent || 0);
            });
            document.getElementById('totalValue').textContent = total.toFixed(2);
        }
    </script>

</body>
</html>
