<?php
session_start();
include_once("connections/connection.php");
$con = connection();

header('Content-Type: application/json');

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

// Retrieve and sanitize form data
$payment_method = mysqli_real_escape_string($con, $_POST['payment_method']);
$customer_paid = floatval($_POST['customer_paid']);
$user_id = $_SESSION['user_id'];

// Get cashier name
$user_query = "SELECT * FROM Users WHERE users_id = $user_id";
$user_result = mysqli_query($con, $user_query);
$user_data = mysqli_fetch_assoc($user_result);
$cashier_name = $user_data['first_name'] . ' ' . $user_data['last_name'];

// Initialize total amount and total invested price
$total_amount = 0;
$total_invested_price = 0;
$has_products = false;

$receiptHTML = "<table><thead><tr><th>Product</th><th>Quantity</th><th>Price</th></tr></thead><tbody>";

// Loop through each product selected
foreach ($_POST['quantity'] as $product_id => $quantity) {
    if ($quantity > 0) {
        $has_products = true;
        // Fetch product details from the database
        $query = "SELECT * FROM Products WHERE product_id = $product_id";
        $result = mysqli_query($con, $query);
        $product = mysqli_fetch_assoc($result);

        $product_name = $product['product_name'];
        $product_selling_price = $product['product_selling_price'];
        $invested_price = $product['invested_price'];
        $size = $product['product_size'];

        $total_price = $product_selling_price * $quantity;
        $total_invested = $invested_price * $quantity;

        // Update total amount and total invested price
        $total_amount += $total_price;
        $total_invested_price += $total_invested;

        // Update stock quantity and cups
        $new_stock_quantity = $product['product_stock_quantity'] - $quantity;
        $new_cups = $product['product_cups'] - $quantity;
        $update_query = "UPDATE Products SET product_stock_quantity = ?, product_cups = ? WHERE product_id = ?";
        $update_stmt = $con->prepare($update_query);
        $update_stmt->bind_param("iii", $new_stock_quantity, $new_cups, $product_id);
        $update_stmt->execute();
        $update_stmt->close();

        // Append to receipt HTML
        $receiptHTML .= "<tr><td>$product_name ($size)</td><td>$quantity</td><td>$total_price</td></tr>";
    }
}

// Calculate change amount
$change_amount = $customer_paid - $total_amount;

$receiptHTML .= "</tbody></table>";
$receiptHTML .= "<p>Total: $total_amount</p>";
$receiptHTML .= "<p>Amount Paid: $customer_paid</p>";
$receiptHTML .= "<p>Change: $change_amount</p>";
$receiptHTML .= "<p>Payment Method: $payment_method</p>";
$receiptHTML .= "<p>Cashier: $cashier_name</p>";

// Check for warnings
if (!$has_products) {
    echo json_encode(['success' => false, 'message' => 'Please insert the product']);
    exit();
}

if ($customer_paid < $total_amount) {
    echo json_encode(['success' => false, 'message' => 'Please insert exact amount']);
    exit();
}

// Prepare the SQL query for inserting into the checkout_transactions table
$stmt = $con->prepare("INSERT INTO Checkout_Transactions (product_id, user_id, payment_method, product_name, quantity, amount_paid, total_amount, total_invested_price, transaction_date, change_amount, size) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)");

// Loop through each product again to insert data (only if no warnings)
$checkout_id = 0;
foreach ($_POST['quantity'] as $product_id => $quantity) {
    if ($quantity > 0) {
        // Fetch product details from the database
        $query = "SELECT * FROM Products WHERE product_id = $product_id";
        $result = mysqli_query($con, $query);
        $product = mysqli_fetch_assoc($result);

        $product_name = $product['product_name'];
        $product_selling_price = $product['product_selling_price'];
        $invested_price = $product['invested_price'];
        $size = $product['product_size'];

        $total_price = $product_selling_price * $quantity;
        $total_invested = $invested_price * $quantity;

        // Bind the values to the statement and execute
        $stmt->bind_param("iissidddds", $product_id, $user_id, $payment_method, $product_name, $quantity, $customer_paid, $total_amount, $total_invested_price, $change_amount, $size);
        $stmt->execute();

        // Get the last inserted ID for checkout_id
        if ($checkout_id == 0) {
            $checkout_id = $stmt->insert_id;
        }

        // Insert into Sales table
        $sales_stmt = $con->prepare("INSERT INTO Sales (product_id, checkout_id, sales_quantity_sold, sales_cups_sold, sales_total_amount, sales_total_invested_price) VALUES (?, ?, ?, ?, ?, ?)");
        $sales_stmt->bind_param("iiiiii", $product_id, $checkout_id, $quantity, $quantity, $total_price, $total_invested);
        $sales_stmt->execute();
        $sales_stmt->close();

        // Check if a matching product already exists in Transactions table
        $trans_query = "SELECT * FROM Transactions WHERE avail_product = ?";
        $trans_stmt = $con->prepare($trans_query);
        $trans_stmt->bind_param("s", $product_name);
        $trans_stmt->execute();
        $trans_result = $trans_stmt->get_result();

        if ($trans_result->num_rows > 0) {
            // If product exists, update the quantity
            $update_trans_query = "UPDATE Transactions SET quantity_count = quantity_count + ? WHERE avail_product = ?";
            $update_trans_stmt = $con->prepare($update_trans_query);
            $update_trans_stmt->bind_param("is", $quantity, $product_name);
            $update_trans_stmt->execute();
            $update_trans_stmt->close();
        } else {
            // If product does not exist, insert a new row
            $insert_trans_query = "INSERT INTO Transactions (product_id, checkout_id, avail_product, quantity_count) VALUES (?, ?, ?, ?)";
            $insert_trans_stmt = $con->prepare($insert_trans_query);
            $insert_trans_stmt->bind_param("iisi", $product_id, $checkout_id, $product_name, $quantity);
            $insert_trans_stmt->execute();
            $insert_trans_stmt->close();
        }

        $trans_stmt->close();
    }
}

// Close the statement and connection
$stmt->close();
$con->close();

echo json_encode([
    'success' => true, 
    'message' => 'Order Successful!', 
    'receiptHTML' => $receiptHTML,
    'total_amount' => $total_amount, 
    'customer_paid' => $customer_paid,
    'payment_method' => $payment_method,
    'change_amount' => $change_amount,
    'cashier_name' => $cashier_name
]);

?>
