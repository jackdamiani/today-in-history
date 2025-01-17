<?php
// Database connection
$servername = "localhost"; // or the host provided by Hostinger
$username = "u880862300_tih_user_stats";
$password = "m?6Y|/&VexQ";
$dbname = "u880862300_user_stats";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read JSON file
$jsonData = file_get_contents('words.json');
$data = json_decode($jsonData, true); // Decode JSON to associative array

// Insert words into `words` table
foreach ($data as $word => $categories) {
    $stmt = $conn->prepare("INSERT IGNORE INTO words (word) VALUES (?)");
    $stmt->bind_param("s", $word);
    $stmt->execute();
}

echo "Words inserted successfully!";
$conn->close();
?>
