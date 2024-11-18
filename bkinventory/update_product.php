<?php
include_once("connections/connection.php");
$con = connection();

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $product_name = mysqli_real_escape_string($con, $_POST['product_name']);
    $category = mysqli_real_escape_string($con, $_POST['category']);
    $size = mysqli_real_escape_string($con, $_POST['size']);
    $price = $_POST['price'];
    $invested_price = $_POST['invested_price'];
    $stock = $_POST['stock'];
    $cups = $_POST['cups'];
    $status = $_POST['status'];

    $query = "UPDATE Products SET product_name = '$product_name', product_category = '$category', 
              product_size = '$size', product_selling_price = '$price', invested_price = '$invested_price', 
              product_stock_quantity = '$stock', product_cups = '$cups', product_status = '$status' 
              WHERE product_id = '$product_id'";
    $result = mysqli_query($con, $query);
    if ($result) {
        echo "Product updated successfully!";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
