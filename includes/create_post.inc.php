<?php
session_start();
require './connection.inc.php';

if (isset($_POST['post-submit'])) {
    // Check if the user is logged in
    if (!isset($_SESSION['userId'])) {
        header("Location: ../login.php?error=notloggedin");
        exit();
    }

    $currentUserId = $_SESSION['userId']; // Use the userId from the session
    $comment = $_POST['comment']; // Store the comment from the form

    // Validate the comment field
    if (empty($comment)) {
        header("Location: ../posts.php?error=emptycomment");
        exit();
    }

    // Process the image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];
        $imageName = time() . '-' . $image['name']; // Prefix the file name with epoch time
        $imageTempName = $image['tmp_name'];
        $uploadPath = '../uploads/' . basename($imageName); // Ensure you have an 'uploads' folder in the root

        if (move_uploaded_file($imageTempName, $uploadPath)) {
            $imageUrl = 'uploads/' . $imageName; // Relative URL to use in the database
        } else {
            header("Location: ../posts.php?error=imageupload");
            exit();
        }
    } else {
        $imageUrl = NULL; // Set image URL to NULL if no image is uploaded
    }

    // Prepare SQL statement for inserting the post
    $sql = "INSERT INTO posts (user_id, main_account, imageUrl, commentText, timestamp) VALUES (:user_id, (SELECT main_account FROM user_accounts WHERE user_id = :user_id), :imageUrl, :commentText, NOW())";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':user_id', $currentUserId);
    $stmt->bindParam(':imageUrl', $imageUrl);
    $stmt->bindParam(':commentText', $comment);

    // Execute the statement and handle the result
    if ($stmt->execute()) {
        header("Location: ../posts.php?success=posted");
        exit();
    } else {
        header("Location: ../posts.php?error=databaseerror");
        exit();
    }
} else {
    header("Location: ../posts.php?error=forbidden");
    exit();
}
