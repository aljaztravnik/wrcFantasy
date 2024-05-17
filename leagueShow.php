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
            width: 80%;
            margin: 0 auto;
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
            color: rgb(255, 156, 18);
        }

        .selected-items-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2rem;
            margin-top: 2rem;
        }

        .item {
            border: 2px solid #ccc;
            border-radius: 5px;
            padding: 5px;
            text-align: center;
            cursor: pointer;
            max-width: 250px;
            max-height: 300px;
        }

        .item img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .driver-info, .team-info {
            margin-top: 10px;
        }

        .driver-name, .team-name {
            font-size: 1.2em;
            font-weight: bold;
        }

        .driver-price, .team-price {
            color: #888;
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
            <?php
            $id_lige = (int) $id_lige;
            $un = $_SESSION['username'];
            $sql_nabor_isset = "SELECT n.idNabor FROM Nabor n
                                INNER JOIN Uporabnik u on n.Uporabnik_idUporabnik = u.idUporabnik
                                WHERE n.Liga_idLiga='$id_lige' AND u.uporabnisko_ime='$un'";
            $result = $conn->query($sql_nabor_isset);
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $id_nabora = $row['idNabor']; 
                $id_nabora = (int) $id_nabora;
                echo "<h3 style='text-align: center;'>SELECTED TEAM:</h3>";
                $sql_get_id_up = "SELECT idUporabnik FROM Uporabnik WHERE uporabnisko_ime='$un'";
                $res = $conn->query($sql_get_id_up);
                $row = $res->fetch_assoc();
                $userID = $row['idUporabnik'];
                $drivers_sql = "SELECT v.idVoznika, `ime in priimek` AS name, v.cena AS price, v.img_src AS image FROM Vozniki v
                                INNER JOIN Nabor_has_Vozniki nv ON v.idVoznika=nv.Vozniki_idVoznika
                                INNER JOIN Nabor n ON n.idNabor=nv.Nabor_idNabor
                                WHERE n.Uporabnik_idUporabnik='$userID' AND n.Liga_idLiga='$id_lige';";
                $teams_sql = "SELECT e.idEkipe AS id, e.ime AS name, e.cena AS price, e.img_src AS image FROM Ekipe e
                                INNER JOIN Nabor_has_Ekipe ne ON ne.Ekipe_idEkipe=e.idEkipe
                                INNER JOIN Nabor n ON n.idNabor=ne.Nabor_idNabor
                                WHERE n.Uporabnik_idUporabnik='$userID' AND n.Liga_idLiga='$id_lige';";

                $drivers_result = $conn->query($drivers_sql);
                $teams_result = $conn->query($teams_sql);

                echo "<div class='selected-items-container'>";
                while ($row = $drivers_result->fetch_assoc()) {
                    echo "<div class='driver-item item' data-id='" . $row['idVoznika'] . "' data-price='" . $row['price'] . "'>";
                    echo "<img src='" . $row['image'] . "' alt='" . $row['name'] . "' class='driver-image'>";
                    echo "<div class='driver-info'>";
                    echo "<p class='driver-name'>" . $row['name'] . "</p>";
                    echo "<p class='driver-price'>" . $row['price'] . "M €</p>";
                    echo "</div></div>";
                }

                while ($row = $teams_result->fetch_assoc()) {
                    echo "<div class='team-item item' data-id='" . $row['id'] . "' data-price='" . $row['price'] . "'>";
                    echo "<img src='" . $row['image'] . "' alt='" . $row['name'] . "' class='team-image'>";
                    echo "<div class='team-info'>";
                    echo "<p class='team-name'>" . $row['name'] . "</p>";
                    echo "<p class='team-price'>" . $row['price'] . "M €</p>";
                    echo "</div></div>";
                }
                echo "</div>";
                $url = "team_selection.php?id=" . $id_lige . "&del=" . $id_nabora;
                echo "<a class='link2' href='" . $url . "' onclikc='return confirmDeletion();'><h3 style='text-align: center;'>CHANGE TEAM</h3></a>";
            } else {
                $url = "team_selection.php?id=" . $id_lige;
                echo "<a class='link2' href='" . $url . "'><h3 style='text-align: center;'>CHOOSE YOUR DRIVERS</h3></a>";
            }
        ?>
        <script>
            function confirmDeletion(){
                return confirm("Are you sure you want to change the selection?");
            }
        </script>
        </main>
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>
