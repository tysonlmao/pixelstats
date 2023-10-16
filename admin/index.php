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
// Query the users from the database and order them by user_role
$sql = "SELECT id, username, email, user_join, user_role FROM users ORDER BY
        CASE
            WHEN user_role = 'Admin' THEN 1
            WHEN user_role = 'Moderator' THEN 2
            WHEN user_role = 'User' THEN 3
            ELSE 4
        END";
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
                                <th>User Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Inside the table body -->
                        <tbody>
                            <?php foreach ($users as $user) : ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td><?= $user['username'] ?></td>
                                    <td><?= $user['email'] ?></td>
                                    <td><?= $user['user_join'] ?></td>
                                    <td><?= $user['user_role'] ?></td>
                                    <td>
                                        <span class="manage-link" data-user-id="<?= $user['id'] ?>">Manage</span> |
                                        <span class="terminate-link" data-user-id="<?= $user['id'] ?>">Terminate</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                        <style>
                            .manage-link,
                            .terminate-link {
                                cursor: pointer;
                            }

                            .manage-link:hover,
                            .terminate-link:hover {
                                text-decoration: underline;
                            }
                        </style>

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

    <!-- Modal for managing user accounts -->
    <div class="modal fade" id="manageUserModal" tabindex="-1" role="dialog" aria-labelledby="manageUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="manageUserModalLabel">Manage User Accounts</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form for managing user accounts goes here -->
                    <!-- You can use this space to display and update user account information -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updateUserAccountsBtn">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to handle the click events -->
    <!-- JavaScript for handling "Terminate" link -->
    <script>
        $(document).ready(function() {
            $('.terminate-link').click(function() {
                var userId = $(this).data('user-id');
                if (confirm("Are you sure you want to terminate this user?")) {
                    $.ajax({
                        type: "POST",
                        url: "../includes/terminate.inc.php", // Update the URL to match the correct path
                        data: {
                            user_id: userId
                        },
                        success: function(response) {
                            alert("User terminated successfully.");
                            location.reload(); // Reload the page to update the user list
                        },
                        error: function(xhr, status, error) {
                            alert("An error occurred while terminating the user.\nStatus: " + status + "\nError: " + error);
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>