<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

//// Include your database connection file
//include 'db_connection.php';
//
//// Get the username of the logged-in user
//$username = $_SESSION['username'];
//
//// Query to get all the leagues the user is joined in
//$sql = "SELECT leagues.name AS league_name, leagues.league_id, leaderboard.position
//        FROM leagues
//        INNER JOIN leaderboard ON leagues.league_id = leaderboard.league_id
//        WHERE leaderboard.username = '$username'";
//$result = mysqli_query($conn, $sql);
//
//// Fetch leagues data
//$leagues = [];
//while ($row = mysqli_fetch_assoc($result)) {
//    $leagues[] = $row;
//}
//
//// Close the connection
//mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WRC Fantasy - My Leagues</title>
    <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <?php include 'header.php'; ?>
        <main>
            <h2>My Leagues</h2>
            <table>
                <thead>
                    <tr>
                        <th>League Name</th>
                        <th>Position in Leaderboard</th>
                    </tr>
                </thead>
                <!--
                <tbody>
                    <?php foreach ($leagues as $league): ?>
                        <tr>
                            <td><?php echo $league['league_name']; ?></td>
                            <td><?php echo $league['position']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                -->
            </table>
        </main>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>