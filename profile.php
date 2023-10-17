<?php
session_start();

// Check if the 'player' query parameter is set
if (!isset($_GET['player'])) {
    // Redirect or display an error message as needed
    echo 'Player not specified.';
    exit();
}

$player = $_GET['player']; // Get the player name from the query parameter

$apiUrl = "https://api.pixelstats.app/requests?uuid=" . $player;
$ch = curl_init($apiUrl);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($ch);

if ($res === false) {
    echo '<div class="alert alert-danger content">cURL Error: ' . curl_error($ch) . '</div>';
} else {
    $data = json_decode($res, true);
    curl_close($ch);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- Set the meta image dynamically -->
    <?php
    $playerUUID = isset($data['player']['uuid']) ? $data['player']['uuid'] : '';
    $avatarURL = "https://mc-heads.net/avatar/" . $playerUUID;
    ?>
    <meta property="og:image" content="<?php echo $avatarURL; ?>">
    <meta name="description" content="<?php echo $data['player']['displayname']; ?>'s player stats on pixelstats">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pixelstats | <?php echo $_GET['player'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/fonts.css">
    <link rel="stylesheet" href="./css/animate.css">
    <!-- Add Bootstrap CSS link here -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.min.css">
</head>

<body class="profile content">
    <!-- Include your header content here -->
    <?php include "./templates/header.php" ?>
    <div class="row px-2">
        <div class="col-md-6"></div>
        <div class="col-md-6">
            <form action="profile.php" method="get">
                <div class="mb-3">
                    <p class="text-end mb-2">Search for a user</p>
                    <input type=" text" class="form-control" id="username" name="player" required>
                </div>
                <button type="submit" class="btn btn-primary d-none">Submit</button>
            </form>
        </div>
    </div>
    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <!-- Sidebar for general player statistics -->
                    <div class="box sidebar" style="background-color: rgba(54, 47, 217, 0.3);">
                        <div class="box-no-border text-end">
                            <h2 class="stat-t">Player</h2>
                            <h2 class="stat-v text-uppercase">
                                <?php echo $data['player']['displayname']; ?>
                            </h2>
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
                    <!-- Content for the specified player's account goes here -->
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
    </main>
    <!-- Include your footer content here -->
    <?php include "./templates/footer.php" ?>

    <!-- Include Bootstrap JS and other scripts here -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>