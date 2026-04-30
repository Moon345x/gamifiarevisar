<?php
    
    require "controller/connection.php";
    
    // consult the existence or results

    // sql consulta general
    $sqlGen = "SELECT * FROM quiz_general WHERE id_student =".$_SESSION["id"];

    // sql consulta learn styles
    $sqlLs = "SELECT * FROM quiz_learn_styles_rs WHERE id_student =".$_SESSION["id"];

    // sql consulta to
    $sqlTp = "SELECT * FROM quiz_type_players_rs WHERE id_student =".$_SESSION["id"];

	//resultados de consulta
    $resultadoGen = $mysqli->query($sqlGen);
    $resultadoLs = $mysqli->query($sqlLs);
    $resultadoTp = $mysqli->query($sqlTp);

    if(( $resultadoGen->num_rows > 0 && $resultadoLs->num_rows > 0  ) && $resultadoTp->num_rows > 0)
    { // se puede cargar el modulo de  graficos 
        include "infoResults.php";
    }
    else
    {
        include  "infoForm.php";
    }
    
?>
