<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wrcFantasy";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];

// Fetching leagues and leaderboard positions for the logged-in user
$sql = "SELECT l.ime AS league_name, lb.tocke AS points, lb.idLeaderboard, l.idLiga AS idLige
        FROM Leaderboard lb
        INNER JOIN Liga l ON lb.id_lige = l.idLiga
        INNER JOIN Uporabnik u ON lb.id_uporabnika = u.idUporabnik
        WHERE u.uporabnisko_ime = '$username'";
$result = $conn->query($sql);

$leagues = [];
while ($row = $result->fetch_assoc()) {
    $leagues[] = $row;
}
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
                        <th>Points in league leaderboard</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($leagues as $league) { 
                        $url = "leagueShow.php?id=" . $league['idLige']?>
                        <tr>
                            <td><a class="link2" href="<?php echo $url; ?>"><?php echo $league['league_name']; ?></a></td>
                            <td><?php echo $league['points']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </main>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
