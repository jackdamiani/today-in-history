<?php
require_once __DIR__ . '/../db_config.php'; // Adjust the path as needed

// Map shorthand parts of speech to full names in the categories table
$partOfSpeechMap = [
    'n' => 'noun',
    'pr' => 'preposition',
    'aj' => 'adjective',
    'v' => 'verb',
    'av' => 'adverb',
    'i' => 'interjection',
    'c' => 'conjunction',
    'pn' => 'pronoun',
];

// Check if a part of speech was provided
if (!isset($_GET['pos'])) {
    die("Usage: add_words.php?pos=<part_of_speech>\nAvailable parts: " . implode(", ", array_keys($partOfSpeechMap)) . "\n");
}

$partOfSpeech = $_GET['pos'];

if (!array_key_exists($partOfSpeech, $partOfSpeechMap)) {
    die("Invalid part of speech. Allowed parts: " . implode(", ", array_keys($partOfSpeechMap)) . "\n");
}

// Get the corresponding full name of the category
$categoryName = $partOfSpeechMap[$partOfSpeech];

// Get category ID for the selected part of speech
$stmt = $conn->prepare("SELECT id FROM categories WHERE category = ?");
if (!$stmt) {
    die("Prepare failed for category select: " . $conn->error);
}
$stmt->bind_param("s", $categoryName);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $row = $result->fetch_assoc()) {
    $categoryId = $row['id'];
} else {
    die("Category not found for: $categoryName\n");
}
$stmt->close();

// Insert words for the selected part of speech
$wordsDictionary = json_decode(file_get_contents(__DIR__ . '/../words.json'), true); // Adjust path to your JSON file
if (!$wordsDictionary) {
    die("Error reading or decoding words.json file.\n");
}

foreach ($wordsDictionary as $word => $posList) {
    if (in_array($partOfSpeech, $posList)) {
        // Insert word into the 'words' table if it doesn't exist yet
        $stmt = $conn->prepare("INSERT IGNORE INTO words (word) VALUES (?)");
        if (!$stmt) {
            die("Prepare failed for word insert: " . $conn->error);
        }
        $stmt->bind_param("s", $word);
        $stmt->execute();
        $stmt->close();

        // Get the word ID
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

        // Insert into 'word_category'
        $stmt = $conn->prepare("INSERT IGNORE INTO word_category (word_id, category_id) VALUES (?, ?)");
        if (!$stmt) {
            die("Prepare failed for word_category insert: " . $conn->error);
        }
        $stmt->bind_param("ii", $wordId, $categoryId);
        $stmt->execute();
        $stmt->close();
    }
}

echo "Words for part of speech '$categoryName' have been added successfully.\n";
?>
