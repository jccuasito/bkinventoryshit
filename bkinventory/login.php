<?php
session_start(); // Start the session
include_once("connections/connection.php");
$con = connection();

// Set the time zone
date_default_timezone_set('Asia/Manila');

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if user exists and fetch user details
    $sql = "SELECT * FROM Users WHERE email = '$email'";
    $result = $con->query($sql) or die ($con->error);
    $row = $result->fetch_assoc();
    $total = $result->num_rows;

    if ($total > 0 && password_verify($password, $row['password'])) {
        $_SESSION['user_id'] = $row['users_id'];
        $_SESSION['user_type'] = $row['type'];
        
        // Save login time in users_activity table
        $user_id = $row['users_id'];
        $login_time = date("Y-m-d H:i:s");
        $insert_activity = "INSERT INTO User_Activity (user_id, login_time) VALUES ('$user_id', '$login_time')";
        $con->query($insert_activity) or die ($con->error);

        // Show login successful message
        echo "<script>alert('Login successful!');</script>";

        // Redirect based on user type
        if ($row['type'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else if ($row['type'] == 'cashier') {
            header("Location: staff_dashboard.php");
        }
    } else {
        $error = "Invalid email or password";
    }
} else {
    $error_message = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basteakoy Inventory Management System</title>
    <style>
        @font-face {
            font-family: 'General Sans';
            src: url('fonts/GeneralSans-Regular.woff2') format('woff2'),
                 url('fonts/GeneralSans-Regular.woff') format('woff'),
                 url('fonts/GeneralSans-Regular.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'General Sans', sans-serif; /* Apply General Sans globally */
        }
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('img/bgimage.png');
            background-size: cover;
            background-position: center;
        }
        .login-container {
            position: relative;
            width: 100%;
            height: 100%;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Slight dark overlay */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .title {
            color: white;
            font-size: 3rem;
            font-weight: bolder;
            margin-bottom: 0.5rem;
        }
        .subtitle {
            color: #FD4F0E;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 2rem;
        }
        .login-box {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
            width: 320px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            color: black;
        }
        input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }
        .login-btn {
            background-color: #FFD357;
            color: #0094FF;
            font-size: 1rem;
            font-weight: bold;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-btn:hover {
            background-color: #e6b800;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="overlay">
            <h1 class="title">BASTEAKOY</h1>
            <h2 class="subtitle">INVENTORY MANAGEMENT SYSTEM</h2>
            <div class="login-box">
                <form action="" method="POST">
                    <label for="email">EMAIL</label>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                    <label for="password">PASSWORD</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <button type="submit" name="login" class="login-btn">LOGIN</button>
                    <?php if (isset($error)): ?>
                        <p style="color: red;"><?php echo $error; ?></p>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
