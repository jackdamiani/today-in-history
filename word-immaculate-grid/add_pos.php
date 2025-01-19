<?php
require_once __DIR__ . '/../db_config.php'; // Adjust the path as needed

// Check if a part of speech was provided
if (!isset($_GET['pos'])) {
    die("Usage: add_words.php?pos=<part_of_speech>\nAvailable parts: n, pr, aj, v, av, i, c, pn\n");
}

$partOfSpeech = $_GET['pos'];
$allowedParts = ['n', 'pr', 'aj', 'v', 'av', 'i', 'c', 'pn'];

if (!in_array($partOfSpeech, $allowedParts)) {
    die("Invalid part of speech. Allowed parts: " . implode(", ", $allowedParts) . "\n");
}

// The rest of the code remains the same as above, handling the insertion logic
// (Copy the logic from the command-line example and use $partOfSpeech from $_GET['pos'])
// Get category ID for the selected part of speech
$stmt = $conn->prepare("SELECT id FROM categories WHERE category = ?");
if (!$stmt) {
    die("Prepare failed for category select: " . $conn->error);
}
$stmt->bind_param("s", $partOfSpeech);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $row = $result->fetch_assoc()) {
    $categoryId = $row['id'];
} else {
    die("Category not found for: $partOfSpeech\n");
}
$stmt->close();

// Insert words for the selected part of speech
foreach ($wordsDictionary as $word => $posList) {
    if (in_array($partOfSpeech, $posList)) {
        // Insert word into the 'words' table if it doesn't exist yet
        $stmt = $conn->prepare("INSERT IGNORE INTO words (word) VALUES (?)");
        $stmt->bind_param("s", $word);
        $stmt->execute();
        $stmt->close();

        // Get the word ID
        $stmt = $conn->prepare("SELECT id FROM words WHERE word = ?");
        $stmt->bind_param("s", $word);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $row = $result->fetch_assoc()) {
            $wordId = $row['id'];
        } else {
            die("Error fetching word ID: " . $conn->error);
        }
        $stmt->close();

        // Insert into 'word_category'
        $stmt = $conn->prepare("INSERT IGNORE INTO word_category (word_id, category_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $wordId, $categoryId);
        $stmt->execute();
        $stmt->close();
    }
}

echo "Words for part of speech '$partOfSpeech' have been added successfully.\n";
?>
