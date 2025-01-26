<?php
require_once __DIR__ . '/../db_config.php'; // Adjust the path as needed

// Fetch all categories from the categories table
$sql = "SELECT id, category FROM categories ORDER BY id";
$result = $conn->query($sql);
if (!$result) {
    die("Error fetching categories: " . $conn->error);
}

$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row; // Store all category data in the array
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get selected categories (user selected 6 categories)
    $selectedCategories = $_POST['categories'] ?? [];
    
    if (count($selectedCategories) != 6) {
        echo "<p>Please select exactly 6 categories.</p>";
    } else {
        // Now we have 6 categories selected, generate the 3x3 grid
        $grid_combinations = [];
        $valid_combinations = [];
        
        // Generate valid combinations (with overlaps > 50)
        for ($i = 0; $i < count($selectedCategories); $i++) {
            for ($j = $i + 1; $j < count($selectedCategories); $j++) {
                $category1 = $selectedCategories[$i];
                $category2 = $selectedCategories[$j];

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
                $stmt->bind_param("ii", $category1, $category2);
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

        // Now, create the 3x3 grid from valid combinations
        $grid_result = [];
        foreach ($selectedCategories as $top) {
            foreach ($selectedCategories as $side) {
                // Skip if it's the same category (diagonal overlap)
                if ($top === $side) continue;

                foreach ($valid_combinations as $comb) {
                    if (($comb['category1'] === $top && $comb['category2'] === $side) ||
                        ($comb['category2'] === $top && $comb['category1'] === $side)) {
                        $grid_result[$top][$side] = $comb['overlap_count'];
                        break;
                    }
                }
            }
        }

        // Output the selected categories and the 3x3 grid
        echo "<h1>Selected Categories for the 3x3 Grid</h1>";
        echo "<h2>Top Row Categories:</h2><ul>";
        foreach ($selectedCategories as $top) {
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
        foreach ($selectedCategories as $side) {
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
        foreach ($selectedCategories as $top) {
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

        foreach ($selectedCategories as $side) {
            echo "<tr><td>";
            $category_name = '';
            foreach ($categories as $category) {
                if ($category['id'] === $side) {
                    $category_name = $category['category'];
                    break;
                }
            }
            echo "$category_name</td>";

            foreach ($selectedCategories as $top) {
                echo "<td>";
                echo $grid_result[$top][$side] ?? 'N/A'; // Show overlap count if available
                echo "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
} else {
    // If form has not been submitted, show the category selection form
    echo "<h1>Select 6 Categories</h1>";
    echo "<form method='POST'>";
    echo "<p>Select 6 categories (hold Ctrl or Command to select multiple):</p>";

    // Display checkboxes for category selection
    echo "<select name='categories[]' multiple='multiple' size='10'>";
    foreach ($categories as $category) {
        echo "<option value='{$category['id']}'>{$category['category']}</option>";
    }
    echo "</select><br><br>";

    // Submit button
    echo "<input type='submit' value='Generate Grid'>";
    echo "</form>";
}
?>
