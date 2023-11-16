<?php
session_start();
require './connection.inc.php';

if (isset($_POST['update-post'], $_POST['post_id'], $_POST['comment'])) {
    // Sanitize the input
    $postId = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS);
    $userId = $_SESSION['userId'];

    // Fetch the original post from the database to check permissions
    $stmt = $pdo->prepare("SELECT user_id FROM posts WHERE id = :post_id");
    $stmt->execute([':post_id' => $postId]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post) {
        // Check if the user is allowed to update the post (either they are an admin or the original poster)
        if ($post['user_id'] == $userId || (isset($_SESSION['userRole']) && $_SESSION['userRole'] === 'Admin')) {
            // Prepare the update statement
            $updateStmt = $pdo->prepare("UPDATE posts SET commentText = :comment WHERE id = :post_id");
            // Execute the update statement
            if ($updateStmt->execute([':comment' => $comment, ':post_id' => $postId])) {
                header("Location: ../posts.php?update=success");
                exit();
            } else {
                // Handle error in update
                header("Location: ../editpost.php?post_id=$postId&error=updatefailed");
                exit();
            }
        } else {
            // User does not have permission to update the post
            header("Location: ../posts.php?error=unauthorized");
            exit();
        }
    } else {
        // Post not found
        header("Location: ../posts.php?error=postnotfound");
        exit();
    }
} else {
    // Redirect them back to the edit form
    header("Location: ../editpost.php?error=invalidrequest");
    exit();
}
