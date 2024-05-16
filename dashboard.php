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
            <?php include 'currentEvent.php'; ?>
            <!-- Profile Summary Section
            <div id="profile-summary">
                <h3>Profile Summary</h3>
                <p>Total Score: 300</p>
                <p>Leagues Joined: 3</p>
                <p>Current Rank: 5</p>
            </div>
            -->
        </main>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>