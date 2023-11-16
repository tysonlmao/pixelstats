<?php
session_start();
require './connection.inc.php';

if (isset($_POST['erase-post'])) :
    // Check if the user is logged in and is an admin
    if (!isset($_SESSION['userId']) || $_SESSION['userRole'] != 'Admin') :
        header("Location: ../login.php?error=unauthorized");
        exit();
    endif;

    // Cast the post ID to integer to ensure proper data type is sent to SQL
    $postId = trim($_POST['post_id']);
    try {
        $sql = "DELETE FROM posts WHERE id = :post_id";
        $stmt = $pdo->prepare($sql);
        if ($stmt == false) :
            // debug
            header("Location: ../posts.php?error=stmtfalse");
            exit("STMT FALSE");
        endif;
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $result = $stmt->execute();
        if ($result == false) :
            // debug
            header("Location: ../posts.php?error=stmtfalse");
            exit("STMT FALSE");
        endif;
        if ($stmt->rowCount() > 0) :
            header("Location: ../posts.php?success=postdeleted");
            exit();
        else :
            // If no rows affected, no post was deleted
            header("Location: ../posts.php?error=nopostdeleted");
            exit();
        endif;
    } catch (PDOException $e) {
        // Catch and handle any PDO errors
        header("Location: ../posts.php?error=databaseerror&message=" . urlencode($e->getMessage()));
        exit();
    }
else :
    header("Location: ../posts.php?error=forbidden");
    exit();
endif;
