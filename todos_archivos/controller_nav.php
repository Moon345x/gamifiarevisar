<?php

//  esta clase se encargará de mandar los respectivos contenidos a la pagina 
class MVController{
    // esta funcion enlaseará el contenido dentro de una section
    public function enrutar()
    {
        if(isset($_GET["action"])){
			$linkeded  = $_GET["action"];//load action

			if($linkeded === 'inicio'){
				$contenido = "models/inicio.php";    
			}else if($linkeded=="frmls"){
				$contenido = "models/formLS.php";    
			}else if($linkeded=="frmtp"){
				$contenido = "models/formTP.php";    
			}else if($linkeded=="gen"){
				$contenido = "models/formGen.php";    
			}else if($linkeded=="graphics"){
				$contenido = "models/graphics.php";
			}else if($linkeded=="options"){
				$contenido = "models/options.php";
			}else if($linkeded=="consultasG"){
				$contenido = "models/consultasG.php";
			}else if($linkeded=="consultasE"){
				$contenido = "models/consultasE.php";
			}else if($linkeded=="exportsF"){
				$contenido = "models/export.php";
			}
			else {
				echo "
				<script type=\"text/javascript\">
					window.location.href = \"404.php\";
				</script>
				";
			}
			
        }else {
            $contenido = "models/inicio.php";    
        }

        include $contenido;
    }
}

//  this class generathe some code
class FormGenerator
{
	public function generateTwoOptions($pregunta, $option1,$option2,$number){
		
		$code =
		"
		<div class=\"card mb-2\">
			<div class=\"card-header\">
				<h5 class=\"card-title\">
					$pregunta
				</h5>
			</div>";

		if(isset($_POST["pregunta".$number])){
			if($_POST["pregunta".$number] === "A"){
				$code = $code.
				"<div class=\"card-body\">
					<div class=\"mb-2 custom-radio custom-control\">
						<input type=\"radio\" 
						id=\"p".$number."A\" name=\"pregunta".$number."\" value=\"A\" class=\"custom-control-input\" checked>
						<label class=\"custom-control-label\" for=\"p".$number."A\">
							$option1
						</label>
					</div>
					<div class=\"mb-2 custom-radio custom-control\">
						<input type=\"radio\" 
						id=\"p".$number."B\" name=\"pregunta".$number."\" value=\"B\" class=\"custom-control-input\">
						<label class=\"custom-control-label\" for=\"p".$number."B\">
							$option2
						</label>
					</div>
				</div>";;
			}else {
				$code = $code.
				"<div class=\"card-body\">
					<div class=\"mb-2 custom-radio custom-control\">
						<input type=\"radio\" 
						id=\"p".$number."A\" name=\"pregunta".$number."\" value=\"A\" class=\"custom-control-input\" >
						<label class=\"custom-control-label\" for=\"p".$number."A\">
							$option1
						</label>
					</div>
					<div class=\"mb-2 custom-radio custom-control\">
						<input type=\"radio\" 
						id=\"p".$number."B\" name=\"pregunta".$number."\" value=\"B\" class=\"custom-control-input\" checked>
						<label class=\"custom-control-label\" for=\"p".$number."B\">
							$option2
						</label>
					</div>
				</div>";
			}
		}else {
			$code =$code.
			"<div class=\"card-body\">
				<div class=\"mb-2 custom-radio custom-control\">
					<input type=\"radio\" 
					id=\"p".$number."A\" name=\"pregunta".$number."\" value=\"A\" class=\"custom-control-input\" >
					<label class=\"custom-control-label\" for=\"p".$number."A\">
						$option1
					</label>
				</div>
				<div class=\"mb-2 custom-radio custom-control\">
					<input type=\"radio\" 
					id=\"p".$number."B\" name=\"pregunta".$number."\" value=\"B\" class=\"custom-control-input\">
					<label class=\"custom-control-label\" for=\"p".$number."B\">
						$option2
					</label>
				</div>
			</div>";
		}

		$code =$code."</div>";
		
		//show question

		echo $code;
	}
	
	public function generateSelect($pregunta,$numero){
		$opciones = array(
			"Totalmente de a cuerdo",
			"De a cuerdo",
			"Un poco de acuerdo",
			"No tengo opinion al respecto",
			"Un poco en desacuerdo",
			"En desacuerdo",
			"Totalmente en desacuerdo"
		);
		echo
		"<div class=\"card  mb-4\"> 
			<div class=\"d-flex card-header\">
				<div class=\" mr-auto p-2 \">
					<label>".$pregunta."</label>
				</div>
				<div class=\"p-2\" >
					<select class=\"form-control \" name=\"p".$numero."\">
		";
		for ($i=0; $i < 7; $i++) { 
			echo "				<option value=\"".($i+1)."\"";
			if(isset($_POST["p".$numero]) && $_POST["p".$numero] == ($i+1)){
				echo "selected";
			}
			echo ">".$opciones[$i]."</option>";
		}
		echo
		"
					</select>
				</div>
			</div>
		</div>
		";



	}
}



?>