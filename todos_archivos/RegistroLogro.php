<?php

$servername = "localhost";
$username = "admin";
$password = "univalle**";
$dbname   = "bdgames";


$idUsuario = $_POST["idUsuario"];
$idLogro = $_POST["idLogro"];


// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     
    $sql = "SELECT * FROM Logros_Usuarios WHERE idUsuario = '$idUsuario' AND idLogro = '$idLogro'";

    $sth = $conn->query($sql);
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    
    $result = $sth->fetchAll();

    if (count($result) > 0) 
    {
        echo "El correo ya se encuentra en uso";
    }else{
        $sql = "INSERT INTO Logros_Usuarios (idUsuario,idLogro)
        VALUES ('$idUsuario' , '$idLogro')";
        // use exec() because no results are returned
        $conn->query($sql);
        echo "New Logro created successfully";

    }
   
  } catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }
  
  $conn = null;

?>