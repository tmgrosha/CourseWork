<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cafeBristo | Booking</title>
    <link rel="stylesheet" href="../assets/css/logReg.css">

</head>

<body>
    <?php
    include "../includes/header2.php"
    ?>
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
</body>

</html>