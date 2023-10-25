<?php
session_start(); // Start or resume the session

if (isset($_SESSION['userId'])) :
    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    header("Location: login.php");
    exit();
else :
    header("Location: login.php");
    exit();
endif;
