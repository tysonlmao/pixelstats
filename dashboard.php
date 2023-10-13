<?php
session_start();

// if userId not set, redirect to login.php
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pixelstats | dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/fonts.css">
    <link rel="stylesheet" href="./css/animate.css">
    <script src="./js/greetings.js"></script>
</head>

<body>
    <?php include "./templates/header.php" ?>
    <main>
        <div class="container">
            <div class="d-flex">
                <h2 id="greeting"></h2>
                <h2 class="px-2"><?php echo $_SESSION['userUsername'] ?></h2>
            </div>
        </div>
        <script src="./js/greetings.js"></script>

    </main>
    <?php include "./templates/footer.php" ?>
</body>

</html>