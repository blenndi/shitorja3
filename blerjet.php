<?php
// Përfshij lidhjen me bazën e të dhënave
$servername = "localhost";
$username = "root";
$password = "";
$database = "shitorja";

// Krijo lidhjen me bazën e të dhënave
$connection = mysqli_connect($servername, $username, $password, $database);

// Kontrollo lidhjen
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Merrni listën e përdoruesve nga baza e të dhënave
$query = "SELECT * FROM klienti";
$result = mysqli_query($connection, $query);
$klientiit = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Nëse forma është paraqitur
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kontrollo për zgjedhjen e përdoruesit
    if (!empty($_POST['id'])) {
        // Merrni ID-në e përdoruesit të zgjedhur
        $id = $_POST['id'];

        // Merrni të dhënat e blerjeve për përdoruesin e zgjedhur
        $query_blerjet = "SELECT * FROM blerja WHERE id = $id";
        echo "Query: $query_blerjet"; // Print the query for debugging

        $result_blerjet = mysqli_query($connection, $query_blerjet);

        // DEBUG: Check if there is an error in the query execution
        if (!$result_blerjet) {
            echo "Error: " . mysqli_error($connection); // Print the MySQL error message
        } else {
            // Fetch the results only if the query was successful
            $blerjet = mysqli_fetch_all($result_blerjet, MYSQLI_ASSOC);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blerjet</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Shiko Blerjet</h1>
    <form method="post">
        <label for="klienti_id">Zgjidh një përdorues:</label>
        <select name="klienti_id" id="klienti_id">
            <?php foreach ($klientiit as $klienti) { ?>
                <option value="<?php echo $klienti['id']; ?>"><?php echo $klienti['emri']; ?></option>
            <?php } ?>
        </select>
        <button type="submit">Shiko Blerjet</button>
    </form>

    <?php if (!empty($blerjet)) { ?>
        <h2>Blerjet e zgjedhura</h2>
        <table>
            <thead>
                <tr>
                    <th>Emri i Produktit</th>
                    <th>Cmimi</th>
                    <th>Data e Blerjes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($blerjet as $blerje) { ?>
                    <tr>
                        <td><?php echo $blerje['emri_produktit']; ?></td>
                        <td><?php echo $blerje['cmimi']; ?></td>
                        <td><?php echo $blerje['data_blerjes']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>

</body>
</html>
