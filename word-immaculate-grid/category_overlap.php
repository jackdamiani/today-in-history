<?php
// Database connection
require_once __DIR__ . '/../db_config.php'; // Adjust the path as needed

// Fetch all category IDs, names, and impossible_group from the categories table
$sql = "SELECT id, category, impossible_group FROM categories ORDER BY id";
$result = $conn->query($sql);
if (!$result) {
    die("Error fetching categories: " . $conn->error);
}

$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row; // Store all category data in the array
}

// Generate all combinations of two categories
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
echo "<table border='1'>
        <tr>
            <th>Category Pair</th>
            <th>Impossible Groups</th>
            <th>Overlapping Words Count</th>
        </tr>";

// Loop through all combinations of categories
foreach ($combinations as $pair) {
    list($category1, $category2) = $pair;

    // Skip if impossible_group is the same and not NULL
    if ($category1['impossible_group'] !== null &&
        $category2['impossible_group'] !== null &&
        $category1['impossible_group'] === $category2['impossible_group']) {
        continue;
    }

    // SQL query to get the total count of overlapping words for the category pair
    $sql = "
        SELECT COUNT(*) AS total_overlap_count
        FROM (
            SELECT wc.word_id
            FROM word_category wc
            WHERE wc.category_id IN (?, ?)
            GROUP BY wc.word_id
            HAVING COUNT(DISTINCT wc.category_id) = 2
        ) AS overlapping_words
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ii", $category1['id'], $category2['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        // Fetch the total count of overlapping words
        $row = $result->fetch_assoc();
        $overlapCount = $row['total_overlap_count'];

        // Add the highlight class if the count is greater than 100
        $highlightClass = ($overlapCount > 100) ? 'highlight' : '';

        // Output the category pair, impossible groups, and overlap count
        echo "<tr class='$highlightClass'>
                <td>{$category1['category']} and {$category2['category']}</td>
                <td>" .
                    ($category1['impossible_group'] ?? 'NULL') . " / " .
                    ($category2['impossible_group'] ?? 'NULL') .
                "</td>
                <td>$overlapCount</td>
              </tr>";
    } else {
        echo "<tr><td>{$category1['category']} and {$category2['category']}</td><td>Error executing query</td><td></td></tr>";
    }

    $stmt->close();
}

// Close the table and HTML tags
echo "</table></body></html>";
?>
