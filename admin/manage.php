<?php
session_start();

if (!isset($_SESSION['userId'])) :
    header("Location: ../login.php");
    exit();
endif;

if (isset($_SESSION['userRole']) && $_SESSION['userRole'] !== 'Admin') :
    header("Location: ../index.php?error=forbidden");
    exit();
endif;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>manage user | pixelstats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/fonts.css">
    <link rel="stylesheet" href="../css/animate.css">
</head>

<body>
    <?php include "../templates/header.php" ?>
    <div class="content">
        <div class="box-no-border">
            <form action="../includes/update_user.inc.php" method="POST">

            </form>
        </div>
    </div>
    <?php include "../templates/footer.php" ?>
</body>

</html>