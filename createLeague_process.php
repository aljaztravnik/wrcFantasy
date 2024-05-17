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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $league_name = $_POST['league_name'];
    $username = $_SESSION['username'];

    // Find the user ID based on the username
    $userQuery = "SELECT idUporabnik FROM Uporabnik WHERE uporabnisko_ime=?";
    $stmt = $conn->prepare($userQuery);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $userResult = $stmt->get_result();
    if ($userResult->num_rows > 0) {
        $userRow = $userResult->fetch_assoc();
        $userId = $userRow['idUporabnik'];

        // Insert the new league into the database
        $insertLeague = "INSERT INTO Liga (ime, id_lastnika) VALUES (?, ?)";
        $stmt = $conn->prepare($insertLeague);
        $stmt->bind_param("si", $league_name, $userId);
        if ($stmt->execute()) {
            echo "League created successfully.";

            // Get new League ID
            $leagueIdQuery = "SELECT idLiga FROM Liga WHERE ime=?";
            $stmt = $conn->prepare($leagueIdQuery);
            $stmt->bind_param("s", $league_name);
            $stmt->execute();
            $leagueIdQueryResult = $stmt->get_result();
            if($leagueIdQueryResult->num_rows > 0){
                $leagueIdRow = $leagueIdQueryResult->fetch_assoc();
                $leagueId = $leagueIdRow['idLiga'];

                // Insert the user and league ID into the Uporabnik_has_Liga table
                $insertUporabnikHasLiga = "INSERT INTO Uporabnik_has_Liga (Uporabnik_idUporabnik, Liga_idLiga) VALUES (?, ?)";
                $stmt = $conn->prepare($insertUporabnikHasLiga);
                $stmt->bind_param("ii", $userId, $leagueId);
                if($stmt->execute()){
                    echo "User and League are connected.";

                    // Insert the user and league ID into the Uporabnik_has_Liga table
                    $tocke = mt_rand(1, 200);
                    $insertLeaderboard = "INSERT INTO Leaderboard (id_uporabnika, tocke, id_lige) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($insertLeaderboard);
                    $stmt->bind_param("iii", $userId, $tocke, $leagueId);
                    if($stmt->execute()){
                        echo "User leaderboard score created.";
                    }
                }
            }
            header("Location: allLeagues.php");
        } else {
            echo "Error creating league.";
            header("Location: allLeagues.php?error=creation_failed");
        }
    } else {
        echo "User not found.";
        header("Location: allLeagues.php?error=user_not_found");
    }
    $stmt->close();
}

$conn->close();
exit();
?>