<?php
session_start();
include_once("connections/connection.php");
$con = connection();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch total users
$total_users_query = "SELECT COUNT(*) AS total_users FROM Users";
$total_users_result = $con->query($total_users_query);
$total_users = $total_users_result->fetch_assoc()['total_users'];

// Fetch total products
$total_products_query = "SELECT COUNT(*) AS total_products FROM Products";
$total_products_result = $con->query($total_products_query);
$total_products = $total_products_result->fetch_assoc()['total_products'];

// Fetch total sales
$total_sales_query = "SELECT COUNT(*) AS total_sales FROM Sales";
$total_sales_result = $con->query($total_sales_query);
$total_sales = $total_sales_result->fetch_assoc()['total_sales'];

// Fetch recent checkout transactions
$recent_checkouts_query = "SELECT * FROM Checkout_Transactions ORDER BY transaction_date DESC LIMIT 5";
$recent_checkouts_result = $con->query($recent_checkouts_query);

// Fetch best-selling products
$best_sellers_query = "SELECT t.product_id, p.product_name, SUM(t.quantity_count) AS quantity_sold 
                       FROM Transactions t
                       JOIN Products p ON t.product_id = p.product_id
                       GROUP BY t.product_id, p.product_name
                       ORDER BY quantity_sold DESC
                       LIMIT 5";
$best_sellers_result = $con->query($best_sellers_query);

// Fetch logged-in user's profile info
$user_id = $_SESSION['user_id']; // Assuming 'user_id' is stored in the session
$user_query = "SELECT user_profile, first_name, last_name FROM Users WHERE users_id = ?";
$stmt = $con->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user_info = $user_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin_dashboard.css">
    <style>
        /* Adjust the size of the profile picture */
        .profile-pic img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
        .user-info {
            display: flex;
            align-items: center;
        }
        .user-info .user-name {
            margin-right: 15px;
        }
        /* Style adjustments for the welcome header */
        .welcome-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #FFF;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <!-- Sidebar -->
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

    <!-- Main Content -->
    <div class="main-content">
        <header class="welcome-header">
            <!-- Display user's first name and last name -->
            <div class="user-info">
                <div class="user-name">
                    <span>Welcome, <?php echo htmlspecialchars($user_info['first_name']) . ' ' . htmlspecialchars($user_info['last_name']); ?></span>
                </div>
                <!-- Display profile picture -->
                <div class="profile-pic">
                    <?php if (!empty($user_info['user_profile']) && file_exists($user_info['user_profile'])): ?>
                        <img src="<?php echo htmlspecialchars($user_info['user_profile']); ?>" alt="Profile Picture">
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <!-- Stats Section -->
        <div class="stats-grid">
            <div class="stat-card blue">
                <h3>Total Users</h3>
                <p id="total-users"><?php echo $total_users; ?></p>
            </div>
            <div class="stat-card purple">
                <h3>Total Products</h3>
                <p id="total-products"><?php echo $total_products; ?></p>
            </div>
            <div class="stat-card green">
                <h3>Total Sales</h3>
                <p id="total-sales"><?php echo $total_sales; ?></p>
            </div>
        </div>

        <!-- Additional Sections -->
        <div class="row">
            <!-- Best Sellers -->
            <div class="card">
                <h3>Best Sellers</h3>
                <ul>
                    <?php while ($row = $best_sellers_result->fetch_assoc()) { ?>
                        <li>
                            <strong><?php echo $row['product_name']; ?></strong>
                            - Sold: <?php echo $row['quantity_sold']; ?>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            
            <!-- Recent Transactions -->
            <div class="card">
                <h3>Recent Transactions</h3>
                <ul>
                    <?php while ($row = $recent_checkouts_result->fetch_assoc()) { ?>
                        <li>
                            <strong><?php echo $row['product_name']; ?></strong>
                            - <?php echo $row['transaction_date']; ?>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    // Detect if the sidebar toggle button is clicked on mobile devices
    const sidebarToggle = document.querySelector('.toggle-arrow');
    const sidebar = document.querySelector('.sidebar');

    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('sidebar-expanded'); // Toggle the expanded state of the sidebar
    });

    // Function to animate counting
    function animateCounter(elementId, endValue) {
        let startValue = 0;
        const duration = 2000; // Duration in ms (2 seconds)
        const increment = endValue / (duration / 50); // Increment per 50ms
        const element = document.getElementById(elementId);
        
        const interval = setInterval(() => {
            startValue += increment;
            if (startValue >= endValue) {
                clearInterval(interval); // Stop the animation once the target value is reached
                startValue = endValue;
            }
            element.textContent = Math.floor(startValue);
        }, 50);
    }

    // Function to start the animation when the page loads
    window.onload = function() {
        // Delay the animation for a more noticeable effect
        setTimeout(() => {
            animateCounter('total-users', <?php echo $total_users; ?>);
            animateCounter('total-products', <?php echo $total_products; ?>);
            animateCounter('total-sales', <?php echo $total_sales; ?>);

            // Add class to fade in the numbers smoothly
            document.querySelectorAll('.stat-card').forEach(card => {
                card.classList.add('show');
            });
        }, 500); // Delay by 500ms
    };
</script>
</body>
</html>
