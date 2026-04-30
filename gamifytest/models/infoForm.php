
<div class="container">
    <div class="row  align-items-center">
        <div class="col-12 col-md-6" >
            <div class="text-left mt-2">
                <img  class="img-fluid" src="models/img/Ultimate/Data.gif" />
                <p>
                    En caso de que desee informarse más a fondo acerca de los estilos de aprendizaje y los perfiles de jugador, puede dirigirse a la página de inicio y visitar las pestañas informativas: 
                    <a href="principal.php?action=inicio" class="stretched-link">Ir al inicio</a> 
                </p>
            </div>  
        </div>

        <div class="col-10 col-md-6" >
            <div class="text-left mt-2">
                <p class="lead">Debe diligenciar todos los test para posteriormente acceder a el análisis de sus respuestas.</p>
                <ul style="list-style-type: none;margin: 0;padding: 0;">
                    <li>
                        <?php if ( $resultadoGen->num_rows == 0 ) {?>
                    
                            <div class="card mb-2" >
                                <div class="card-body">
                                    <h5 class="card-title">¡Test de Información General!</h5>
                                    <p class="card-text "> Aún debes diligenciar el <i>Test de Información General</i> </p>
                                    <a href="principal.php?action=gen" class="btn btn-primary">Ir a Test Información General.</a>
                                </div>
                            </div>
                
                        <?php }else {?>
                            <div class="alertB success mb-2">
                                <span class="closebtnB" onclick="this.parentElement.style.display='none';">&times;</span> 
                                <h5 class="card-title">¡Envio Exitoso!</h5>
                                <p class="card-text "> Ha diligenciado correctamente el test de información general</p>
                            </div>
                        <?php }?>   
                    </li>

                    <li>
                        <?php if ( $resultadoLs->num_rows == 0 ) {?>
                            <div class="card mb-2" >
                                <div class="card-body">
                                    <h5 class="card-title">¡Test de Estilos de Aprendizaje!</h5>
                                    <p class="card-text "> Aún debes diligenciar el <i>Test de Estilos de Aprendizaje</i> </p>
                                    <a href="principal.php?action=frmls" class="btn btn-primary">Ir a Test de Estilos de Aprendizaje.</a>
                                </div>
                            </div>
                        
                        <?php }else {?>
                            <div class="alertB success mb-2">
                                <span class="closebtnB" onclick="this.parentElement.style.display='none';">&times;</span> 
                                <h5 class="card-title">¡Envio Exitoso!</h5>
                                <p class="card-text "> Ha diligenciado correctamente el test de estilos de aprendizaje</p>
                            </div>
                        <?php }?>
                    </li>

                    <li> 
                        <?php if ( $resultadoTp->num_rows == 0 ) {?>
                        
                            <div class="card mb-2" >
                                <div class="card-body">
                                    <h5 class="card-title">¡Test de Perfiles de jugador!</h5>
                                    <p class="card-text "> Aún debes diligenciar el <i>Test de Perfiles de jugador</i> </p>
                                    <a href="principal.php?action=frmtp" class="btn btn-primary">Ir a Test de Perfiles de jugador.</a>
                                </div>
                            </div>
                        
                        <?php }else{?>
                            <div class="alertB success mb-2">
                                <span class="closebtnB" onclick="this.parentElement.style.display='none';">&times;</span> 
                                <h5 class="card-title">¡Envio Exitoso!</h5>
                                <p class="card-text "> Ha diligenciado correctamente el test de Perfiles de jugadores</p>
                            </div>
                        <?php }?>
                    </li>
                </ul>
            </div>
        </div>
       
    </div>
</div>

    