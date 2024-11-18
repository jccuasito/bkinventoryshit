<?php
include_once("connections/connection.php");
$con = connection();

if (isset($_POST['users_id'])) {
    $userId = $_POST['users_id'];

    // Set user_id to NULL in related tables to prevent foreign key constraint errors
    $updateQueries = [
        "UPDATE products SET product_edit_by=NULL WHERE product_edit_by='$userId'",
        "UPDATE products SET product_last_edit_by=NULL WHERE product_last_edit_by='$userId'",
        "UPDATE checkout_transactions SET user_id=NULL WHERE user_id='$userId'",
        // Add more queries for other related tables as needed
    ];

    $allUpdatesSuccessful = true;
    foreach ($updateQueries as $query) {
        if (!mysqli_query($con, $query)) {
            $allUpdatesSuccessful = false;
            echo "Error updating related tables: " . mysqli_error($con) . "<br>";
        }
    }

    if ($allUpdatesSuccessful) {
        // Delete related records in User_Activity table first
        $deleteActivityQuery = "DELETE FROM User_Activity WHERE user_id='$userId'";
        if (mysqli_query($con, $deleteActivityQuery)) {
            // Now delete the user from the Users table
            $deleteUserQuery = "DELETE FROM Users WHERE users_id='$userId'";
            if (mysqli_query($con, $deleteUserQuery)) {
                // Redirect to utilities.php after successful deletion
                header("Location: utilities.php");
                exit();
            } else {
                // If user deletion failed, display an error
                echo "Error deleting user: " . mysqli_error($con);
            }
        } else {
            // If activity deletion failed, display an error
            echo "Error deleting user activity: " . mysqli_error($con);
        }
    }
}
?>
