<?php
// Start the session
session_start();

// Define variables and initialize with empty values
$name = $email = $phone = $password = $confirm_password = "";
$name_err = $email_err = $phone_err = $password_err = $confirm_password_err = $terms_err = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "db.php"; // Ensure this file contains the database connection

    // Validate full name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter your full name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate email address
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email address.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email address format.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate phone number
    if (empty(trim($_POST["phone"]))) {
        $phone_err = "Please enter your phone number.";
    } elseif (!preg_match('/^\d{10}$/', trim($_POST["phone"]))) {
        $phone_err = "Phone number must be 10 digits.";
    } else {
        $phone = trim($_POST["phone"]);
    }

    // Validate password
    if (empty(trim($_POST["password_login"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password_login"])) < 6) {
        $password_err = "Password must be at least 6 characters.";
    } else {
        $password = trim($_POST["password_login"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm your password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if ($password !== $confirm_password) {
            $confirm_password_err = "Passwords do not match.";
        }
    }

    // Validate terms checkbox
    if (!isset($_POST["terms_checkbox"])) {
        $terms_err = "You must agree to the terms and conditions.";
    }

    // Check for any errors before inserting into the database
    if (empty($name_err) && empty($email_err) && empty($phone_err) && empty($password_err) && empty($confirm_password_err) && empty($terms_err)) {
        // Check if phone number or email already exists in user_reg
        $sql = "SELECT id FROM user_reg WHERE phone = ? OR email = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $phone, $email);

            if ($stmt->execute()) {
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $phone_err = "A user with this phone number or email already exists.";
                } else {
                    // Prepare to insert into user_reg
                    $sql = "INSERT INTO user_reg (fullname, email, phone, password) VALUES (?, ?, ?, ?)";

                    if ($stmt = $conn->prepare($sql)) {
                        $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Using bcrypt for password hashing
                        $stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);

                        if ($stmt->execute()) {
                            // Get the last inserted user ID
                            $user_id = $stmt->insert_id;

                            // Prepare to insert or update user_valid
                            $sql = "INSERT INTO user_valid (id, phone_number, password) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE password = VALUES(password)";

                            if ($stmt = $conn->prepare($sql)) {
                                $stmt->bind_param("iss", $user_id, $phone, $hashed_password);

                                if ($stmt->execute()) {
                                    // Redirect to login page
                                    header("location: login.php");
                                    exit();
                                } else {
                                    echo "Oops! Something went wrong. Please try again later.";
                                }
                            }
                        } else {
                            echo "Oops! Something went wrong. Please try again later.";
                        }
                    }
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CaféBristo |Registration</title>
    <link rel="icon" href="../assets/images/rlogo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/logReg.css">
</head>

<body>
    <?php
    //  include "../includes/header3.php"; 
    ?>
    <section class="form-page">
        <div class="formReg">
            <h2>Café Registration</h2>
            <form id="registrationForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
                    <span class="error"><?php echo $name_err; ?></span>
                </div>
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    <span class="error"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:<span style="color: red;">*</span></label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
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
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <div class="password-input">
                        <input type="password" id="confirm_password" name="confirm_password">
                        <i class="fa fa-eye-slash" id="eye-slash-confirm" onclick="togglePasswordVisibility('confirm_password')"></i>
                        <i class="fa fa-eye" id="eye-confirm" style="display: none;" onclick="togglePasswordVisibility('confirm_password')"></i>
                    </div>
                    <span class="error"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-term-group">
                    <input type="checkbox" id="terms_checkbox" name="terms_checkbox">
                    <label for="terms_checkbox">I agree to the <a href="../pages/privacy-policy.php">terms & conditions</a></label>
                </div>
                <div id="errorMessages"><?php echo $terms_err; ?></div>
                <div class="form-group text-center">
                    <button type="submit">Register</button>
                </div>
                <hr>
                <div class="reglog">
                    <p>Already have an account? <a class="alt-log" href="login.php">Login here!</a></p>
                </div>
            </form>
        </div>
    </section>
    <script src="../assets/js/script.js"></script>
</body>

</html>
