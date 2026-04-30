<?php
    $error=0;
    // import connection 
    require "controller/connection.php";

    //session start
    session_start();
    
    // login
    if($_POST){
        // load data of form 
        $usuario = $_POST['user'];
        $password = $_POST['password'];

//Modificado MD5 ppbm 3-11-2020. 10:00:00
	$passwordmd5=MD5($password);

        // query
        // $sql = "SELECT id, username, passwrd, mail, type_user FROM client WHERE username='$usuario'";;
        $sql = "SELECT * FROM client WHERE username='$usuario'";;
        // execute query
        $resultado = $mysqli->query($sql);
        // number of rows
        $num = $resultado->num_rows;//extract the number of rows

        // validate user existece
        if($num>0){//User exist

            //copy the result
            $row = $resultado->fetch_assoc();
            //db password
            $password_bd = $row['passwrd'];

//Modificado MD5 ppbm 3-11-2020. 10:00:00
            
            //if($password_bd == $password){
	    if($password_bd == $passwordmd5){

                // session
                $_SESSION['id']= $row['id_student'];
                $_SESSION['mail']= $row['mail'];
                $_SESSION['username']= $row['username'];
                $_SESSION['type_user']= $row['type_user'];
                $_SESSION['acept_terms'] = true;

                // session init
                header("Location: principal.php");
                
            }else {
                $error=2;
            }
        }else {//inexistece user
            $error=1;
        }

    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login Data Student</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="css/personalStyles.css" rel="stylesheet" />
        <link rel="icon" href="models/img/fab.ico" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>
        <script type="text/javascript" src="js/personalJs.js"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication" style="background: #6696FF;">
            <div id="layoutAuthentication_content" ">
                <main>
                    <div class="container" >
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5 mb-4">
                                    <div class="card-header">
                                        <h3 class="text-center font-weight-light my-4"><strong> BIENVENIDO </strong></h3>
                                        
                                    </div>
                                    
                                    <div class="card-body">

                                    <!-- formulario -->
                                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">

                                            <div class="form-group">
                                                <label class="small mb-1" for="inputEmailAddress">Usuario</label>
                                                <input class="form-control py-4" id="inputEmailAddress"
                                                name="user"
                                                 type="text" placeholder="Ingrese su Usuario" />
                                            </div>
                                            <?php
                                            if($error == 1){
                                                echo '<div class="alert">',
                                                "<span class=\"closebtn\" onclick=\"this.parentElement.style.display='none';\">&times;</span> ",
                                                '<strong>¡Error!</strong> El usuario no se encuentra registrado',
                                                '</div>';
                                            }
                                            ?>
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputPassword">Contraseña </label>
                                                <input class="form-control py-4" id="inputPassword" 
                                                name="password"
                                                type="password" placeholder="ingrese su contraseña" />
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" id="rm" " type="checkbox" />
                                                    <label class="custom-control-label" for="rm" onclick="showPassword('inputPassword')">Mostrar contraseña</label>
                                                </div>
                                            </div>
                                            <?php
                                            if($error == 2){
                                                echo '<div class="alert">',
                                                "<span class=\"closebtn\" onclick=\"this.parentElement.style.display='none';\">&times;</span> ",
                                                '<strong>¡Error!</strong> La contraseña no coinside',
                                                '</div>';
                                            }
                                            ?>
                                            
                                            <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a href=""></a>
                                                <!-- <a class="small" href="password.php">¿Olvido su contraseña?</a> -->
                                                <!-- <a class="btn btn-primary"href="index.html">Ingresar</a> -->
                                                <button type="submit" class="btn btn-primary" >Ingresar</a>
                                                
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small"><a href="register.php">¿No tienes una cuenta? ¡Registrate!</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Gamifytest 2020</div>
                            
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
