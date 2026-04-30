<?php
    require "controller/connection.php";

    // consult the institutes
    $instituteOptions =  $mysqli->query("SELECT DISTINCT(isntitucion) FROM `quiz_general` WHERE isntitucion != 'Ninguna';");


    //filtrer option
    if(isset($_POST["institutionFilter"])){
        if($_POST["institutionFilter"] != 'Todas'){
            $whereOption = "WHERE  quiz_general.isntitucion = '".$_POST["institutionFilter"]."' ";
            $whereandOption = "AND quiz_general.isntitucion = '".$_POST["institutionFilter"]."' ";
        }else {
            $whereOption ="";
            $whereandOption="";
        }
    }else {
        $whereOption ="";
        $whereandOption ="";
    }
 

    // consulta de resultados para genero 
    
    
    $generoAll = ($mysqli->query("SELECT COUNT(genero) FROM `quiz_general` $whereOption ; "))->fetch_all();
    $generoM = ($mysqli->query("SELECT COUNT(genero) FROM `quiz_general` WHERE genero = 'Masculino' $whereandOption ;"))->fetch_all();
    $generoF = ($mysqli->query("SELECT COUNT(genero) FROM `quiz_general` WHERE genero = 'Femenino' $whereandOption  ;"))->fetch_all();

    

    if($generoAll[0][0] != 0){
        $p_genero_m = (($generoM[0])[0]/($generoAll[0])[0])*100;
        $p_genero_f = (($generoF[0])[0]/($generoAll[0])[0])*100;
        $generoOTR = (($generoAll[0])[0] - (($generoF[0])[0]+($generoM[0])[0]));
        $p_genero_otr = ($generoOTR/($generoAll[0])[0])*100;
    }else {
        $p_genero_m = 0;
        $p_genero_f = 0;
        $p_genero_otr = 0;
    }
    


    ///////////////// consulta de la splataformas
    // SELECT `plataform`,COUNT(`plataform`) AS `num` FROM `plataform_rate` INNER JOIN `quiz_general` ON plataform_rate.id_student = quiz_general.id_student GROUP BY `plataform`

    $palaform_rate = $mysqli->query("SELECT `plataform`,COUNT(`plataform`) AS `num` FROM `plataform_rate` INNER JOIN `quiz_general` ON plataform_rate.id_student = quiz_general.id_student $whereOption GROUP BY `plataform`;")  ;
    
    $labelsPR = array($palaform_rate->num_rows);
    $valsPR = array($palaform_rate->num_rows);
    // asign vals 
    for ($i=0; $i < $palaform_rate->num_rows; $i++) { 
        $rowPR  =  $palaform_rate->fetch_assoc();
        $labelsPR[$i] = $rowPR["plataform"];
        $valsPR[$i] = $rowPR["num"];
    }


    /////////////////////// consulta de edades
    $rango_edad = $mysqli->query("SELECT `r_edad`, COUNT(`r_edad`) AS `num` FROM `quiz_general` $whereOption GROUP BY `r_edad`;");
    $labelsEdad = array($rango_edad->num_rows);
    $valsEdad = array($rango_edad->num_rows);
    //asign vals 
    for($i=0; $i < $rango_edad->num_rows;$i++){
        $rowEdad = $rango_edad->fetch_assoc();
        $labelsEdad[$i] = $rowEdad["r_edad"];
        $valsEdad[$i] = $rowEdad["num"];
    }
    

    ////////////////////// consulta de grado
    $grado_rate =  $mysqli->query("SELECT `grado`, COUNT(`grado`) AS `num` FROM `quiz_general` $whereOption GROUP BY `grado`;");
    $labelsGrado = array($grado_rate->num_rows);
    $valsGrado = array($grado_rate->num_rows);
    //load vals
    for ($i=0; $i < $grado_rate->num_rows; $i++) { 
        $rowGrado = $grado_rate->fetch_assoc();
        $labelsGrado[$i] = $rowGrado["grado"];
        $valsGrado[$i] = $rowGrado["num"];
    }


    //////////////////// consulta de perfiles de jugador 
  
    $type_player_rate = $mysqli->query(
        "SELECT 
        (SUM(`philanthrop`)/ (COUNT(*)*100))*100 AS `ph`, 
        (SUM(`socialiser`)/ (COUNT(*)*100))*100 AS `so`, 
        (SUM(`free_spirit`)/ (COUNT(*)*100))*100 AS `fr`, 
        (SUM(`achiever`)/ (COUNT(*)*100))*100 AS `ar`, 
        (SUM(`player`)/ (COUNT(*)*100))*100 AS `pl`, 
        (SUM(`disruptor`)/ (COUNT(*)*100))*100 AS `di` 
        FROM `quiz_type_players_rs` INNER JOIN `quiz_general` ON quiz_type_players_rs.id_student = quiz_general.id_student 
        $whereandOption ;")->fetch_assoc();

    

    //////////////////7/ estilos de aprendizaje
    $learn_st_pers_SENS = $mysqli->query(
        "SELECT AVG(`perception_val`) AS `val` FROM quiz_learn_styles_rs INNER JOIN quiz_general ON quiz_learn_styles_rs.id_student = quiz_general.id_student  WHERE `perception`='SENS' $whereandOption  GROUP BY `perception`")->fetch_assoc();
    $learn_st_pers_INT = $mysqli->query(
        "SELECT AVG(`perception_val`) AS `val` FROM quiz_learn_styles_rs INNER JOIN quiz_general ON quiz_learn_styles_rs.id_student = quiz_general.id_student  WHERE `perception`='INT' $whereandOption GROUP BY `perception`")->fetch_assoc();

    $learn_st_proc_ACT = $mysqli->query(
        "SELECT AVG(`processes_val`) AS `val` FROM quiz_learn_styles_rs INNER JOIN quiz_general ON quiz_learn_styles_rs.id_student = quiz_general.id_student  WHERE  `processes`='ACT' $whereandOption GROUP BY `processes`")->fetch_assoc();
    $learn_st_proc_REF = $mysqli->query(
        "SELECT AVG(`processes_val`) AS `val` FROM quiz_learn_styles_rs INNER JOIN quiz_general ON quiz_learn_styles_rs.id_student = quiz_general.id_student  WHERE `processes`='REF' $whereandOption GROUP BY `processes`")->fetch_assoc();

    $learn_st_inp_VIS = $mysqli->query(
        "SELECT AVG(`input_val`) AS `val` FROM quiz_learn_styles_rs INNER JOIN quiz_general ON quiz_learn_styles_rs.id_student = quiz_general.id_student  WHERE `input`='VIS' $whereandOption GROUP BY `input`")->fetch_assoc();
    $learn_st_inp_VERB = $mysqli->query(
        "SELECT AVG(`input_val`) AS `val` FROM quiz_learn_styles_rs INNER JOIN quiz_general ON quiz_learn_styles_rs.id_student = quiz_general.id_student  WHERE `input`='VERB' $whereandOption GROUP BY `input`")->fetch_assoc();

    $learn_st_under_SEC = $mysqli->query(
        "SELECT AVG(`understand_val`) AS `val` FROM quiz_learn_styles_rs INNER JOIN quiz_general ON quiz_learn_styles_rs.id_student = quiz_general.id_student  WHERE `understand`='SEC'$whereandOption GROUP BY `understand`")->fetch_assoc();
    $learn_st_under_GLOB = $mysqli->query(
        "SELECT AVG(`understand_val`) AS `val` FROM quiz_learn_styles_rs INNER JOIN quiz_general ON quiz_learn_styles_rs.id_student = quiz_general.id_student  WHERE `understand`='GLOB' $whereandOption GROUP BY `understand`")->fetch_assoc();
    


    // generador de barras de porcentaje 
    function genPorcentBar($labelP,$porcentaje)
    {
        echo 
        '<h4 class="small">
            '.$labelP.' 
            <span class="float-right font-weight-bold">
                '.round($porcentaje,2).'%
            </span>
        </h4>
        <div class="progress mb-4">
            <div class="progress-bar';
        
        if ($porcentaje <15 ){
            echo ' bg-info';
        }else if ($porcentaje <35 ){
            echo ' bg-success';
        }else if ($porcentaje <55 ){
            echo ' bg-warning';
        }else {
            echo ' bg-danger';
        }
        
        echo '" role="progressbar" style="width: '. $porcentaje.'%;" 
                aria-valuenow="'.round($porcentaje,2).'" aria-valuemin="0" aria-valuemax="100">
                '.round($porcentaje,2).'%
            </div>
        </div>';

    }

    function genPorcentBarDescription($labelP,$porcentaje,$val,$bg)
    {
        echo 
        '<h4 class="small">
            '.$labelP.' 
            <span class="float-right font-weight-bold">
                '.round($porcentaje,2).'%
            </span>
        </h4>
        <div class="progress mb-4">
            <div class="progress-bar';
        echo '" role="progressbar" style="width: '. $porcentaje.'%; background-color: '.$bg.'" 
                aria-valuenow="'.round($porcentaje,2).'" aria-valuemin="0" aria-valuemax="100">
                '.$val.'
            </div>
        </div>';

    }

    if(isset($_POST["institutionFilter"])){
        if($_POST["institutionFilter"] == 'Ninguna'){
            $selectNinguna = true;
        }else {
            $selectNinguna = false;
        }
    }else {
        $selectNinguna = false;
    }
    

?>
<div class="header col-lg-8" >
    <h1 class="page-header-title" >Consultas </h1>
    <h6 class="text-muted">Recuerde que las opciones de filtrado obedecen a los datos ingresados en los test realizados previamente </h6>
</div>

<!-- formulario de control y filtros-->
<div class="row">

    
    <script>
        function getValSelect(){
            $("#FconsultaGen_id").submit();
        }
    </script>
    <!-- fistros -->
    <form id="FconsultaGen_id" name="FconsultaGen" action="<?php echo "?action=consultasG"?>"  method="POST" >
        <div class="form-group">
            <div class="card mb-4 " >
                <div class="d-flex card-header">
                    <div class="mr-auto p-2">
                        <label >Filtro de institución</label>        
                    </div>
                    <div class=" p-2">
                        <select id="institution" class="form-control" name="institutionFilter" onchange="getValSelect()">
                            <option value="Todas">Todas</option>
                            <?php if($selectNinguna){?>
                                    <option value="Ninguna" selected >Ninguna</option>
                            <?php } else {?>
                                <option value="Ninguna"  >Ninguna</option>
                            <?php } ?>
                            
                            <?php
                                for ($i=0; $i < $instituteOptions->num_rows; $i++) { 
                                    //get data
                                    $instituto = $instituteOptions->fetch_assoc();
                                    //set option
                                    if(isset($_POST["institutionFilter"])){
                                        if($_POST["institutionFilter"] == $instituto["isntitucion"] ){
                                            $select = "selected";
                                        }else {
                                            $select = "";
                                        }
                                    }

                                    echo "
                                    <option value=\"".$instituto["isntitucion"]."\" ".$select.">
                                    ".$instituto["isntitucion"]."
                                    </option>
                                    ";
                                    
                                }
                            ?>
                        </select>      
                    </div>
                </div>
            </div>
            <!--<button type="submit" class="btn btn-primary">Filtrar</a>-->
        </div>
    </form>


</div>
<div id="contenido">
    <div class="row">
        <!-- pie  -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Proporción en género
                    </h6>
                </div>
                <!-- body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col chart-pie pt-4 pb-2">
                            <canvas id="pyegenero" width="419" height="183" class="chartjs-render-monitor" style="display: block; width: 559px; height: 245px;"></canvas>
                        </div>
                        <!-- specific mo -->
                        <div class="col-5 align-self-center">
                            <?php genPorcentBarDescription("Femenino ",$p_genero_f,($generoF[0])[0],"#4e73df")?>
                            <?php genPorcentBarDescription("Masculino ",$p_genero_m,($generoM[0])[0],"#1cc88a")?>
                            <?php genPorcentBarDescription("Otros ",$p_genero_otr,$generoOTR,"#36b9cc")?>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- RANGO EDADES -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Proporción en rangos de edades
                    </h6>
                </div>
                <!-- body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col chart-pie pt-4 pb-2">
                            <canvas id="pyedades" width="419" height="183" class="chartjs-render-monitor" style="display: block; width: 559px; height: 245px;"></canvas>
                        </div>
                        <div class="col-5 align-self-center" >
                            <?php
                                $total_ed = count($labelsEdad);
                                $colors_arr = array('#4325CC', '#D6D0C9','#2C1982', '#BF7398','#FC890D', '#33C41A');
                                $cont_Ed = 0;
                                for ($i =0 ; $i<$total_ed; $i++)
                                {
                                    $cont_Ed += $valsEdad[$i];
                                }
                                for ($i =0 ; $i<$total_ed; $i++)
                                {
                                    genPorcentBarDescription($labelsEdad[$i],($valsEdad[$i]/$cont_Ed)*100,$valsEdad[$i],$colors_arr[$i]);
                                }
                                unset($colors_arr);
                            ?>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- GRADO RATE -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Proporción de grado
                    </h6>
                </div>
                <!-- body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col chart-pie pt-4 pb-2">
                            <canvas id="pyegrado" width="600" height="200" class="chartjs-render-monitor" style="display: block; width: 559px; height: 245px;"></canvas>
                        </div>    
                        <div class="col-6 align-self-center" >
                            <?php
                                $total_gr = count($labelsGrado);
                                
                                $colors_arr = array('#4325CC', '#D17D24','#1B0C5E', 
                                '#F564DA','#53F736', '#584A96',
                                '#D49A5D','#184710', '#D67E20',
                                '#2E961B','#851770', '#21B59F',
                                '#080126','#482FBA', '#B06617',
                                '#754108','#8C0774', '#BF8F5C');

                                $cont_Gr =0;
                                for ($i =0 ; $i<$total_gr; $i++){
                                    $cont_Gr += $valsGrado[$i];
                                }
                                for ($i =0 ; $i<$total_gr; $i++)
                                {
                                    genPorcentBarDescription($labelsGrado[$i]."°",($valsGrado[$i]/$cont_Gr)*100,$valsGrado[$i],$colors_arr[$i]);
                                }
                                unset($colors_arr);
                            ?>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- bar reds -->
        <div class="col-xl-8 ">
            <div class="card shadow mb-4">
                <!-- header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Uso de plataformas 
                    </h6>
                </div>
                <!-- body -->
                <div class="card-body">
                    <div class="chart-bar ">
                        <canvas id="plataformRate" width="446" height="160" class="chartjs-render-monitor" style="display: block; width: 446px; height: 160px;"></canvas>
                        
                    </div>
                </div>

            </div>
        </div>    
    </div>

    <!-- selections  -->
    <div class="row">
        <!-- tipos de jugador  -->
        <div class="col-xxl-4 col-xl-6 mb-4">
            <div class="card card-header-actions h-100">
                <div class="card-header">
                    Perfiles de jugadores
                </div>
                <div class="card-body">

                    <?php genPorcentBar("Filantropo ",$type_player_rate["ph"])?>
                    <?php genPorcentBar("Socializador ",$type_player_rate["so"])?>
                    <?php genPorcentBar("Espiritu libre",$type_player_rate["fr"])?>
                    <?php genPorcentBar("Triunfador ",$type_player_rate["ar"])?>
                    <?php genPorcentBar("Jugador ",$type_player_rate["pl"])?>
                    <?php genPorcentBar("Disruptor ",$type_player_rate["di"])?>

                </div>

            </div>
        </div>

        <div class="col-xl-6 col-lg-5 ">
            <div class="card shadow mb-4">
                <!-- header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Porcentaje de participación en Tipos de jugador
                    </h6>
                </div>
                <!-- body -->
                <div class="card-body">
                    <div class="polar-chart pt-4 pb-2">
                        <canvas id="pyeTp" class="chartjs-render-monitor" 
                        style="display: block; width: 559px; height: 340px;"></canvas>
                    </div>
                </div>
            </div>
        </div>


        <!-- bar reds -->
        <div class="col-xl-8 ">
            <div class="card shadow mb-4">
                <!-- header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                    Distribución de estilos de aprendizaje 
                    </h6>
                </div>
                <!-- body -->
                <div class="card-body">
                    <div class="chart-bar ">
                        <canvas id="chPercepcion" width="446" height="160" class="chartjs-render-monitor" style="display: block; width: 446px; height: 160px;"></canvas>
                        <canvas id="chCanal" width="446" height="160" class="chartjs-render-monitor" style="display: block; width: 446px; height: 160px;"></canvas>
                        <canvas id="chProceso" width="446" height="160" class="chartjs-render-monitor" style="display: block; width: 446px; height: 160px;"></canvas>
                        <canvas id="chEntendimiento" width="446" height="160" class="chartjs-render-monitor" style="display: block; width: 446px; height: 160px;"></canvas>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>
    



<!-- SCRIPS -->
<!-- PIE GENERO -->
<script>
    new Chart(document.getElementById("pyegenero"),{
        type: 'doughnut',
        data: {
            labels: ["Femenino", "Masculino", "Otros"],
            datasets: [{
            data: [
                '<?php echo round($p_genero_f,3)?>' ,
                '<?php echo round($p_genero_m,3)?>' ,
                '<?php echo round($p_genero_otr,3)?>' ,
            ],
            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
            hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            
            legend: {
                display: false,
                position: 'bottom',
                labels:{
                    boxWidth: 10,
                    padding: 20
                },
                generateLabels: function(val){
                    alert(val);
                }
            },
            
        }
    });
</script>

<!-- plataform rate -->
<script>
    
    //data convert 
    var valsData = [<?php echo '"'.implode('","', $valsPR).'"' ?>];
    for (var i =0; i< valsData.length; i++){
        valsData[i] = parseInt(valsData[i],10);
    }

    new Chart(document.getElementById("plataformRate"),{
        type: "bar",
        data: {
            labels: [<?php echo '"'.implode('","', $labelsPR).'"' ?>],
            datasets: [{
                label: "Revenue",
                backgroundColor: "rgba(0, 97, 242, 1)",
                hoverBackgroundColor: "rgba(0, 97, 242, 0.9)",
                borderColor: "#4e73df",
                data: [
                    <?php echo '"'.implode('","', $valsPR).'"' ?>
                ]
            }]
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                xAxes: [{
                   
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    
                    maxBarThickness: 40
                }],
                yAxes: [{
                   
                    gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2],
                        
                    },
                    ticks: {
                        min:0,
                        
                    },
                }]
            },
            legend: {
                display: false
            },
            tooltips: {
                titleMarginBottom: 10,
                titleFontColor: "#6e707e",
                titleFontSize: 14,
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: "#dddfeb",
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
                position: 'nearest',                
               
            },
        }
    });

    
    
</script>

<!-- EDADES -->
<script>
    
    new Chart(document.getElementById("pyedades"),{
        type: 'doughnut',
        data: {
            labels: [<?php echo '"'.implode('","', $labelsEdad).'"' ?>],
            datasets: [{
            data: [<?php echo '"'.implode('","', $valsEdad).'"' ?>],
            backgroundColor: ['#4325CC', '#D6D0C9','#2C1982', '#BF7398','#FC890D', '#33C41A'],
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false,
                position: 'bottom',
                labels:{
                    boxWidth: 10,
                    padding: 20
                }
            },
            
        }
    });
</script>

<!-- GRADO -->
<script>
    new Chart(document.getElementById("pyegrado"),{
        type: 'doughnut',
        data: {
            labels: [<?php echo '"'.implode('","', $labelsGrado).'"' ?>],
            datasets: [{
            data: [<?php echo '"'.implode('","', $valsGrado).'"' ?>],
            backgroundColor: ['#4325CC', '#D17D24','#1B0C5E', 
                                '#F564DA','#53F736', '#584A96',
                                '#D49A5D','#184710', '#D67E20',
                                '#2E961B','#851770', '#21B59F',
                                '#080126','#482FBA', '#B06617',
                                '#754108','#8C0774', '#BF8F5C', ],
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false,
                position: 'bottom',
                labels:{
                    boxWidth: 10,
                    padding: 20
                }
            },
            cutoutPercentage: 60,
            
        }
    });
</script>


 <!-- Tipos de jugador  -->
<script>
    new Chart(document.getElementById("pyeTp"),{
        type: 'polarArea',
        data: {
            labels: ['Filantropo','Socializador', 'Espiritu Libre', 'Triunfador', 'Jugador', 'Disruptor' ],
            datasets: [{
                data: [
                    <?php echo round($type_player_rate["ph"],3)?>,
                    <?php echo round($type_player_rate["so"],3)?>,
                    <?php echo round($type_player_rate["fr"],3)?>,
                    <?php echo round($type_player_rate["ar"],3)?>,
                    <?php echo round($type_player_rate["pl"],3)?>,
                    <?php echo round($type_player_rate["di"],3)?>,],
                backgroundColor: ['#556270C0', '#4ECDC4B0','#C7F464B0', 
                                    '#FF6B6BB0','#C44D58B0', '#A7A8F2B0',],
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: true,
                position: 'left',
                labels:{
                    boxWidth: 10,
                    padding: 20
                }
            },
            
            
        }
    });
</script>

<!-- Estilos de aprendizaje -->
<script>    
    new Chart(document.getElementById("chPercepcion"),{
        type: "horizontalBar",  
        data: {
            labels: ["Sensitivo","Intuitivo"],
            datasets: [{
                label: "Percepcion",
                backgroundColor: ["#CC2A41","#E8CAA4"],

                data: [
                    <?php if(isset($learn_st_pers_SENS["val"])){echo $learn_st_pers_SENS["val"];}else {echo 0;}?>,
                    <?php if(isset($learn_st_pers_INT["val"])){echo $learn_st_pers_INT["val"];}else {echo 0;}?>,
                ]
            }]
        },
        
        options: {
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                
                xAxes: [{
                   
                    gridLines: {
                        
                        drawBorder: false
                    },
                    ticks: {
                        min: 0,   // minimum value will be 0
                        max: 11,
                        stepSize:1,                        
                    },
                    
                    maxBarThickness: 40
                }],
                yAxes: [{
                   
                    gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2],
                        
                    },
                    ticks: {
                        min:0,
                        
                    },
                }]
            },

            legend: {
                display: true,
                position: 'top',
                labels:{
                    boxWidth: 0,
                    padding: 0,
                    fontStyle: 'bold'
                }
            },
            
            tooltips: {
                titleMarginBottom: 10,
                titleFontColor: "#6e707e",
                titleFontSize: 14,
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: "#dddfeb",
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
               
            },

            
        },
       
        
    });
    new Chart(document.getElementById("chCanal"),{
        type: "horizontalBar",  
        data: {
            labels: ["Visual","Verbal"],
            datasets: [{
                label: "Canal",
                backgroundColor: ["#4ECDC4","#C7F464"],

                data: [
                    <?php if(isset($learn_st_inp_VIS["val"])){echo $learn_st_inp_VIS["val"];}else {echo 0;}?>,
                    <?php if(isset($learn_st_inp_VERB["val"])){echo $learn_st_inp_VERB["val"];}else {echo 0;}?>,
                ]
            }]
        },
        options: {
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                xAxes: [{
                    ticks: {
                        min: 0,   // minimum value will be 0
                        max: 11,
                        stepSize:1,                        
                    },
                   
                    gridLines: {
                        
                        drawBorder: false
                    },
                    
                    maxBarThickness: 40
                }],
                yAxes: [{
                   
                    gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2],
                        
                    },
                    ticks: {
                        min:0,
                        
                    },
                }]
            },
            legend: {
                display: true,
                position: 'top',
                labels:{
                    boxWidth: 0,
                    padding: 0,
                    fontStyle: 'bold'
                }
            },
            tooltips: {
                titleMarginBottom: 10,
                titleFontColor: "#6e707e",
                titleFontSize: 14,
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: "#dddfeb",
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
               
            },

            
        },
       
        
    });
    new Chart(document.getElementById("chProceso"),{
        type: "horizontalBar",  
        data: {
            labels: ["Activo","Reflexivo"],
            datasets: [{
                label: "Procesamiento",
                backgroundColor: ["#036564","#CDB380"],

                data: [
                    <?php if(isset($learn_st_proc_ACT["val"])){echo $learn_st_proc_ACT["val"];}else {echo 0;}?>,
                    <?php if(isset($learn_st_proc_REF["val"])){echo $learn_st_proc_REF["val"];}else {echo 0;}?>,
                ]
            }]
        },
        options: {
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                xAxes: [{
                    ticks: {
                        min: 0,   // minimum value will be 0
                        max: 11,
                        stepSize:1,                        
                    },
                   
                    gridLines: {
                        
                        drawBorder: false
                    },
                    
                    maxBarThickness: 40
                }],
                yAxes: [{
                   
                    gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2],
                        
                    },
                    ticks: {
                        min:0,
                        
                    },
                }]
            },
            legend: {
                display: true,
                position: 'top',
                labels:{
                    boxWidth: 0,
                    padding: 0,
                    fontStyle: 'bold'
                }
            },
            tooltips: {
                titleMarginBottom: 10,
                titleFontColor: "#6e707e",
                titleFontSize: 14,
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: "#dddfeb",
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
               
            },

            
        },
       
        
    });
    new Chart(document.getElementById("chEntendimiento"),{
        type: "horizontalBar",  
        data: {
            labels: ["Global","Secuencial"],
            datasets: [{
                label: "Entendimiento",
                backgroundColor: ["#FF9900","#424242"],

                data: [
                    <?php if(isset($learn_st_under_GLOB["val"])){echo $learn_st_under_GLOB["val"];}else {echo 0;}?>,
                    <?php if(isset($learn_st_under_SEC["val"])){echo $learn_st_under_SEC["val"];}else {echo 0;}?>,
                ]
            }]
        },
        options: {
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                xAxes: [{
                    ticks: {
                        min: 0,   // minimum value will be 0
                        max: 11,
                        stepSize:1,                        
                    },
                   
                    gridLines: {
                        
                        drawBorder: false
                    },
                    
                    maxBarThickness: 40
                }],
                yAxes: [{
                   
                    gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2],
                        
                    },
                    ticks: {
                        min:0,
                        
                    },
                }]
            },
            legend: {
                display: true,
                position: 'top',
                labels:{
                    boxWidth: 0,
                    padding: 0,
                    fontStyle: 'bold'
                }
            },
            tooltips: {
                titleMarginBottom: 10,
                titleFontColor: "#6e707e",
                titleFontSize: 14,
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: "#dddfeb",
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
               
            },

            
        },
       
        
    });
</script>

