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

    $usersQuest = $mysqli->query("SELECT
    *
FROM
    `client`
INNER JOIN `quiz_general` ON `client`.`id_student` = `quiz_general`.`id_student`
INNER JOIN `quiz_learn_styles_rs` ON `client`.`id_student` = `quiz_learn_styles_rs`.`id_student`
INNER JOIN `quiz_type_players_rs` ON `client`.`id_student` = `quiz_type_players_rs`.`id_student`WHERE type_user != 1 $whereandOption $whereandOptionG");

    //function to get the label 
    function getLabel($dimention, $leb){
        switch ($dimention) {
            case "perception":
                if ($leb == "INT"){
                    return "Intuitivo";
                }else {
                    return "Sensitivo";
                }
                break;
            case "input":
                if ($leb == "VERB"){
                    return "Verbal";
                }else {
                    return "Visual";
                }
                break;
            case "processes":
                if ($leb == "REF"){
                    return "Reflectivo";
                }else {
                    return "Activo";
                }
                break;
            case "understand":
                if ($leb == "GLOB"){
                    return "Global";
                }else {
                    return "Secuencial";
                }
                break;
            
            default:
                return "";
                break;
        }
    }

?>
<script>
    $("#containerMain").attr("style","background-color:#ffffff");
</script>


<!-- formulario de control y filtros-->
<div class="row">
    <!-- fistros -->
    <script>
      function getValSelect(){
        $("#formExporttabel").submit();
      }
    </script>

    <form id="formExporttabel" name="formInstTable" action="<?php echo "?action=exportsF"?>"  method="POST" >
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
<div class="row" >
    <div class="col">
        
        <table id="tableUsers" class="table table-striped table-bordered dataTable" >
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Usuario</th>
                    <th>Genero</th>
                    <th>institución</th>
                    <th>Grado</th>
                    <th>Rango de edad</th>
                    <th>Percepción</th>
                    <th>Percepción Val.</th>
                    <th>Canal</th>
                    <th>Canal Val.</th>
                    <th>Proceso</th>
                    <th>Proceso Val.</th>
                    <th>Entendimiento</th>
                    <th>Entendimiento Val.</th>
                    <th>Filánthopo</th>
                    <th>Socializador</th>
                    <th>Espíritu Libre</th>
                    <th>Triunfador</th>
                    <th>Jugador</th>
                    <th>Disruptor</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php for($i =0; $i<$usersQuest->num_rows;$i++){
                        $usr = $usersQuest->fetch_assoc();
                    ?>
                    <tr>
                        <td><?php echo $usr["id_student"];?></td>
                        <td><?php echo $usr["username"]?></td>
                        <td><?php echo $usr["genero"]?></td>
                        <td><?php echo $usr["isntitucion"]?></td>
                        <td><?php echo $usr["grado"]?></td>
                        <td><?php echo $usr["r_edad"]?></td>
                        <td><?php echo getLabel("perception", $usr["perception"]);?></td>
                        <td><?php echo $usr["perception_val"]?></td>
                        <td><?php echo getLabel("input", $usr["input"]);?></td>
                        <td><?php echo $usr["input_val"]?></td>
                        <td><?php echo getLabel("processes", $usr["processes"]);?></td>
                        <td><?php echo $usr["processes_val"]?></td>
                        <td><?php echo getLabel("understand", $usr["understand"]);?></td>
                        <td><?php echo $usr["understand_val"]?></td>
                        <td><?php echo $usr["philanthrop"]?></td>
                        <td><?php echo $usr["socialiser"]?></td>
                        <td><?php echo $usr["free_spirit"]?></td>
                        <td><?php echo $usr["achiever"]?></td>
                        <td><?php echo $usr["player"]?></td>
                        <td><?php echo $usr["disruptor"]?></td>

                    </tr>
                <?php }?>
            </tbody>
        </table>

    </div>
</div>


<!-- datatable -->


<script>
    $(document).ready(function() {
        var table = $('#tableUsers').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ]
        } );


        // var contM = document.getElementById("#containerMain").attr("background-color","#ffff");
        // $('#tableUsers tbody').on( 'click', 'tr', function () {
        //     //console.log( (table.row( this ).data())[0 ]);
        //     $("#idConsult").val((table.row( this ).data())[0 ]);
        //     $("#usernameConsult").val((table.row( this ).data())[1 ]);
        //     $("#formExporttabel").submit();
            
        // } )
    } );
    
</script>
