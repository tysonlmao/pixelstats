<?php
if (isset($_GET['player'])) {
    $username = $_GET['player'];

    $apiUrl = "https://api.pixelstats.app/requests?uuid=" . $username;
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);

    if ($res === false) :
        echo '<div class="alert alert-danger">cURL Error: ' . curl_error($ch) . '</div>';
    else :
        $data = json_decode($res, true);
    endif;

    curl_close($ch);
}
?>

<?php if (isset($_SESSION['userId'])) : // User is logged in 
?>
    <nav class="navbar navbar-expand-lg content">
        <div class="container-fluid">
            <a class="navbar-brand" href="/index.php">pixelstats</a>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="btn btn-link btn-nav" href="/posts.php">post</a>
                </li>
                <ul class="navbar-nav justify-content-end mx-3">
                    <form action="profile.php" method="get" class="mb-0">
                        <div class="align-items-center">
                            <input type="text" class="form-control" id="username" name="player" required>
                        </div>
                    </form>
                </ul>
                <ul class="navbar-nav ml-auto">
                </ul>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Account
                    </button>

                    <ul class="dropdown-menu">
                        <li> <a href="/dashboard.php" class="nav-link text-black">Dashboard</a>
                        </li>
                        <li> <a href="/settings.php" class="nav-link text-black">Settings</a>
                        </li>
                        <?php if (isset($_SESSION['userRole']) && $_SESSION['userRole'] == "Admin") :
                        ?>
                            <li class="nav-item">
                                <a href="/admin/index.php" class="nav-link dropdown-item text-black">Admin</a>
                            </li>
                        <?php
                        endif; ?>
                        <li class="nav-item">
                            <a href="/logout.php" class="nav-link text-black">Sign Out</a>
                        </li>
                    </ul>
                </div>

            </ul>
        </div>
    </nav>
<?php else : // User is not logged in, show login and register
    if (isset($_GET['player']) && !empty($_GET['player'])) :
    else :
    endif;
?>
    <nav class="navbar navbar-expand-lg content">
        <div class="container-fluid">
            <a class="navbar-brand" href="/index.php">pixelstats</a>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="btn btn-link btn-nav" href="/posts.php">feed</a>
                    <a class="btn btn-link btn-nav" href="/login.php">login</a>
                </li>
            </ul>
            <ul class="navbar-nav justify-content-end">
                <form action="profile.php" method="get" class="mb-0">
                    <div class="align-items-center">
                        <input type="text" class="form-control" id="username" name="player" required>
                    </div>
                </form>
            </ul>
        </div>
    </nav>
<?php endif; ?>




</nav>