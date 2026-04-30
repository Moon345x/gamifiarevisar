<?php
    $error =0;
    // import connection 
    require "controller/connection.php";


    if($_POST){
        //post validate
        $post = (isset($_POST['user']) && !empty($_POST['user'])) &&
        // (isset($_POST['email']) && !empty($_POST['email'])) &&
        (isset($_POST['password']) && !empty($_POST['password'])) &&
        (isset($_POST['password_conf']) && !empty($_POST['password_conf']));

        if($post){
            
            
            if($_POST['password_conf'] === $_POST['password']){
        
                // load data of form 
                $usuario = $_POST['user'];
                // $email = $_POST['email'];
                $password = $_POST['password'];
                // query probe
                $sqluser = "SELECT * FROM client WHERE username='$usuario'";
                // $sqlemail = "SELECT * FROM client WHERE mail='$email'";
                
                // execute query to prevent variuis same users
                $num_user = ($mysqli->query($sqluser))->num_rows;
                // $num_email = ($mysqli->query($sqlemail))->num_rows;
        
                if($num_user>0){
                    $error = 2;//user error,yet exist
                }
                // else if($num_email>0){
                //     $error = 3;//mail error, yet exist
                // }
                else  {
                    
                    //el usuario no ha sido registrado
                    // $sql = "INSERT INTO client (username, passwrd, mail, type_user)
                    //         VALUES ('$usuario', '$password', '$email', 0)";

//Modificado MD5 ppbm 3-11-2020. 10:00:00
	$passwordmd5=MD5($password);

                    $sql = "INSERT INTO client (username, passwrd, type_user)
                            VALUES ('$usuario', '$passwordmd5', 0)";


                    if($mysqli->query($sql)){
                        echo '<div class="alertB success">',
                        "<span class=\"closebtnB\" onclick=\"this.parentElement.style.display='none';\">&times;</span> ",
                        '<strong>¡Exito!</strong> se ha registrado con exito',
                        '</div>';
                        header("Location: index.php");
                    }else {
                        echo '<div class="alertB">',
                        "<span class=\"closebtnB\" onclick=\"this.parentElement.style.display='none';\">&times;</span> ",
                        '<strong>¡Cuidado!</strong> error con el server<br>', $sql ,$mysqli->error,
                        '</div>';    
                    }
                    
                }
            }else {
                $error=1;
            }
        }else {
            echo '<div class="alertB">',
            "<span class=\"closebtnB\" onclick=\"this.parentElement.style.display='none';\">&times;</span> ",
            '<strong>¡Cuidado!</strong> Verifique que los campos no estén vacios',
            '</div>';
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
        <title>Register Data Student</title>
        <link rel="icon" href="models/img/fab.ico" />
        <link href="css/styles.css" rel="stylesheet" />
        <link href="css/personalStyles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>
        <script type="text/javascript" src="js/personalJs.js"></script>

    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication" style="background: #6696FF;">
            <div id="layoutAuthentication_content" >
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <div class="card shadow-lg border-0 rounded-lg mt-4 mb-4">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Crea Una Cuenta</h3></div>
                                    <div class="card-body">
                                        
                                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>" >
                                            <!-- nameuser -->
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputFirstName">Usuario</label>
                                                <input class="form-control py-4" id="inputFirstName" 
                                                name="user"
                                                type="text" placeholder="Ingresa el nombre del usuario"
                                                value="<?php if (isset($_POST['user'])) {echo $_POST['user'] ;}?>"
                                                />
                                            </div>
                                            <?php
                                            if($error == 2){
                                                echo '<div class="alert">',
                                                "<span class=\"closebtn\" onclick=\"this.parentElement.style.display='none';\">&times;</span> ",
                                                '<strong>¡Error!</strong> Este nombre de usuario ya se encuentra registrado',
                                                '</div>';
                                            }
                                            ?>
                                            
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputPassword">Contraseña</label>
                                                        <input class="form-control py-4" id="inputPassword" 
                                                        name="password"
                                                        type="password" placeholder="Ingrese una contraseña" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputConfirmPassword">Confirme Contraseña</label>
                                                        <input class="form-control py-4" id="inputConfirmPassword" 
                                                        name="password_conf"
                                                        type="password" placeholder="Confirmar Contraseña" />
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            if($error == 1){
                                                echo '<div class="alert">',
                                                "<span class=\"closebtn\" onclick=\"this.parentElement.style.display='none';\">&times;</span> ",
                                                '<strong>¡Error!</strong> Las contraseñas no coinciden',
                                                '</div>';
                                            }
                                            ?>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" id="rm" onclick="showPassword('inputConfirmPassword')" type="checkbox" />
                                                    <label class="custom-control-label" for="rm" onclick="showPassword('inputPassword')">Mostrar contraseña</label>
                                                </div>
                                            </div>
                                            <div class="form-group mt-4 ">
                                                <!-- <a class="btn btn-primary btn-block" href="login.html">Crear Cuenta</a> -->
                                                <button type="submit" class="btn btn-primary btn-block" >Crear Cuenta</a>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small"><a href="index.php">¿Ya tienes cuenta?<br> ingresar</a></div>
                                    </div>
                                </div >
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
