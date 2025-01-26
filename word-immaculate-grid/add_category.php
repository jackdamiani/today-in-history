<?php
require_once __DIR__ . '/../db_config.php'; // Adjust the path as needed

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryName = $_POST['category_name'] ?? null;
    $regex = $_POST['regex'] ?? null;
    $impossibleGroup = $_POST['impossible_group'] ?? null;

    // Validate inputs
    if (empty($categoryName) || empty($regex)) {
        die("Both 'category name' and 'regex' are required.");
    }

    // Insert the new category into the categories table
    $stmt = $conn->prepare("INSERT INTO categories (category, impossible_group, regex) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed for category insert: " . $conn->error);
    }
    $stmt->bind_param("sis", $categoryName, $impossibleGroup, $regex);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $categoryId = $stmt->insert_id; // Get the newly inserted category ID
    } else {
        die("Error inserting category: " . $conn->error);
    }
    $stmt->close();

    // Load and decode words.json
    $wordsDictionary = json_decode(file_get_contents(__DIR__ . '/words.json'), true);
    if (!$wordsDictionary) {
        die("Error reading or decoding words.json file.");
    }

    // Process words that match the regex
    foreach ($wordsDictionary as $word => $posList) {
        if (preg_match("/$regex/", $word)) {
            // Insert word into the 'words' table if it doesn't exist
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

    echo "Category '$categoryName' and its words have been successfully added!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Category</title>
</head>
<body>
    <h1>Add New Category</h1>
    <form method="POST" action="">
        <label for="category_name">Category Name:</label><br>
        <input type="text" id="category_name" name="category_name" required><br><br>

        <label for="regex">Regex:</label><br>
        <input type="text" id="regex" name="regex" required><br><br>

        <label for="impossible_group">Impossible Group (optional):</label><br>
        <input type="number" id="impossible_group" name="impossible_group"><br><br>

        <button type="submit">Add Category</button>
    </form>
</body>
</html>
