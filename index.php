<?php
session_start();

$eshte_login = !empty($_SESSION['ID']);

// Redirect the user to the login page if not logged in and trying to add items to the cart
if (!$eshte_login && $_SERVER["REQUEST_METHOD"] == "POST") {
    header("Location: login.html");
    exit();
}

// Kontrolloni nëse është kyqur një përdorues
if ($eshte_login) {
    // Merrni emrin e përdoruesit nga sesioni
    $emri_perdoruesit = $_SESSION['username'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online shop</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<header>
    <div class="container">
        <h1>Shitorja Online</h1>
        <nav>
            <ul>
                <!-- Search bar -->
                <form class="search-container" action="search.php" method="GET">
                    <input class="search-input" type="text" name="query" placeholder="Search products...">
                    <button class="search-button" type="submit"><i class="fas fa-search"></i> Search</button>
                </form>

                <?php if ($eshte_login) { ?>
                    <?php if ($emri_perdoruesit != "admin") { ?>
                        <li class="icon"><a href="shporta.php"><i class="fas fa-shopping-cart"></i></a></li> <!-- Cart icon -->
                    <?php } else { ?>
                        <li class="icon"><a href="blerjet.php"><i class="fas fa-shopping-cart"> blerjet</i></a></li> <!-- Purchase history icon -->
                    <?php } ?>
                    <li class="icon"><span><i class="fas fa-user"></i></span></li>
                    <li class="icon"><a href="logout.php"><i class="fas fa-sign-out-alt"></i></a></li> <!-- Logout icon -->
                <?php } else { ?>
                    <li class="icon"><a href="login.html"><i class="fas fa-sign-in-alt"></i> Login</a></li> <!-- Login icon -->
                <?php } ?>
            </ul>
        </nav>
    </div>
</header>

<div class="container">
    <h2>Mirësevini në Dyqanin tonë Online</h2><br>
    <p>Zbuloni produktet dhe ofertat tona më të fundit.</p>
</div>

<section class="products">
    <div class="container">
        <h2>Produktet Tona</h2>
        <div class="product">
            <img src="laptop.jpeg" alt="Product 1" width="50">
            <h3>Laptop Lenovo IdeaPad 3 15ALC6, 15.6", AMD Ryzen 5, 8GB RAM, 512GB SSD, AMD  </h3>
            <p>600 €</p>
            <?php if ($eshte_login) { ?>
                <button type="button" onclick="shtoArtikull('Laptop Lenovo IdeaPad 3', this)">Shto në shportë</button>
            <?php } ?>
        </div>

        <div class="product">
            <h3>Kompjuter Gjirafa50 FenixSeries 23, Intel Core i5-14400F, 16GB RAM, 500GB SSD  </h3>
            <p>890 €</p>
            <?php if ($eshte_login) { ?>
                <button type="button" onclick="shtoArtikull('Kompjuter FenixSeries 23', this)">Shto në shportë</button>
            <?php } ?>
        </div>
        <!-- Add more products as needed -->
    </div>
</section>

<footer>
    <div class="container">
        <p>&copy; 2024 Online Shop. All rights reserved.</p>
    </div>
</footer>

<script>
    function shtoArtikull(emriArtikullit, button) {
        // Bëni një kërkesë AJAX për të shtuar artikullin në bazën e të dhënave
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                button.style.backgroundColor = "green"; // Ndryshimi i ngjyrës së butonit në gjelbër
            }
        };
        xhttp.open("POST", "shto_artikull.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("artikull=" + emriArtikullit);
    }
</script>
<script src="//code.tidio.co/vudjsjpcvgxsnaibkzbgtqy0l5lf1wab.js" async></script>
</body>
</html>