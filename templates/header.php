<?php
$showAdminBar = false;
if (isset($_SESSION['userId'])) {
    // User is logged in
    if (isset($_SESSION['userRole']) && $_SESSION['userRole'] === 'Admin') {
        // User is an admin
        $showAdminBar = true;
    }
}
?>
<div class="navbar">
    <?php if ($showAdminBar) : ?>
        <div class="admin-bar">
            <a href="/index.php" class="nav-link"><i class="bi bi-house-door"></i> Home</a>
            <a href="/dashboard.php" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="/admin/index.php" class="nav-link"><i class="bi bi-people"></i> Manage Users</a>
            <a href="/settings.php" class="nav-link"><i class="bi bi-gear"></i> Settings</a>
            <a href="/logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i> Sign Out</a>
            <?php if (isset($_SESSION['userUsername'])) : ?>
                <p class="nav-text">Howdy, <?php echo $_SESSION['userUsername']; ?></p>
            <?php endif; ?>
        </div>
    <?php else : // Display this code if the user is not an admin 
    ?>
        <?php if (isset($_SESSION['userId'])) : // User is logged in 
        ?>
            <a href="/dashboard.php" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="/settings.php" class="nav-link"><i class="bi bi-gear"></i> Settings</a>
            <a href="/logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i> Sign Out</a>
            <?php if (isset($_SESSION['userUsername'])) : ?>
                <p class="nav-text">Howdy, <?php echo $_SESSION['userUsername']; ?></p>
            <?php endif; ?>
        <?php else : // User is not logged in, show login and register
        ?>
            <nav class="navbar navbar-expand-lg content">
                <div class="container-fluid">
                    <a class="navbar-brand text-white" href="/index.php">pixelstats</a>
                    <ul class="navbar-nav">
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="btn text-white" href="/login.php">Login</a>
                        </li>
                    </ul>
                </div>
            </nav>
        <?php endif; ?>


    <?php endif; ?>
</div>
</div>
</nav>