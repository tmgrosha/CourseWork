<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles2.css">
    <title>cafeBristo | Contact Us</title>
</head>

<body>
    <?php
    include "../includes/header2.php"
    ?>
    <main>
        <section class="contact-page">
            <div class="query-form">
                <h2>Contact Form</h2>
                <form action="#" method="post" id="contactForm">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required><br>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="example@example.com" required><br>
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="4" placeholder="Write your query here...." required></textarea><br>
                    <input type="submit" value="Submit">
                </form>
            </div>
            <div class="contact-info">
                <ul class="contact-social-icons">
                    <li><a href="#" class="icon-link"><i class="fab fa-twitter"></i></a></li>
                    <li><a href="#" class="icon-link"><i class="fab fa-facebook-f"></i></a></li>
                    <li><a href="#" class="icon-link"><i class="fab fa-instagram"></i></a></li>
                    <li><a href="#" class="icon-link"><i class="fab fa-discord"></i></a></li>
                    <li><a href="#" class="icon-link"><i class="fab fa-github"></i></a></li>
                </ul>
                <p>123 Cafe Street, Cityville, State, ZIP</p>
                <p>Phone: +1 (234) 567-8901</p>
                <p>Email: info@cafename.com</p>
                <p>Opening Hours:</p>
                <ul>
                    <li>Monday - Friday: 8:00 AM - 8:00 PM</li>
                    <li>Saturday - Sunday: 9:00 AM - 6:00 PM</li>
                </ul>
            </div>
        </section>
        <section class="map" id="map">
            <div class="location">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14822.208405916585!2d72.1521431!3d21.7588833!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395f5a703ba199af%3A0xafaa594be7928ffd!2sSumeru%20Arc!5e0!3m2!1sen!2snp!4v1721239281754!5m2!1sen!2snp" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="location-info">
                <h1>Our Location</h1>
                <p>123 Cafe Street, Cityville, State, ZIP</p>
                <p>123 Cafe Street, Cityville, State, ZIP</p>
                <p>123 Cafe Street, Cityville, State, ZIP</p>
                <p>123 Cafe Street, Cityville, State, ZIP</p>
            </div>

        </section>
    </main>

    <?php
    include "../includes/footer.php"
    ?>

</body>

</html>