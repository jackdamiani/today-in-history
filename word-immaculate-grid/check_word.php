<?php
// Ensure $_POST data is received properly
$word = $_POST['word'] ?? null;
$category1 = $_POST['category1'] ?? null;
$category2 = $_POST['category2'] ?? null;

// Check for missing parameters
if (!$word || !$category1 || !$category2) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
    exit;
}

// Database connection (Ensure $conn is already established in your db_config.php)
require_once __DIR__ . '/../db_config.php'; // Adjust path if needed

// Use a raw SQL query
$sql = "
    SELECT *
    FROM words w
    JOIN word_category wc1 ON w.id = wc1.word_id
    JOIN categories c1 ON wc1.category_id = c1.id AND c1.category = '$category1'
    JOIN word_category wc2 ON w.id = wc2.word_id
    JOIN categories c2 ON wc2.category_id = c2.id AND c2.category = '$category2'
    WHERE w.word = '$word'
";

// Execute the query
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    // If results are found
    echo json_encode(['status' => 'success', 'message' => 'Word is valid in both categories']);
} else {
    // If no results are found
    echo json_encode(['status' => 'error', 'message' => 'Word is not valid in both categories']);
}
?>