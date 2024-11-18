<?php
include_once("connections/connection.php");
session_start(); // Start the session
$con = connection();

$product_image = "";
if (isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];
    // Get product details including the image
    $query = "SELECT * FROM Products WHERE product_id='$productId'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $product_image = $row['product_image'] ?? "";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $category = $_POST['category'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    $invested_price = $_POST['invested_price'];
    $stock = $_POST['stock'];
    $cups = $_POST['cups'];
    $status = $_POST['status'];
    $userId = $_SESSION['user_id']; // Assuming you store the user's ID in the session

    // Ensure price and invested_price are formatted correctly
    $price = number_format((float)$price, 2, '.', '');
    $invested_price = number_format((float)$invested_price, 2, '.', '');

    // Handle file upload
    if (!empty($_FILES['product_image']['name'])) {
        $product_image = $_FILES['product_image']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($product_image);

        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
            // File uploaded successfully
            $product_image = $target_file;
        } else {
            echo "Error uploading the file.";
            exit();
        }
    } else {
        // If no new image is uploaded, use the existing image
        $query = "SELECT product_image FROM Products WHERE product_id='$productId'";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);
        $product_image = $row['product_image'];
    }

    // Update product information along with image path
    $query = "SELECT product_edit_by FROM Products WHERE product_id='$productId'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $currentEditBy = $row['product_edit_by'] ? $row['product_edit_by'] : $userId; // Default to userId if not set

    if ($currentEditBy != $userId) {
        // Update both product_edit_by and product_last_edit_by
        $query = "UPDATE Products SET product_name='$productName', product_category='$category', product_size='$size', product_selling_price='$price', invested_price='$invested_price', product_stock_quantity='$stock', product_cups='$cups', product_status='$status', product_image='$product_image', product_edit_by='$userId', product_last_edit_by='$currentEditBy', product_last_edited=CURRENT_TIMESTAMP WHERE product_id='$productId'";
    } else {
        // Update only product_last_edit_by
        $query = "UPDATE Products SET product_name='$productName', product_category='$category', product_size='$size', product_selling_price='$price', invested_price='$invested_price', product_stock_quantity='$stock', product_cups='$cups', product_status='$status', product_image='$product_image', product_last_edit_by='$userId', product_last_edited=CURRENT_TIMESTAMP WHERE product_id='$productId'";
    }

    // Debugging statements
    echo "SQL Query: " . $query . "<br>";
    echo "User ID: " . $userId . "<br>";

    $result = mysqli_query($con, $query);

    if ($result) {
        header("Location: staff_products.php");
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($con);
    }
}
?>
