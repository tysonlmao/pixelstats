<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=pixelstats', 'root', 'root');
} catch (PDOException $e) {
    die("Error connecting to the server: $e");
}
