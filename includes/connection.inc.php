<?php

include "config.php";

try {
    $pdo = new PDO('mysql:host=localhost;dbname=' . PDO_DBNAME, PDO_DBUSER, PDO_DBPASSWORD);
} catch (PDOException $e) {
    die("Error connecting to the server: $e");
}
