<?php
// Start session and check if user is logged in
include 'auth_session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <link rel="stylesheet" href="../assets/css/includes.css"> -->
    <!-- <link rel="stylesheet" href="../assets/css/styles.css"> -->
    <!-- <link rel="stylesheet" href="../assets/css/includes.css"> -->
    <link rel="stylesheet" href="../assets/css/includes.css">
    <link rel="stylesheet" href="../assets/css/logReg.css">

</head>

<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <a href="index.php" class="brand-logo"><img src="../assets/images/rlogo.png" alt="CafeBristo"></a>
                <div class="navbar-links">
                    <ul class="nav-links">
                        <li><a href="profile.php">Edit Profile</a></li>
                        <li><a href="menu.php">place order</a></li>
                        <li><a href="booking.php">Book table</a></li>
                    </ul>
                    <div class="auth-links">
                        <a href="../users/logout.php" class="login-link"><i class="fas fa-sign-in-alt"></i> Logout</a>
                        <div class="cart">
                            <a href="cart.php"><i class="fas fa-shopping-cart"></i></a>
                        </div>
                    </div>
                </div>

                <a href="#" class="sidenav-trigger menu" onclick="toggleNav()">
                    <i class="fas fa-bars"></i>
                </a>
                <a href="#" class="sidenav-trigger close" onclick="toggleNav()">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </nav>
        <ul class="sidenav" id="mobile-nav">
            <li><a href="profile.php">Edit Profile</a></li>
            <li><a href="menu.php">place order</a></li>
            <li><a href="booking.php">Book table</a></li>
            <li><a href="../users/logout.php">Logout</a></li>
            <li><a href="cart.php"><i class="fas fa-shopping-cart"></i></a></li>
        </ul>
    </header>
    <main>
        <section class="menu-page">
            <div class="reserve">
                <h1>Table Reservation</h1>
                <form action="reserve_table.php" method="post">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <label for="phone">Phone:</label>
                    <input type="tel" id="phone" name="phone" required>
                    <div class="required-details">
                        <div>
                            <label for="date">Date:</label>
                            <input type="date" id="date" name="date" required>
                        </div>
                        <div>
                            <label for="time">Time:</label>
                            <input type="time" id="time" name="time" required>
                        </div>
                        <div>
                            <label for="people">Number of people:</label>
                            <input type="number" id="people" name="people" min="2" required>
                        </div>
                    </div>
                    <label for="special_requests">Special Requests:</label>
                    <textarea id="special_requests" name="special_requests" rows="7">This is my sister's birthday. My sister and I will be there just in time. I want you to bring a surprise cake for us</textarea>
                    <br>
                    <input type="submit" value="Submit">
                </form>
            </div>
        </section>
    </main>
    <?php
    include('../includes/footer.php');
    ?>
    <script src="../assets/js/script.js"></script>
</body>

</html>