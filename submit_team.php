<?php
session_start();

// Function to send JSON response
function sendResponse($success, $message) {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit();
}

if (!isset($_SESSION['username'])) {
    sendResponse(false, 'User not authenticated.');
}

if (!isset($_GET['id']) || !isset($_GET['team']) || !isset($_GET['driver1']) || !isset($_GET['driver2'])) {
    sendResponse(false, 'Parameters missing.');
}

$id_lige = $_GET['id'];
$team = $_GET['team'];
$driver1 = $_GET['driver1'];
$driver2 = $_GET['driver2'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wrcFantasy";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    sendResponse(false, 'Database connection failed: ' . $conn->connect_error);
}

// Get the current user's ID
$username = $_SESSION['username'];
$get_user_id_sql = "SELECT idUporabnik FROM Uporabnik WHERE uporabnisko_ime = ?";
$stmt = $conn->prepare($get_user_id_sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['idUporabnik'];
} else {
    sendResponse(false, 'User not found.');
}

$stmt->close();

// Create a new entry in the Nabor table
$create_nabor_sql = "INSERT INTO Nabor (Uporabnik_idUporabnik, Liga_idLiga) VALUES (?, ?)";
$stmt = $conn->prepare($create_nabor_sql);
$stmt->bind_param("ii", $user_id, $id_lige);
if ($stmt->execute()) {
    $nabor_id = $conn->insert_id;

    // Insert drivers into Nabor_has_Vozniki
    $driver_ids = [$driver1, $driver2];
    foreach ($driver_ids as $driver_id) {
        $driver_id = (int)$driver_id;
        $insert_driver_sql = "INSERT INTO Nabor_has_Vozniki (Nabor_idNabor, Vozniki_idVoznika, Vozniki_Ekipe_idEkipe) VALUES (?, ?, (SELECT Ekipe_idEkipe FROM Vozniki WHERE idVoznika=?))";
        $stmt = $conn->prepare($insert_driver_sql);
        $stmt->bind_param("iii", $nabor_id, $driver_id, $driver_id);
        if (!$stmt->execute()) {
            sendResponse(false, 'Error inserting driver: ' . $stmt->error);
        }
    }
    
    // Insert team into Nabor_has_Ekipe
    $team_id = (int)$team;
    $insert_team_sql = "INSERT INTO Nabor_has_Ekipe (Nabor_idNabor, Ekipe_idEkipe) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_team_sql);
    $stmt->bind_param("ii", $nabor_id, $team_id);
    if ($stmt->execute()) {
        sendResponse(true, 'Team saved successfully.');
    } else {
        sendResponse(false, 'Error inserting team: ' . $stmt->error);
    }
} else {
    sendResponse(false, 'Error creating nabor: ' . $stmt->error);
}

$stmt->close();
$conn->close();
?>
