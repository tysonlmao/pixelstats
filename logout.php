<?php
session_start(); // Start or resume the session

if (isset($_SESSION['userId'])) :
    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to a page after logout (e.g., the login page)
    header("Location: login.php");
    exit();
else :
    // If the user is not logged in, redirect them to the login page
    header("Location: login.php");
    exit();
endif;
