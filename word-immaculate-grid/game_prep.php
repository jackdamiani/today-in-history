<?php
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

// Get all valid combinations (those with overlaps > 50)
$valid_combinations = [];
for ($i = 0; $i < count($categories); $i++) {
    for ($j = $i + 1; $j < count($categories); $j++) {
        $category1 = $categories[$i];
        $category2 = $categories[$j];

        // Skip if impossible_group is the same and not NULL
        if ($category1['impossible_group'] !== null &&
            $category2['impossible_group'] !== null &&
            $category1['impossible_group'] !== 0 &&
            $category2['impossible_group'] !== 0 &&
            $category1['impossible_group'] === $category2['impossible_group']) {
            continue;
        }

        // Query to get the overlap count for this category pair
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
            $row = $result->fetch_assoc();
            $overlapCount = $row['total_overlap_count'];

            // Add the combination to the valid list if the overlap count is > 50
            if ($overlapCount > 50) {
                $valid_combinations[] = [
                    'category1' => $category1,
                    'category2' => $category2,
                    'overlap_count' => $overlapCount,
                ];
            }
        }
        $stmt->close();
    }
}

// Now we need to generate a valid 3x3 grid using 6 categories from the valid combinations
// Let's find a set of 6 categories that work (i.e., all their overlaps > 50)
$grid_combinations = [];
$selected_categories = [];

foreach ($valid_combinations as $comb) {
    // Check if we have 6 unique categories (3 for the top, 3 for the side)
    if (count($selected_categories) < 6) {
        $selected_categories[] = $comb['category1']['id'];
        $selected_categories[] = $comb['category2']['id'];
    }
    if (count(array_unique($selected_categories)) === 6) {
        break;
    }
}

// Now we need to organize them into a 3x3 grid
$top_categories = array_slice($selected_categories, 0, 3); // First 3 for the top
$side_categories = array_slice($selected_categories, 3, 3); // Last 3 for the side

// Prepare the grid results
$grid_result = [];
foreach ($top_categories as $top) {
    foreach ($side_categories as $side) {
        // Find the overlap count for this combination
        foreach ($valid_combinations as $comb) {
            if (($comb['category1']['id'] === $top && $comb['category2']['id'] === $side) ||
                ($comb['category2']['id'] === $top && $comb['category1']['id'] === $side)) {
                $grid_result[$top][$side] = $comb['overlap_count'];
                break;
            }
        }
    }
}

// Output the result as requested
echo "<h1>3x3 Grid for Category Overlaps (with > 50 overlaps)</h1>";
echo "<h2>Top Row Categories:</h2><ul>";
foreach ($top_categories as $top) {
    $category_name = '';
    foreach ($categories as $category) {
        if ($category['id'] === $top) {
            $category_name = $category['category'];
            break;
        }
    }
    echo "<li>$category_name</li>";
}
echo "</ul>";

echo "<h2>Left Column Categories:</h2><ul>";
foreach ($side_categories as $side) {
    $category_name = '';
    foreach ($categories as $category) {
        if ($category['id'] === $side) {
            $category_name = $category['category'];
            break;
        }
    }
    echo "<li>$category_name</li>";
}
echo "</ul>";

echo "<h2>Grid of Overlap Counts:</h2>";
echo "<table border='1'><tr><th></th>";
foreach ($top_categories as $top) {
    $category_name = '';
    foreach ($categories as $category) {
        if ($category['id'] === $top) {
            $category_name = $category['category'];
            break;
        }
    }
    echo "<th>$category_name</th>";
}
echo "</tr>";

foreach ($side_categories as $side) {
    echo "<tr><td>";
    $category_name = '';
    foreach ($categories as $category) {
        if ($category['id'] === $side) {
            $category_name = $category['category'];
            break;
        }
    }
    echo "$category_name</td>";

    foreach ($top_categories as $top) {
        echo "<td>";
        echo $grid_result[$top][$side] ?? 'N/A'; // Show overlap count if available
        echo "</td>";
    }
    echo "</tr>";
}
echo "</table>";
?>
