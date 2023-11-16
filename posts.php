<?php
session_start();

include "./includes/connection.inc.php";

// Query to retrieve post data from the database
$sql = "SELECT id, main_account, imageUrl, commentText, timestamp FROM posts ORDER BY timestamp DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pixelstats | feed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/fonts.css">
</head>

<body>

    <body>
        <?php include "./templates/header.php" ?>
        <main class="content">
            <h2 class="fs-1 text-center mb-5">Latest Posts</h2>
            <div class="article-post mb-5">
                <form action="./includes/create_post.inc.php" method="post" enctype="multipart/form-data">

                    <label for="comment">Comment:</label><br>
                    <textarea id="comment" name="comment" rows="4" cols="50" required></textarea><br><br>

                    <label for="image">Upload Image:</label><br>
                    <input type="file" id="image" name="image" accept="image/*" required><br><br>
                    <input type="submit" name="post-submit" value="Create Post">
                </form>
            </div>
            <style>
                h3 {
                    color: #D0A2F7;
                    font-family: "VCR OSD Mono";
                    text-transform: uppercase;
                    margin: 0;
                }

                span {
                    font-size: 12px;
                    font-family: "Roboto Mono";
                    text-transform: uppercase;
                    color: #DCBFFF;
                }

                h4 {
                    font-family: "Lora", system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
                }
            </style>
            <?php foreach ($posts as $post) : ?>
                <article class="article-post mb-2">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <img src="https://mc-heads.net/avatar/<?php echo $post['main_account']; ?>" alt="<?php echo $post['main_account']; ?>" width="72px">
                                </div>
                                <div class="col-md-9">
                                    <h3><?php echo $post['main_account']; ?></h3>
                                    <p><?php echo $post['timestamp'] ?></p>
                                </div>
                                <div class="box-no-border">
                                    <h4><?php echo $post['commentText']; ?></h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="embed-responsive embed-responsive-16by9" style="max-height: 300px;">
                                <a href="<?php echo $post['imageUrl'] ?>" target="_blank">
                                    <img src="<?php echo $post['imageUrl']; ?>" alt="Your Image" class="embed-responsive-item">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <form action="editpost.php" method="get">
                            <input type="hidden" name="post_id" value='<?php echo $post['id']; ?>'>
                            <button type="submit" class="btn btn-primary">Edit post</button>
                        </form>
                        <?php if (isset($_SESSION['userRole']) && $_SESSION['userRole'] == 'Admin') : ?>
                            <form action="./includes/delete_post.inc.php" method="post">
                                <input type="hidden" name="post_id" value='<?php echo $post['id']; ?>'>
                                <button type="submit" name="erase-post" class="btn btn-danger mx-2">Erase Post</button>
                            </form>
                    </div>
                <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </main>
        <?php include "./templates/footer.php" ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</body>

</html>