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

// If form is submitted with selected categories
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['list1_categories'], $_POST['list2_categories'])) {
    $selectedList1 = $_POST['list1_categories'];
    $selectedList2 = $_POST['list2_categories'];

    if (count($selectedList1) !== 3 || count($selectedList2) !== 3) {
        die("You must select exactly 3 categories from each list.");
    }

    // Generate all pairwise combinations between selected categories from list 1 and list 2
    $combinations = [];
    foreach ($selectedList1 as $category1) {
        foreach ($selectedList2 as $category2) {
            $combinations[] = [$category1, $category2];
        }
    }

    $rows = []; // Array to store results for comparisons

    // Loop through all combinations
    foreach ($combinations as $pair) {
        list($category1_id, $category2_id) = $pair;

        // Get the category data from the database
        $stmt = $conn->prepare("SELECT category, impossible_group FROM categories WHERE id IN (?, ?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ii", $category1_id, $category2_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            $category1 = $row;
            $row = $result->fetch_assoc();
            $category2 = $row;
        } else {
            continue; // Skip if category data is not found
        }
        $stmt->close();

        // Skip if impossible_group is the same and not NULL
        if ($category1['impossible_group'] !== null &&
            $category2['impossible_group'] !== null &&
            $category1['impossible_group'] !== 0 &&
            $category2['impossible_group'] !== 0 &&
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
        $stmt->bind_param("ii", $category1_id, $category2_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            // Fetch the total count of overlapping words
            $row = $result->fetch_assoc();
            $overlapCount = $row['total_overlap_count'];

            // Add the row to the $rows array
            $rows[] = [
                'categories' => "{$category1['category']} and {$category2['category']}",
                'impossible_groups' =>
                    ($category1['impossible_group'] ?? 'NULL') . " / " .
                    ($category2['impossible_group'] ?? 'NULL'),
                'overlap_count' => $overlapCount,
            ];
        }

        $stmt->close();
    }

    // Sort rows by overlap_count in descending order
    usort($rows, function ($a, $b) {
        return $b['overlap_count'] <=> $a['overlap_count'];
    });

    // Output the results
    echo "<h1>Category Pairwise Comparison Report</h1>";
    echo "<table border='1'>
            <tr>
                <th>Category Pair</th>
                <th>Impossible Groups</th>
                <th>Overlapping Words Count</th>
            </tr>";

    foreach ($rows as $row) {
        $highlightClass = ($row['overlap_count'] > 100) ? 'highlight' : '';
        echo "<tr class='$highlightClass'>
                <td>{$row['categories']}</td>
                <td>{$row['impossible_groups']}</td>
                <td>{$row['overlap_count']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    // Display the category selection form
    echo "<h1>Select Categories for Comparison</h1>";
    echo "<form method='POST' action=''>
            <div style='display: flex; gap: 20px;'>
                <div>
                    <h3>List 1</h3>";
    foreach ($categories as $category) {
        echo "<input type='checkbox' name='list1_categories[]' value='{$category['id']}'> {$category['category']}<br>";
    }
    echo "  </div>
                <div>
                    <h3>List 2</h3>";
    foreach ($categories as $category) {
        echo "<input type='checkbox' name='list2_categories[]' value='{$category['id']}'> {$category['category']}<br>";
    }
    echo "  </div>
            </div>
            <br><input type='submit' value='Compare Categories'>
          </form>";
}
?>
