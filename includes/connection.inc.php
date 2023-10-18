<?php
if (!defined('RECAPTCHA_SECRET_KEY')) {
    include "../includes/config.inc.php";
}

$dbname = PDO_DBNAME;
$dbuser = PDO_DBUSER;
$dbpassword = PDO_DBPASSWORD;
try {
    $pdo = new PDO("mysql:host=localhost;dbname=$dbname", $dbuser, $dbpassword);
} catch (PDOException $e) {
    die("Error connecting to the server: $e");
}
