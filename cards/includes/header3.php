<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../assets/images/rlogo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/includes.css">
</head>
<body>
<header>
    <nav class="navbar">
        <div class="container">
            <a href="../index.php" class="brand-logo"><img src="../assets/images/rlogo.png" alt="CafeBristo"></a>
            <div class="navbar-links">
                <ul class="nav-links">
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../pages/menu.php">Menu</a></li>
                    <li><a href="../pages/about.php">About</a></li>
                    <li><a href="../pages/contact.php">Contact</a></li>
                    <li><a href="../pages/booking.php">Book table</a></li>
                </ul>
                <div class="auth-links">
                    <a href="../users/registration.php" class="register-link"><i class="fas fa-user-plus"></i> Register</a>
                    <a href="../users/login.php" class="login-link"><i class="fas fa-sign-in-alt"></i> Login</a>
                    <div class="cart">
                        <a href="../pages/cart.php"><i class="fas fa-shopping-cart"></i></a>
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
        <li><a href="../index.php">Home</a></li>
        <li><a href="../pages/menu.php">Menu</a></li>
        <li><a href="../pages/about.php">About</a></li>
        <li><a href="../pages/contact.php">Contact</a></li>
        <li><a href="../users/registration.php">Register</a></li>
        <li><a href="../users/login.php">Login</a></li>
        <li><a href="../pages/cart.php"><i class="fas fa-shopping-cart"></i></a></li>
    </ul>
    
</header>
<script src="../assets/js/script.js"></script>
