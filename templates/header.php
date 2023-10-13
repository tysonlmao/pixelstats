<nav class="navbar mt-2 mb-2">
    <div class="container">
        <a href="/" class="nav-link">pixelstats</a>
        <div class="d-flex justify-content-end">
            <?php
            if (isset($_SESSION['userId'])) {
                // User is logged in
                echo '<a href="/dashboard.php" class="nav-link">Dashboard</a>';
                echo '<a href="/settings.php" class="nav-link">Settings</a>';
                echo '<a href="/logout.php" class="nav-link">Sign out</a>';

                // Check if the user has the 'Admin' role
                if (isset($_SESSION['userRole']) && $_SESSION['userRole'] === 'Admin') {
                    echo '<a href="/admin/index.php" class="nav-link">Admin</a>';
                }
            } else {
                // User is not logged in, show login and register
                echo '<a href="/login.php" class="nav-link">Login</a>';
            }
            ?>
        </div>
    </div>
</nav>