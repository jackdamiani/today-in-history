<?php
    $servername = "localhost"; // or the host provided by Hostinger
    $username = "u880862300_tih_user_stats";
    $password = "m?6Y|/&VexQ";
    $dbname = "u880862300_user_stats";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the input data
    $input = json_decode(file_get_contents('php://input'), true);
    $num_guesses = $input['num_guesses'];
    echo $num_guesses

    // Determine passed value based on num_guesses
    $passed = $num_guesses == 16 ? 0 : 1;
    $date = date('Y-m-d'); // Today's date

    // Retrieve the latest id from the table
    $sql = "SELECT id FROM test WHERE date = ? ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);
    echo $result;

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_id = intval($row['id']);
    } else {
        // If no records exist, start with an initial ID
        $last_id = 0;
    }

    // Increment the id by one
    $new_id = str_pad($last_id + 1, 10, '0', STR_PAD_LEFT);

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO test (id, num_guesses, passed, date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siis", $new_id, $num_guesses, $passed, $date);

    if ($stmt->execute()) {
        echo "New record created successfully with ID: $new_id";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close connection
    $stmt->close();
    $conn->close();
?>