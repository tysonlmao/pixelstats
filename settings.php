<?php
session_start();

// if userId not set, redirect to login.php
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

require "./includes/connection.inc.php"; // Include your database connection code

// Fetch user account data from the database
$userId = $_SESSION['userId'];
$sql = "SELECT main_account, alt_account FROM user_accounts WHERE user_id = :userId";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmt->execute();
$userAccountData = $stmt->fetch(PDO::FETCH_ASSOC);

// Initialize variables to store the current values
$currentMainAccount = $userAccountData['main_account'];
$currentAltAccount = $userAccountData['alt_account'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $mainAccount = $_POST['mainAccount'];
    $altAccount = $_POST['altAccount'];

    if ($mainAccount !== $currentMainAccount || $altAccount !== $currentAltAccount) {
        // Only update the database if there are changes
        $sql = "UPDATE user_accounts SET main_account = :mainAccount, alt_account = :altAccount WHERE user_id = :userId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':mainAccount', $mainAccount, PDO::PARAM_STR);
        $stmt->bindParam(':altAccount', $altAccount, PDO::PARAM_STR);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Update the current values with new ones
            $currentMainAccount = $mainAccount;
            $currentAltAccount = $altAccount;
        }
    }
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
    <script>
        // JavaScript function to show the "Save Changes" button
        function showSaveButton() {
            document.getElementById('saveButton').style.display = 'block';
        }
    </script>
</head>

<body>
    <?php include "./templates/header.php" ?>
    <header class="box signup-header">
        <div class="container text-center">
            <h2 href="/settings.php" class="header-text animate__animated animate__fadeIn">Settings</h2>
        </div>
    </header>
    <main class="container">
        <div class="row box-no-border">
            <div class="col-md-6 text-end">
                <h4>Account information</h4>
            </div>
            <div class="col-md-6">
                <div>
                    <div class="d-flex">
                        <h4><?php echo $_SESSION['userUsername'] ?></h4>
                        <?php
                        // Query the user_role from the database
                        $userId = $_SESSION['userId'];
                        $sql = "SELECT user_role FROM users WHERE id = :id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
                        $stmt->execute();
                        $user = $stmt->fetch();

                        $role = $user ? $user['user_role'] : 'User';
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
                <form method="POST" action="settings.php">
                    <div class="form-group">
                        <label for="mainAccount">Main account</label>
                        <input type="text" class="form-control" name="mainAccount" id="mainAccount" placeholder="Enter your main account" oninput="showSaveButton();" value="<?php echo $currentMainAccount; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="altAccount">Alt account</label>
                        <input type="text" class="form-control" name="altAccount" id="altAccount" placeholder="Enter your alt account" oninput="showSaveButton();" value="<?php echo $currentAltAccount; ?>">
                    </div>

                    <button type="submit" class="btn btn-primary" style="display: none;" id="saveButton">Save Changes</button>
                </form>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <?php include "./templates/footer.php" ?>
</body>

</html>