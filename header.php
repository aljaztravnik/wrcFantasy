<?php
session_start();

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['username']);
}

// Include this file in your dashboard and team selection pages
?>

<header>
    <h1>Welcome to WRC Fantasy</h1>
    <nav>
        <ul>
            <li><a href="dashboard.php" class="link">Dashboard</a></li>
            <li><a href="team_selection.php" class="link">Team Selection</a></li>
            <!-- Add more links as needed -->
        </ul>
        <div id="login-status">
            <?php if (isLoggedIn()): ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </nav>
</header>