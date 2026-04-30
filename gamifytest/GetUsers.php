<?php
$servername = "localhost";
$username = "admin";
$password = "univalle**";
$dbname   = "bdgames";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT idEquipo, nombre, puntuacion FROM Equipos";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "id: " . $row["idEquipo"]. " - Name: " . $row["nombre"]. " " . $row["puntuacion"]. "<br>";
  }
} else {
  echo "0 results";
}
$conn->close();
?>