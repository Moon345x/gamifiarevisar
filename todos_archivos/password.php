<?php
require "controller/connection.php";
require "controller/controllerMail.php";
//set error
$error = false;
$messaje = "";
if($_POST){
    if($_POST["correo"] === "" ){ // error de campo vacio 
        $error = true;
        $messaje = "Campo vacio!!!";
        // echo var_dump($_POST);
    }else { 
        $correo = $_POST["correo"];
        $sql = "SELECT * FROM client WHERE mail='$correo'";
        // execute query
        $resultado = $mysqli->query($sql);
        // number of rows
        $num = $resultado->num_rows;//extract the number of rows

        // validate user existece
        if($num>0){//User exist
            $row = $resultado->fetch_assoc();
            //db password
            $password_bd = $row['passwrd'];
            $username_r = $row['username'];
            
            
            $mailer =  new MailerW();
            
            $retorno = $mailer->sendRecoveryEmail($correo,$username_r,$password_bd);
            if($retorno["result"] == "ERROR"){
                echo '<div class="alertB">',
                "<span class=\"closebtn\" onclick=\"this.parentElement.style.display='none';\">&times;</span> ",
                ' '.$retorno["message"],
                '</div>';
            }else {
                echo '<div class="alertB success">',
                "<span class=\"closebtn\" onclick=\"this.parentElement.style.display='none';\">&times;</span> ",
                ' '.$retorno["message"],
                '</div>';
                header("Location: index.php");
            }
            
            
        }else {//error de usuario no existente
            $error = true;
            $messaje = "Este correo no se encuentra registrado";
        }
    }
    
}
// "Este correo no se encuentra registrado"


?>


<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Data student - Recovery</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="css/personalStyles.css" rel="stylesheet" />
        <link rel="icon" href="models/img/fab.ico" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.
        js" crossorigin="anonymous"></script>
        
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication" style="background: #6696FF;">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Recuperación de contraseña</h3></div>
                                    <div class="card-body">
                                        <div class="small mb-3 text-muted">
                                            Ingrese tu ditección de correo para enviar la información de tu usuario.
                                        </div>
                                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputEmailAddress">Email</label>
                                                <input class="form-control py-4" id="inputEmailAddress" type="email" aria-describedby="emailHelp" placeholder="Ingresa tu correo" 
                                                name="correo"
                                                value="<?php
                                                if(isset($_POST["correo"])){
                                                    echo $_POST["correo"];
                                                }
                                                ?>"
                                                />
                                            </div>
                                            <?php
                                            if($error){
                                                echo '<div class="alert">',
                                                "<span class=\"closebtn\" onclick=\"this.parentElement.style.display='none';\">&times;</span> ",
                                                '<strong>¡Error!</strong> '.$messaje,
                                                '</div>';
                                            }
                                            
                                            ?>
                                            <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small" href="index.php">Retornar al Ingreso</a>
                                                <button type="submit" class="btn btn-primary">Recuperar contraseña</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small"><a href="register.php">¿Necesitas una contraseña?<br>¡Registrate!</a></div>
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
                            <div class="text-muted">Copyright &copy; Your Website 2020</div>
                            
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
