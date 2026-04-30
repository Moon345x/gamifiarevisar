<?php
    require "controller/controller_nav.php";
    require "controller/connection.php";
    require_once 'controller/dompdf/autoload.inc.php';
    session_start();

    if(!isset($_SESSION['id'])){
        header("Location: 404.php");
    }
    
    $nombre = $_SESSION['username'];
    $type_user = $_SESSION['type_user'];

    $getor_de_contenido= new MVController();

    // load optios --------------------------------------------
    // consult the existence or results

    // sql consulta general
    $sqlGenOption= "SELECT * FROM quiz_general WHERE id_student =".$_SESSION["id"];

    // sql consulta learn styles
    $sqlLsOption = "SELECT * FROM quiz_learn_styles_rs WHERE id_student =".$_SESSION["id"];

    // sql consulta to player's type
    $sqlTpOption = "SELECT * FROM quiz_type_players_rs WHERE id_student =".$_SESSION["id"];

	//resultados de consulta
    $resultadoGen = $mysqli->query($sqlGenOption);
    $resultadoLs = $mysqli->query($sqlLsOption);
    $resultadoTp = $mysqli->query($sqlTpOption);

    
    if($resultadoGen->num_rows > 0){
        $genTestOption = false; // not abiable general test
    }else {
        $genTestOption = true; // abiable general test
    }

    if($resultadoLs->num_rows > 0){
        $lstTestOption = false; // not abiable general test
    }else {
        $lstTestOption = true; // abiable general test
    }

    if($resultadoTp->num_rows > 0){
        $tpTestOption = false; // not abiable general test
    }else {
        $tpTestOption = true; // abiable general test
    }
    

    if((($genTestOption && $lstTestOption) 
        && $tpTestOption)
        && ($type_user == 0 && ($_SESSION["acept_terms"] || ! isset($_GET["action"])))){

        $modalLaunch = true;
    }else {
        $modalLaunch = false;
    }
    
    
?>


<!DOCTYPE html>
<!-- <html lang="en"> -->
<html lang="en"> <!-- change to spanish-->
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Comp atible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />

        <title>Data student</title>
        <!-- icon app -->
        <link rel="icon" href="models/img/fab.ico" />

        <link href="css/personalStyles.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>

        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <!-- <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script> -->
        <script src="assets/demo/datatables-demo.js"></script>

        <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
        <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>
        <script src="js/spanishDatatables.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.1/css/select.bootstrap.min.css">
    









        

    </head>

    <body class="sb-nav-fixed">

        <!-- nav bar -->
        <!-- <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark" > -->
        <!-- <nav class="sb-topnav navbar navbar-expand navbar-dark " style="background-color: #222230;"> -->
        <!-- <nav class="sb-topnav navbar navbar-expand navbar-dark " style="background-color: #1D1F34;"> -->
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark" >

            <a class="navbar-brand" href="principal.php?action=inicio">Data Student</a>  <!-- se le coloca la pagina inicial-->
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>

            
            <!-- Navbar-->
            <ul class="navbar-nav ml-auto mr-0 mr-md-3 my-2 my-md-0" >
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php
                        //load the name
                            echo "$nombre ";
                        ?>
                        <i class="fas fa-user fa-fw"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <!--user options-->
                        <a class="dropdown-item" href="logout.php">Salir</a>
                    </div>
                </li>
            </ul>
        </nav>



        <!-- barra lateral -->
        <div id="layoutSidenav">
            
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu" >
                        <div class="nav" >


                            <div class="sb-sidenav-menu-heading">PRINCIPAL</div><!-- titulo divisor-->

                            <a class="nav-link" href="principal.php?action=inicio">
                                <div class="sb-nav-link-icon"><i class="fa fa-home"></i></div>
                                Inicio
                            </a>

                            <!-- OPCIONES DE ADMIN  --------------------------------- -->
                            <?php if ($type_user == 1){?>
                            <a class="nav-link" href="principal.php?action=options">
                                <div class="sb-nav-link-icon"><i class="fas fa-tasks"></i></div>
                                Opciones
                            </a>
                            <?php  }?>

                            <!--RESULTADOS DE ENCUESTAS  --------------------------------- -->
                            <?php if ($type_user == 0){?>
                            <a class="nav-link" href="principal.php?action=graphics">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                Resultados
                            </a>
                            <?php  }?>

                            <!-- ENCUESTAS  --------------------------------- -->
                            <?php if ($type_user == 0){?>
                                <?php if(($genTestOption || $lstTestOption ) || $tpTestOption){?>
                                    <div class="sb-sidenav-menu-heading">Encuestas</div><!-- titulo divisor-->
                                <?php  }?>

                                <?php if($genTestOption ){?>
                                <!-- Encuestas -->
                                <a class="nav-link" href="principal.php?action=gen">
                                    <div class="sb-nav-link-icon"><i class="fa fa-globe"></i></div>
                                    Test de Información General
                                </a>
                                <?php  }?>

                                <?php if($lstTestOption ){?>
                                <a class="nav-link" href="principal.php?action=frmls">
                                    <div class="sb-nav-link-icon"><i class="fa fa-graduation-cap"></i></div>
                                    Test de Estilos de Aprendizaje
                                </a>
                                <?php  }?>

                                <?php if($tpTestOption ){?>
                                <a class="nav-link" href="principal.php?action=frmtp">
                                    <div class="sb-nav-link-icon"><i class="fa fa-gamepad"></i></div>
                                    Test de Perfiles de Jugadores
                                </a>
                                <?php  }?>
                            <?php  }?>

                            <!-- CONSULTAS  --------------------------------- -->
                            <?php if ($type_user == 1){?>
                                <div class="sb-sidenav-menu-heading">Consultas</div>
                                <a class="nav-link" href="principal.php?action=consultasG">
                                    <div class="sb-nav-link-icon"><i class="fas fa-search"></i></div>
                                    Consultas Generales
                                </a>
                                <a class="nav-link" href="principal.php?action=consultasE">
                                    <div class="sb-nav-link-icon"><i class="fa fa-table"></i></div>
                                    Consulta especifica  
                                </a>
                                <a class="nav-link" href="principal.php?action=exportsF">
                                    <div class="sb-nav-link-icon"><i class="fas fa-file-export"></i></div>
                                    Exportar
                                </a>
                                

                            <?php  }?>
                        </div>
                    </div>

                    <!-- footer -->
                    <div class="sb-sidenav-footer">
                        <div class="small">Ingresaste como:</div>
                        <!-- Start Bootstrap -->
                        <?php
                            if($type_user == 0){
                                echo "Estudiante";
                            }else {
                                echo "Administrador";
                            }
                        ?>
                    </div>
                </nav>
            </div>


            
            <div id="layoutSidenav_content" >
                <!-- contenido -->
                <main>
                    <div id="containerMain" class="container-fluid " style="background-color: #EEF1F9">



                    
                        <?php  if($modalLaunch){ ?>
                           
                            <!-- Modal -->
                            <div class="modal fade " id="modalCondition"   role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                <div class="modal-dialog" role="document" id="appd">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Información importante</h5>
                                            <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button> -->
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                                Por favor diligencie el siguiente formulario. La información recopilada a través de este formulario está enmarcada dentro del desarrollo de una propuesta de investigación doctoral de un estudiante de doctorado en Ingeniería de la Universidad del Valle y será utilizada únicamente con fines académicos.
                                            </p>
                                            <p>
                                                En caso de tener alguna duda o solicitud puntual por favor enviar un correo a: <a href="mailto:yuri.bermudez@correounivalle.edu.co">yuri.bermudez@correounivalle.edu.co </a>  
                                            </p>
                                            <!-- En cumplimiento a nuestro deber de informar tal como lo dispone la Ley 1518 de 2012, la Universidad del Valle a través del programa de doctorado en Ingeniería énfasis en Ciencias de la computación, le comunica que los datos personales suministrados mediante el presente formulario serán utilizados como parte del desarrollo de un trabajo de investigación doctoral de un estudiante de doctorado en Ingeniería de la Universidad del Valle y serán utilizados únicamente con fines académicos y tratados conforme la política de protección de datos personales (Resolución de Rectoría 3.524 de 2019)  publicada en la página web: 
                                            <a href="http://www.univalle.edu.co/">http://www.univalle.edu.co/</a> -->
                                        </div>
                                        <div class="modal-footer">
                                            <a type="button" class="btn btn-secondary" href="logout.php">No aceptar</a>
                                            <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="aceptChanges()">Aceptar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>    
                                // $('#modalCondition').modal('show');
                                $('#modalCondition').modal({
                                    backdrop: false,
                                    show: true,
                                    keyboard: false
                                });
                                function aceptChanges(){
                                    <?php
                                       $_SESSION["acept_terms"] = false;
                                        ?>
                                    
                                }

                            </script>
                        <?php } ?>
                        


                        
                        <?php
                        //gestor de contenido 
                            $getor_de_contenido->enrutar();
                        ?>
                    </div>
                </main>


                <footer class="py-3 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted"> Copyright &copy; Gamifytest 2020</div>
                            
                        </div>
                    </div>
                </footer>



            </div>
        </div>


         <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script> -->
        <script src="js/scripts.js"></script>
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script> -->
        <!-- <script src="assets/demo/chart-area-demo.js"></script> -->
        <!-- <script src="assets/demo/chart-bar-demo.js"></script> -->
        <!-- <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script> -->
        <!-- <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script> -->
        <!-- <script src="assets/demo/datatables-demo.js"></script> -->
</html>
