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
    <title>pixelstats | settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/fonts.css">
    <link rel="stylesheet" href="./css/animate.css">
</head>

<body>
    <?php include "./templates/header.php" ?>
    <header class="box signup-header">
        <div class="container text-center">
            <h2 href="/settings.php" class="header-text animate__animated animate__fadeIn">Settings</h2>
        </div>
    </header>
    <main class="container">
        <i class="mt-5"></i>
        <div class="row box-no-border">
            <div class="col-md-6 text-end">
                <h4>Account information</h4>
            </div>
            <div class="col-md-6">
                <div>
                    <div class="d-flex">
                        <h4><?php echo $_SESSION['userUsername'] ?></h4>
                        <?php
                        require "./includes/connection.inc.php"; // Include your database connection code

                        // Query the user_role from the database
                        $userId = $_SESSION['userId']; // Assuming you have the user's ID in the session
                        $sql = "SELECT user_role FROM users WHERE id = :id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
                        $stmt->execute();
                        $user = $stmt->fetch();

                        $role = $user ? $user['user_role'] : 'User'; // Default to 'User' if no role is found
                        $badgeClass = '';
                        switch ($role) {
                            case 'Admin':
                                $badgeClass = 'text-bg-danger';
                                break;
                            case 'Moderator':
                                $badgeClass = 'text-bg-success';
                                break;
                            default:
                                $badgeClass = 'text-bg-secondary';
                                break;
                        }
                        ?>

                        <span class="mx-1 badge <?php echo $badgeClass; ?>" style="height: 20px;"><?php echo $role; ?></span>
                    </div>
                    <small><?php echo $_SESSION['userEmail'] ?></small>
                    <h4>Joined</h4>
                    <small><?php echo date("F j, Y", strtotime($_SESSION['userJoin'])) ?></small>
                    <h4>User ID</h4>
                    <small><?php echo $_SESSION['userId'] ?></small>
                </div>
            </div>
        </div>
        <div class="row box-no-border">
            <div class="col-md-6 text-end">
                <h4>Minecraft accounts</h4>
            </div>
            <div class="col-md-6">
                <h4>test</h4>
            </div>
        </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <?php include "./templates/footer.php" ?>
</body>

</html>