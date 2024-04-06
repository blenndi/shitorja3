<?php
session_start(); // Start the session

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shitorja";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check for POST request and the existence of the submitted article
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['artikull'])) {
    // Get the name of the article from the request
    $emri_artikullit = $_POST['artikull'];

    // Prepare SQL statement to find the ID of the article based on its name
    $sql = "SELECT id FROM artikulli WHERE emri = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("s", $emri_artikullit);

    // Execute the prepared statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if there is a result
    if ($result->num_rows > 0) {
        // Get the first row from the result
        $row = $result->fetch_assoc();

        // Get the ID of the article
        $id_artikulli = $row["id"];

        // Get the user's ID from the session
        $id_klienti = $_SESSION['ID'];

        // Prepare SQL statement to insert the article into the klienti_artikulli table
        $sql_shto = "INSERT INTO klienti_artikulli (id_klienti, id_artikulli, statusi) VALUES (?, ?, 'shtuar në shportë')";

        // Prepare the statement
        $stmt_shto = $conn->prepare($sql_shto);

        // Bind parameters
        $stmt_shto->bind_param("ii", $id_klienti, $id_artikulli);

        // Execute the prepared statement to add the article to the cart
        if ($stmt_shto->execute()) {
            echo "Artikulli u shtua në shportë me sukses.";
        } else {
            echo "Gabim gjatë shtimit të artikullit në shportë: " . $stmt_shto->error;
        }
    } else {
        echo "Artikulli nuk u gjet në bazën e të dhënave.";
    }

    // Close the prepared statement
    $stmt->close();
    $stmt_shto->close();
}

// Close the database connection
$conn->close();
?>
