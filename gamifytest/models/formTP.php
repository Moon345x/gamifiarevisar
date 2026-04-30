<?php
    require "controller/connection.php";
    
    $fg = new FormGenerator();

    // consult the existence or results
	$sql = "SELECT * FROM quiz_type_players WHERE id_student =".$_SESSION["id"];
	// echo $_SESSION["id"];
	$resultado = $mysqli->query($sql);
	$num = $resultado->num_rows;//extract the number of rows
	// echo $num;
	if($num >0){//probar si hay resultados de encuestas previas 
		// mostrar resultados
		$showForm=false;
		
		echo "
		<script type=\"text/javascript\">
			window.location.href = \"principal.php?action=graphics\";
		</script>
		";
	}else{

        if($_POST){
            //vars
            $dimention= array(0,0,0,0,0,0); //dimenciones de evaluacion 
            $total_puntos=0;

            //consulta
            $sqltp = "INSERT INTO quiz_type_players VALUES(".$_SESSION["id"];
            $sqltp_rs = "INSERT INTO quiz_type_players_rs (id_student, philanthrop, socialiser, free_spirit, achiever, disruptor, player )  VALUES(".$_SESSION["id"];

            //recorrer puntos
            $total_puntos=0;
            for ($i=1; $i < 25; $i++) { 
                $pos = floor (($i - 1)/4) ;//posicion  en el array de dimenciones 
                $dimention[$pos] += $_POST["p".$i];//acomulacion de los puntos en cada dimencion
                $total_puntos +=$_POST["p".$i];//acomulacion de los puntos en total

                $sqltp .= ",".$_POST["p".$i];//build sql 
            }

            $sqltp .= ");";

            // porcents
            $philantrop_porcent = (100.0/$total_puntos)*$dimention[0];
            $socializer_porcent = (100.0/$total_puntos)*$dimention[1];
            $free_spirit_porcent = (100.0/$total_puntos)*$dimention[2];
            $archiver_porcent = (100.0/$total_puntos)*$dimention[3];
            $disruptor_porcent = (100.0/$total_puntos)*$dimention[4];
            $player_porcent = (100.0/$total_puntos)*$dimention[5];

            $sqltp_rs .= ",".$philantrop_porcent.",".$socializer_porcent
                        .",".$free_spirit_porcent.",".$archiver_porcent
                        .",".$disruptor_porcent.",".$player_porcent.");";
            
            
            if(!($mysqli->query($sqltp) && $mysqli->query($sqltp_rs) )){
                echo '<div class="alertB">',
                "<span class=\"closebtnB\" onclick=\"this.parentElement.style.display='none';\">&times;</span> ",
                '<strong>¡Cuidado!</strong> error con el server<br>',$mysqli->error,
                '</div>';
            }else {
                echo "
                <script type=\"text/javascript\">
                    window.location.href = \"principal.php?action=graphics\";
                </script>
                ";
            }
        }

        

    }
?>


<div class=" ml-n1"  >

    <div class="header col-lg-8" >
        <h1 class="page-header-title" >Encuesta para: Tipos de jugador  </h1>
        Lea cada una de las afirmaciones descritas a continuación y seleccione que tan de acuerdo está con cada una de ellas, cabe aclarar que no hay respuestas correctas o incorrectas, solo se quiere evaluar su opinión. 
        <br>
        <br>
    </div>

   
    
    <form name="F3" action="<?php echo "?action=frmtp"?>"  method="POST" > 
        <div class="  mb-2">
            <div class="tab-content" id="myTabContent">

                <div class="tab-pane show active" id="tp1" role="tabpanel" >
                    <!-- Philantrop -->
                        <?php $fg->generateSelect("1. Me siento feliz siendo capaz de ayudar a los demás.",1);?>
                        <?php $fg->generateSelect("2. Me gusta guiar a los demás en situaciones nuevas.",2);?>
                        <?php $fg->generateSelect("3. Me gusta compartir mi conocimiento con los demás.",3);?>
                        <?php $fg->generateSelect("4. El bienestar de los demás es importante para mí.",4);?>
                    <!-- socializer -->
                        <?php $fg->generateSelect("5. Interactuar con los demás es importante para mí.",5);?>
                        <?php $fg->generateSelect("6. Me gusta formar parte de un equipo.",6);?>
                        <?php $fg->generateSelect("7. Sentir que formo parte de una comunidad es importante para mí.",7);?>
                        <?php $fg->generateSelect("8. Disfruto participando en actividades grupales.",8);?>
                </div>
                <div class="tab-pane" id="tp2" role="tabpanel" >
                    <!-- Free spirit -->
                        <?php $fg->generateSelect("9. Seguir mi propio camino es importante para mí",9);?>
                        <?php $fg->generateSelect("10. A menudo me dejo llevar por la curiosidad.",10);?>
                        <?php $fg->generateSelect("11. Me gusta probar cosas nuevas.",11);?>
                        <?php $fg->generateSelect("12. Ser independiente es importante para mí.",12);?>
                    <!-- Archiver -->
                        <?php $fg->generateSelect("13. Me gusta superar las dificultades.",13);?>
                        <?php $fg->generateSelect("14. Realizar siempre por completo mis tareas es importante para mí.",14);?>
                        <?php $fg->generateSelect("15. Me resulta difícil abandonar un problema antes de solucionarlo.",15);?>
                        <?php $fg->generateSelect("16. Me gusta dominar tareas difíciles.",16);?>
                </div>
                <div class="tab-pane" id="tp3" role="tabpanel" >
                    <!-- Disruptor -->
                        <?php $fg->generateSelect("17. Me gusta provocar a las demás personas.",17);?>
                        <?php $fg->generateSelect("18. Me gusta cuestionar el porque cosas.",18);?>
                        <?php $fg->generateSelect("19. Me describo a mí mism@ como un rebelde.",19);?>
                        <?php $fg->generateSelect("20. No me gusta seguir las reglas.",20);?>
                    <!-- Player -->
                        <?php $fg->generateSelect("21. Me gustan las competiciones en las que se pueda ganar un premio.",21);?>
                        <?php $fg->generateSelect("22. Pienso que los premios son una buena manera de motivarme.",22);?>
                        <?php $fg->generateSelect("23. Recuperar lo invertido es importante para mí.",23);?>
                        <?php $fg->generateSelect("24. Si el premio es adecuado, voy a hacer un esfuerzo.",24);?>
                        
                        <button type="submit" class="btn btn-primary" >Enviar respuestas</a>
                        
                </div>
                
                <br>
                <ul class="nav nav-pills" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active"  data-toggle="tab" href="#tp1" aria-selected="true" onclick="goTop()">1</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"  data-toggle="tab" href="#tp2" aria-selected="false" onclick="goTop()">2</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"  data-toggle="tab" href="#tp3" aria-selected="false" onclick="goTop()">3</a>
                    </li>
                </ul>
            </div>
        </div>
    
    </form>
    

</div>

<script>
    function goTop(){
    window.scroll(0, 0);
    }

    
</script>
