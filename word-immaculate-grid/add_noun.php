<?php
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
$sql = "SELECT id FROM categories WHERE category_name = '$categoryName'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    $conn->query("INSERT INTO categories (category_name) VALUES ('$categoryName')");
    $categoryId = $conn->insert_id;
} else {
    $category = $result->fetch_assoc();
    $categoryId = $category['id'];
}

// Loop through the dictionary and filter for words with 'n' in their parts of speech
foreach ($wordsDictionary as $word => $posList) {
    if (in_array('n', $posList)) {
        // Insert word into the 'words' table if it doesn't exist yet
        $sql = "INSERT IGNORE INTO words (word) VALUES ('$word')";
        $conn->query($sql);
        
        // Get word ID (assumes word is unique)
        $sql = "SELECT id FROM words WHERE word = '$word'";
        $result = $conn->query($sql);
        $word = $result->fetch_assoc();
        $wordId = $word['id'];

        // Insert into 'word_category' table to associate the word with the 'noun' category
        $sql = "INSERT INTO word_category (word_id, category_id) VALUES ('$wordId', '$categoryId')";
        $conn->query($sql);
    }
}

echo "Words categorized successfully!";
$conn->close();
?>
