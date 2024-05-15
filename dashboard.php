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
    <title>WRC Fantasy - Dashboard</title>
    <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <?php
            include 'header.php';
        ?>
        <main>
            <h2>Upcoming Events</h2>
            <div id="events-list">
                <!-- Events will be dynamically loaded here via PHP -->
            </div>
            <h2>League Standings</h2>
            <div id="league-standings">
                <!-- League standings will be dynamically loaded here via PHP -->
            </div>
        </main>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
