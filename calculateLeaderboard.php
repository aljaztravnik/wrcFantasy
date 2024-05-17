<?php
$finishingOrderDrivers = array(
    1 => 7, //"Sébastien Ogier",
    2 => 11, //"Ott Tänak",
    3 => 9, //"Kalle Rovanperä",
    4 => 8, //"Thierry Neuville",
    5 => 5, //"Takamoto Katsuta",
    6 => 3, //"Elfyn Evans",
    7 => 4, //"Adrien Fourmaux",
    8 => 2, //"Esapekka Lappi",
    9 => 12, //"Andreas Mikkelsen",
    10 => 10, //"Dani Sordo",
    11 => 6, //"Grégoire Munster",
    12 => 1, //"Lorenzo Bertelli",
    13 => 13, //"Jourdan Serderidis",
);
$finishingOrderTeams = array(
    1 => 3, //"Toyota Gazoo Racing World Team",
    2 => 1, //"Hyundai Shell Mobis World Rally Team",
    3 => 2, //"M-Sport Ford World Rally Team",
);
$driverPoints = array(1 => 18, 2 => 15, 3 => 13, 4 => 10, 5 => 8, 6 => 6, 7 => 4, 8 => 3, 9 => 2, 10 => 1,);
$teamPoints = array(1 => 15, 2 => 10, 3 => 5);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wrcFantasy";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all selections
$naboriQuery = "SELECT * FROM Nabor";
$stmt = $conn->prepare($naboriQuery);
$stmt->execute();
$naboriResult = $stmt->get_result();

if ($naboriResult->num_rows > 0) {
    while ($nabor = $naboriResult->fetch_assoc()) {
        $tocke = 0;
        $idNabor = $nabor['idNabor'];
        $idUporabnik = $nabor['Uporabnik_idUporabnik'];
        $idLiga = $nabor['Liga_idLiga'];

        // Calculate points for selected drivers
        $voznikiQuery = "SELECT * FROM Nabor_has_Vozniki WHERE Nabor_idNabor=?";
        $stmt = $conn->prepare($voznikiQuery);
        $stmt->bind_param("i", $idNabor);
        $stmt->execute();
        $voznikiResult = $stmt->get_result();

        if ($voznikiResult->num_rows > 0) {
            while ($voznik = $voznikiResult->fetch_assoc()) {
                $voznikId = $voznik['Vozniki_idVoznika'];
                if (array_search($voznikId, $finishingOrderDrivers) !== false) {
                    $position = array_search($voznikId, $finishingOrderDrivers);
                    $tocke += isset($driverPoints[$position]) ? $driverPoints[$position] : 0;
                }
            }
        }

        // Calculate points for selected teams
        $teamsQuery = "SELECT * FROM Nabor_has_Ekipe WHERE Nabor_idNabor=?";
        $stmt = $conn->prepare($teamsQuery);
        $stmt->bind_param("i", $idNabor);
        $stmt->execute();
        $teamsResult = $stmt->get_result();

        if ($teamsResult->num_rows > 0) {
            while ($team = $teamsResult->fetch_assoc()) {
                $ekipaId = $team['Ekipe_idEkipe'];
                if (array_search($ekipaId, $finishingOrderTeams) !== false) {
                    $position = array_search($ekipaId, $finishingOrderTeams);
                    $tocke += isset($teamPoints[$position]) ? $teamPoints[$position] : 0;
                }
            }
        }

        // Update user's score in the leaderboard
        $tockeQuery = "SELECT tocke FROM Leaderboard WHERE id_uporabnika=? AND id_lige=?";
        $tockeStmt = $conn->prepare($tockeQuery);
        $tockeStmt->bind_param("ii", $idUporabnik, $idLiga);
        $tockeStmt->execute();
        $tockeResult = $tockeStmt->get_result();
        $tockeAssoc = $tockeResult->fetch_assoc();
        $tocke += $tockeAssoc['tocke'];
        
        $updateQuery = "UPDATE Leaderboard SET tocke = ? WHERE id_uporabnika = ? AND id_lige = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("iii", $tocke, $idUporabnik, $idLiga);
        $updateStmt->execute();
        $updateStmt->close();
    }
}

$conn->close();
?>