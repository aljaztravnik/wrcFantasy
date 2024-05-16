<?php
// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['username']);
}
?>

<header>
    <h1>Welcome to WRC Fantasy</h1>
    <nav>
        <ul>
            <li><a href="dashboard.php" class="link">Dashboard</a></li>
            <li><a href="team_selection.php" class="link">Team Selection</a></li>
            <li><a href="leagues.php" class="link">My Leagues</a></li>
            <li><a href="calendar.php" class="link">Calendar</a></li>
            <!-- Add more links as needed -->
        </ul>
        <div id="login-status">
            <?php if (isLoggedIn()): ?>
                <a href="logout.php" class="link2">Logout</a>
            <?php else: ?>
                <a href="login.php" class="link2">Login</a>
                <a href="register.php" class="link2">Register</a>
            <?php endif; ?>
        </div>
    </nav>
</header>