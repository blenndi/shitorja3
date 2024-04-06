<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Online shop</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="index.php">Logout</a></li>
                </ul>
                
            </nav>
        </div>
    </header>
    <?php
session_start();
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

// Process adding and removing items from the cart
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if an article is being added
    if (isset($_POST['artikull'])) {
        $artikull = $_POST['artikull'];

        // Sanitize the input
        $artikull = htmlspecialchars($artikull);

        // Find the article ID from the database
        $sql = "SELECT id FROM artikulli WHERE emri='$artikull'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_artikulli = $row['id'];

            // Add the article to the klienti_artikulli table using the session ID of the logged-in user
            $id_klienti = $_SESSION['ID']; // Retrieve the session ID
            $sql_insert = "INSERT INTO klienti_artikulli (id_klienti, id_artikulli, statusi) VALUES ($id_klienti, $id_artikulli, 'shtuar në shportë')";
            if ($conn->query($sql_insert) === TRUE) {
                echo "Artikulli u shtua në shportë.";
            } else {
                echo "Gabim: " . $sql_insert . "<br>" . $conn->error;
            }
        } else {
            echo "Artikulli nuk u gjet.";
        }
    } elseif (isset($_POST['artikull_id'])) {
        // Remove the article from the cart if 'artikull_id' is posted
        $artikull_id = $_POST['artikull_id'];

        $sql_remove = "DELETE FROM klienti_artikulli WHERE id = $artikull_id AND statusi = 'shtuar në shportë'";

        if ($conn->query($sql_remove) === TRUE) {
            echo "Artikulli u hoq nga shporta me sukses.";
        } else {
            echo "Gabim duke hequr produktin: " . $conn->error;
        }
    }
}

// Display the items in the cart
if (isset($_SESSION['ID'])) {
    $id_klienti = $_SESSION['ID'];

    $sql_shporta = "SELECT artikulli.id, artikulli.emri, artikulli.çmimi, klienti_artikulli.id AS id FROM artikulli INNER JOIN klienti_artikulli ON artikulli.id = klienti_artikulli.id_artikulli WHERE klienti_artikulli.id_klienti = $id_klienti AND klienti_artikulli.statusi = 'shtuar në shportë'";
    $result_shporta = $conn->query($sql_shporta);

    if ($result_shporta->num_rows > 0) {
        $total_cmimi = 0;

        echo "<h1>Shporta</h1>";
        echo "<table border='1'>";
        echo "<tr><th>Emri i artikullit</th><th>Çmimi</th><th>Veprim</th></tr>";
        while ($row = $result_shporta->fetch_assoc()) {
            echo "<tr><td>".$row["emri"]."</td><td>".$row["çmimi"]."</td>";
            echo "<td><form method='post' action='".$_SERVER['PHP_SELF']."'>";
            echo "<input type='hidden' name='artikull_id' value='".$row["id"]."'>";
            echo "<input type='submit' value='Remove'></form></td></tr>";
            $total_cmimi += $row["çmimi"];
        }
        echo "<tr><td><strong>Totali</strong></td><td colspan='2'>$total_cmimi</td></tr>";
        echo "</table>";
    } else {
        echo "Shporta është e zbrazët.";
    }
} else {
    echo "Session 'ID' is not set. Please make sure you are logged in.";
}
// Process adding and removing items from the cart
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['buy'])) {
        // Get client information
        $id_klienti = $_SESSION['ID'];
        $sql_get_client_info = "SELECT emri, email, adresa FROM klienti WHERE id = $id_klienti";
        $result_client_info = $conn->query($sql_get_client_info);
        if ($result_client_info->num_rows > 0) {
            $row = $result_client_info->fetch_assoc();
            $emri = $row['emri'];
            $email = $row['email'];
            $adresa = $row['adresa'];

            // Insert into 'blerja' table
            $sql_insert_blerje = "INSERT INTO blerja (emri, email, adresa) VALUES ('$emri', '$email', '$adresa')";
            if ($conn->query($sql_insert_blerje) === TRUE) {
                $blerja_id = $conn->insert_id; // Get the ID of the inserted blerja record

                // Insert purchased items into 'blerja_artikulli'
                $sql_select_shporta = "SELECT id_artikulli FROM klienti_artikulli WHERE id_klienti = $id_klienti AND statusi = 'shtuar në shportë'";
                $result_select_shporta = $conn->query($sql_select_shporta);
                if ($result_select_shporta->num_rows > 0) {
                    while ($row = $result_select_shporta->fetch_assoc()) {
                        $id_artikulli = $row['id_artikulli'];
                        $sql_insert_blerja_artikulli = "INSERT INTO blerja_artikulli (id_blerja, id_artikulli) VALUES ($blerja_id, $id_artikulli)";
                        $conn->query($sql_insert_blerja_artikulli);
                    }
                }
                // Clear the shopping cart
                $sql_clear_cart = "DELETE FROM klienti_artikulli WHERE id_klienti = $id_klienti AND statusi = 'shtuar në shportë'";
                $conn->query($sql_clear_cart);
                echo "Blerja u krye me sukses.";
            } else {
                echo "Gabim në procesimin e blerjes: " . $conn->error;
            }
        } else {
            echo "Informacioni i klientit nuk u gjet.";
        }
    }
}

// Display the items in the cart
// Your existing code to display the items in the cart

if (isset($_SESSION['ID'])) {
    echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>";
    echo "<button type='submit' name='buy'>Buy</button>";
    echo "</form>";
}

$conn->close();
?>


