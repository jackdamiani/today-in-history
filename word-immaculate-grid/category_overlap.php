<?php
// Database connection
require_once __DIR__ . '/../db_config.php'; // Adjust the path as needed

// Fetch all category IDs from the categories table
$sql = "SELECT id FROM categories ORDER BY id";
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
    for ($j = $i + 1; $j < count($categories); $j++) { 
        $combinations[] = [$categories[$i], $categories[$j]];
    }
}

// Start HTML output
echo "<html><head><title>Overlapping Categories Count Report</title>";
// Inline CSS to highlight the rows where overlap count > 100
echo "<style>
        .highlight {
            background-color: yellow;
        }
      </style></head><body>";
echo "<h1>Overlapping Categories Count Report</h1>";
echo "<table border='1'><tr><th>Category Pair</th><th>Overlapping Words Count</th></tr>";

// Output the combinations to the console using JavaScript for debugging purposes
echo "<script>";
echo "console.log('Combinations:', " . json_encode($combinations) . ");";
echo "</script>";

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
        $overlapCount = $row['total_overlap_count'];

        // Add the highlight class if the count is greater than 100
        $highlightClass = ($overlapCount > 100) ? 'highlight' : '';

        // Output the category pair and overlap count with or without highlight
        echo "<tr class='$highlightClass'>
                <td>Categories $category1 and $category2</td>
                <td>$overlapCount</td>
              </tr>";
    } else {
        echo "<tr><td>Categories $category1 and $category2</td><td>Error executing query</td></tr>";
    }
}

// Close the table and HTML tags
echo "</table></body></html>";
?>
