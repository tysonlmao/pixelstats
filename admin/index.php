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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.min.css">
</head>


<body class="content">
    <?php include "../templates/header.php" ?>
    <header class="box signup-header">
        <div class="container text-center">
            <h2 class="header-text animate__animated animate__fadeIn">Admin</h2>
        </div>
    </header>

    <main>
        <!-- tabs here -->
        <div class="box-no-border">
            <!-- Users List content goes here -->
            <div class="row">
                <div class="col-md-6">
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="site-search" placeholder="Search by username">
                        <label for="site-search">Search by username</label>
                    </div>
                </div>
                <i class="mt-3"></i>
                <!-- Add the jQuery code here -->
                <script>
                    $(document).ready(function() {
                        // Store the original table data
                        var originalTableData = $('.content').html();

                        // Function to show/hide search results
                        function updateSearchResults() {
                            // Get the search input value
                            var searchValue = $('#site-search').val().toLowerCase();

                            // Initialize an empty array to store filtered results
                            var filteredResults = [];

                            // Loop through the users and find matches
                            <?php foreach ($users as $user) : ?>
                                var username = '<?= strtolower($user['username']) ?>';
                                if (username.includes(searchValue)) {
                                    filteredResults.push(
                                        '<tr>' +
                                        '<td><?= $user['id'] ?></td>' +
                                        '<td><?= $user['username'] ?></td>' +
                                        '<td><?= $user['email'] ?></td>' +
                                        '<td><?= $user['user_join'] ?></td>' +
                                        '<td><?= $user['user_role'] ?></td>' +
                                        '<td>' +
                                        '<span class="manage-link" data-user-id="<?= $user['id'] ?>">Manage</span> | ' +
                                        '<span class="terminate-link" data-user-id="<?= $user['id'] ?>">Terminate</span>' +
                                        '</td>' +
                                        '</tr>'
                                    );
                                }
                            <?php endforeach; ?>

                            // Update the table with the filtered results
                            var updatedTable = '<table class="table table-dark table-hover"><thead><tr><th>User ID</th><th>Username</th><th>Email</th><th>Join Date</th><th>User Role</th><th>Action</th></tr></thead><tbody>';
                            updatedTable += filteredResults.join('');
                            updatedTable += '</tbody></table';

                            // Display the updated table or a message if no results are found
                            if (filteredResults.length > 0) {
                                $('#search-results').html(updatedTable).show();
                            } else {
                                $('#search-results').html('<p>No results found.</p>').hide();
                            }
                        }

                        // Call the function on page load to handle the initial state
                        updateSearchResults();

                        // Add an event listener for the input event of the site-search input
                        $('#site-search').on('input', updateSearchResults);
                    });
                </script>
            </div>
            <div id="search-results"></div>
            <div class="tab-pane fade" id="placeholder1" role="tabpanel" aria-labelledby="placeholder1-tab">
                <div class="box">placeholder</div>
            </div>
        </div>
    </main>

    <?php include "../templates/footer.php" ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Modal for managing user accounts -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            // Store the original table data
            var originalTableData = $('.content').html();

            // Add an event listener to the search input
            $('#site-search').on('input', function() {
                // Get the search input value
                var searchValue = $(this).val().toLowerCase();

                // Initialize an empty array to store filtered results
                var filteredResults = [];

                // Loop through the users and find matches
                <?php foreach ($users as $user) : ?>
                    var username = '<?= strtolower($user['username']) ?>';
                    if (username.includes(searchValue)) {
                        filteredResults.push(
                            '<tr>' +
                            '<td><?= $user['id'] ?></td>' +
                            '<td><?= $user['username'] ?></td>' +
                            '<td><?= $user['email'] ?></td>' +
                            '<td><?= $user['user_join'] ?></td>' +
                            '<td><?= $user['user_role'] ?></td>' +
                            '<td>' +
                            '<span class="manage-link" data-user-id="<?= $user['id'] ?>">Manage</span> | ' +
                            '<span class="terminate-link" data-user-id="<?= $user['id'] ?>">Terminate</span>' +
                            '</td>' +
                            '</tr>'
                        );
                    }
                <?php endforeach; ?>

                // Update the table with the filtered results
                var updatedTable = '<table class="table table-dark table-hover"><thead><tr><th>User ID</th><th>Username</th><th>Email</th><th>Join Date</th><th>User Role</th><th>Action</th></tr></thead><tbody>';
                updatedTable += filteredResults.join('');
                updatedTable += '</tbody></table>';

                // Display the updated table or a message if no results are found
                if (filteredResults.length > 0) {
                    $('#search-results').html(updatedTable);
                } else {
                    $('#search-results').html('<p>No results found.</p>');
                }
            });
        });
        // Function to handle termination
        function terminateUser(userId) {
            $.ajax({
                type: "POST",
                url: "./includes/terminate.inc.php",
                data: {
                    user_id: userId
                },
                success: function(response) {
                    // Handle the response from the server
                    if (response === "User terminated successfully.") {
                        // Reload the page or update the user list as needed
                        location.reload();
                    } else {
                        // Handle errors or display a message to the user
                        console.log("Error: " + response);
                    }
                }
            });
        }

        // Add a click event handler for the "Terminate" links
        $('.terminate-link').click(function() {
            var userId = $(this).data('user-id');
            if (confirm("Are you sure you want to terminate this user?")) {
                $.ajax({
                    type: "POST",
                    url: "./inc/terminate.inc.php", // Update the URL to match the correct path
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
    </script>

</body>

</html>