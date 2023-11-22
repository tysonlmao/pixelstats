<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pixelstats | register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/fonts.css">
    <link rel="stylesheet" href="./css/animate.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.min.css">

</head>

<body class="content">
    <?php include "./templates/header.php" ?>
    <header class="box signup-header">
        <div class="container text-center">
            <h2 class="header-text animate__animated animate__fadeIn">Join now</h2>
        </div>
    </header>
    <main class="animate__animated animate__fadeInUp">
        <div class="box px-3">
            <form action="./includes/signup.inc.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="passwordConfirm" class="form-label">Confirm password</label>
                            <input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control">
                        </div>
                    </div>
                    <div id="emailHelp" class="form-text text-white ">Password must contain 8 digits, a capital letter, a number, and a special character</div>
                </div>
                <button type="submit" class="btn btn-primary" name="register-submit">Submit</button>
            </form>
        </div>

    </main>
    <?php include "./templates/footer.php" ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>