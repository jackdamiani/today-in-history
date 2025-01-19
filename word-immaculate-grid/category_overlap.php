<?php
// Database connection
require_once __DIR__ . '/../db_config.php'; // Adjust the path as needed

// Fetch all category IDs from the categories table
$sql = "SELECT id FROM categories";
$result = $conn->query($sql);
if (!$result) {
    die("Error fetching categories: " . $conn->error);
}

$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row['id']; // Store all category IDs in the array
}

// Generate all combinations of two categories (including same category pairs)
$combinations = [];
for ($i = 0; $i < count($categories); $i++) {
    for ($j = $i; $j < count($categories); $j++) { // Allow i == j for same category pairs
        $combinations[] = [$categories[$i], $categories[$j]];
    }
}

// Start HTML output
echo "<html><head><title>Overlapping Categories Count Report</title></head><body>";
echo "<h1>Overlapping Categories Count Report</h1>";
echo "<table border='1'><tr><th>Category Pair</th><th>Overlapping Words Count</th></tr>";

// Loop through all combinations of categories
foreach ($combinations as $pair) {
    list($category1, $category2) = $pair;

    // SQL query to get the total count of overlapping words for the category pair
    $sql = "
        SELECT COUNT(*) AS total_overlap_count
        FROM (
            SELECT wc.word_id
            FROM word_category wc
            WHERE wc.category_id IN ($category1, $category2)
            GROUP BY wc.word_id
            HAVING COUNT(DISTINCT wc.category_id) = 2
        ) AS overlapping_words
    ";

    $result = $conn->query($sql);
    if ($result) {
        // Fetch the total count of overlapping words
        $row = $result->fetch_assoc();
        $overlapCount = $row['total_overlap_count'] ?? 0; // Default to 0 if no result

        // Display the category pair and overlap count in the table
        echo "<tr><td>Category $category1 and Category $category2</td><td>$overlapCount</td></tr>";
    } else {
        echo "<tr><td>Category $category1 and Category $category2</td><td>Error executing query</td></tr>";
    }
}

// Close the table and HTML tags
echo "</table></body></html>";
?>
