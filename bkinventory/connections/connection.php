<?php

function connection(){
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "bkinventory";

    $con = new mysqli($host, $username, $password, $database);

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error); // Use die() to stop execution on error
    }

    return $con; // Return the connection object
}

?>