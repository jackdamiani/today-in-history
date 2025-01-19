<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost"; // or the host provided by Hostinger
$username = "u880862300_tih_user_stats";
$password = "m?6Y|/&VexQ";
$dbname = "u880862300_user_stats";

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read JSON file containing words and parts of speech
$jsonData = file_get_contents('words.json');
$wordsDictionary = json_decode($jsonData, true);

// Get category ID for 'n' (noun)
$categoryName = 'noun';

// Insert category if it doesn't exist
$sql = "SELECT id FROM categories WHERE category = '$categoryName'";
$result = $conn->query($sql);
// Check if the query was successful
if ($result === false) {
    // Query failed, print error message
    die('Query failed: ' . $conn->error);
}
if ($result->num_rows == 0) {
    $conn->query("INSERT INTO categories (category) VALUES ('$categoryName')");
    $categoryId = $conn->insert_id;
} else {
    $stmt = $conn->prepare("SELECT id FROM categories WHERE category = ?");
    $stmt->bind_param("s", $categoryName);

    $stmt->execute();
    $stmt->bind_result($categoryId);
    $stmt->fetch();
    $stmt->close();

    echo "Category ID: " . $categoryId;
}

// Loop through the dictionary and filter for words with 'n' in their parts of speech
foreach ($wordsDictionary as $word => $posList) {
    if (in_array('n', $posList)) {
        // Insert word into the 'words' table if it doesn't exist yet
        $stmt = $conn->prepare("INSERT IGNORE INTO words (word) VALUES (?)");
        if (!$stmt) {
            die("Prepare failed for word insert: " . $conn->error);
        }
        $stmt->bind_param("s", $word);
        $stmt->execute();
        $stmt->close();

        // Get the word ID (assumes word is unique)
        $stmt = $conn->prepare("SELECT id FROM words WHERE word = ?");
        if (!$stmt) {
            die("Prepare failed for word select: " . $conn->error);
        }
        $stmt->bind_param("s", $word);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $row = $result->fetch_assoc()) {
            $wordId = $row['id'];
        } else {
            die("Error fetching word ID: " . $conn->error);
        }
        $stmt->close();

        // Insert into 'word_category' table to associate the word with the 'noun' category
        $stmt = $conn->prepare("INSERT IGNORE INTO word_category (word_id, category_id) VALUES (?, ?)");
        if (!$stmt) {
            die("Prepare failed for word_category insert: " . $conn->error);
        }
        $stmt->bind_param("ii", $wordId, $categoryId);
        $stmt->execute();
        $stmt->close();
    }
}


echo "Words categorized successfully!";
$conn->close();
?>
