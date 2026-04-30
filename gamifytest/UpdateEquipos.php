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



$idEquipo = $_POST["idEquipo"];
//$avatar = $_POST["avatar"];
$puntuacion = $_POST["puntuacion"];






// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE Equipos SET puntuacion = puntuacion + '$puntuacion' WHERE idEquipo = '$idEquipo'";
   //$sql = "SELECT contraseÃ±a  FROM Usuarios " ;

   $sth = $conn->query($sql);
   $sth->setFetchMode(PDO::FETCH_ASSOC);
    
   $result = $sth->fetchAll();

    echo "Equipo Actualizado correctamente from php";
       
    
    } catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
    }
  
  $conn = null;




?>