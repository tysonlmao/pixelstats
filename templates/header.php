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
                    <a class="btn btn-link btn-nav" href="/posts.php">feed</a>
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
            <div class="flexbox">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="btn btn-link btn-nav" href="/posts.php">feed</a>
                        <a class="btn btn-link btn-nav" href="/login.php">login</a>
                    </li>
                </ul>
                <form action="profile.php" method="get" class="mb-0 form-inline justify-content-end">
                    <div class="align-items-center">
                        <input type="text" class="form-control f-c-s" id="username" name="player" required placeholder="search">
                    </div>
                </form>
            </div>
        </div>
    </nav>
<?php endif; ?>
<div class="content">
    <?php
    if (isset($_GET['error'])) :
        if ($_GET['error'] == 'emptyFields') :
            $message = "Please fill in all the fields";
            echo '<div class="alert alert-danger content" role="alert">' . $message . '</div>';
        elseif ($_GET['error'] == 'invalidUsername') :
            $message = "Please enter a valid username";
            echo '<div class="alert alert-danger content" role="alert">' . $message . '</div>';
        elseif ($_GET['error'] == 'invalidEmail') :
            $message = "Please enter a valid email address";
            echo '<div class="alert alert-danger content" role="alert">' . $message . '</div>';
        elseif ($_GET['error'] == 'passwordMismatch') :
            $message = "Passwords entered do not match. Please try again";
            echo '<div class="alert alert-danger content" role="alert">' . $message . '</div>';
        elseif ($_GET['error'] == 'usernameTaken') :
            $message = "That username is taken, please try again";
            echo '<div class="alert alert-danger content" role="alert">' . $message . '</div>';
        elseif ($_GET['error'] == 'emailTaken') :
            $message = "This email address is already in use.";
            echo '<div class="alert alert-danger content" role="alert">' . $message . '</div>';
        elseif ($_GET['error'] == 'sqlerror') :
            $message = "Error occurred on the server. Please contact the system administrator";
            echo '<div class="alert alert-danger content" role="alert">' . $message . '</div>';
        elseif ($_GET['error'] == 'databaseerror') :
            $message = 'Database connection error. Please contact the <a href="mailto:tyson@tysonlmao.dev">webmaster</a>';
            echo '<div class="alert alert-danger content" role="alert">' . $message . '</div>';
        elseif ($_GET['error'] == 'emailPasswordInvalid' || $_GET['error'] == 'emptyFields') :
            $message = 'Invalid email or password';
            echo '<div class="alert alert-danger content">' . $message . '</div>';
        endif;
        if (isset($_GET['success'])) :
            if ($_GET['error'] == 'success') :
                $message = 'Registration successful. <a href="/login.php">Login here</a>';
                echo '<div class="alert alert-success content" role="alert">' . $message . '</div>';
            elseif ($_GET['success'] == 'posted') :
                $message = 'Posted successfully';
                echo '<div class="alert alert-success content">' . $message . '</div>';
            elseif ($_GET['success'] == 'postdeleted') :
                $message = 'Post deleted successfully';
                echo '<div class="alert alert-success content">' . $message . '</div>';
            endif;
        endif;
    endif;
    ?>
</div>