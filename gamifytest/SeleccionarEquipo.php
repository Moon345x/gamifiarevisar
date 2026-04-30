<?php



class TableRows extends RecursiveIteratorIterator {
    function __construct($it) {
      parent::__construct($it, self::LEAVES_ONLY);
    }
  
    function current() {
      return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
    }
  
    function beginChildren() {
      echo "<tr>";
    }
  
    function endChildren() {
      echo "</tr>" . "\n";
    }
  }



$servername = "localhost";
$username = "admin";
$password = "univalle**";
$dbname   = "bdgames";



$idUsuario = $_POST["idUsuario"];
$equipo = $_POST["equipo"];
//$puntuacion = $_POST["puntuacion"];



// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE Usuarios SET equipo = '$equipo'  WHERE idUsuario = '$idUsuario'";
   //$sql = "SELECT contraseña  FROM Usuarios " ;

   $sth = $conn->query($sql);
   //$sth->setFetchMode(PDO::FETCH_ASSOC);
    
   //$result = $sth->fetchAll();

    echo "cambio el equipo";
       
    
    } catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
    }
  
  $conn = null;




?>