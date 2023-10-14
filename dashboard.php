<?php
session_start();

// if userId not set, redirect to login.php
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

// Include your database connection script
require "./includes/connection.inc.php";

// Fetch account information for the logged-in user
$userId = $_SESSION['userId'];
$sql = "SELECT main_account, alt_account FROM user_accounts WHERE user_id = :userId";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmt->execute();
$accountInfo = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if account information is available
if ($accountInfo) {
    $mainAccount = $accountInfo['main_account'];
    $altAccount = $accountInfo['alt_account'];
} else {
    // Account information not found
    $mainAccount = "Unset";
    $altAccount = "Unset";
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
    <style>
        /* Hide the tab navigation elements */
        #accountTabs {
            display: none;
        }
    </style>
    <script src="./js/greetings.js"></script>
</head>

<body class="dashboard">
    <?php include "./templates/header.php" ?>
    <main>
        <div class="container">
            <div class="d-flex mb-3">
                <h2 id="greeting"></h2>
                <h2 class="px-2" id="accountTitle"><?php echo $_SESSION['userUsername'] ?></h2>
            </div>
            <?php
            // echo $mainAccount; // dump it to the screen

            $apiUrl = "https://api.pixelstats.app/requests?uuid=" . $mainAccount;
            $ch = curl_init($apiUrl);

            // curl options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = curl_exec($ch);
            if ($res === false) {
                echo '<div class="alert alert-danger">cURL Error: ' . curl_error($ch) . '</div>';
            } else {
                // if cURL success
                $data = json_decode($res, true);
            }
            ?>
            <!-- Header with the "Switch Accounts" button -->
            <header class="box signup-header">
                <div class="container text-center switcher">
                    <h2 id="mainAccountTitle"><?php echo $mainAccount ?></h2>
                    <h3 id="altAccountTitle"><?php echo $altAccount ?></h3>
                    <button class="btn btn-dark" id="switchButton">
                        <p>Switch Accounts</p>
                    </button>
                </div>
            </header>

            <div class="tab-content" id="accountTabsContent">
                <div class="tab-pane fade show active" id="mainAccount" role="tabpanel" aria-labelledby="mainAccount-tab">
                    <!-- Content for main account -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="box-no-border">test</div>
                        </div>
                        <div class="col-md-8">
                            <div class="box-no-border">test</div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="altAccount" role="tabpanel" aria-labelledby="altAccount-tab">
                    <!-- Content for alt account -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="box-no-border">test</div>
                        </div>
                        <div class="col-md-8">
                            <div class="box-no-border">test</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include "./templates/footer.php" ?>

    <!-- Include Bootstrap JS for tab functionality -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

    <script>
        // Function to switch the account titles, tabs, and content
        function switchAccounts() {
            const mainTitle = document.getElementById("mainAccountTitle");
            const altTitle = document.getElementById("altAccountTitle");
            const mainTab = document.getElementById("mainAccount-tab");
            const altTab = document.getElementById("altAccount-tab");
            const mainContent = document.getElementById("mainAccount");
            const altContent = document.getElementById("altAccount");

            // Swap the text content of h2 and h3
            const tempTitle = mainTitle.textContent;
            mainTitle.textContent = altTitle.textContent;
            altTitle.textContent = tempTitle;

            // Toggle active content
            mainContent.classList.toggle("show");
            mainContent.classList.toggle("active");
            altContent.classList.toggle("show");
            altContent.classList.toggle("active");
        }
        // Add event listener to the "Switch Accounts" button
        const switchButton = document.getElementById("switchButton");
        switchButton.addEventListener("click", function() {
            // Call the function to switch account titles, tabs, and content
            switchAccounts();
        });
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>