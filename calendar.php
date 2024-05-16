<?php
session_start();
include 'events.php';
//if (!isset($_SESSION['username'])) {
//    header("Location: login.php");
//    exit();
//}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WRC Fantasy - Calendar</title>
    <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <?php include 'header.php'; ?>
        <main>
            <h2>WRC Calendar Events 2024</h2>
            <div class="events-grid">
                <?php foreach ($rally_events as $event): ?>
                    <div class="event-card">
                        <img src="<?php echo $event['image_link']; ?>" alt="<?php echo $event['event_name']; ?>">
                        <div class="event-info">
                            <h3><?php echo $event['event_name']; ?></h3>
                            <p>Start Date: <?php echo $event['startDate']; ?></p>
                            <p>End Date: <?php echo $event['endDate']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>