<?php
include_once("connections/connection.php");
$con = connection();

// Check if the user_id is provided
if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    // Fetch user activity data (login/logout times for a specific user)
    $query = "SELECT login_time, logout_time FROM User_Activity WHERE user_id = $userId ORDER BY users_activity_id DESC";
    $result = mysqli_query($con, $query);

    if ($result) {
        $activityData = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $activityData[] = [
                'login_time' => $row['login_time'] ?? null,
                'logout_time' => $row['logout_time'] ?? null
            ];
        }

        // Return the activity data as a JSON response
        echo json_encode($activityData);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
?>
