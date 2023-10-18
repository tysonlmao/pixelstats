<?php
// Include your database connection code here
require_once "../includes/connection.inc.php";

if (!defined("ABSPATH")) :
    die("File cannot be directly accessed.");
endif;

// Check if you have received the user ID for termination
if (isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];

    try {
        // Start a database transaction
        $pdo->beginTransaction();

        // Step 1: Delete the user from the 'users' table
        $deleteUserSQL = "DELETE FROM users WHERE id = :userId";
        $deleteUserStmt = $pdo->prepare($deleteUserSQL);
        $deleteUserStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $deleteUserStmt->execute();

        // Step 2: Delete associated rows in the 'user_accounts' table
        $deleteUserAccountsSQL = "DELETE FROM user_accounts WHERE user_id = :userId";
        $deleteUserAccountsStmt = $pdo->prepare($deleteUserAccountsSQL);
        $deleteUserAccountsStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $deleteUserAccountsStmt->execute();

        // Commit the transaction
        $pdo->commit();

        // Send a success response
        echo "User terminated successfully.";
    } catch (PDOException $e) {
        // Handle any database errors and roll back the transaction
        $pdo->rollBack();
        echo "An error occurred while terminating the user: " . $e->getMessage();
    }
} else {
    echo "User ID not provided for termination.";
}
