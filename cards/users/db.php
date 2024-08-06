<?php
// db.php

// Database credentials
$host = 'localhost'; // or your database host
$user = 'root'; // or your database username
$password = ''; // or your database password
$dbname = 'cafebristo'; // your cafebristo

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>