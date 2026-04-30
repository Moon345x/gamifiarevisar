<?php

    
    require "controller/connection.php";
    

    //opciones de eliminacion
    
    if(isset($_POST["ins-option"])){
        if($_POST["ins-option"] == "update"){

            // new info
            $new_name = $_POST["ins-name"];
            $new_description = $_POST["ins-desc"];
            $id_modify = $_POST["ins-id"];

            //sql
            $sqlUpdate = "UPDATE institution 
                            SET ins_name='$new_name',  ins_description='$new_description'
                            WHERE id_institut = ".$_POST["ins-id"];               
            
            
            $mysqli->query($sqlUpdate);

        }else if($_POST["ins-option"] == "delete"){
            // delete  sql
            $sqlDelete = "DELETE FROM institution WHERE id_institut = ".$_POST["ins-id"];                  
            $mysqli->query($sqlDelete);
        }else if($_POST["ins-option"] == "add"){

            $new_name = $_POST["ins-name"];
            $new_description = $_POST["ins-desc"];
            // delete  sql
            $sqlInsert = "INSERT INTO institution (ins_name, ins_description) VALUES ('$new_name', '$new_description')";
            
            $mysqli->query($sqlInsert);
            
        }

        $_POST["ins-option"] = "";
    }

    //SQL INSTITUTIONS
    $sql_Select_inst= "SELECT * FROM institution";
    //Execute the sql
    $instituntes_res =  $mysqli->query($sql_Select_inst);
    if(!$instituntes_res){
        //mostrar error 
        echo '
        <script>
            alert("'.$mysqli->error.'");
        </script>';

        // desviar al inicio
        echo "
        <script type=\"text/javascript\">
            window.location.href = \"principal.php?action=inicio\";
        </script>
        ";
    }



     // function of convert string

    function convertString($str_in){
        $string_return = preg_replace("/[\r\n|\n|\r]+/", "\\n", $str_in);
        return $string_return;
    }
    

?>

<div class="pt-4">
    <div class="card shadow mb-2 ">
        <div class="card-header ">
            <h6 class="m-1 font-weight-bold text-primary">Manejo de Instituciones </h6>
        </div>
        <div class="card-body">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-auto col-mb-2">
                        <!-- atable of institutions -->
                        <table class="table table-hover" id="inttitute-table" >
                            <thead class="thead-dark ">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Id</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Descripción</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    for ($i=0; $i < $instituntes_res->num_rows; $i++) { 
                                        $instituntes_rows = $instituntes_res->fetch_assoc();
                                        // <tr onClick=\"loadInfoInst('".$instituntes_rows["id_institut"]."','".$instituntes_rows["ins_name"]."',".$instituntes_rows["ins_description"]."')\">
                                        echo "
                                        <tr onClick=\"loadInfoInst('".$instituntes_rows["id_institut"]."','".$instituntes_rows["ins_name"]."','".convertString($instituntes_rows["ins_description"])."')\">
                                            <th scope=\"row\">".($i+1)."</th>
                                            <td>".$instituntes_rows["id_institut"]."</td>
                                            <td>".$instituntes_rows["ins_name"]."</td>
                                            <td>".$instituntes_rows["ins_description"]."</td>
                                        </tr>
                                        ";
                                    }
                                ?>
                                
                            </tbody>
                        </table>
                        
                    </div>
                    <div class="col-sm">
                        <form action="#" method="post">
                            <div class="form-group">
                                <input name="ins-option" id="ins-option" style="display: none;" />
                                <label for="nombre-ins">Nombre </label>
                                <div class="row">
                                    <div class="col-12 col-md-8">
                                        <input name="ins-name" id="nombre-ins" class="form-control" placeholder="Nombre" />
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <input name="ins-id" id="id-ins" class="form-control" placeholder="#ID" readonly />
                                    </div>
                                </div>
                                    
                                
                                <label for="descrip-ins">Descripción</label>
                                <textarea name="ins-desc" id="descrip-ins"  class="form-control"  rows="3"></textarea>
                            </div>
                            <div class="row">   
                                <button onclick="deleteInst()" id="delete"  class="btn btn-danger m-1 mb-2">Eliminar</button>
                                <button onclick="updateInst()" id="update" class="btn btn-info m-1 mb-2">Actualizar</button>
                                <button onclick="addInst()" id="add" class="btn btn-primary m-1 mb-2">Adherir</button>
                            </div>     
                        </form>
                    </div>
                </div>
            </div>
            
        </div>    
    </div> 
</div>




<script>
    function loadInfoInst(id, nombre, description) {
        $("#nombre-ins").val(nombre);
        $("#descrip-ins").val(description);
        $("#id-ins").val(id);
    };

    function deleteInst(){
        if($("#id-ins").val().length != 0){
            if(confirm("¿Desea eliminar esta opción?")) {
                $("#ins-option").val("delete");
            }
        }
    }
    function updateInst(){
        if($("#id-ins").val().length != 0){
            if(confirm("¿Desea modificar esta opción?")) {
                
                $("#ins-option").val("update");
            }
        }
    }

    function addInst(){
        $("#ins-option").val("add");
    }
    
    // $(document).ready(function() {
    //     alert("asdfasd");
    //     $('#dataTable').DataTable();
    // });
    
</script>
