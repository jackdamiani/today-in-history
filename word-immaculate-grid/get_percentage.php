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

    // // Test words
    // $word = 'set';
    // $category1 = '3letters';
    // $category2 = 'noun';

    if (empty($word) || empty($category1) || empty($category2)) {
        echo json_encode(["error" => "Invalid input"]);
        exit;
    }

    // Get total number of guesses in this category
    $queryTotal = "SELECT COUNT(*) AS total FROM answers 
                JOIN categories c1 ON answers.category1_id = c1.id 
                JOIN categories c2 ON answers.category2_id = c2.id 
                WHERE c1.category = ? AND c2.category = ?";
    $stmtTotal = $conn->prepare($queryTotal);
    $stmtTotal->bind_param("ss", $category1, $category2);
    $stmtTotal->execute();
    $resultTotal = $stmtTotal->get_result()->fetch_assoc();
    $totalGuesses = $resultTotal['total'] ?? 0;

    // Get total number of guesses in this category
    $queryTotal2 = "SELECT COUNT(*) AS total FROM answers 
                JOIN categories c1 ON answers.category1_id = c1.id 
                JOIN categories c2 ON answers.category2_id = c2.id 
                WHERE c2.category = ? AND c1.category = ?";
    $stmtTotal = $conn->prepare($queryTotal2);
    $stmtTotal->bind_param("ss", $category1, $category2);
    $stmtTotal->execute();
    $resultTotal2 = $stmtTotal->get_result()->fetch_assoc();
    $totalGuesses2 = $resultTotal2['total'] ?? 0;

    $totalGuessesTotal = $totalGuesses + $totalGuesses2;

    // Get count of the specific word guessed
    $queryWord = "SELECT COUNT(*) AS total FROM answers 
    JOIN categories c1 ON answers.category1_id = c1.id 
    JOIN categories c2 ON answers.category2_id = c2.id 
    JOIN words w ON answers.word_id = w.id 
    WHERE c1.category = ? AND c2.category = ? AND w.word = ?";
    $stmtWord = $conn->prepare($queryWord);
    $stmtWord->bind_param("sss", $category1, $category2, $word);
    $stmtWord->execute();
    $resultWord = $stmtWord->get_result()->fetch_assoc();
    $wordCount = $resultWord['total'] ?? 0;

    // Get count of the specific word guessed
    $queryWord2 = "SELECT COUNT(*) AS total FROM answers 
    JOIN categories c1 ON answers.category1_id = c1.id 
    JOIN categories c2 ON answers.category2_id = c2.id 
    JOIN words w ON answers.word_id = w.id 
    WHERE c2.category = ? AND c1.category = ? AND w.word = ?";
    $stmtWord = $conn->prepare($queryWord2);
    $stmtWord->bind_param("sss", $category1, $category2, $word);
    $stmtWord->execute();
    $resultWord2 = $stmtWord->get_result()->fetch_assoc();
    $wordCount2 = $resultWord2['total'] ?? 0;

    $wordCountTotal = $wordCount + $wordCount2;

    // Calculate percentage
    // $percentage = ($wordCountTotal / $totalGuessesTotal) * 100;

    echo json_encode(['status' => 'success', "totalCount" => $totalGuessesTotal, "wordCount" => $wordCountTotal, "total1" => $totalGuesses, "total2" => $totalGuesses2, "word1" => $wordCount, "word2" => $wordCount2]);
}
?>
