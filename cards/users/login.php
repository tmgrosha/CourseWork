<?php
session_start(); // Start session to store user login status

// Define variables and initialize with empty values
$phone = $password = "";
$phone_err = $password_err = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "db.php"; // Ensure this path is correct

    // Validate phone number
    if (empty(trim($_POST["phone_login"]))) {
        $phone_err = "Please enter your phone number.";
    } else {
        $phone = trim($_POST["phone_login"]);
        // Check if phone number is valid (basic validation)
        if (!preg_match('/^\d{10}$/', $phone)) {
            $phone_err = "Please enter a valid phone number.";
        }
    }

    // Validate password
    if (empty(trim($_POST["password_login"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password_login"]);
    }

    // Process login if there are no validation errors
    if (empty($phone_err) && empty($password_err)) {
        // Prepare a SELECT statement for user_valid
        $sql = "SELECT phone_number, password, privilege FROM user_valid WHERE phone_number = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $phone);

            if ($stmt->execute()) {
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($db_phone, $hashed_password, $privilege);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, start a new session
                            $_SESSION["phone"] = $db_phone;
                            $_SESSION["privilege"] = $privilege; // Store privilege in session

                            // Redirect user based on privilege
                            if ($privilege === 'admin') {
                                header("location: admin_panel.php");
                            } else {
                                header("location: profile.php");
                            }
                            exit();
                        } else {
                            $password_err = "Invalid credentials.";
                        }
                    }
                } else {
                    $phone_err = "Invalid credentials.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }

        // Close connection
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CaféBristo | Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/logReg.css">
    <style>
        .error {
            color: red;
            font-size: 14px;
            display: block;
        }
    </style>
</head>
<body>
    <?php
    //  include "../includes/header3.php";
     ?>

    <section class="form-page">
        <div class="formlog">
            <h2>Café Login</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="phone_login">Phone Number:</label>
                    <input type="tel" id="phone_login" name="phone_login" placeholder="98XXXXXXXX" value="<?php echo htmlspecialchars($phone); ?>">
                    <span class="error"><?php echo $phone_err; ?></span>
                </div>
                <div class="form-group">
                    <label for="password_login">Password:</label>
                    <div class="password-input">
                        <input type="password" id="password_login" name="password_login">
                        <i class="fa fa-eye-slash" id="eye-slash" onclick="togglePasswordVisibility()"></i>
                        <i class="fa fa-eye" id="eye" style="display: none;" onclick="togglePasswordVisibility()"></i>
                    </div>
                    <span class="error"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group text-center">
                    <button type="submit">Login</button>
                </div>
                <hr>
                <div class="reglog">
                    <p>Don't have an account? <a class="alt-log" href="registration.php">Register now!</a></p>
                </div>
            </form>
        </div>
    </section>

    <script src="../assets/js/script.js"></script>
</body>
</html>
