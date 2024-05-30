<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Client Date</title>
    <script>
        function sendClientDate() {
            var clientDate = new Date();
            var utcDate = new Date(Date.UTC(
                clientDate.getFullYear(),
                clientDate.getMonth(),
                clientDate.getDate(),
                clientDate.getHours(),
                clientDate.getMinutes(),
                clientDate.getSeconds()
            ));
            document.cookie = "clientDate=" + encodeURIComponent(utcDate.toISOString()) + "; path=/";
            // var clientDate = new Date();
            // // Set the cookie with the client date
            // document.cookie = "clientDate=" + encodeURIComponent(clientDate.toISOString()) + "; path=/";
            // console.log(clientDate)
        }
    </script>
</head>
<body onload="sendClientDate()">
    <h1>Getting Client Date</h1>
    <div id="result"></div>

    <?php
    // Access the cookie in PHP
    date_default_timezone_set('Your_Client_Time_Zone');

    echo $_COOKIE['clientDate'];
    
    $clientDate = $_COOKIE['clientDate'];
    // Convert to PHP DateTime object
    $date = new DateTime($clientDate);
    
    // Perform any operations you need with the date
    echo "The client's date and time is: " . $date->format('Y-m-d');

    echo $date;

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

    // // Get the raw input
    // $raw_input = file_get_contents('php://input');
    // echo "Raw input: $raw_input\n";

    // // Decode the JSON input
    // $input = json_decode($raw_input, true);
    // var_dump($input);
    

    // // Get the input data
    // $input = json_decode(file_get_contents('php://input'), true);
    // $num_guesses = $input['num_guesses'];
    // echo $num_guesses;

    // Determine passed value based on num_guesses
    $num_guesses = 3;
    echo $num_guesses;
    $passed = $num_guesses == 16 ? 0 : 1;
    // $date = date('Y-m-d'); // Today's date


    $stmt = $conn->prepare("SELECT id FROM test WHERE date = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    echo $result;
    // Retrieve the latest id from the table
    // $sql = "SELECT id FROM test";
    // $sql = "SELECT id FROM test WHERE date = ? ORDER BY id DESC LIMIT 1";
    // $result = $conn->query($sql);
    // echo $sql;
    // echo $result;

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
</body>
</html>

<?php
    
?>