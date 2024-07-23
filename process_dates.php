<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientDate = isset($_POST['client_date']) ? $_POST['client_date'] : null;
    $anotherDate = isset($_POST['another_date']) ? $_POST['another_date'] : null;

    if ($clientDate && $anotherDate) {
        $clientDate = date('Y-m-d', strtotime($clientDate));
        $anotherDate = date('Y-m-d', strtotime($anotherDate));

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


        $clientDate = $_POST['date'];
        $date = new DateTime($clientDate);
        $date = $date->format('Y-m-d');

        // Prepare and execute the SQL query
        $sql = "SELECT num_guesses FROM test WHERE date BETWEEN ? and ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $date, $anotherDate);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch the data and store in an array
        $num_guesses = [];
        while ($row = $result->fetch_assoc()) {
            $num_guesses[] = $row['num_guesses'];
        }

        echo 'Select statement';
        echo $result;
        echo $num_guesses;

        // Output data as JSON
        echo json_encode($num_guesses);

        // Close connection
        $stmt->close();
        $conn->close();

        // Assuming you have a PDO connection $pdo
        // try {
        //     $pdo = new PDO('mysql:host=your_host;dbname=your_db', 'your_username', 'your_password');
        //     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //     $stmt = $pdo->prepare('SELECT * FROM your_table WHERE your_date_column BETWEEN :clientDate AND :anotherDate');
        //     $stmt->execute(['clientDate' => $clientDate, 'anotherDate' => $anotherDate]);

        //     $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //     echo json_encode($results);
        // } catch (PDOException $e) {
        //     echo 'Database error: ' . $e->getMessage();
        // }
    } else {
        echo 'Invalid dates';
    }
} 
?>
