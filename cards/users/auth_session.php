<?php
    session_start();

    if(!isset($_SESSION["phone"])) {
        header("Location: login.php");
        exit();
    }
?>