<?php
    require "controller/connection.php";
    
    //SQL INSTITUTIONS
    $sql_Select_inst= "SELECT * FROM institution";
    //Execute the sql
    $instituntes_res =  $mysqli->query($sql_Select_inst);



    
    #fsearch values in database for result
    $sql = "SELECT * FROM quiz_general WHERE id_student=".$_SESSION["id"];
    $resultado = $mysqli->query($sql);# execute uerry
    $num = $resultado->num_rows;# count rows
    if($num>0){
        $showForm=false;
		echo "
		<script type=\"text/javascript\">
			window.location.href = \"principal.php?action=graphics\";
		</script>
		";
    }else {
        $showForm=true; 
        if($_POST){
		// evitar que el estudiante selecciones al opciopn de ninguno
		$noNnOption = true;

		if(isset($_POST["pginstitute"])){
                    if($_POST["pginstitute"] == "Ninguna" ){
                        $noNnOption = false;
                    }
	        }

            if(
		$noNnOption &&
                isset($_POST["pgenero"]) &&
                isset($_POST["grado"]) &&
                isset($_POST["edad"]) &&
                (isset($_POST["Facebook"]) ||
                isset($_POST["Instagram"]) ||
                isset($_POST["TikTok"]) ||
                isset($_POST["Pinterest"]) ||
                isset($_POST["correo"]) ||
                isset($_POST["Genius"]) ||
                isset($_POST["LinkedIn"]) ||
                isset($_POST["WhatsApp"]) ||
                isset($_POST["Telegram"]) ||
                isset($_POST["Canva"]) ||
                isset($_POST["Twitter"]) ||
                isset($_POST["Twitch"]) ||
                isset($_POST["YouTube"])
                ))
            {
                // validate the semester seleccion 
                if($_POST["grado"] === "S" ){
                    $_POST["grado"] = "Semestre: ".$_POST["semestre"];
                }
                
                   $sql_geneal = 
                    "INSERT INTO quiz_general VALUES("
                    .$_SESSION["id"].","
                    ."'".$_POST["pginstitute"]."'".","
                    ."'".$_POST["pgenero"]."'".","
                    ."'".$_POST["grado"]."'".","
                    ."'".$_POST["edad"]."'".");";

                    

                    

                    if($mysqli->query($sql_geneal)){
                        // direcition init
                        if(isset($_POST["Facebook"])){
                            $mysqli->query("INSERT INTO plataform_rate VALUES ("
                                    .$_SESSION["id"].",'Facebook');"
                                );
                        }

                        if(isset($_POST["Instagram"])){
                            $mysqli->query("INSERT INTO plataform_rate VALUES ("
                                    .$_SESSION["id"].",'Instagram');"
                                );
                        }
                        if(isset($_POST["TikTok"])){
                            $mysqli->query("INSERT INTO plataform_rate VALUES ("
                                    .$_SESSION["id"].",'TikTok');"
                                );
                        }
                        if(isset($_POST["Pinterest"])){
                            $mysqli->query("INSERT INTO plataform_rate VALUES ("
                                    .$_SESSION["id"].",'Pinterest');"
                                );
                        }
                        if(isset($_POST["correo"])){
                            $mysqli->query("INSERT INTO plataform_rate VALUES ("
                                    .$_SESSION["id"].",'correo');"
                                );
                        }
                        if(isset($_POST["Genius"])){
                            $mysqli->query("INSERT INTO plataform_rate VALUES ("
                                    .$_SESSION["id"].",'Genius');"
                                );
                        }
                        if(isset($_POST["LinkedIn"])){
                            $mysqli->query("INSERT INTO plataform_rate VALUES ("
                                    .$_SESSION["id"].",'LinkedIn');"
                                );
                        }
                        if(isset($_POST["WhatsApp"])){
                            $mysqli->query("INSERT INTO plataform_rate VALUES ("
                                    .$_SESSION["id"].",'WhatsApp');"
                                );
                        }
                        
                        if(isset($_POST["Telegram"])){
                            $mysqli->query("INSERT INTO plataform_rate VALUES ("
                                    .$_SESSION["id"].",'Telegram');"
                                );
                        }
                        if(isset($_POST["Canva"])){
                            $mysqli->query("INSERT INTO plataform_rate VALUES ("
                                    .$_SESSION["id"].",'Canva');"
                                );
                        }
                        if(isset($_POST["Twitter"])){
                            $mysqli->query("INSERT INTO plataform_rate VALUES ("
                                    .$_SESSION["id"].",'Twitter');"
                                );
                        }
                        if(isset($_POST["Twitch"])){
                            $mysqli->query("INSERT INTO plataform_rate VALUES ("
                                    .$_SESSION["id"].",'Twitch');"
                                );
                        }
                        if(isset($_POST["YouTube"])){
                            $mysqli->query("INSERT INTO plataform_rate VALUES ("
                                    .$_SESSION["id"].",'YouTube');"
                                );
                        }

                        //redirecionar pagina
                        echo "
                        <script type=\"text/javascript\">
                            window.location.href = \"principal.php?action=graphics\";
                        </script>
                        ";

                     

                    }else {
                        echo '<div class="alertB">',
						"<span class=\"closebtnB\" onclick=\"this.parentElement.style.display='none';\">&times;</span> ",
						'<strong>¡Cuidado!</strong> error con el server<br>',$mysqli->error,
						'</div>'; 
                    }                   

            }   else {
                echo 
                '<div class="alertB ">',
                "<span class=\"closebtn\" onclick=\"this.parentElement.style.display='none';\">&times;</span> ",
                'HACE FALTA ALGUNO DE LOS CAMPOS POR LLENAR, POR FAVOR REVISAR',
                '</div>';
            }
        }

    }

?>

<?php if($showForm){?>
<div class=" mt-4 ml-n1"  >

    <div class="header col-lg-8" >
        <h1 class="page-header-title" >Test: Información general  </h1>
        <h6 class="text-muted"> Por favor lea atentamente y seleccione la respuesta dependiendo más se adapte a usted. </h6>
    </div>

    <form name="F1" action="<?php echo "?action=gen"?>"  method="POST" >
        <div class="col-lg-auto  mb-2">
            <div class="card mb-2">
                <div class="card-header">
                    <h5 class="card-title">
                        Institución de procedencia</h5>
                    <h6 class="text-muted font-italic"> Seleccione la opción suministrada por el docente, en caso de no pertenecer a una institución, opte por la opción de 'Ninguna'. </h6>
                </div>        
                <div class="card-body" >

                    <select class="col-8 " name="pginstitute" id="pginstitute">
                        <option value="Ninguna">--</option>
                        <?php
                            for ($i=0; $i < $instituntes_res->num_rows; $i++) { 
                                //get data
                                $instituntes_rows = $instituntes_res->fetch_assoc();
                                //set option
                                if(isset($_POST["pginstitute"])){
                                    if($_POST["pginstitute"] == $instituntes_rows["ins_name"]){
                                        $checkeded = "selected";
                                    }else {
                                        $checkeded = "";
                                    }
                                }
                                echo "
                                <option value=\"".$instituntes_rows["ins_name"]."\" ".$checkeded."> 
                                ".$instituntes_rows["ins_name"]."
                                </option>
                                ";   
                            }
                        ?>

                    </select>
                </div>
        
            </div>
            
            <!-- pregunta  de genero-->
            <div class="card mb-2">

                <div class="card-header">
                    <h5 class="card-title">Género.</h5>
                </div>

                <div class="card-body" >
                    <?php
                        if(isset($_POST["pgenero"])){
                            if($_POST["pgenero"] === "Femenino"){
                                echo 
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pg1\" name=\"pgenero\" class=\"custom-control-input\"
                                    value=\"Femenino\"
                                    checked>
                                    <label class=\"custom-control-label\" for=\"pg1\">Femenino</label>
                                </div>";
                            }else{
                                echo 
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pg1\" name=\"pgenero\" class=\"custom-control-input\"
                                    value=\"Femenino\">
                                    <label class=\"custom-control-label\" for=\"pg1\">Femenino</label>
                                </div>";
                            }

                            if($_POST["pgenero"] === "Masculino"){
                                echo 
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pg2\" name=\"pgenero\" class=\"custom-control-input\"
                                    value=\"Masculino\" checked>
                                    <label class=\"custom-control-label\" for=\"pg2\">Masculino</label>
                                </div>";
                            }else{
                                echo 
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pg2\" name=\"pgenero\" class=\"custom-control-input\"
                                    value=\"Masculino\">
                                    <label class=\"custom-control-label\" for=\"pg2\">Masculino</label>
                                </div>";
                            }

                            if($_POST["pgenero"] === "NN"){
                                echo 
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pg5\" name=\"pgenero\" class=\"custom-control-input\"
                                    value=\"NN\" checked>
                                    <label class=\"custom-control-label\" for=\"pg5\">Prefiero no indicarlo</label>
                                </div>";
                            }else{
                                echo 
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pg5\" name=\"pgenero\" class=\"custom-control-input\"
                                    value=\"NN\">
                                    <label class=\"custom-control-label\" for=\"pg5\">Prefiero no indicarlo</label>
                                </div>";
                            }

                        }else{	
                        echo 
                        "<div class=\"mb-2 custom-radio custom-control\">
                            <input type=\"radio\" id=\"pg1\" name=\"pgenero\" class=\"custom-control-input\"
                            value=\"Femenino\">
                            <label class=\"custom-control-label\" for=\"pg1\">Femenino</label>
                        </div>
                        <div class=\"mb-2 custom-radio custom-control\">
                            <input type=\"radio\" id=\"pg2\" name=\"pgenero\" class=\"custom-control-input\"
                            value=\"Masculino\">
                            <label class=\"custom-control-label\" for=\"pg2\">Masculino</label>
                        </div>
                        <div class=\"mb-2 custom-radio custom-control\">
                            <input type=\"radio\" id=\"pg5\" name=\"pgenero\" class=\"custom-control-input\"
                            value=\"NN\">
                            <label class=\"custom-control-label\" for=\"pg5\">Prefiero no indicarlo</label>
                        </div>";
                        
                        }
                    ?>
                </div>
            </div>    

            <!-- GRADO -->
            <div class="card mb-2">
                <div class="card-header">
                    <h5 class="card-title">Grado de colegio o semestre de pregrado segun corresponda que curse actualmente</h5>
                    <h6 class="text-muted font-italic"> Nota: seleccione de 6° a 11° si corresponde a educación secundaria o la opción de semestre indicando el actualmente cursado si se encuentra en educación superior.</h6>
                </div>
                <div class="card-body" >

                    <?php
                        if(isset($_POST["grado"])){
                            if($_POST["grado"] === "6" ){
                                echo
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pgr1\" name=\"grado\" class=\"custom-control-input\"
                                    value=\"6\" checked 
                                    onChange=\"habilitarSemstre();\">
                                    <label class=\"custom-control-label\" for=\"pgr1\">6° de secundaria (Colegio)</label>
                                </div>";
                            }else{
                                echo
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pgr1\" name=\"grado\" class=\"custom-control-input\"
                                    value=\"6\"
                                    onChange=\"habilitarSemstre();\">
                                    <label class=\"custom-control-label\" for=\"pgr1\">6° de secundaria (Colegio)</label>
                                </div>";
                            }

                            if($_POST["grado"] === "7" ){
                                echo
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pgr2\" name=\"grado\" class=\"custom-control-input\"
                                    value=\"7\" checked
                                    onChange=\"habilitarSemstre();\">
                                    <label class=\"custom-control-label\" for=\"pgr2\">7° de secundaria (Colegio)</label>
                                </div>";
                            }else{
                                echo
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pgr2\" name=\"grado\" class=\"custom-control-input\"
                                    value=\"7\"
                                    onChange=\"habilitarSemstre();\">
                                    <label class=\"custom-control-label\" for=\"pgr2\">7° de secundaria (Colegio)</label>
                                </div>";
                            }

                            if($_POST["grado"] === "8" ){
                                echo
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pgr3\" name=\"grado\" class=\"custom-control-input\"
                                    value=\"8\" checked
                                    onChange=\"habilitarSemstre();\">
                                    <label class=\"custom-control-label\" for=\"pgr3\">8° de secundaria (Colegio)</label>
                                </div>";
                            }else{
                                echo
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pgr3\" name=\"grado\" class=\"custom-control-input\"
                                    value=\"8\"
                                    onChange=\"habilitarSemstre();\">
                                    <label class=\"custom-control-label\" for=\"pgr3\">8° de secundaria (Colegio)</label>
                                </div>";
                            }

                            if($_POST["grado"] === "9" ){
                                echo
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pgr4\" name=\"grado\" class=\"custom-control-input\"
                                    value=\"9\" checked
                                    onChange=\"habilitarSemstre();\">
                                    <label class=\"custom-control-label\" for=\"pgr4\">9° de secundaria (Colegio)</label>
                                </div>";
                            }else{
                                echo
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pgr4\" name=\"grado\" class=\"custom-control-input\"
                                    value=\"9\"
                                    onChange=\"habilitarSemstre();\">
                                    <label class=\"custom-control-label\" for=\"pgr4\">9° de secundaria (Colegio)</label>
                                </div>";
                            }

                            if($_POST["grado"] === "10" ){
                                echo
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pgr6\" name=\"grado\" class=\"custom-control-input\"
                                    value=\"10\" checked
                                    onChange=\"habilitarSemstre();\">
                                    <label class=\"custom-control-label\" for=\"pgr6\">10° de secundaria (Colegio)</label>
                                </div>";
                            }else{
                                echo
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pgr6\" name=\"grado\" class=\"custom-control-input\"
                                    value=\"10\"
                                    onChange=\"habilitarSemstre();\">
                                    <label class=\"custom-control-label\" for=\"pgr6\">10° de secundaria (Colegio)</label>
                                </div>";
                            }

                            if($_POST["grado"] === "11" ){
                                echo
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pgr7\" name=\"grado\" class=\"custom-control-input\"
                                    value=\"11\" checked
                                    onChange=\"habilitarSemstre();\">
                                    <label class=\"custom-control-label\" for=\"pgr7\">11° de secundaria (Colegio)</label>
                                </div>";
                            }else{
                                echo
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pgr7\" name=\"grado\" class=\"custom-control-input\"
                                    value=\"11\"
                                    onChange=\"habilitarSemstre();\">
                                    <label class=\"custom-control-label\" for=\"pgr7\">11° de secundaria (Colegio)</label>
                                </div>";
                            }

                            // seleccion de semestre
                            if(substr( $_POST["grado"],0,1) === "S" ){
                                // get text
                                 
                                $selectpr = array("","","","","","","","","","","","","");
                                $selectpr[$_POST["semestre"]] = "selected=\"selected\"";
                                

                                echo
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pgr5\" name=\"grado\" class=\"custom-control-input\"
                                    value=\"S\" checked
                                    onChange=\"habilitarSemstre();\">
                                    <label class=\"custom-control-label\" for=\"pgr5\">Semestre (Universidad)</label>
                                    
                                    <select class=\"col-2 ml-4\" name=\"semestre\" id=\"boton\"  style=\"display:\"  >
                                        <option $selectpr[1]>1</option>
                                        <option $selectpr[2]>2</option>
                                        <option $selectpr[3]>3</option>
                                        <option $selectpr[4]>4</option>
                                        <option $selectpr[5]>5</option>
                                        <option $selectpr[6]>6</option>
                                        <option $selectpr[7]>7</option>
                                        <option $selectpr[8]>8</option>
                                        <option $selectpr[9]>9</option>
                                        <option $selectpr[10]>10</option>
                                        <option $selectpr[11]>11</option>
                                        <option $selectpr[12]>12</option>
                                    </select>
                                    
                                </div>";
                            }else{
                                echo
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pgr5\" name=\"grado\" class=\"custom-control-input\"
                                    value=\"S\"
                                    onChange=\"habilitarSemstre();\">
                                    <label class=\"custom-control-label\" for=\"pgr5\">Semestre (educación superior)</label>
                                    <select class=\"col-2 ml-4\" name=\"semestre\" id=\"boton\"  style=\"display:none\">
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                        <option>6</option>
                                        <option>7</option>
                                        <option>8</option>
                                        <option>9</option>
                                        <option>10</option>
                                        <option>11</option>
                                        <option>12</option>
                                    </select>
                                </div>";
                            }
                            

                        }else {
                            echo
                            "<div class=\"mb-2 custom-radio custom-control\">
                                <input type=\"radio\" id=\"pgr1\" name=\"grado\" class=\"custom-control-input\"
                                value=\"6\" onChange=\"habilitarSemstre();\">
                                <label class=\"custom-control-label\" for=\"pgr1\">6° de secundaria (Colegio)</label>
                            </div>
                            <div class=\"mb-2 custom-radio custom-control\">
                                <input type=\"radio\" id=\"pgr2\" name=\"grado\" class=\"custom-control-input\"
                                value=\"7\" onChange=\"habilitarSemstre();\">
                                <label class=\"custom-control-label\" for=\"pgr2\">7° de secundaria (Colegio)</label>
                            </div>
                            <div class=\"mb-2 custom-radio custom-control\">
                                <input type=\"radio\" id=\"pgr3\" name=\"grado\" class=\"custom-control-input\"
                                value=\"8\" onChange=\"habilitarSemstre();\">
                                <label class=\"custom-control-label\" for=\"pgr3\">8° de secundaria (Colegio)</label>
                            </div>
                            <div class=\"mb-2 custom-radio custom-control\">
                                <input type=\"radio\" id=\"pgr4\" name=\"grado\" class=\"custom-control-input\"
                                value=\"9\" onChange=\"habilitarSemstre();\">
                                <label class=\"custom-control-label\" for=\"pgr4\">9° de secundaria (Colegio)</label>
                            </div>
                            <div class=\"mb-2 custom-radio custom-control\">
                                <input type=\"radio\" id=\"pgr6\" name=\"grado\" class=\"custom-control-input\"
                                value=\"10\" onChange=\"habilitarSemstre();\">
                                <label class=\"custom-control-label\" for=\"pgr6\">10° de secundaria (Colegio)</label>
                            </div>
                            <div class=\"mb-2 custom-radio custom-control\">
                                <input type=\"radio\" id=\"pgr7\" name=\"grado\" class=\"custom-control-input\"
                                value=\"11\" onChange=\"habilitarSemstre();\">
                                <label class=\"custom-control-label\" for=\"pgr7\">11° de secundaria (Colegio)</label>
                            </div>
                            <div class=\"mb-2 custom-radio custom-control\">
                                <input type=\"radio\" id=\"pgr5\" name=\"grado\" class=\"custom-control-input\"
                                value=\"S\" onChange=\"habilitarSemstre();\" >
                                <label class=\"custom-control-label\" for=\"pgr5\">Semestre (Universidad) </label>
                                <select class=\"col-2 ml-4\" name=\"semestre\" id=\"boton\"  style=\"display:none\">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                    <option>6</option>
                                    <option>7</option>
                                    <option>8</option>
                                    <option>9</option>
                                    <option>10</option>
                                    <option>11</option>
                                    <option>12</option>
                                </select>
                            </div>";
                        }
                    ?>
                    
                </div>
            </div>

            <!-- EDAD -->
            <div class="card mb-2">
                <div class="card-header">
                    <h5 class="card-title">Rango de edad.</h5>
                </div>
                <div class="card-body" ">
                    <?php
                        if(isset($_POST["edad"]) ){
                            if($_POST["edad"] === "10-12" ){
                                echo
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pe1\" name=\"edad\" class=\"custom-control-input\"
                                    value=\"10-12\" checked>
                                    <label class=\"custom-control-label\" for=\"pe1\">10-12</label>
                                </div>";
                            }else{
                                echo
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pe1\" name=\"edad\" class=\"custom-control-input\"
                                    value=\"10-12\">
                                    <label class=\"custom-control-label\" for=\"pe1\">10-12</label>
                                </div>";
                            }

                            if($_POST["edad"] === "13-15" ){
                                echo
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pe2\" name=\"edad\" class=\"custom-control-input\"
                                    value=\"13-15\" checked>
                                    <label class=\"custom-control-label\" for=\"pe2\">13-15</label>
                                </div>";
                            }else{
                                echo
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pe2\" name=\"edad\" class=\"custom-control-input\"
                                    value=\"13-15\">
                                    <label class=\"custom-control-label\" for=\"pe2\">13-15</label>
                                </div>";
                            }

                            if($_POST["edad"] === "16-18" ){
                                echo
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pe3\" name=\"edad\" class=\"custom-control-input\"
                                    value=\"16-18\" checked>
                                    <label class=\"custom-control-label\" for=\"pe3\">16-18</label>
                                </div>";
                            }else{
                                echo
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pe3\" name=\"edad\" class=\"custom-control-input\"
                                    value=\"16-18\">
                                    <label class=\"custom-control-label\" for=\"pe3\">16-18</label>
                                </div>";
                            }

                            if($_POST["edad"] === "mas de 18" ){
                                echo
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pe4\" name=\"edad\" class=\"custom-control-input\"
                                    value=\"mas de 18\" checked>
                                    <label class=\"custom-control-label\" for=\"pe4\">Mayor de 18</label>
                                </div>";
                            }else{
                                echo
                                "<div class=\"mb-2 custom-radio custom-control\">
                                    <input type=\"radio\" id=\"pe4\" name=\"edad\" class=\"custom-control-input\"
                                    value=\"mas de 18\">
                                    <label class=\"custom-control-label\" for=\"pe4\">Mayor de 18</label>
                                </div>";
                            }

                        }else {
                            echo
                            "<div class=\"mb-2 custom-radio custom-control\">
                                <input type=\"radio\" id=\"pe1\" name=\"edad\" class=\"custom-control-input\"
                                value=\"10-12\">
                                <label class=\"custom-control-label\" for=\"pe1\">10-12</label>
                            </div>
                            <div class=\"mb-2 custom-radio custom-control\">
                                <input type=\"radio\" id=\"pe2\" name=\"edad\" class=\"custom-control-input\"
                                value=\"13-15\">
                                <label class=\"custom-control-label\" for=\"pe2\">13-15</label>
                            </div>
                            <div class=\"mb-2 custom-radio custom-control\">
                                <input type=\"radio\" id=\"pe3\" name=\"edad\" class=\"custom-control-input\"
                                value=\"16-18\">
                                <label class=\"custom-control-label\" for=\"pe3\">16-18</label>
                            </div>
                            <div class=\"mb-2 custom-radio custom-control\">
                                <input type=\"radio\" id=\"pe4\" name=\"edad\" class=\"custom-control-input\"
                                value=\"mas de 18\">
                                <label class=\"custom-control-label\" for=\"pe4\">Mayor de 18</label>
                            </div>
                            
                            ";
                        }
                    ?>
                </div>
            </div>

            <!-- PLATAFORMAS -->
            
            <div class="card mb-2">
                <div class="card-header">
                    <h5 class="card-title">
                    ¿Con que redes sociales y/o herramientas se asocia más frecuentemente?
                    </h5>
                    <h6><i>Seleccione varias opciones con las cuales se identifique de manera más fuerte.</i></h6>
                    
                </div>
                <div class="card-body" >
                    
                    <div class="custom-control custom-checkbox">
                        <?php
                            if (isset($_POST["Facebook"])){
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"Facebook\" value=\"Facebook\" name=\"Facebook\" checked>";
                            }else {
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"Facebook\" value=\"Facebook\" name=\"Facebook\">";
                            }
                        ?>
                        <label for="Facebook" class="custom-control-label">Facebook</label>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <?php
                            if (isset($_POST["Instagram"])){
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"Instagram\" value=\"Instagram\" name=\"Instagram\" checked>";
                            }else {
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"Instagram\" value=\"Instagram\" name=\"Instagram\">";
                            }
                        ?>
                        <label for="Instagram" class="custom-control-label">Instagram</label>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <?php
                            if (isset($_POST["TikTok"])){
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"TikTok\" value=\"TikTok\" name=\"TikTok\" checked>";
                            }else {
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"TikTok\" value=\"TikTok\" name=\"TikTok\">";
                            }
                        ?>
                        <label for="TikTok" class="custom-control-label">Tik Tok</label>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <?php
                            if (isset($_POST["Pinterest"])){
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"Pinterest\" value=\"Pinterest\" name=\"Pinterest\" checked>";
                            }else {
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"Pinterest\" value=\"Pinterest\" name=\"Pinterest\">";
                            }
                        ?>
                        <label for="Pinterest" class="custom-control-label">Pinterest</label>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <?php
                            if (isset($_POST["correo"])){
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"correo\" value=\"correo\" name=\"correo\" checked>";
                            }else {
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"correo\" value=\"correo\" name=\"correo\">";
                            }
                        ?>
                        <label for="correo" class="custom-control-label"> Correo Electrónico</label>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <?php
                            if (isset($_POST["Genius"])){
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"Genius\" value=\"Genius\" name=\"Genius\" checked>";
                            }else {
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"Genius\" value=\"Genius\" name=\"Genius\">";
                            }
                        ?>
                        <label for="Genius" class="custom-control-label">Genius</label>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <?php
                            if (isset($_POST["LinkedIn"])){
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"LinkedIn\" value=\"LinkedIn\" name=\"LinkedIn\" checked>";
                            }else {
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"LinkedIn\" value=\"LinkedIn\" name=\"LinkedIn\">";
                            }
                        ?>
                        <label for="LinkedIn" class="custom-control-label">LinkedIn</label>
                    </div>

                    <div class="custom-control custom-checkbox">
                       <?php
                            if (isset($_POST["WhatsApp"])){
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"WhatsApp\" value=\"WhatsApp\" name=\"WhatsApp\" checked>";
                            }else {
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"WhatsApp\" value=\"WhatsApp\" name=\"WhatsApp\">";
                            }
                        ?>
                        <label for="WhatsApp" class="custom-control-label">WhatsApp</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                       <?php
                            if (isset($_POST["Telegram"])){
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"Telegram\" value=\"Telegram\" name=\"Telegram\" checked>";
                            }else {
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"Telegram\" value=\"Telegram\" name=\"Telegram\">";
                            }
                        ?>
                        <label for="Telegram" class="custom-control-label">Telegram</label>
                    </div>

                    <div class="custom-control custom-checkbox">
                       <?php
                            if (isset($_POST["Canva"])){
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"Canva\" value=\"Canva\" name=\"Canva\" checked>";
                            }else {
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"Canva\" value=\"Canva\" name=\"Canva\">";
                            }
                        ?>
                        <label for="Canva" class="custom-control-label">Canva</label>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <?php
                            if (isset($_POST["Twitter"])){
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"Twitter\" value=\"Twitter\" name=\"Twitter\" checked>";
                            }else {
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"Twitter\" value=\"Twitter\" name=\"Twitter\">";
                            }
                        ?>
                        <label for="Twitter" class="custom-control-label">Twitter</label>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <?php
                            if (isset($_POST["Twitch"])){
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"Twitch\" value=\"Twitch\" name=\"Twitch\" checked>";
                            }else {
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"Twitch\" value=\"Twitch\" name=\"Twitch\">";
                            }
                        ?>
                        <label for="Twitch" class="custom-control-label">Twitch</label>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <?php
                            if (isset($_POST["YouTube"])){
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"YouTube\" value=\"YouTube\" name=\"YouTube\" checked>";
                            }else {
                                echo "<input class=\"custom-control-input\" type=\"checkbox\" id=\"YouTube\" value=\"YouTube\" name=\"YouTube\">";
                            }
                        ?>
                        <label for="YouTube" class="custom-control-label">YouTube</label>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary" >ENVIAR</a>
        </div>
    </form>
</div>

<script>
    // habilita la posibilidad de ingresar el semestre
    function habilitarSemstre()
    {
        if( document.getElementById('pgr5').checked) 
        {
            document.getElementById('boton').style.display = "";
        }
        else 
        {
            document.getElementById('boton').style.display = "none";
        }
    }

    
</script>
<?php }?>
