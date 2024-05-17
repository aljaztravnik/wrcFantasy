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

// Get user ID from username
$sql = "SELECT idUporabnik FROM Uporabnik WHERE uporabnisko_ime = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['idUporabnik'];

// Handle joining a league via GET request
if (isset($_GET['id'])) {
    $league_id = $_GET['id'];

    // Check if user is already in the league
    $check_sql = "SELECT * FROM Uporabnik_has_Liga WHERE Uporabnik_idUporabnik = ? AND Liga_idLiga = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $league_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows == 0) {
        // Add user to the league
        $insert_sql = "INSERT INTO Uporabnik_has_Liga (Uporabnik_idUporabnik, Liga_idLiga) VALUES (?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ii", $user_id, $league_id);
        $insert_stmt->execute();

        // Add user to the league
        $tocke = 0;
        $insert_sql = "INSERT INTO Leaderboard (id_uporabnika, tocke, id_lige) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iii", $user_id, $tocke, $league_id);
        $insert_stmt->execute();
        echo "<p>You have successfully joined the league!</p>";
        header("Location: leagues.php");
        exit();
    } else {
        echo "<p>You are already a member of this league.</p>";
    }
}

?>