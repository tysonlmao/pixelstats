<?php
include "connection.inc.php";

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Start a database transaction
    $pdo->beginTransaction();

    try {
        // Delete the user from the user_accounts table
        $sqlDeleteUserAccounts = "DELETE FROM user_accounts WHERE user_id = :userId";
        $stmtDeleteUserAccounts = $pdo->prepare($sqlDeleteUserAccounts);
        $stmtDeleteUserAccounts->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmtDeleteUserAccounts->execute();

        // Delete the user from the users table
        $sqlDeleteUser = "DELETE FROM users WHERE id = :userId";
        $stmtDeleteUser = $pdo->prepare($sqlDeleteUser);
        $stmtDeleteUser->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmtDeleteUser->execute();

        // Commit the transaction
        $pdo->commit();

        // Termination was successful
        header("Location: ../admin/index.php?delete=success&id=$userId");
        exit;
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $pdo->rollBack();

        // Termination failed
        echo "Failed to terminate the user: " . $e->getMessage();
    }
} else {
    // Handle invalid or missing user ID
    echo "Invalid or missing user ID.";
}
