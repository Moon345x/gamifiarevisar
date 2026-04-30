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



$correo = $_POST["correo"];
$contraseña = $_POST["contraseña"];


// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM Usuarios WHERE correo = '" . $correo . "'" ;
   //$sql = "SELECT contraseña  FROM Usuarios " ;

   $sth = $conn->query($sql);
   $sth->setFetchMode(PDO::FETCH_ASSOC);
    
   $result = $sth->fetchAll();

    if (count($result) > 0) 
    {
        foreach($result as $r) 
        {
            if($r['contraseña'] == $contraseña){
                //echo "Usted se logueo perfectakmente ";
                echo json_encode($r);
            }else{
                echo "sus credenciales no son validas";
            }
            
        }
    }else{
        echo "sus credenciales no son validas";
    }
   
    
    } catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
    }
  
  $conn = null;




?>