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

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $login_sql = "SELECT geslo FROM Uporabnik WHERE uporabnisko_ime=?";
        $stmt = $conn->prepare($login_sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $login_result = $stmt->get_result();
        
        if($login_result->num_rows > 0){
            $row = $login_result->fetch_assoc();
            $hashed_password_from_db = $row["geslo"];
            // Primerjava zakriptiranega gesla iz baze z vnešenim geslom
            if(password_verify($password, $hashed_password_from_db)){
                echo "Correct password.";
                $_SESSION['username'] = $username;
                header("Location: dashboard.php");
                exit();
            }else{
                echo "Wrong password.";
                header("Location: login.php?error=wrong_password");
                exit();
            }
        }else{
            echo "Username was not found.";
            header("Location: login.php?error=invalid_username");
            exit();
        }
    }
}

// Redirect to login page if accessed directly
header("Location: login.php");
exit();
?>