<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['userId'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pixelstats | login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/fonts.css">
    <link rel="stylesheet" href="./css/animate.css">
</head>

<body>
    <?php include "./templates/header.php" ?>
    <header class="box signup-header">
        <div class="container text-center">
            <h2 class="header-text animate__animated animate__fadeIn">Login</h2>
        </div>
    </header>
    <main>
        <div class="box">
            <div class="row px-3">
                <div class="col-md-6">
                    <?php
                    if (isset($_GET['error'])) :
                        if ($_GET['error'] == 'emailPasswordInvalid' || $_GET['error'] == 'emptyFields') :
                            $message = 'Invalid email or password';
                            echo '<div class="alert alert-danger">' . $message . '</div>';
                        endif;
                    endif;
                    ?>
                    <form action="./includes/login.inc.php" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email or username</label>
                            <input type="text" class="form-control" id="email" aria-describedby="emailHelp" name="email" placeholder="john@gmail.com">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="pwd" placeholder="Password"></input>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary w-100" name="login-submit">Login</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-end">
                    <h4>Forgot password?</h4>
                    <small>Open a ticket <a href="https://help.tysonlmao.dev" target="_blank">here</a> using the email you registered with.</small>
                    <h4 class="mt-5">Dont have an account?</h4>
                    <a href=" /register.php" class="btn btn-primary mt-2">Register here</a>
                </div>
            </div>
        </div>
    </main>
    <?php include "./templates/footer.php" ?>
</body>