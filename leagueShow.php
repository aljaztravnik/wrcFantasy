<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: dashboard.php");
    exit();
}

$id_lige = $_GET['id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wrcFantasy";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_get_ime = "SELECT ime FROM Liga WHERE idLiga='$id_lige';";


$result_ime_lige = $conn->query($sql_get_ime);
$imeLige = $result_ime_lige->fetch_assoc()['ime'];

$sql_get_leaderboard = "SELECT u.uporabnisko_ime AS username, l.id_lastnika AS lastnik, u.idUporabnik, lb.tocke FROM Leaderboard lb
                        INNER JOIN Uporabnik u on u.idUporabnik = lb.id_uporabnika
                        INNER JOIN Liga l on l.idLiga = lb.id_lige
                        WHERE lb.id_lige='$id_lige';";
$result_get_leaderboard = $conn->query($sql_get_leaderboard);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WRC Fantasy - My Leagues</title>
    <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <style>
        table {
            width: 80%; /* Adjust table width as needed */
            margin: 0 auto; /* Center the table horizontally */
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .owner {
            font-weight: bold;
            color: rgb(255, 156, 18); /* Change color as needed */
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php include 'header.php'; ?>
        <main>
            <h1 style="text-align: center; font-size: 60px;"><?php echo $imeLige ?></h1>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    while ($row = $result_get_leaderboard->fetch_assoc()) {
                        $username = $row['username'];
                        $points = $row['tocke'];
                        $isOwner = ($row['lastnik'] == $row['idUporabnik']); // Check if the user is the owner

                        // Apply different styles for the owner
                        $class = $isOwner ? 'owner' : '';
                        ?>
                        <tr>
                            <td class="<?php echo $class; ?>"><?php echo $username; ?></td>
                            <td><?php echo $points; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </main>
        <?php $url = "team_selection.php?id=" . $id_lige?>
        <a class="link2" href="<?php echo $url; ?>"><h3 style="text-align: center;">CHOOSE YOUR DRIVERS</h3></a>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>