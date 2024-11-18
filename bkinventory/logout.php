<?php
session_start(); // Start the session
include_once("connections/connection.php");
$con = connection();

// Set the time zone
date_default_timezone_set('Asia/Manila');

if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $logout_time = date("Y-m-d H:i:s");

    // Update logout time in users_activity table
    $update_activity = "UPDATE User_Activity SET logout_time='$logout_time' WHERE user_id='$user_id' ORDER BY users_activity_id DESC LIMIT 1";
    $con->query($update_activity) or die ($con->error);
}

session_destroy(); // Destroy the session
unset($_SESSION['user_id']); // Unset the session variables
unset($_SESSION['user_type']);
header("Location: login.php"); // Redirect to the login page
exit();
?>
