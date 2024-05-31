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

    // Get today's date
    $today = date('Y-m-d');
    echo $today;

    // Prepare and execute the SQL query
    $sql = "SELECT num_guesses FROM test WHERE passed = 1 AND date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the data and store in an array
    $num_guesses = [];
    while ($row = $result->fetch_assoc()) {
        $num_guesses[] = $row['num_guesses'];
    }

    // Output data as JSON
    echo json_encode($num_guesses);

    // Close connection
    $stmt->close();
    $conn->close();
?>