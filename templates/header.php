<?php if (isset($_SESSION['userId'])) : // User is logged in 
?>
    <nav class="navbar navbar-expand-lg content">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="/index.php">pixelstats</a>
            <ul class="navbar-nav">
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="/dashboard.php" class="nav-link text-white">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="/settings.php" class="nav-link text-white">Settings</a>
                </li>
                <?php if (isset($_SESSION['userRole']) && $_SESSION['userRole'] == "Admin") :
                ?>
                    <li class="nav-item">
                        <a href="/admin/index.php" class="nav-link text-white">Admin</a>
                    </li>
                <?php
                endif; ?>
                <li class="nav-item">
                    <a href="/logout.php" class="nav-link text-white">Sign Out</a>
                </li>
            </ul>
        </div>
    </nav>
<?php else : // User is not logged in, show login and register
?>
    <nav class="navbar navbar-expand-lg content">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="/index.php">pixelstats</a>
            <ul class="navbar-nav">
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="btn btn-primary" href="/login.php">Login</a>
                </li>
            </ul>
        </div>
    </nav>
<?php endif; ?>
</nav>