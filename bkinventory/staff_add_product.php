<?php
include_once("connections/connection.php");
session_start(); // Start the session
$con = connection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productName = $_POST['product_name'];
    $category = $_POST['category'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    $invested_price = $_POST['invested_price'];
    $stock = $_POST['stock'];
    $cups = $_POST['cups'];
    $status = $_POST['status'];

    // File upload logic
    if (!empty($_FILES['product_image']['name'])) {
        $product_image = $_FILES['product_image']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($product_image);

        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
            // File uploaded successfully
            $product_image = $target_file; // Store the image path
        } else {
            echo "Error uploading the file.";
            exit();
        }
    } else {
        $product_image = ""; // No image provided
    }

    // Insert new product into database
    $query = "INSERT INTO Products (product_name, product_category, product_size, product_selling_price, invested_price, product_stock_quantity, product_cups, product_status, product_image) 
              VALUES ('$productName', '$category', '$size', '$price', '$invested_price', '$stock', '$cups', '$status', '$product_image')";

    $result = mysqli_query($con, $query);

    if ($result) {
        // Redirect to products page after successful insertion
        header("Location: staff_products.php");
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

?>
