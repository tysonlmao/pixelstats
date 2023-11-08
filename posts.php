<?php
session_start();

include "./includes/connection.inc.php";

// Query to retrieve post data from the database
$sql = "SELECT main_account, imageUrl, commentText, timestamp FROM posts ORDER BY timestamp DESC";
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
            <div class="box-no-border text-center">
                <h2 class="fs-1">Latest Posts</h2>
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
                                <img src="<?php echo $post['imageUrl']; ?>" alt="Your Image" class="embed-responsive-item">
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </main>
        <?php include "./templates/footer.php" ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</body>

</html>