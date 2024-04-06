<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style2.css">
    <script>
        function validateForm() {
            var newPassword = document.getElementById("newPassword").value;
            if (newPassword.length < 8) {
                alert("Password duhet te jete me shume se 8 karaktera.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
<div class="container">
    <form action="register.php" method="post" onsubmit="return validateForm()">
        <label for="newUsername">New Username:</label>
        <input type="text" id="newUsername" name="newUsername" required><br><br>
        
        <label for="newEmail">Email:</label>
        <input type="email" id="newEmail" name="newEmail" required><br><br>
        
        <label for="newPassword">New Password:</label>
        <input type="password" id="newPassword" name="newPassword" required><br><br>
        
        <label for="newContact">Contact:</label>
        <input type="text" id="newContact" name="newContact" required><br><br>
        
        <label for="newCity">City:</label>
        <input type="text" id="newCity" name="newCity" required><br><br>
        
        <label for="newAddress">Address:</label>
        <input type="text" id="newAddress" name="newAddress" required><br><br>
        
        <input type="submit" value="Register">
    </form>
</div>
</body>
</html>


<?php
// Establish a MySQL connection (replace with your database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$database = "shitorja"; // Ndryshoni këtë me emrin e bazës së dhënave tuaj

$conn = new mysqli($servername, $username, $password, $database);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = $_POST["newUsername"];
    $newEmail = $_POST["newEmail"];
    $newPassword = $_POST["newPassword"];
    $newContact = $_POST["newContact"];
    $newCity = $_POST["newCity"];
    $newAddress = $_POST["newAddress"];

    // Assuming you have a database connection established here

    // Insert new user into the database
    $sql = "INSERT INTO klienti (emri, email, password, kontakti, qyteti, adresa) 
            VALUES ('$newUsername', '$newEmail', '$newPassword', '$newContact', '$newCity', '$newAddress')"; // Ndryshoni "shitorja" me emrin e tabelës së duhur
    if ($conn->query($sql) === TRUE) {
        // Registration successful
        echo '<script>alert("User registered successfully!");</script>';
        echo '<script>window.location.href = "login.html";</script>';
        exit();
    } else {
        // Registration failed
        echo '<script>alert("Error occurred during registration. Please try again.");</script>';
        echo '<script>window.location.href = "register.html";</script>';
        exit();
    }
}

// Close the database connection
$conn->close();
?>


