<?php
header('Content-Type: application/json');

// Get the raw POST data from the request body
$data = json_decode(file_get_contents('php://input'), true);

// Ensure the data is valid (check if word, category1, and category2 are present)
$word = $data['word'] ?? null;
$category1 = $data['category1'] ?? null;
$category2 = $data['category2'] ?? null;

// Check for missing parameters
if (!$word || !$category1 || !$category2) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
    exit;
}

// Database connection (Ensure $conn is already established in your db_config.php)
require_once __DIR__ . '/../db_config.php'; // Adjust path if needed

// Use a SQL query with placeholders
$sql = "
    SELECT *
    FROM words w
    JOIN word_category wc1 ON w.id = wc1.word_id
    JOIN categories c1 ON wc1.category_id = c1.id AND c1.category = ?
    JOIN word_category wc2 ON w.id = wc2.word_id
    JOIN categories c2 ON wc2.category_id = c2.id AND c2.category = ?
    WHERE w.word = ?
";

// Prepare the query
$stmt = $conn->prepare($sql);

// Bind parameters to placeholders
$stmt->bind_param("sss", $category1, $category2, $word);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Check if results are found
if ($result && $result->num_rows > 0) {
    echo json_encode(['status' => 'success', 'message' => 'Word is valid in both categories']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Word is not valid in both categories']);
}
?>
