<?php
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated.']);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wrcFantasy";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$drivers = $data['drivers'];
$team = $data['team'];

if (count($drivers) != 2 || !$team) {
    echo json_encode(['success' => false, 'message' => 'Invalid selection.']);
    exit();
}

// Get the current user's ID
$username = $_SESSION['username'];
$get_user_id_sql = "SELECT idUporabnik FROM Uporabnik WHERE uporabnisko_ime = '$username'";
$result = $conn->query($get_user_id_sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['idUporabnik'];
} else {
    echo json_encode(['success' => false, 'message' => 'User not found.']);
    exit();
}

// Create a new entry in the Nabor table
$create_nabor_sql = "INSERT INTO Nabor (Uporabnik_idUporabnik, Liga_idLiga) VALUES ($user_id, 1)";
if ($conn->query($create_nabor_sql) === TRUE) {
    $nabor_id = $conn->insert_id;
    
    // Insert drivers into Nabor_has_Vozniki
    foreach ($drivers as $driver) {
        $driver_id = (int)$driver;
        $insert_driver_sql = "INSERT INTO Nabor_has_Vozniki (Nabor_idNabor, Vozniki_idVoznika, Vozniki_Ekipe_idEkipe) VALUES ($nabor_id, $driver_id, 1)";
        $conn->query($insert_driver_sql);
    }
    
    // Insert team into Nabor_has_Ekipe
    $team_id = (int)$team;
    $insert_team_sql = "INSERT INTO Nabor_has_Ekipe (Nabor_idNabor, Ekipe_idEkipe) VALUES ($nabor_id, $team_id)";
    $conn->query($insert_team_sql);

    echo json_encode(['success' => true, 'message' => 'Team saved successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error creating nabor.']);
}

$conn->close();
?>
