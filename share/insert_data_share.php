<!-- <!DOCTYPE html>
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
        }
    </script>
</head> -->
<!-- <body onload="sendClientDate()">
    <h1>Getting Client Date</h1>
    <div id="result"></div> -->

<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // allow errors
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // get date from cookie
        // $clientDate = $_COOKIE['clientDate'];

        $date = $_POST['date'];

        // Convert to PHP DateTime object
        // $date = new DateTime($clientDate);
        $date = new DateTime($date);
        $date = $date->format('Y-m-d');
        
        // connect to server
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
        // echo "Connected successfully";

        // Determine passed value based on num_guesses
        // $num_guesses = $_POST['num_guess'];
        
        // $passed = $num_guesses == 16 ? 0 : 1;

        try {
            $stmt = $conn->prepare("SELECT id FROM share WHERE date = ? ORDER BY id DESC LIMIT 1");

            // Check for errors during preparation
            if (!$stmt) {
                die("Error preparing statement: " . $conn->error);
            }

            $stmt->bind_param("s", $date);

            // Execute the statement
            if (!$stmt->execute()) {
                // Error occurred during execution
                die("Error executing statement: " . $stmt->error);
            }

            $result = $stmt->get_result();
            

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $last_id = intval($row['id']);
            }
            else {
                // If no records exist, start with an initial ID
                $last_id = 0;
                }
        } catch (Exception $e) {
            // echo "An error occurred: " . $e->getMessage();
            $last_id = 0;
        }

        // Increment the id by one
        $new_id = str_pad($last_id + 1, 10, '0', STR_PAD_LEFT);

        // Insert data into the database
        $stmt = $conn->prepare("INSERT INTO share (date) VALUES (?, ?)");
        $stmt->bind_param("ss", $new_id, $date);

        if ($stmt->execute()) {
            echo $result;
            echo $last_id;
            echo "New record created successfully with ID: $new_id";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
?>
<!-- </body>
</html> -->
