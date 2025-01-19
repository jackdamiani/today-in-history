<?php
// Database configuration
$servername = "localhost"; // or the host provided by Hostinger
$username = "u880862300_tih_user_stats";
$password = "m?6Y|/&VexQ";
$dbname = "u880862300_user_stats";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>