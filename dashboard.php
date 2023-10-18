<?php
session_start();

// if userId not set, redirect to login.php
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}
require __DIR__ . '/includes/config.inc.php';
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
    echo '<div class="alert alert-danger content">cURL Error: ' . curl_error($ch) . '</div>';
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.min.css">

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
    <main class="content">
        <div class="container">
            <div class="d-flex mb-3">
                <h2 id="greeting"></h2>
                <h2 class="px-2" id="accountTitle"><?php echo $_SESSION['userUsername'] ?>.</h2>
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
                            <div class="box" style="background-color: rgba(54, 47, 217, 0.3);">
                                <div class="box-no-border">
                                    <h3 class="stat-t">Network Level</h3>
                                    <p class="stat-v">
                                        <?php if (isset($data['player']['networkExp'])) : ?>
                                            <?php echo number_format((sqrt(2 * $data['player']['networkExp'] + 30625) / 50) - 2.5, 2); ?>
                                        <?php else : ?>
                                            Unknown
                                        <?php endif; ?>
                                    </p>
                                    <h3 class="stat-t">Achievement Points</h3>
                                    <p class="stat-v">
                                        <?php if (isset($data['player']['achievementPoints'])) : ?>
                                            <?php echo $data['player']['achievementPoints']; ?>
                                        <?php else : ?>
                                            Unknown
                                        <?php endif; ?>
                                    </p>
                                    <h3 class="stat-t">First login</h3>
                                    <p class="stat-v">
                                        <?php if (isset($data['player']['firstLogin'])) : ?>
                                            <?php echo date("Y-m-d", (int)($data['player']['firstLogin'] / 1000)); ?>
                                        <?php else : ?>
                                            Unknown
                                        <?php endif; ?>
                                    </p>
                                    <h3 class="stat-t">Last login</h3>
                                    <p class="stat-v">
                                        <?php if (isset($data['player']['lastLogin'])) : ?>
                                            <?php echo date("Y-m-d", (int)($data['player']['lastLogin'] / 1000)); ?>
                                        <?php else : ?>
                                            Hidden
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>



                        </div>
                        <div class="col-md-8">
                            <!-- Add your main account content here -->
                            <?php if ($cactusKitPreference == 1 && isset($data)) : ?>
                                <!-- This block of code will only be executed if the conditions are met and keys are defined -->
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
                                                <?php
                                                $cactusKitWins = 0; // Initialize a variable to store the sum of wins
                                                if (isset($data['player']['stats']['Duels']['sw_duel_cactus_kit_wins'])) {
                                                    $cactusKitWins += $data['player']['stats']['Duels']['sw_duel_cactus_kit_wins'];
                                                }
                                                if (isset($data['player']['stats']['Duels']['sw_doubles_cactus_kit_wins'])) {
                                                    $cactusKitWins += $data['player']['stats']['Duels']['sw_doubles_cactus_kit_wins'];
                                                }
                                                ?>
                                                <!-- sw_duel_cactus_kit_wins -->
                                                <h3 class="cactus text-center">
                                                    <?php echo $cactusKitWins; ?>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>



                            <div class="box" style="background-color: rgb(208, 20, 127, 0.3); border: 3px solid rgb(208, 20, 127); box-shadow: 0 0 10px 1.5px #d0147f">
                                <a data-toggle="collapse" href="#bedWarsAccordion" role="button" aria-expanded="false" aria-controls="bedWarsAccordion">
                                    <h3 class="accord-button">BedWars</h3>
                                </a>
                                <div class="collapse" id="bedWarsAccordion">
                                    <!-- Content for BedWars goes here -->
                                    <div class="row align-items-center">
                                        <div class="stat mb-4"><?php echo $data['player']['stats']['Bedwars']['Experience'] ?></div>
                                        <div class="col-md-3">
                                            <h3 class="stat-title">FINALS</h3>
                                            <p class="stat"><?php echo $data['player']['stats']['Bedwars']['final_kills_bedwars'] ?></p>
                                            <h3 class="stat-title">DEATHS</h3>
                                            <p class="stat"><?php echo $data['player']['stats']['Bedwars']['final_deaths_bedwars'] ?></p>
                                            <h3 class="stat-title">FKDR</h3>
                                            <h3 class="stat"><?php echo number_format(($data['player']['stats']['Bedwars']['final_kills_bedwars'] / $data['player']['stats']['Bedwars']['final_deaths_bedwars']), 2) ?></h3>
                                        </div>
                                        <div class="col-md-3">
                                            <h3 class="stat-title">BEDS</h3>
                                            <p class="stat"><?php echo $data['player']['stats']['Bedwars']['beds_broken_bedwars'] ?></p>
                                            <h3 class="stat-title">BEDS LOST</h3>
                                            <p class="stat"><?php echo $data['player']['stats']['Bedwars']['beds_lost_bedwars'] ?></p>
                                            <h3 class="stat-title">BBLR</h3>
                                            <h3 class="stat"><?php echo number_format(($data['player']['stats']['Bedwars']['beds_broken_bedwars'] / $data['player']['stats']['Bedwars']['beds_lost_bedwars']), 2) ?></h3>
                                        </div>
                                        <div class="col-md-3">
                                            <h3 class="stat-title">KILLS</h3>
                                            <p class="stat"><?php echo $data['player']['stats']['Bedwars']['kills_bedwars'] ?></p>
                                            <h3 class="stat-title">DEATHS</h3>
                                            <p class="stat"><?php echo $data['player']['stats']['Bedwars']['deaths_bedwars'] ?></p>
                                            <h3 class="stat-title">KDR</h3>
                                            <h3 class="stat"><?php echo number_format(($data['player']['stats']['Bedwars']['kills_bedwars'] / $data['player']['stats']['Bedwars']['deaths_bedwars']), 2) ?></h3>
                                        </div>
                                        <div class="col-md-3">
                                            <h3 class="stat-title">WINS</h3>
                                            <p class="stat"><?php echo $data['player']['stats']['Bedwars']['wins_bedwars'] ?></p>
                                            <h3 class="stat-title">LOSSES</h3>
                                            <p class="stat"><?php echo $data['player']['stats']['Bedwars']['losses_bedwars'] ?></p>
                                            <h3 class="stat-title">WLR</h3>
                                            <h3 class="stat"><?php echo number_format(($data['player']['stats']['Bedwars']['wins_bedwars'] / $data['player']['stats']['Bedwars']['losses_bedwars']), 2) ?></h3>
                                        </div>
                                    </div>
                                    <div class="container">
                                        <table class="table table-dark table-hover mt-3">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Wins</th>
                                                    <th>Losses</th>
                                                    <th>WLR</th>
                                                    <th>Final</th>
                                                    <th>Deaths</th>
                                                    <th>FKDR</th>
                                                </tr>
                                            </thead>
                                            <tr>
                                                <td>Overall</td>
                                                <td><?php echo $data['player']['stats']['Bedwars']['wins_bedwars'] ?></td>
                                                <td><?php echo $data['player']['stats']['Bedwars']['losses_bedwars'] ?></td>
                                                <td><?php echo number_format(($data['player']['stats']['Bedwars']['wins_bedwars'] / $data['player']['stats']['Bedwars']['losses_bedwars']), 2) ?></td>
                                                <td><?php echo $data['player']['stats']['Bedwars']['final_kills_bedwars'] ?></td>
                                                <td><?php echo $data['player']['stats']['Bedwars']['final_deaths_bedwars'] ?></td>
                                                <td><?php echo number_format(($data['player']['stats']['Bedwars']['final_kills_bedwars'] / $data['player']['stats']['Bedwars']['final_deaths_bedwars']), 2) ?></td>

                                            </tr>
                                            <tbody>
                                                <tr>
                                                    <td>Solo</td>
                                                    <td><?php echo $data['player']['stats']['Bedwars']['eight_one_wins_bedwars'] ?></td>
                                                    <td><?php echo $data['player']['stats']['Bedwars']['eight_one_losses_bedwars'] ?></td>
                                                    <td><?php echo number_format(($data['player']['stats']['Bedwars']['eight_one_wins_bedwars'] / $data['player']['stats']['Bedwars']['eight_one_losses_bedwars']), 2) ?></td>
                                                    <td><?php echo $data['player']['stats']['Bedwars']['eight_one_final_kills_bedwars'] ?></td>
                                                    <td><?php echo $data['player']['stats']['Bedwars']['eight_one_final_deaths_bedwars'] ?></td>
                                                    <td><?php echo number_format(($data['player']['stats']['Bedwars']['eight_one_final_kills_bedwars'] / $data['player']['stats']['Bedwars']['eight_one_final_deaths_bedwars']), 2) ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Doubles</td>
                                                    <td><?php echo $data['player']['stats']['Bedwars']['eight_two_wins_bedwars'] ?></td>
                                                    <td><?php echo $data['player']['stats']['Bedwars']['eight_two_losses_bedwars'] ?></td>
                                                    <td><?php echo number_format(($data['player']['stats']['Bedwars']['eight_two_wins_bedwars'] / $data['player']['stats']['Bedwars']['eight_two_losses_bedwars']), 2) ?></td>
                                                    <td><?php echo $data['player']['stats']['Bedwars']['eight_two_final_kills_bedwars'] ?></td>
                                                    <td><?php echo $data['player']['stats']['Bedwars']['eight_two_final_deaths_bedwars'] ?></td>
                                                    <td><?php echo number_format(($data['player']['stats']['Bedwars']['eight_two_final_kills_bedwars'] / $data['player']['stats']['Bedwars']['eight_two_final_deaths_bedwars']), 2) ?></td>
                                                </tr>
                                                <tr>
                                                    <td>3v3v3v3</td>
                                                    <td><?php echo $data['player']['stats']['Bedwars']['four_three_wins_bedwars'] ?></td>
                                                    <td><?php echo $data['player']['stats']['Bedwars']['four_three_losses_bedwars'] ?></td>
                                                    <td><?php echo number_format(($data['player']['stats']['Bedwars']['four_three_wins_bedwars'] / $data['player']['stats']['Bedwars']['four_three_losses_bedwars']), 2) ?></td>
                                                    <td><?php echo $data['player']['stats']['Bedwars']['four_three_final_kills_bedwars'] ?></td>
                                                    <td><?php echo $data['player']['stats']['Bedwars']['four_three_final_deaths_bedwars'] ?></td>
                                                    <td><?php echo number_format(($data['player']['stats']['Bedwars']['four_three_final_kills_bedwars'] / $data['player']['stats']['Bedwars']['four_three_final_deaths_bedwars']), 2) ?></td>
                                                </tr>
                                                <tr>
                                                    <td>4v4v4v4</td>
                                                    <td><?php echo $data['player']['stats']['Bedwars']['four_four_wins_bedwars'] ?></td>
                                                    <td><?php echo $data['player']['stats']['Bedwars']['four_four_losses_bedwars'] ?></td>
                                                    <td><?php echo number_format(($data['player']['stats']['Bedwars']['four_four_wins_bedwars'] / $data['player']['stats']['Bedwars']['four_four_losses_bedwars']), 2) ?></td>
                                                    <td><?php echo $data['player']['stats']['Bedwars']['four_four_final_kills_bedwars'] ?></td>
                                                    <td><?php echo $data['player']['stats']['Bedwars']['four_four_final_deaths_bedwars'] ?></td>
                                                    <td><?php echo number_format(($data['player']['stats']['Bedwars']['four_four_final_kills_bedwars'] / $data['player']['stats']['Bedwars']['four_four_final_deaths_bedwars']), 2) ?></td>
                                                </tr>
                                                <tr>
                                                    <td>4v4</td>
                                                    <td><?php echo $data['player']['stats']['Bedwars']['two_four_wins_bedwars'] ?></td>
                                                    <td><?php echo $data['player']['stats']['Bedwars']['two_four_losses_bedwars'] ?></td>
                                                    <td><?php echo number_format(($data['player']['stats']['Bedwars']['two_four_wins_bedwars'] / $data['player']['stats']['Bedwars']['two_four_losses_bedwars']), 2) ?></td>
                                                    <td><?php echo $data['player']['stats']['Bedwars']['two_four_final_kills_bedwars'] ?></td>
                                                    <td><?php echo $data['player']['stats']['Bedwars']['two_four_final_deaths_bedwars'] ?></td>
                                                    <td><?php echo number_format(($data['player']['stats']['Bedwars']['two_four_final_kills_bedwars'] / $data['player']['stats']['Bedwars']['two_four_final_deaths_bedwars']), 2) ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="box" style="background-color: rgb(152, 72, 145, 0.3); border: 3px solid rgb(152, 72, 145); box-shadow: 0 0 10px 1.5px #984891">
                                <a data-toggle="collapse" href="#duelsAccordion" role="button" aria-expanded="false" aria-controls="duelsAccordion" class="accord-button">
                                    <h3 class="accord-button">Duels</h3>
                                </a>
                                <div class="collapse" id="duelsAccordion">
                                    <!-- Content for Duels goes here -->
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <h3 class="stat-title">WINS</h3>
                                            <p class="stat"><?php echo $data['player']['stats']['Duels']['wins'] ?></p>
                                            <h3 class="stat-title">LOSSES</h3>
                                            <p class="stat"><?php echo $data['player']['stats']['Duels']['losses'] ?></p>
                                            <h3 class="stat-title">WLR</h3>
                                            <h3 class="stat"><?php echo number_format(($data['player']['stats']['Duels']['wins'] / $data['player']['stats']['Duels']['losses']), 2) ?></h3>
                                        </div>
                                        <div class="col-md-6">
                                            <h3 class="stat-title">KILLS</h3>
                                            <p class="stat"><?php echo $data['player']['stats']['Duels']['kills'] ?></p>
                                            <h3 class="stat-title">DEATHS</h3>
                                            <p class="stat"><?php echo $data['player']['stats']['Duels']['deaths'] ?></p>
                                            <h3 class="stat-title">KDR</h3>
                                            <h3 class="stat"><?php echo number_format(($data['player']['stats']['Duels']['kills'] / $data['player']['stats']['Duels']['deaths']), 2) ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="box" style="background-color: rgb(8, 52, 164, 0.3); border: 3px solid rgb(8, 52, 164); box-shadow: 0 0 10px 1.5px #0834a4">
                                <a data-toggle="collapse" href="#skyWarsAccordion" role="button" aria-expanded="false" aria-controls="skyWarsAccordion" class="accord-button">
                                    <?php
                                    /**
                                     * @todo #9 basic skywars stats
                                     */
                                    ?>
                                    <h3 class="accord-button">SkyWars</h3>
                                </a>
                                <div class="collapse" id="skyWarsAccordion">
                                    <!-- Content for SkyWars goes here -->
                                    text
                                </div>
                            </div>
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
                        echo '<div class="alert alert-danger content">cURL Error: ' . curl_error($chAlt) . '</div>';
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
    <script src="./js/accountSwitch.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>