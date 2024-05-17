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

$query = "SELECT Liga.idLiga, Liga.ime, Uporabnik.uporabnisko_ime 
          FROM Liga 
          JOIN Uporabnik ON Liga.id_lastnika = Uporabnik.idUporabnik";
$result = $conn->query($query);
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
        .wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .content {
            width: 80%;
            max-width: 800px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .top-right {
            display: flex;
            justify-content: flex-end;
        }
        .form-container {
            display: none;
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            width: 100%;
            max-width: 600px;
        }
        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .form-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function toggleForm() {
            var formContainer = document.getElementById('form-container');
            if (formContainer.style.display === 'none' || formContainer.style.display === '') {
                formContainer.style.display = 'block';
            } else {
                formContainer.style.display = 'none';
            }
        }
    </script>
</head>
<body>
<?php include 'header.php'; ?>
    <div class="wrapper">
        <div class="top-right">
            <a href="javascript:void(0);" class="link2" onclick="toggleForm()">Create League</a>
        </div>
        <div id="form-container" class="form-container">
            <form action="createLeague_process.php" method="POST">
                <label for="league_name">League Name:</label>
                <input type="text" id="league_name" name="league_name" required>
                <button type="submit">Create League</button>
            </form>
        </div>
        <div class="content">
            <h1>Available Leagues</h1>
            <table>
                <tr>
                    <th>League Name</th>
                    <th>Owner</th>
                    <th>Action</th>
                </tr>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['ime']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['uporabnisko_ime']) . "</td>";
                        echo "<td><a href='join_league.php?id=" . $row['idLiga'] . "' class='link2'>Join</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No leagues available</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>

<?php
$conn->close();
?>
