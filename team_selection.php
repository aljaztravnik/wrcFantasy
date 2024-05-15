<?php
session_start();

if (!isset($_SESSION['username'])) {
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
    <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <?php
            include 'header.php';
        ?>
        <main>
            <h2>Choose Your Drivers</h2>
            <div id="drivers-list">
                <!-- Drivers will be dynamically loaded here via PHP -->
            </div>
            <h2>Choose Your Teams</h2>
            <div id="teams-list">
                <!-- Teams will be dynamically loaded here via PHP -->
            </div>
            <button id="submit-team">Submit Team</button>
        </main>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
