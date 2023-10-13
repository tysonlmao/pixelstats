<?php
session_start();

// Check if userId is not set
if (!isset($_SESSION['userId'])) {
    header("Location: /login.php");
    exit();
}

// Check if the user is not an admin (assuming 'Admin' is the role for administrators)
if (isset($_SESSION['userRole']) && $_SESSION['userRole'] !== 'Admin') {
    header("Location: ../dashboard.php?error=forbidden");
    exit();
}

require "../includes/connection.inc.php";
// Query the users from the database
$sql = "SELECT id, username, email, user_join, user_role FROM users";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pixelstats | admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/fonts.css">
    <link rel="stylesheet" href="../css/animate.css">
</head>

<body>
    <?php include "../templates/header.php" ?>
    <header class="box signup-header">
        <div class="container text-center">
            <h2 href="/settings.php" class="header-text animate__animated animate__fadeIn">Admin</h2>
        </div>
    </header>

    <main>
        <!-- tabs here -->
        <div class="box-no-border">
            <nav>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="users-tab" data-toggle="tab" href="#users" role="tab" aria-controls="users" aria-selected="true">Users List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="placeholder1-tab" data-toggle="tab" href="#placeholder1" role="tab" aria-controls="placeholder1" aria-selected="false">Placeholder 1</a>
                    </li>
                </ul>
            </nav>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="users" role="tabpanel" aria-labelledby="users-tab">
                    <!-- Users List content goes here -->
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Join Date</th>
                                <th>User role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user) : ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td><?= $user['username'] ?></td>
                                    <td><?= $user['email'] ?></td>
                                    <td><?= $user['user_join'] ?></td>
                                    <td><?= $user['user_role'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="placeholder1" role="tabpanel" aria-labelledby="placeholder1-tab">
                    <div class="box">placeholder</div>
                </div>
            </div>
        </div>
    </main>

    <?php include "../templates/footer.php" ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>