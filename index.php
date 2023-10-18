<?php
session_start();
/**
 * @todo #12 setup custom error pages + .htaccess (301, 404, 500)
 */
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pixelstats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/fonts.css">
</head>

<body>
    <?php include "./templates/header.php" ?>
    <main class="content">
        <div class="container">
            <div class="container">
                <h2>Welcome to Pixelstats</h2>
                <p>This site is in heavy development.</p>
                <?php
                if (isset($_GET['player']) && !empty($_GET['player'])) :
                    // Retrieve the username from the URL
                    $username = $_GET['player'];

                    // You can use $username to fetch and display the user's stats here
                    // For example, you can use cURL to fetch data from your data source

                    // Sample cURL code (replace with your actual API endpoint)
                    $apiUrl = "https://api.pixelstats.app/requests?uuid=" . $username;
                    $ch = curl_init($apiUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $res = curl_exec($ch);

                    if ($res === false) :
                        echo '<div class="alert alert-danger">cURL Error: ' . curl_error($ch) . '</div>';
                    else :
                        $data = json_decode($res, true);
                    // Display the user's stats here using data from $data
                    // You can format and style the data as needed.
                    endif;

                    curl_close($ch);
                else :
                endif;
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <form action="profile.php" method="get">
                            <div class="mb-3">
                                <label for="username" class="form-label">Search by username</label>
                                <input type="text" class="form-control" id="username" name="player" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </main>
    <?php include "./templates/footer.php" ?>
</body>

</html>