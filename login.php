<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "shitorja";

$conn = new mysqli($servername, $username, $password, $database);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"]; // Hash the password using md5 for simplicity (Not recommended for production)

    // Query to check user credentials
    $sql = "SELECT * FROM klienti WHERE emri = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    // If a matching record is found, set up the session and redirect to index.php
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['ID'] = $user['id'];
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        // Invalid credentials, redirect back to the login page
        header("Location: login.html");
        exit();
    }
}

// Close the database connection
$conn->close();
?>
