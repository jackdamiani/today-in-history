<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Ensure the form data is received
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

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $word = $_POST['word'] ?? '';
    $category1 = $_POST['category1'] ?? '';
    $category2 = $_POST['category2'] ?? '';

    // // Example
    // $word = 'set';
    // $category1 = "3letters";
    // $category2 = 'noun';

    // Lookup word ID from the words table
    $wordidQuery = "SELECT id FROM words WHERE word = ?";
    $stmtWord = $conn->prepare($wordidQuery);
    $stmtWord->bind_param("s", $word);
    $stmtWord->execute();
    $resultWord = $stmtWord->get_result()->fetch_assoc();
    $wordid = $resultWord['id'] ?? 0;

    // Lookup category1 ID
    $category1idQuery = "SELECT id FROM categories WHERE category = ?";
    $stmtCategory1 = $conn->prepare($category1idQuery);
    $stmtCategory1->bind_param("s", $category1);
    $stmtCategory1->execute();
    $resultCategory1 = $stmtCategory1->get_result()->fetch_assoc();
    $category1id = $resultCategory1['id'] ?? 0;

    // Lookup category2 ID
    $category2idQuery = "SELECT id FROM categories WHERE category = ?";
    $stmtCategory2 = $conn->prepare($category2idQuery);
    $stmtCategory2->bind_param("s", $category2);
    $stmtCategory2->execute();
    $resultCategory2 = $stmtCategory2->get_result()->fetch_assoc();
    $category2id = $resultCategory2['id'] ?? 0;

    // Insert into answers table
    $insertAnswerQuery = "INSERT INTO answers (word_id, category1_id, category2_id) VALUES (?, ?, ?)";
    $stmtInsertAnswer = $conn->prepare($insertAnswerQuery);
    $stmtInsertAnswer->bind_param("iii", $wordid, $category1id, $category2id);
    $stmtInsertAnswer->execute();

    echo json_encode(['status' => 'success', 'message' => 'Inserted answer']);
}
?>
