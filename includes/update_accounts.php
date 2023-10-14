<?php
session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: /login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require "./includes/connection.inc.php"; // Include your database connection code

    $userId = $_SESSION['userId'];
    $mainAccount = $_POST['mainAccount'];
    $altAccount = $_POST['altAccount'];

    $sql = "UPDATE user_accounts SET main_account = :mainAccount, alt_account = :altAccount WHERE user_id = :userId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':mainAccount', $mainAccount, PDO::PARAM_STR);
    $stmt->bindParam(':altAccount', $altAccount, PDO::PARAM_STR);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: /settings.php");
        exit();
    } else {
        echo "Account update failed.";
    }
}
