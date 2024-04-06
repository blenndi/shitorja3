<?php
session_start();

// Assuming you have a database connection established
// Replace database credentials with your actual database details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shitorja";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search query from the form
$search_query = $_GET['query'];

// Prepare SQL statement to search for products
$sql = "SELECT * FROM artikulli WHERE emri LIKE '%$search_query%'";

$result = $conn->query($sql);

echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Search Results</title>";
echo "<link rel='stylesheet' href='style.css'>";
echo "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>";
echo "<script>";
echo "
    $(document).ready(function() {
        $('.shto-form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                success: function(response) {
                    console.log(response); // Log the response for debugging
                    // You can display a success message or handle the response as needed
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Log any errors for debugging
                    // You can display an error message or handle the error as needed
                }
            });
        });
    });
";
echo "</script>";
echo "</head>";
echo "<body>";

if ($result->num_rows > 0) {
    echo "<div class='container'>";
    echo "<h2>Search Results</h2>";
    // Output data of each matching row
    while ($row = $result->fetch_assoc()) {
        echo "<div class='product'>";
        echo "<img src='laptop.jpeg' alt='Product Image' width='50'>"; // Default image path
        echo "<h3>" . $row["emri"] . "</h3>";
        echo "<p>" . $row["çmimi"] . " €</p>"; // Assuming 'çmimi' is the price column name
        if (!empty($_SESSION['ID'])) {
            echo "<form class='shto-form' method='post' action='shto_artikull.php'>";
            echo "<input type='hidden' name='artikull' value='" . $row["emri"] . "'>";
            echo "<button type='submit'>Shto në shportë</button>";
            echo "</form>";
        }
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "<div class='container'>";
    echo "<h2>No results found for '$search_query'</h2>";
    echo "</div>";
}

echo "</body>";
echo "</html>";

$conn->close();
?>
