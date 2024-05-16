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

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$drivers_sql = "SELECT idVoznika, `ime in priimek` AS name, cena AS price, img_src AS image FROM Vozniki";
$teams_sql = "SELECT idEkipe AS id, ime AS name, cena AS price, img_src AS image FROM Ekipe";

$drivers_result = $conn->query($drivers_sql);
$teams_result = $conn->query($teams_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WRC Fantasy - Team Selection</title>
    <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <?php include 'header.php'; ?>
        <main>
            <h1 id="budget">Budget: 60 million €</h1>
            <h2>Choose Your Drivers</h2>
            <div id="drivers-list" class="item-list">
                <?php
                if ($drivers_result->num_rows > 0) {
                    while($row = $drivers_result->fetch_assoc()) {
                        echo "<div class='driver-item item' data-id='" . $row['idVoznika'] . "' data-price='" . $row['price'] . "'>";
                        echo "<img src='" . $row['image'] . "' alt='" . $row['name'] . "' class='driver-image'>";
                        echo "<div class='driver-info'>";
                        echo "<p class='driver-name'>" . $row['name'] . "</p>";
                        echo "<p class='driver-price'>" . $row['price'] . "M €</p>";
                        echo "</div></div>";
                    }
                } else {
                    echo "0 results";
                }
                ?>
            </div>
            <h2>Choose Your Team</h2>
            <div id="teams-list" class="item-list">
                <?php
                if ($teams_result->num_rows > 0) {
                    while($row = $teams_result->fetch_assoc()) {
                        echo "<div class='team-item item' data-id='" . $row['id'] . "' data-price='" . $row['price'] . "'>";
                        echo "<img src='" . $row['image'] . "' alt='" . $row['name'] . "' class='team-image'>";
                        echo "<div class='team-info'>";
                        echo "<p class='team-name'>" . $row['name'] . "</p>";
                        echo "<p class='team-price'>" . $row['price'] . "M €</p>";
                        echo "</div></div>";
                    }
                } else {
                    echo "0 results";
                }
                ?>
            </div>
            <button id="submit-team">Submit Team and Drivers</button>
        </main>
    </div>
    <?php include 'footer.php'; ?>
    
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        let selectedDrivers = [];
        let selectedTeam = null;
        let budget = 60000000; // 60 million €

        function updateBudgetDisplay() {
            document.getElementById("budget").innerText = `Budget: ${budget.toLocaleString()} €`;
        }

        function toggleSelection(element, selectedArray, maxSelections) {
            const id = element.getAttribute("data-id");
            const price = parseInt(element.getAttribute("data-price"));

            if (element.classList.contains("selected")) {
                element.classList.remove("selected");
                const index = selectedArray.indexOf(id);
                if (index > -1) {
                    selectedArray.splice(index, 1);
                    budget += price * 1000000;
                }
            } else {
                if (selectedArray.length < maxSelections) {
                    element.classList.add("selected");
                    selectedArray.push(id);
                    budget -= price * 1000000;
                }
            }
            updateBudgetDisplay();
        }

        document.querySelectorAll(".driver-item").forEach(item => {
            item.addEventListener("click", function() {
                toggleSelection(item, selectedDrivers, 2);
            });
        });

        document.querySelectorAll(".team-item").forEach(item => {
            item.addEventListener("click", function() {
                const id = item.getAttribute("data-id");
                const price = parseInt(item.getAttribute("data-price"));

                if (selectedTeam === id) {
                    item.classList.remove("selected");
                    selectedTeam = null;
                    budget += price * 1000000;
                } else {
                    if (selectedTeam) {
                        document.querySelector(`.team-item[data-id="${selectedTeam}"]`).classList.remove("selected");
                        budget += parseInt(document.querySelector(`.team-item[data-id="${selectedTeam}"]`).getAttribute("data-price")) * 1000000;
                    }
                    item.classList.add("selected");
                    selectedTeam = id;
                    budget -= price * 1000000;
                }
                updateBudgetDisplay();
            });
        });

        document.getElementById("submit-team").addEventListener("click", function() {
            if (selectedDrivers.length === 2 && selectedTeam && budget >= 0) {
                alert("Team submitted successfully!");
                // Submit data to the server
                fetch('submit_team.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        drivers: selectedDrivers,
                        team: selectedTeam
                    })
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          alert("Team saved successfully!");
                      } else {
                          alert("Error saving team.");
                      }
                  });
            } else {
                alert("Please select 2 drivers and 1 team and ensure your budget is within the limit.");
            }
        });

        updateBudgetDisplay();
    });
    </script>
</body>
</html>

<?php
$conn->close();
?>
