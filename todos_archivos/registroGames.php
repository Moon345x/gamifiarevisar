<?php
$servername = "localhost";
$username = "admin";
$password = "univalle**";
$dbname = "bdgames";


//variables enviadas por el usuari0
$nombre = $_POST["nombre"];
$correo = $_POST["correo"];
$contraseña = $_POST["contraseña"];
$curso = $_POST["curso"];
$edad = $_POST["edad"];


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO Usuarios (nombre,correo,contraseña,avatar,puntuacion,curso,edad,equipo)
VALUES ('". $nombre "' , '". $correo"', '" . $contraseña "',0,0,'" . $curso"','" . $edad "',0)";

if ($conn->query($sql) === TRUE) {
  echo "New User Created";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>