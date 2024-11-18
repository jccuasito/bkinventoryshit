<?php
include_once("connections/connection.php");
$con = connection();

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Example registration script
$email = 'test_user@example.com';
$password = 'test_password';
$type = 'admin'; // Should match the enum values in your database
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $con->prepare("INSERT INTO users (email, password, type) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $email, $hashed_password, $type);
$stmt->execute();
$stmt->close();

echo "User registered with hashed password and type.";
$con->close();
?>
