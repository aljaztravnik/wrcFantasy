<?php
session_start();

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['username']);
}

// Redirect to login page if user is not logged in
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WRC Fantasy - Team Selection</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
        include 'header.php';
    ?>
    <main>
        <h2>Choose Your Drivers</h2>
        <div id="drivers-list">
            <!-- Drivers will be dynamically loaded here via JavaScript -->
        </div>
        <h2>Choose Your Teams</h2>
        <div id="teams-list">
            <!-- Teams will be dynamically loaded here via JavaScript -->
        </div>
        <button id="submit-team">Submit Team</button>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> WRC Fantasy</p>
    </footer>
</body>
</html>
