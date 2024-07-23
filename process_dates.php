<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientDate = isset($_POST['client_date']) ? $_POST['client_date'] : null;
    $anotherDate = isset($_POST['another_date']) ? $_POST['another_date'] : null;

    if ($clientDate && $anotherDate) {
        $clientDate = date('Y-m-d H:i:s', strtotime($clientDate));
        $anotherDate = date('Y-m-d H:i:s', strtotime($anotherDate));

        // Assuming you have a PDO connection $pdo
        try {
            $pdo = new PDO('mysql:host=your_host;dbname=your_db', 'your_username', 'your_password');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare('SELECT * FROM your_table WHERE your_date_column BETWEEN :clientDate AND :anotherDate');
            $stmt->execute(['clientDate' => $clientDate, 'anotherDate' => $anotherDate]);

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($results);
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    } else {
        echo 'Invalid dates';
    }
} else {
    echo 'Invalid request method';
}
?>
