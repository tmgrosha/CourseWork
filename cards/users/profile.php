<?php
// Start session and check if user is logged in
include 'auth_session.php';

require_once "db.php";

// Initialize variables
$phone = $_SESSION["phone"];
$name = $email = $current_password = $new_password = $confirm_password = "";
$name_err = $email_err = $phone_err = $current_password_err = $new_password_err = $confirm_password_err = "";
$user_not_found = "";
$profile_update_success = "";
$password_update_success = "";

// Fetch user data from the database
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $sql = "SELECT fullname, email, phone FROM user_reg WHERE phone = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $phone);
        if ($stmt->execute()) {
            $stmt->bind_result($name, $email, $phone);
            if (!$stmt->fetch()) {
                $user_not_found = "No user found.";
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
}

// Update profile details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_profile'])) {
        // Validate and update profile information
        $name = trim($_POST["name"]);
        $email = trim($_POST["email"]);
        $phone = trim($_POST["phone"]);

        if (empty($name)) {
            $name_err = "Please enter your full name.";
        }

        if (empty($email)) {
            $email_err = "Please enter your email address.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Invalid email address format.";
        }

        if (empty($phone)) {
            $phone_err = "Please enter your phone number.";
        } elseif (!preg_match('/^\d{10}$/', $phone)) {
            $phone_err = "Phone number must be 10 digits.";
        }

        if (empty($name_err) && empty($email_err) && empty($phone_err)) {
            // Update user profile details
            if ($stmt = $conn->prepare("UPDATE user_reg SET fullname = ?, email = ?, phone = ? WHERE phone = ?")) {
                $stmt->bind_param("ssss", $name, $email, $phone, $phone);
                if ($stmt->execute()) {
                    $profile_update_success = "Profile updated successfully.";
                    $_SESSION["phone"] = $phone;
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
                $stmt->close();
            }
        }
    } elseif (isset($_POST['change_password'])) {
        // Validate and change password
        $current_password = trim($_POST["current_password"]);
        $new_password = trim($_POST["new_password"]);
        $confirm_password = trim($_POST["confirm_password"]);

        if (empty($current_password)) {
            $current_password_err = "Please enter your current password.";
        }

        if (strlen($new_password) < 6) {
            $new_password_err = "New password must be at least 6 characters.";
        } elseif ($new_password !== $confirm_password) {
            $confirm_password_err = "Passwords do not match.";
        }

        if (empty($current_password_err) && empty($new_password_err) && empty($confirm_password_err)) {
            // Check current password
            if ($stmt = $conn->prepare("SELECT password FROM user_valid WHERE phone_number = ?")) {
                $stmt->bind_param("s", $phone);
                if ($stmt->execute()) {
                    $stmt->bind_result($hashed_password);
                    if ($stmt->fetch() && password_verify($current_password, $hashed_password)) {
                        // Password is correct, proceed to update
                        if ($stmt = $conn->prepare("UPDATE user_valid SET password = ? WHERE phone_number = ?")) {
                            $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);
                            $stmt->bind_param("ss", $hashed_new_password, $phone);
                            if ($stmt->execute()) {
                                $password_update_success = "Password updated successfully.";
                            } else {
                                echo "Oops! Something went wrong. Please try again later.";
                            }
                            $stmt->close();
                        }
                    } else {
                        $current_password_err = "Current password is incorrect.";
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
                $stmt->close();
            }
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - CaféBristo</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/includes.css">
    <link rel="stylesheet" href="../assets/css/profile.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <a href="index_user.php" class="brand-logo"><img src="../assets/images/rlogo.png" alt="CafeBristo"></a>
                <div class="navbar-links">
                    <ul class="nav-links">
                        <li><a href="profile.php">Edit Profile</a></li>
                        <li><a href="menu.php">Place Order</a></li>
                        <li><a href="booking.php">Book Table</a></li>
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
            <li><a href="menu.php">Place Order</a></li>
            <li><a href="booking.php">Book Table</a></li>
            <li><a href="../users/logout.php">Logout</a></li>
            <li><a href="cart.php"><i class="fas fa-shopping-cart"></i></a></li>
        </ul>
    </header>
    <section class="profile-page">
        <h1>Welcome <?php echo htmlspecialchars($name); ?></h1>
        <?php if (!empty($user_not_found)) echo '<p class="error">' . $user_not_found . '</p>'; ?>
        <div class="profile-container">
            <!-- Profile Info Display -->
            <div class="profile-info">
                <?php if (empty($user_not_found)) : ?>
                    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($name); ?></p>
                    <p><strong>Email Address:</strong> <?php echo htmlspecialchars($email); ?></p>
                    <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($phone); ?></p>
                    <button id="edit-button">Edit Profile</button>
                    <button id="change-password-button">Change Password</button>
                <?php endif; ?>
            </div>
            <!-- Edit Profile Form -->
            <div class="edit-form">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <?php if (!empty($profile_update_success)) echo '<p class="success-message">' . $profile_update_success . '</p>'; ?>

                    <div class="form-group">
                        <label for="name">Full Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
                        <span class="error"><?php echo $name_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                        <span class="error"><?php echo $email_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                        <span class="error"><?php echo $phone_err; ?></span>
                    </div>
                    <input type="submit" name="update_profile" value="Update Profile">
                </form>
            </div>
            <!-- Change Password Form -->
            <div class="change-password-form">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <?php if (!empty($password_update_success)) echo '<p class="success-message">' . $password_update_success . '</p>'; ?>

                    <div class="form-group">
                        <label for="current_password">Current Password:</label>
                        <input type="password" id="current_password" name="current_password">
                        <span class="error"><?php echo $current_password_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password:</label>
                        <input type="password" id="new_password" name="new_password">
                        <span class="error"><?php echo $new_password_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password">
                        <span class="error"><?php echo $confirm_password_err; ?></span>
                    </div>
                    <input type="submit"class name="change_password" value="Change Password">
                </form>
            </div>
        </div>
    </section>
    <?php include "../includes/footer.php"; ?>

    <script src="../assets/js/script.js">
    </script>
    <script>
        document.getElementById('edit-button').addEventListener('click', function() {
            document.querySelector('.edit-form').classList.add('active');
            document.querySelector('.change-password-form').classList.remove('active');
        });

        document.getElementById('change-password-button').addEventListener('click', function() {
            document.querySelector('.change-password-form').classList.add('active');
            document.querySelector('.edit-form').classList.remove('active');
        });

        document.getElementById('cancel-edit').addEventListener('click', function() {
            document.querySelector('.edit-form').classList.remove('active');
        });

        document.getElementById('cancel-password').addEventListener('click', function() {
            document.querySelector('.change-password-form').classList.remove('active');
        });
    </script>
</body>

</html>