<?php
    require "controller/connection.php";
    // consult the institutes
    $instituteOptions =  $mysqli->query("SELECT DISTINCT(isntitucion) FROM `quiz_general` WHERE isntitucion != 'Ninguna';");

    if(isset($_POST["institutionFilter"])){
        if($_POST["institutionFilter"] == 'Ninguna'){
            $selectNinguna = true;
        }else {
            $selectNinguna = false;
        }
    }else {
        $selectNinguna = false;
    }


    //filtrer option
    //institute
    if(isset($_POST["institutionFilter"])){
        if($_POST["institutionFilter"] != 'Todas'){
            $whereandOption = "AND quiz_general.isntitucion = '".$_POST["institutionFilter"]."' ";
        }else { 
            $whereandOption="";
        }
    }else {
        $whereandOption ="";
    }
  //genero
    if(isset($_POST["generoFilter"])){
        if($_POST["generoFilter"] != 'Todas'){
            $whereandOptionG = "AND quiz_general.genero = '".$_POST["generoFilter"]."' ";
        }else { 
            $whereandOptionG="";
        }
    }else {
        $whereandOptionG ="";
    }

    $usersQuest = $mysqli->query("SELECT client.id_student, client.username, quiz_general.genero, quiz_general.isntitucion FROM client INNER JOIN quiz_general ON client.id_student = quiz_general.id_student WHERE type_user != 1 $whereandOption $whereandOptionG");
    
    

    if(isset($_POST["id_student"])){
        if (strlen ($_POST["id_student"])){
            $whereandOptionSelect =  "WHERE id_student = ".$_POST["id_student"];
        }else {
            $whereandOptionSelect ="";
        }
    }else {
        $whereandOptionSelect  ="";
    }

    
    $resultadoLs = $mysqli->query("SELECT * FROM quiz_learn_styles_rs $whereandOptionSelect");
    $resultadoTp = $mysqli->query("SELECT * FROM quiz_type_players_rs $whereandOptionSelect");

    $rowsLs = $resultadoLs->fetch_assoc();
    $rowsTp = $resultadoTp->fetch_assoc();
    
    // labest to graphics 
    $labelsLs= array();
    $show_all = false;
    if ($rowsLs && $rowsTp && isset($_POST["id_student"])){
      if(strlen($_POST["id_student"]) != 0){
        $show_all = true;
        
        if ($rowsLs["perception"] == "INT"){
          $labelsLs["perception"] = "Intuitivo";
        }else {
          $labelsLs["perception"] = "Sensitivo";
        }


        if ($rowsLs["input"] == "VERB"){
          $labelsLs["input"] = "Verbal";
        }else {
          $labelsLs["input"] = "Visual";
        }

        if ($rowsLs["processes"] == "REF"){
          $labelsLs["processes"] = "Reflectivo";
        }else {
          $labelsLs["processes"] = "Activo";
        }

        if ($rowsLs["understand"] == "GLOB"){
          $labelsLs["understand"] = "Global";
        }else {
          $labelsLs["understand"] = "Secuencial";
        }

        // valores de la data
        $dataLs = array();
        $dataLs["perception"] = $rowsLs["perception_val"];
        $dataLs["input"] = $rowsLs["input_val"];
        $dataLs["processes"] = $rowsLs["processes_val"];
        $dataLs["understand"] = $rowsLs["understand_val"];

        // colores de la fuerza con la que se inclina 
        $backg = array();
        $backg_b = array();

        //funciton to generate the colors
        function getcolor($puntaje)
        {
          if($puntaje<=4){
            $color["rgba"] = "rgba(0, 146, 134, 0.4)";
            $color["rgb"] = "rgb(0, 146, 134)";
          }else if($puntaje<=8){
            $color["rgba"] = "rgba(0, 188, 170, 0.5)";
            $color["rgb"] = "rgb(0, 188, 170)";
          }else {
            $color["rgba"] = "rgba(44, 252, 189, 0.6)";
            $color["rgb"] = "rgb(44, 252, 189)";
          }
          return $color;
        }
        //this function allow know the value of points
        function getKnow($puntos, $equ, $modF){
          if($puntos <= 4){
            return "Presenta una <strong>preferencia equilibrada </strong> por las direccionamiento <i>".$equ."</i> aunque con una preferencia leve por un direccionamiento más ".$modF."<br>";
          }else if($puntos <= 8){
            return "Presenta una <strong>preferencia Moderada </strong>por un direccionamiento ".$modF.
            " así que se le puede facilitar el aprendizaje si se le brinda apoyo en esa dirección <br>";
          }else {
            return "Presenta una <strong>preferencia fuerte </strong>por un direccionamiento ".$modF.
            " así que puede que se dificulte la tarea de aprender si no se le brinda apoyo en esa dirección <br>";
          }
          
        }
        
        //set 
        $backg_b["perception"] = getcolor($rowsLs["perception_val"])["rgb"];
        $backg["perception"] = getcolor($rowsLs["perception_val"])["rgba"];

        $backg_b["input"] = getcolor($rowsLs["input_val"])["rgb"];
        $backg["input"] = getcolor($rowsLs["input_val"])["rgba"];

        $backg_b["processes"] = getcolor($rowsLs["processes_val"])["rgb"];
        $backg["processes"] = getcolor($rowsLs["processes_val"])["rgba"];
        
        $backg_b["understand"] = getcolor($rowsLs["understand_val"])["rgb"];
        $backg["understand"] = getcolor($rowsLs["understand_val"])["rgba"];


        //tratamiento
        
        $motivacionesTP = array(
          "philanthrop" => '<h6 class=" card-subtitle">Componente Filántropo: </h6>
            <p class="card-text p-2">
              <ul>
              <li> Impulsad@ por un <i> Propósito </i> en particular </i>
              <li> Requiere un propósito como motivación principal, siendo capaz de trabajar por él sin esperar algo a cambio. </li>
              <li> Puede ser de mucha ayuda para servir constructivamente y generar una experiencia positiva. </li>
              <li> Responde de manera motivada ante elementos de intercambio de conocimiento, tareas administrativas, roles de guía y actividades de comercio o colección. </li>
              </ul>
            </p>',

          "socialiser" =>
            '<h6 class=" card-subtitle">Componente Socializador:  </h6>
            <p class="card-text p-2">
              <ul>
              <li>Impulsad@ por las <i>Relaciones Interpersonales </i></li>
              <li>Requiere de un entorno que les permita generar distintas conexiones interpersonales con los demás participantes. </li>
              <li>Es visto muy motivado dentro de los entornos en donde se pueda interactuar con otros y crear conexiones sociales, respondiendo a estímulos y actividades elaboradas para desarrollarse de manera grupal. </li>
              <li>Responde de manera positiva ante actividades que se definan en entornos de trabajo en equipo o agrupaciones, gremios, etc. </li>
              </ul>
            </p>',

          "free_spirit" => 
            '<h6 class=" card-subtitle">Componente Espíritu Libre:  </h6>
            <p class="card-text p-2">
              <ul>
              <li>Impulsad@ por <i>Autonomía</i></li>
              <li>Requiere de un entorno que no limite las acciones de descubrimiento y exploración. </li>
              <li>Puede ser de ayuda en contextos de navegación, creatividad e innovación, aportando buenos resultados mientras no exista un control externo que les impida percibir una libertad. </li>
              <li>Responde continuamente a los estímulos de exploración, personalización, contenido desbloqueable y sorpresas. </li>
              </ul>
            </p>',

          "achiever" =>
            '<h6 class=" card-subtitle">Componentes Triunfador: </h6>
            <p class="card-text p-2">
              <ul>
              <li>Impulsad@ por la <i>Competencia </i></li>
              <li>Requiere un entorno competitivo, en donde se recompense sus logros con tareas que parezcan un reto, no solo con la complejidad de la tarea si no con la dificultad que presenta ante sus habilidades. </li>
              <li>Es capaz de completar tareas que requieran niveles de habilidades específicos para desarrollarlas. </li>
              <li>Responde de manera activa dentro de actividades que representen desafíos, donde sea capaz de medir su progreso, requieran del aprendizaje de nuevas habilidades. </li>
              </ul>
            </p>',
          
          "player" => 
            '<h6 class=" card-subtitle">Componente Jugador: </h6>
            <p class="card-text p-2">
              <ul>
              <li>Impulsad@ por las <i>Recompensas</i> </li>
              <li>Requiere de una recompensa a cambio de realizar las tareas propuestas. </li>
              <li>Se mantiene el interés siempre y cuando la recompensa sea de su agrado o despierte su curiosidad. </li>
              <li>Responde de manera comprometida con cualquier actividad propuesta que conlleve un premio, reconocimiento, puntuación o ganancia. </li>
              </ul>
            </p>',
          
          "disruptor" => 
            '<h6 class=" card-subtitle"> Componente Disruptor: <i>Cambio</i>  </h6>
            <p class="card-text p-2">
              <ul>
              <li>Impulsad@ por el<i>Cambio</i> </li>
              <li>No requieren un entorno específico para que entren a ejercer sus intereses, pues su motivación está en impulsar un cambio dentro del sistema planteado, colocando sus límites a prueba y forzando nuevas opciones. </li>
              <li>Presta una gran ayuda para determinar la consistencia en las actividades empleadas y muchas veces para generar cambios positivos o innovadores. </li>
              </ul>
            </p>'
        );

        function getScaleMotivations($resultadosTP,$motivacionesTPI){
          //$arrayVals = $rowsTp;
          $arrayVals = $resultadosTP;
          for ($i=0; $i < 6; $i++) { 
            //take MAx
            $maxVal = max( $arrayVals);
            //take indx
            $maxIndex = array_search( $maxVal, $arrayVals);
            //delete max val
            unset($arrayVals[$maxIndex]);

            //show options
            echo $motivacionesTPI[$maxIndex];
          }
        }
      }
    }
?>


<!-- formulario de control y filtros-->
<div class="row">
    <!-- fistros -->
    <script>
      function getValSelect(){
        $("#formInstTable_id").submit();
      }
    </script>

    <form id="formInstTable_id" name="formInstTable" action="<?php echo "?action=consultasE"?>"  method="POST" >
        <div class="form-group">
            <div class="card mb-4 mt-4 " >
                <div class="d-flex card-header">
                  <div class="row"> 
                    <div class="col">
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

                    <div class="col">
                      <div class="mr-auto p-2">
                          <label >Filtro de genero</label>        
                      </div>
                      <div class=" p-2">
                          <select id="generofiltrer" class="form-control" name="generoFilter" onchange="getValSelect()">
                              <option value="Todas">Todas</option>
                              <?php
                                $select ="";
                                if(isset($_POST["generoFilter"])){
                                  if($_POST["generoFilter"] == "Masculino" ){
                                    $select = "selected";
                                  }else {
                                      $select = "";
                                  }
                                }
                                echo "<option value=\"Masculino\" ".$select.">Masculino</option>";


                                $select ="";
                                if(isset($_POST["generoFilter"])){
                                  if($_POST["generoFilter"] == "Femenino" ){
                                    $select = "selected";
                                  }else {
                                      $select = "";
                                  }
                                }
                                echo "<option value=\"Femenino\" ".$select.">Femenino</option>";

                                $select ="";
                                if(isset($_POST["generoFilter"])){
                                  if($_POST["generoFilter"] == "NN" ){
                                    $select = "selected";
                                  }else {
                                      $select = "";
                                  }
                                }
                                echo "<option value=\"NN\" ".$select.">Otros</option>";
                              ?>
                          </select>      
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            <input type="hidden" id="idConsult" name="id_student" >
            <input type="hidden" id="usernameConsult" name="usernameConsult" >
        </div>
        
    </form>


</div>

<!-- tabla -->
<div class="row">
    <div class="col-md-12">
        <table id="tableUsers" class="table table-striped table-bordered dataTable" style="width:100%">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Usuario</th>
                    <th>Genero</th>
                    <th>institución</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php for($i =0; $i<$usersQuest->num_rows;$i++){
                        $usr = $usersQuest->fetch_assoc();
                    ?>
                    <!-- <?php echo "<tr onClick=\"$('#formInstTable_id').submit();\">";?> -->
                    <tr>
                        <td><?php echo $usr["id_student"];?></td>
                        <td><?php echo $usr["username"]?></td>
                        <td><?php echo $usr["genero"]?></td>
                        <td><?php echo $usr["isntitucion"]?></td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>



<?php
  if ($show_all){
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

<h2 class="mt-4">Resultados para:</h2>
<h4 class="mb-2 text-primary"> <?php echo $_POST["usernameConsult"];?></h4>

  <!-- Pestañas de muestra -->
<div class="mt-3 ml-n2 ">
  <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="pills-ls-tab" data-toggle="pill" href="#pills-ls" role="tab" aria-controls="pills-ls" aria-selected="true">Estilos de aprendizaje</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="pills-tp-tab" data-toggle="pill" href="#pills-tp" role="tab" aria-controls="pills-tp" aria-selected="false">Perfiles de jugadores</a>
    </li>
  </ul>
</div>

<!-- contenido de las pestañas -->
<div class="tab-content col-auto id="pills-tabContent">

  <!-- Graficas estilos de aprendizaje  -->
  <div class="tab-pane fade show active" id="pills-ls" role="tabpanel" aria-labelledby="pills-ls-tab">
    <!-- graphic -->
    <canvas  id="bar-chart"  class="chartjs-render-monitor" style=" width: 1100rem; height: 420rem;" ></canvas>
    <!-- info -->

    <div class="">
      
	    <h4 class="text-info"> Análisis</h4>
      <p class="card-tex lead">
        Para realizar el análisis de las respuestas al test de estilos de aprendizaje de Felder y Silverman se deben considerar las siguientes escalas:<br/>
        Si su puntaje en la escala está entre 1 - 3 usted presenta una <strong>preferencia equilibrada</strong> entre los dos extremos de los estilos de aprendizaje de esa dimensión.. Significa que de cualquier forma se le facilita el aprendizaje. Sin embargo, se puede presentar una <strong>preferencia muy leve</strong> por alguno de los estilos de aprendizaje.<br/>
        Si su puntaje está entre 5 - 7 usted presenta una <strong>preferencia moderada</strong> hacia uno de los dos extremos de los estilos de aprendizaje de esa dimensión y aprenderá más fácilmente si se le brindan apoyos relacionados con ese estilo de aprendizaje. <br/>
        Si su puntaje en la escala está entre 9 - 11 usted presenta una <strong>preferencia muy fuerte</strong> por uno de los dos extremos de los estilos de aprendizaje de esa dimensión. Usted puede llegar a presentar dificultades para aprender en un ambiente en el cual no cuente con apoyo relacionado con ese estilo de aprendizaje.<br/>
        A continuación se indica el significado de los resultados obtenidos en cada dimensión de aprendizaje de acuerdo a las respuestas que usted brindó en el test de estilos de aprendizaje:
      </p>	

      <dl class="blockquote">
        <dt class="mt-4 font-italic">¿Qué tipo de información perciben preferentemente los estudiantes?</dt>
        <dd class="pl-4 ">
          <?php 
            echo getKnow($rowsLs["perception_val"]," Sensorial e Intuitiva ",$labelsLs["perception"]  );
          ?>
        </dd>

        <dt class="mt-4 font-italic">¿A través de qué modalidad sensorial es más efectivamente percibida la información cognitiva?</dt>
        <dd class="pl-4">
          <?php 
            echo getKnow($rowsLs["input_val"]," Visual y Verbal ",$labelsLs["input"]  );
          ?>
        </dd>

        <dt class="mt-4 font-italic">¿Con qué tipo de organización de la información está más cómodo el estudiante a la hora de trabajar?</dt>
        <dd class="pl-4">
          <?php 
            echo getKnow($rowsLs["processes_val"]," Activo y Reflexivo ",$labelsLs["processes"]  );
          ?>
        </dd>

        <dt class="mt-4 font-italic">¿Cómo progresa el estudiante en su aprendizaje?</dt>
        <dd class="pl-4">
          <?php 
            echo getKnow($rowsLs["understand_val"]," Global y Secuencial ",$labelsLs["understand"]  );
          ?>
        </dd>
      </dl>
    </div>
  </div>

  <!-- Graficas de tipos de jugador -->
  <div class="tab-pane fade" id="pills-tp" role="tabpanel" aria-labelledby="pills-tp-tab">
    <div class="row align-items-center"">
      <div class="col-12 col-md-6 m-2" >
        <canvas id="chartjs-3"  class="chartjs-render-monitor b-2" ></canvas>
      </div>
      <div class="col-10 col-md-4" >
        <ul class="list-group list-group-flush text-center">
          <li class="list-group-item">
              <?php
                if(max($rowsTp) == $rowsTp["philanthrop"]){echo "<strong>";}
              ?>
             Filántropo :  <?php echo round( $rowsTp["philanthrop"] ,3);?> %
             <?php
                if(max($rowsTp) == $rowsTp["philanthrop"]){echo "</strong>";}
              ?>
          </li>

          <li class="list-group-item">
              <?php
                if(max($rowsTp) == $rowsTp["socialiser"]){echo "<strong >";}
              ?>
             Socializador :   <?php echo round( $rowsTp["socialiser"] ,3); ?> %
             <?php
                if(max($rowsTp) == $rowsTp["socialiser"]){echo "</strong>";}
              ?>
          </li>

          <li class="list-group-item">
              <?php
                if(max($rowsTp) == $rowsTp["free_spirit"]){echo "<strong>";}
              ?>
             Espíritu Libre :  <?php echo round( $rowsTp["free_spirit"] ,3); ?> %
             <?php
                if(max($rowsTp) == $rowsTp["free_spirit"]){echo "</strong >";}
              ?>
          </li>

          <li class="list-group-item">
              <?php
                if(max($rowsTp) == $rowsTp["achiever"]){echo "<strong>";}
              ?>
             Triunfador :   <?php echo round( $rowsTp["achiever"] ,3); ?> %
             <?php
                if(max($rowsTp) == $rowsTp["achiever"]){echo "</strong>";}
              ?>
          </li>

          <li class="list-group-item">
              <?php
                if(max($rowsTp) == $rowsTp["player"]){echo "<strong>";}
              ?>
             Jugador :  <?php echo round( $rowsTp["player"],3); ?> %
             <?php
                if(max($rowsTp) == $rowsTp["player"]){echo "</strong>";}
              ?>
          </li>

          <li class="list-group-item">
              <?php
                if(max($rowsTp) == $rowsTp["disruptor"]){echo "<strong>";}
              ?>
             Disruptor : <?php echo round( $rowsTp["disruptor"] ,3); ?> %    
             <?php
                if(max($rowsTp) == $rowsTp["disruptor"]){echo "</strong>";}
              ?>
          </li>
        </ul>
      </div>
    </div>

    <div class="card mt-2 shadow">
      <div class="card-header">
        <h5>Escala de motivaciones </h5>
        <h6 class="card-subtitle mb-2 text-muted"> 
        Encontrarás ordenadas de manera descendente aquellas descripciones de las motivaciones, desde la más importante a la que menos puede generar un impacto importante, partiendo desde el perfil de jugador inferido por las respuestas al test de Perfiles de Jugadores.
        </h6>
      </div>
      <div class="card-body">
        <?php
          getScaleMotivations($rowsTp,$motivacionesTP);
        ?>
      </div>

    </div>
  </div>
</div>

<?php   }?>


<!-- script barchart -->
<script>
    // Bar chart
    new Chart(document.getElementById("bar-chart"), {
        type: 'horizontalBar',
        data: {
          labels: [
            'Percepcion: ' +'<?php echo $labelsLs["perception"] ?>' ,
            'Canal: ' + '<?php echo $labelsLs["input"] ?>' ,
            'Proceso: ' + '<?php echo $labelsLs["processes"] ?>' ,
            'Entendimiento: ' + '<?php echo $labelsLs["understand"] ?>' 
            ],
          
          datasets: [
            {
              label: "Puntuación",
              borderWidth: 1,
              backgroundColor: [
                '<?php echo $backg["perception"] ?>' ,
                '<?php echo $backg["input"] ?>' ,
                '<?php echo $backg["processes"] ?>' ,
                '<?php echo $backg["understand"] ?>' 
              ],
              borderColor: [
                '<?php echo $backg_b["perception"] ?>' ,
                '<?php echo $backg_b["input"] ?>' ,
                '<?php echo $backg_b["processes"] ?>' ,
                '<?php echo $backg_b["understand"] ?>' 
              ],
              
              borderWidth:1,
              
              data: [
                '<?php echo $rowsLs["perception_val"] ?>' ,
                '<?php echo $rowsLs["input_val"] ?>' ,
                '<?php echo $rowsLs["processes_val"] ?>' ,
                '<?php echo $rowsLs["understand_val"] ?>' 
              ],
              
            }
          ]
        },
        options: {
          legend: { display: false },
          title: {
            display: false,
            text: 'Estilos de aprendizaje'
          },
          scales: {
            xAxes: [{
              display: true,
              ticks: {
                  min: 0,   // minimum value will be 0
                  max: 11,
                  stepSize:1,
                  
                  
              }
            }],
            yAxes: [{
              display: true,
              ticks: {
                
                  
              }
            }],

          }
          
        }
    });
</script>

<!-- script radial barchart -->
<script>
    new Chart(document.getElementById("chartjs-3"),{
      type:"radar",
      data:{
        labels:["Filántropo ","Socializador ","Espíritu Libre","Triunfador ","Jugador ","Disruptor "],
        datasets:[{
          label:"Procentaje",
          data: [ 
                '<?php echo round($rowsTp["philanthrop"],3) ?>' ,
                '<?php echo round($rowsTp["socialiser"] ,3)?>' ,
                '<?php echo round($rowsTp["free_spirit"] ,3)?>' ,
                '<?php echo round($rowsTp["achiever"] ,3)?>',
                '<?php echo round($rowsTp["player"] ,3)?>', 
                '<?php echo round( $rowsTp["disruptor"] ,3)?>'  
              ],
          fill:true,
          backgroundColor:"rgba(72, 123, 170, 0.2 )",
          borderColor:"#6369D1",
          pointBackgroundColor:"#2D8C83",
          pointBorderColor:"#ffAABB",
          pointHoverBackgroundColor:"#7BEDBA",
          pointHoverBorderColor:"#1AB3A6"
        }
        
        ]
      },
      options:{
        legend: { display: false },
        elements:{
          line:{
            tension:0,
            borderWidth:1.5
          }
        },
        scale: {
          angleLines: {
            display: true
          },
          gridLines:{
            display: true
          },
          ticks: {
              suggestedMin: 0,
          }
          
          
        }
      }});
</script>


<script>
    $(document).ready(function() {
        var table = $('#tableUsers').DataTable( {
            select: true,
        } );

        $('#tableUsers tbody').on( 'click', 'tr', function () {
            //console.log( (table.row( this ).data())[0 ]);
            $("#idConsult").val((table.row( this ).data())[0 ]);
            $("#usernameConsult").val((table.row( this ).data())[1 ]);
            $("#formInstTable_id").submit();
            
        } )
    } );
    
</script>


