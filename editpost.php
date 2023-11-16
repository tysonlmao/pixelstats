<?php
session_start();
require './includes/connection.inc.php';

// Check if the user is logged in and the post_id is set
if (!isset($_SESSION['userId'], $_GET['post_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$userId = $_SESSION['userId'];
$userRole = $_SESSION['userRole'] ?? null; // Assuming you set a userRole in $_SESSION when logging in
$postId = $_GET['post_id'];

// Fetch the post from the database
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :post_id");
$stmt->execute([':post_id' => $postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the post belongs to the user or if the user is an admin
if ($post && ($post['user_id'] == $userId || $userRole === 'Admin')) {
    // User is allowed to edit the post or is an admin
    // Show the form with the post data
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Edit Post</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="./css/style.css">
        <link rel="stylesheet" href="./css/fonts.css">
        <link rel="stylesheet" href="./css/animate.css">
    </head>

    <body>
        <?php include "./templates/header.php" ?>
        <main class="content">
            <article class="article-post mb-2">
                <form action="./includes/update_post.inc.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">

                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <label for="comment">Comment:</label><br>
                            <textarea id="comment" name="comment" rows="4" cols="50" required><?php echo htmlspecialchars($post['commentText']); ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" name="update-post" class="btn btn-primary">Update Post</button>
                    </div>
                </form>
            </article>
        </main>
    </body>

    </html>
<?php
} else {
    // User does not have permission to edit the post
    echo "You do not have permission to edit this post.";
    // Alternatively, redirect to another page or show a 403 Forbidden error
}
?>