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
            <a href="/index.php" class="nav-link"><i class="bi bi-house-door"></i></a>
            <a href="/dashboard.php" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="/admin/index.php" class="nav-link"><i class="bi bi-people"></i> Manage users</a>
            <a href="/settings.php" class="nav-link"><i class="bi bi-gear"></i> Settings</a>
            <a href="/logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i> Sign out</a>
            <?php if (isset($_SESSION['userUsername'])) : ?>
                <p">Howdy, <?php echo $_SESSION['userUsername']; ?></p>
                <?php endif; ?>
        </div>
    <?php else : // Display this code if the user is not an admin 
    ?>
        <?php if (isset($_SESSION['userId'])) : // User is logged in 
        ?>
            <a href="/dashboard.php" class="nav-link">Dashboard</a>
            <a href="/settings.php" class="nav-link">Settings</a>
            <a href="/logout.php" class="nav-link">Sign out</a>

            <?php if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'Admin') : // User is not an admin 
            ?>
                <a href="/admin/index.php" class="nav-link">Admin</a>
            <?php endif; ?>
            <?php if (isset($_SESSION['userUsername'])) : ?>
                <a class="nav-link d-flex justify-content-end">Howdy, <?php echo $_SESSION['userUsername']; ?></a>
            <?php endif; ?>
        <?php else : // User is not logged in, show login and register 
        ?>
            <a href="/login.php" class="nav-link">Login</a>
        <?php endif; ?>
    <?php endif; ?>
</div>
</div>
</nav>