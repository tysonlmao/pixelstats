<?php
session_start();

if (isset($_POST['newUsername']) && isset($_POST['newRole']) && isset($_POST['userId'])) {
    $newUsername = $_POST['newUsername'];
    $newRole = $_POST['newRole'];
    $userId = $_POST['userId'];

    require "../../includes/connection.inc.php"; // Include your database connection script

    $sql = "UPDATE users SET username = :username, user_role = :role WHERE id = :userId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $newUsername, PDO::PARAM_STR);
    $stmt->bindParam(':role', $newRole, PDO::PARAM_STR);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Update was successful
        header("Location: /admin/?success=update");
        exit();
    } else {
        // Update failed
        header("Location: /admin/?error=update");
        exit();
    }
} else {
    // Redirect or handle errors if input is not set
    header("Location: /admin/?error=missing_data");
    exit();
}
