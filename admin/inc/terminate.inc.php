<?php
// Include your database connection code here
require_once "../includes/connection.inc.php";

// Check if you have received the user ID for termination
if (isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];

    try {
        $pdo->beginTransaction();

        $deleteUserSQL = "DELETE FROM users WHERE id = :userId";
        $deleteUserStmt = $pdo->prepare($deleteUserSQL);
        $deleteUserStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $deleteUserStmt->execute();

        $deleteUserAccountsSQL = "DELETE FROM user_accounts WHERE user_id = :userId";
        $deleteUserAccountsStmt = $pdo->prepare($deleteUserAccountsSQL);
        $deleteUserAccountsStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $deleteUserAccountsStmt->execute();

        $pdo->commit();

        // Send a success response
        echo "User terminated successfully.";
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "An error occurred while terminating the user: " . $e->getMessage();
    }
} else {
    echo "User ID not provided for termination.";
}
