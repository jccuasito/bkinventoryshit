<?php
include_once("connections/connection.php");
$con = connection();

if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];

    // Update related records to set product_id to NULL or another default value
    $updateTransactionsQuery = "UPDATE transactions SET product_id=NULL WHERE product_id='$productId'";
    mysqli_query($con, $updateTransactionsQuery);

    $updateSalesQuery = "UPDATE sales SET product_id=NULL WHERE product_id='$productId'";
    mysqli_query($con, $updateSalesQuery);

    $updateCheckoutTransactionsQuery = "UPDATE checkout_transactions SET product_id=NULL WHERE product_id='$productId'";
    mysqli_query($con, $updateCheckoutTransactionsQuery);

    // Now delete the product from the Products table
    $deleteProductQuery = "DELETE FROM Products WHERE product_id='$productId'";
    if (mysqli_query($con, $deleteProductQuery)) {
        // Redirect to products.php after successful deletion
        header("Location: products.php");
    } else {
        // If product deletion failed, display an error
        echo "Error deleting product: " . mysqli_error($con);
    }
}
?>
