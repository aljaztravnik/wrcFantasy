<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wrcFantasy";

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the username already exists
    $usernameCheck = "SELECT * FROM Uporabnik WHERE uporabnisko_ime=?";
    $stmt = $conn->prepare($usernameCheck);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $usernameCheck_result = $stmt->get_result();
    if ($usernameCheck_result->num_rows > 0) {
        echo "Username already exists.";
        header("Location: login.php?error=invalid_username");
        exit();
    }

    // Insert the new user into the database
    $register = "INSERT INTO Uporabnik (uporabnisko_ime, geslo) VALUES (?, ?)";
    $stmt = $conn->prepare($register);
    $stmt->bind_param("ss", $username, $password);
    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        echo "Registration successful.";
        header("Location: dashboard.php");
    } else {
        echo "Registration failed.";
        header("Location: register.php");
    }
}

// Redirect to login page if accessed directly
header("Location: register.php");
exit();
?>