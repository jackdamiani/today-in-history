<?php
// Check POST data
if (!isset($_POST['word'], $_POST['category1'], $_POST['category2'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing parameters']);
    exit;
}


require_once __DIR__ . '/../db_config.php'; // Adjust as needed

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get POST data (assuming the word and categories are sent via POST)
$word = $_POST['word'];
$category1 = $_POST['category1'];
$category2 = $_POST['category2'];

// Prepare the SQL query
$sql = "SELECT *
        FROM words w
        JOIN word_category wc1 ON w.id = wc1.word_id
        JOIN categories c1 ON wc1.category_id = c1.id AND c1.category = ?
        JOIN word_category wc2 ON w.id = wc2.word_id
        JOIN categories c2 ON wc2.category_id = c2.id AND c2.category = ?
        WHERE w.word = ?";

// Prepare and execute the statement
$stmt = $pdo->prepare($sql);
$stmt->execute([$category1, $category2, $word]);

// Check if a result was found
if ($stmt->rowCount() > 0) {
    echo json_encode(['status' => 'success', 'message' => 'Word is valid in both categories!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Word is not valid in both categories.']);
}
?>
