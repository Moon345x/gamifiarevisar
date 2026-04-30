<?php

$servername = "localhost";
$username = "admin";
$password = "univalle**";
$dbname   = "bdgames";


$nombre = $_POST["nombre"];
$correo = $_POST["correo"];
$contraseña = $_POST["contraseña"];
$curso = $_POST["curso"];
$edad = $_POST["edad"];

// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     
    $sql = "SELECT * FROM Usuarios WHERE correo = '$correo'";

    $sth = $conn->query($sql);
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    
    $result = $sth->fetchAll();

    if (count($result) > 0) 
    {
        echo "El correo ya se encuentra en uso";
    }else{
        $sql = "INSERT INTO Usuarios (nombre,correo,contraseña,avatar,puntuacion,curso,edad,equipo)
        VALUES ('$nombre' , '$correo', '$contraseña',0,0,'$curso','$edad',0)";
        // use exec() because no results are returned
        $conn->query($sql);
        echo "New record created successfully";

    }
   
  } catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }
  
  $conn = null;


/* 

if (!$conn->query($sql)) {
    echo "New User Created";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
$conn->close(); */



/* 

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
die("Connection buenarda: ");
$sql = "INSERT INTO Usuarios (nombre,correo,contraseña,avatar,puntuacion,curso,edad,equipo)
VALUES ('". $nombre "' , '". $correo"', '" . $contraseña "',0,0,'" . $curso"','" . $edad "',0)";

if ($conn->query($sql) === TRUE) {
  echo "New User Created";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close(); */
?>