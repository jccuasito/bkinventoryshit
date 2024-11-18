<?php
session_start();
include_once("connections/connection.php");
$con = connection();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$salesData = [
    'total_amount' => 0,
    'total_invested' => 0,
    'quantity_sold' => 0
];

$filter = "today"; // Default filter is "today"

// Handle filter change
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filter'])) {
    $filter = $_POST['filter'];
    header("Location: sales.php?filter=" . $filter);
    exit();
}

if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
}

// Calculate start dates for week, month, and year
$startOfWeek = date("Y-m-d", strtotime('monday this week'));
$startOfMonth = date("Y-m-01"); // Start of this month
$startOfYear = date("Y-01-01"); // Start of this year

$query = "";

if ($filter == "today") {
    $query = "SELECT SUM(sales_total_amount) as total_amount, SUM(sales_total_invested_price) as total_invested, SUM(sales_quantity_sold) as quantity_sold FROM Sales WHERE DATE(sales_date) = CURDATE()";
} elseif ($filter == "this_week") {
    $query = "SELECT SUM(sales_total_amount) as total_amount, SUM(sales_total_invested_price) as total_invested, SUM(sales_quantity_sold) as quantity_sold FROM Sales WHERE sales_date BETWEEN '$startOfWeek' AND CURDATE()";
} elseif ($filter == "this_month") {
    $query = "SELECT SUM(sales_total_amount) as total_amount, SUM(sales_total_invested_price) as total_invested, SUM(sales_quantity_sold) as quantity_sold FROM Sales WHERE sales_date BETWEEN '$startOfMonth' AND CURDATE()";
} elseif ($filter == "this_year") {
    $query = "SELECT SUM(sales_total_amount) as total_amount, SUM(sales_total_invested_price) as total_invested, SUM(sales_quantity_sold) as quantity_sold FROM Sales WHERE sales_date BETWEEN '$startOfYear' AND CURDATE()";
}

if ($query != "") {
    $result = $con->query($query);
    if ($result->num_rows > 0) {
        $salesData = $result->fetch_assoc();
    } else {
        // Output debug information if no rows are returned
        echo "No sales data found for this period.<br>";
    }
}

// Function to get sales history with filtering
function getSalesHistory($filter, $con) {
    $currentDate = date("Y-m-d");
    $sql = "SELECT product_name, quantity, size, transaction_date 
            FROM checkout_transactions WHERE ";

    // Apply filter based on the selected filter
    switch ($filter) {
        case 'today':
            $sql .= "DATE(transaction_date) = '$currentDate'";
            break;
        case 'this_week':
            $startOfWeek = date("Y-m-d", strtotime('monday this week'));
            $sql .= "transaction_date BETWEEN '$startOfWeek' AND '$currentDate'";
            break;
        case 'this_month':
            $startOfMonth = date("Y-m-01");
            $sql .= "transaction_date BETWEEN '$startOfMonth' AND '$currentDate'";
            break;
        case 'this_year':
            $startOfYear = date("Y-01-01");
            $sql .= "transaction_date BETWEEN '$startOfYear' AND '$currentDate'";
            break;
        default:
            $sql .= "1"; // No filter, return all data
            break;
    }

    // Add ordering clause to sort by latest transaction date first
    $sql .= " ORDER BY transaction_date DESC";

    // Execute SQL query
    $result = $con->query($sql);
    $history = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $history[] = $row;
        }
    }
    return $history;
}


// Get the filter value from the URL parameter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'today';

// Get sales data and history
$salesHistory = getSalesHistory($filter, $con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Management</title>
    <link rel="stylesheet" href="css/sales.css">
    <style>
        .scroll-panel {
            max-height: 400px; /* Adjust this height as needed */
            overflow-y: auto;
        }
    </style>
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
                    <li><a href="admin_dashboard.php"><img src="img/dashboard.png" alt="Dashboard"><span>Dashboard</span></a></li>
                    <li><a href="admin_pos.php"><img src="img/pos.png" alt="POS"><span>POS</span></a></li>
                    <li><a href="utilities.php"><img src="img/utilities.png" alt="Utilities"><span>Utilities</span></a></li>
                    <li><a href="products.php"><img src="img/products.png" alt="Products"><span>Products</span></a></li>
                    <li><a href="sales.php"><img src="img/sales.png" alt="Sales"><span>Sales</span></a></li>
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
            <div class="dashboard-header">
                <h1>Sales Report</h1>
            </div>

            <div class="sales-report">
                <h2>Sales Report</h2>
                <div class="report-buttons">
                    <form method="POST" action="">
                        <button class="report-btn" name="filter" value="today">Today</button>
                        <button class="report-btn" name="filter" value="this_week">This Week</button>
                        <button class="report-btn" name="filter" value="this_month">This Month</button>
                        <button class="report-btn" name="filter" value="this_year">This Year</button>
                    </form>
                    <div class="download-btn-container">
                        <button class="reportdownload" id="downloadBtn">
                            <img src="img/download-to-storage-drive.png" alt="Download" width="20" height="20">
                        </button>
                    </div>
                </div>

                <div class="sales-summary">
                    <div class="sales-box">
                        <h3>Amount</h3>
                        <p id="amountTotal">₱<?php echo number_format($salesData['total_amount'], 2); ?></p>
                    </div>
                    <div class="sales-box">
                        <h3>Total Invested Price</h3>
                        <p id="total-invested-price">₱<?php echo number_format($salesData['total_invested'], 2); ?></p>
                    </div>
                    <div class="sales-box">
                        <h3>Total Product Sold</h3>
                        <p id="productTotal"><?php echo number_format($salesData['quantity_sold']); ?></p>
                    </div>
                </div>

            </div>

            <!-- Modal for downloading the report -->
            <div id="downloadModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Download Sales Report</h2>
                    <form method="POST" action="download_csv.php">
                        <button class="modal-btn" name="csv_filter" value="today">Today</button>
                        <button class="modal-btn" name="csv_filter" value="this_week">This Week</button>
                        <button class="modal-btn" name="csv_filter" value="this_month">This Month</button>
                        <button class="modal-btn" name="csv_filter" value="this_year">This Year</button>
                    </form>
                </div>
            </div>

            <!-- Sales History Section -->
            <div class="sales-history">
                <h2>History Sale</h2>
                <div class="scroll-panel">
                    <table id="salesTable">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Size</th>
                                <th>Transaction Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($salesHistory as $history): ?>
                            <tr>
                                <td><?php echo $history['product_name']; ?></td>
                                <td><?php echo $history['quantity']; ?></td>
                                <td><?php echo $history['size']; ?></td>
                                <td><?php echo $history['transaction_date']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
    </div>

    <script>

        // Detect if the sidebar toggle button is clicked on mobile devices
        const sidebarToggle = document.querySelector('.toggle-arrow');
        const sidebar = document.querySelector('.sidebar');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-expanded'); // Toggle the expanded state of the sidebar
        });

        // Script to handle modal popup for downloading the report
        document.getElementById('downloadBtn').onclick = function() {
            document.getElementById('downloadModal').style.display = 'block';
        }

        document.getElementsByClassName('close')[0].onclick = function() {
            document.getElementById('downloadModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('downloadModal')) {
                document.getElementById('downloadModal').style.display = 'none';
            }
        }
    </script>
</body>
</html>
