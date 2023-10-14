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

// Fetch the user's cactus_kit preference from the database
$sqlPreference = "SELECT cactus_kit FROM user_accounts WHERE user_id = :userId";
$stmtPreference = $pdo->prepare($sqlPreference);
$stmtPreference->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmtPreference->execute();
$userPreference = $stmtPreference->fetch(PDO::FETCH_ASSOC);

// Set $cactusKitPreference based on the database value
if ($userPreference) {
    $cactusKitPreference = $userPreference['cactus_kit'];
} else {
    // Set a default value if no preference is found
    $cactusKitPreference = 1; // Assuming 1 is the default value
}

// Check if account information is available
if ($accountInfo) {
    $mainAccount = $accountInfo['main_account'];
    $altAccount = $accountInfo['alt_account'];
} else {
    // Account information not found
    $mainAccount = "Unset";
    $altAccount = "Unset";
}

$selectedAccount = $mainAccount; // Initially, set the selected account to mainAccount

// Check if the current view is the "altAccount" view, and if so, switch to altAccount
if (isset($_GET['view']) && $_GET['view'] === 'altAccount') {
    $selectedAccount = $altAccount;
}

$apiUrl = "https://api.pixelstats.app/requests?uuid=" . $selectedAccount;
$ch = curl_init($apiUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($ch);

if ($res === false) {
    echo '<div class="alert alert-danger">cURL Error: ' . curl_error($ch) . '</div>';
} else {
    // If cURL request is successful, decode the JSON response
    $data = json_decode($res, true);
}

// Close cURL handle
curl_close($ch);

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
                        <div class="col-md-4 text-end">
                            <!-- echo $data['player']['achievementPoints'] . "test"; -->
                            <div class="box">
                                <div class="box-no-border">
                                    <h3 class="stat-t">Network Level</h3>
                                    <p class="stat-v"><?php echo number_format((sqrt(2 * $data['player']['networkExp'] + 30625) / 50) - 2.5, 2); ?></p>
                                    <h3 class="stat-t">Achievement Points</h3>
                                    <p class="stat-v"><?php echo $data['player']['achievementPoints'] ?></p>
                                    <h3 class="stat-t">First login</h3>
                                    <p class="stat-v"><?php echo date("Y-m-d ", (int)($data['player']['firstLogin'] / 1000)); ?></p>
                                    <h3 class="stat-t">Last login</h3>
                                    <!-- Y-m-d H:i:s -->
                                    <p class="stat-v"><?php echo date("Y-m-d ", (int)($data['player']['lastLogin'] / 1000)); ?></p>

                                </div>
                            </div>
                        </div>
                        <div class=" col-md-8">
                            <!-- Add your main account content here -->
                            <?php if ($cactusKitPreference == 1 && isset($data)) : ?>
                                <!-- This block of code will only be executed if the conditions are met -->
                                <div class="box cactus" style="border: 3px solid #acc42c !important; box-shadow: 0 0 10px 1.5px #acc42c; background-color: rgba(172, 196, 44, 0.3);">
                                    <div class="row px-5">
                                        <div class="col-md-2 align-items-end">
                                            <img src="./public/cactus.png" alt="cactus" height="128px">
                                        </div>
                                        <div class="col-md-10 d-flex justify-content-end align-items-center">
                                            <style>
                                                .cactus {
                                                    font-family: "VCR OSD Mono";
                                                    font-size: 72px;
                                                    color: #acc42c;
                                                }
                                            </style>
                                            <div class="align-items-center">
                                                <!-- sw_duel_cactus_kit_wins -->
                                                <h3 class="cactus text-center">
                                                    <?php
                                                    $x = $data['player']['stats']['Duels']['sw_duel_cactus_kit_wins'] + $data['player']['stats']['Duels']['sw_doubles_cactus_kit_wins'];
                                                    echo $x ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="altAccount" role="tabpanel" aria-labelledby="altAccount-tab">
                    <!-- Content for alt account -->
                    <?php
                    // Fetch data for the altAccount
                    $apiUrlAlt = "https://api.pixelstats.app/requests?uuid=" . $altAccount;
                    $chAlt = curl_init($apiUrlAlt);

                    curl_setopt($chAlt, CURLOPT_RETURNTRANSFER, true);
                    $resAlt = curl_exec($chAlt);

                    if ($resAlt === false) {
                        echo '<div class="alert alert-danger">cURL Error: ' . curl_error($chAlt) . '</div>';
                    } else {
                        $dataAlt = json_decode($resAlt, true);

                        if (isset($dataAlt) && isset($dataAlt['player']['achievementPoints'])) {
                        } else {
                            echo "Data not available";
                        }
                    }

                    curl_close($chAlt);
                    ?>
                    <div class="row">
                        <div class="col-md-4 text-end">
                            <!-- echo $data['player']['achievementPoints'] . "test"; -->
                            <div class="box" style="background-color: rgb(54, 47, 217, 0.3);">
                                <div class="box-no-border">
                                    <h3 class="stat-t">Achievement Points</h3>
                                    <p class="stat-v"><?php echo $dataAlt['player']['achievementPoints'] ?></p>
                                    <h3 class="stat-t">First login</h3>
                                    <p class="stat-v"><?php echo date("Y-m-d ", (int)($dataAlt['player']['firstLogin'] / 1000)); ?></p>
                                    <h3 class="stat-t">Last login</h3>
                                    <!-- Y-m-d H:i:s -->
                                    <p class="stat-v"><?php echo date("Y-m-d ", (int)($dataAlt['player']['lastLogin'] / 1000)); ?></p>

                                </div>
                            </div>
                        </div>
                        <div class=" col-md-8">
                            <!-- Add your main account content here -->
                            <!-- update user_accounts set cactus_kit = 1 where user_id=4; -->
                            <?php if ($cactusKitPreference == 1 && isset($data)) : ?>
                                <!-- This block of code will only be executed if the conditions are met -->
                                <div class="box cactus" style="border: 3px solid #acc42c !important; box-shadow: 0 0 10px 1.5px #acc42c; background-color: rgba(172, 196, 44, 0.3);">
                                    <div class="row px-5">
                                        <div class="col-md-2 align-items-end">
                                            <img src="./public/cactus.png" alt="cactus" height="128px">
                                        </div>
                                        <div class="col-md-10 d-flex justify-content-end align-items-center">
                                            <style>
                                                .cactus {
                                                    font-family: "VCR OSD Mono";
                                                    font-size: 72px;
                                                    color: #acc42c;
                                                }
                                            </style>
                                            <div class="align-items-center">
                                                <!-- sw_duel_cactus_kit_wins -->
                                                <h3 class="cactus text-center">
                                                    <?php
                                                    $x = $dataAlt['player']['stats']['Duels']['sw_duel_cactus_kit_wins'] + $dataAlt['player']['stats']['Duels']['sw_doubles_cactus_kit_wins'];
                                                    echo $x ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
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