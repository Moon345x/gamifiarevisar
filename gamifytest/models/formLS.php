
<?php
	require "controller/connection.php";

	$fg = new FormGenerator();

	// consult the existence or results
	$sql = "SELECT * FROM quiz_learn_styles_rs WHERE id_student =".$_SESSION["id"];
	// echo $_SESSION["id"];
	$resultado = $mysqli->query($sql);
	$num = $resultado->num_rows;//extract the number of rows
	// echo $num;
	if($num >0){//probar si hay resultados de encuestas previas 
		// mostrar resultados
		$showForm=false;
		echo "
		<script type=\"text/javascript\">
			window.location.href = \"principal.php?action=graphics\";
		</script>
		";
		
		
	}else {// si no dejar presentar la encuesta
		$showForm=true;

		if($_POST){
			//  var_dump($_POST);
			//serch the first fields 
			
			//test each question
			$revisarP = true;
			for ($i=1; $i <= 44; $i++) { 
				if(!(isset($_POST["pregunta".$i]))){
					$revisarP = false;
					break;
				}
			}

			//probe the error 
			if($revisarP){ //all questions send success

				//guardar preguntas 
				//build sql and results
				
				$resultA= array(0,0,0,0);
				$resultB= array(0,0,0,0);

				

				//sql's
				$sqlT ="INSERT INTO quiz_learn_styles ( id_student";
				$sqlB ="VALUE (".$_SESSION["id"];
				

				for ($i=1; $i <= 44; $i++) { 
					$sqlT = $sqlT.", p".$i;
					$sqlB = $sqlB.","."'".$_POST["pregunta".$i]."'";

					if($_POST["pregunta".$i] === 'A'){
						$resultA[(($i+3)%4)]++;
					}else {
						$resultB[(($i+3)%4)]++;
					}
				}
				// append sql
				$sql2 = $sqlT.") ".$sqlB.") ";

				//processs the data 

				//process
				if($resultA[0] >$resultB[0]){
					$process = 'ACT';
				}else {
					$process = 'REF';
				}
				$process_value = abs($resultA[0] - $resultB[0]);

				//Perception
				if($resultA[1] >$resultB[1]){
					$perception = 'SENS';
				}else {
					$perception = 'INT';
				}
				$perception_value = abs( $resultA[1] - $resultB[1] );

				//flume
				if($resultA[2] >$resultB[2]){
					$channel = 'VIS';
				}else {
					$channel = 'VERB';
				}
				$channel_value = abs($resultA[2] - $resultB[2]);

				//understand
				if($resultA[3] >$resultB[3]){
					$understand = 'SEC';
				}else {
					$understand = 'GLOB';
				}
				$understand_value = abs($resultA[3] - $resultB[3]);

				$sql3 = "INSERT INTO `quiz_learn_styles_rs` 
				VALUES (".$_SESSION["id"].","
				."'".$perception."'".",".$perception_value.","
				."'".$channel."'".",".$channel_value.","
				."'".$process."'".",".$process_value.","
				."'".$understand."'".",".$understand_value.")";

				//execute fields 
				if(
				$mysqli->query($sql2) &&
				$mysqli->query($sql3)
				){
					echo "
					<script type=\"text/javascript\">
						window.location.href = \"principal.php?action=graphics\";
					</script>
					";
					
				}else {
					echo '<div class="alertB">',
					"<span class=\"closebtnB\" onclick=\"this.parentElement.style.display='none';\">&times;</span> ",
					'<strong>¡Cuidado!</strong> error con el server<br>', $sql2, $sql3 ,$mysqli->error,
					'</div>';    
				}
			}else{// error un question
				echo
				'<div class="alertB ">',
				"<span class=\"closebtn\" onclick=\"this.parentElement.style.display='none';\">&times;</span> ",
				'HACE FALTA LA PREGUNTA '.$i,
				'</div>';	
			}
		}

	}
	

	
	
?>
<?php if($showForm){?>
<div class="container "  >

	<div class="header col-lg-8 " >
		<h1 class="page-header-title" >Test Estilos de aprendizaje  </h1>
		<p class="page-header-text  mb-5" style="text-align: justify;">
			Lee detenidamente y selecciona la opción que más se adapte a su manera de aprender; en caso de que las dos opciones que se le muestran correspondan a su parecer, seleccione aquella con la que se sienta más identificad@. 
		</p>
	
	</div>
	
	
	<form name="F1" action="<?php echo "?action=frmls"?>"  method="POST" >
		<div class="col-lg-auto  mb-2">
			
			
			<div class="tab-content" id="myTabContent">
				<div class="tab-pane  show active" id="t1" role="tabpanel" >
					<?php
						$fg->generateTwoOptions(
							'1. Entiendo mejor algo cuando:',
							'Lo practico constantemente.',
							'Solo pienso y reflexiono sobre ello constantemente.',
							1);
						$fg->generateTwoOptions(
							'2. ¿Qué tipo de enfoque puedes utilizar más a menudo para analizar un problema?',
							'Realista:<br> Eres una persona más realista con respecto a la situación e intentas buscar soluciones a partir de lo puedes visualizar.',
							'Innovador:<br> Eres una persona más innovadora, pensando en las distintas soluciones que podrías llegar a generar para solucionar el problema.',
							2);
						$fg->generateTwoOptions(
							'3. Cuando piensas en lo que hiciste el día anterior, lo haces basándote en:',
							'Imágenes de lo que sucedió. ',	
							'Relatos o palabras de lo que sucedió.',
							3);
						$fg->generateTwoOptions(
							'4. En el instante en el que entiendes algo lo haces:',
							'Entendiendo los detalles de un tema pero no viendo su estructura completa.',
							'Entendiendo la estructura completa del tema pero no viendo claramente los detalles.',
							4);
						$fg->generateTwoOptions(
							'5. Cuando intentas aprender algo nuevo, te ayuda:',
							'Hablar o debatir de ello con otras personas.',
							'Pensar en ello y reflexionar sobre el tema.',
							5);
						$fg->generateTwoOptions(
							'6. Si fueses un profesor, preferirías dar un curso:',
							'Que trate más sobre hechos y situaciones reales de la vida.',
							'Que trate con ideas y teorías.',
							6);
						$fg->generateTwoOptions(
							'7. Prefiero obtener información nueva por medio de:',
							'Imágenes, diagramas, gráficas o mapas.',
							'Instrucciones escritas o información verbal.',
							7);
						$fg->generateTwoOptions(
							'8. ¿De qué maneras logras entender mejor un tema?:',
							'Entiendo primero cada una de sus partes y luego su totalidad.',
							'Comprendo su estructura total y luego como encajan sus partes.',
							8);
						$fg->generateTwoOptions(
							'9. En un grupo de estudio que trabaja con un material difícil, es más probable que mi actitud sea:',
							'Participar y contribuir con ideas para llegar a una solución.',
							'No participar y solo escuchar para aclarar mis ideas.',
							9);
						$fg->generateTwoOptions(
							'10. Se me facilita más Aprender mediante:',
							'El uso de hechos, acciones y prácticas.',
							'El uso de Teoría y conceptos.',
							10);
						$fg->generateTwoOptions(
							'11. En un libro con muchas imágenes y gráficas es más probable que:',
							'Me enfoque más en revisar cuidadosamente las imágenes y las gráficas.',
							'Me enfoque mas en leer todo el texto y el contenido escrito.',
							11);
					?>
				</div>
				<div class="tab-pane " id="t2" role="tabpanel" >
					<?php
						$fg->generateTwoOptions(
							'12. Cuando resuelvo problemas de matemáticas:',
							'Por lo general llego a soluciones paso por paso.',
							'A menudo llego a las soluciones rápidamente, pero luego tengo que batallar para averiguar los pasos que me llevaron a las soluciones.',
							12);
						$fg->generateTwoOptions(
							'13. En las clases que he tomado: ',
							'Por lo general he llegado a conocer a muchos de los estudiantes.',
							'Pocas veces he llegado a conocer a muchos de los estudiantes.',
							13);
						$fg->generateTwoOptions(
							'14. Cuando leo temas que no son de ficción, prefiero:',
							'Algo que me enseñe nuevos hechos o me diga como hacer algo.',
							'Algo que me dé nuevas ideas para pensar y me motive a imaginar.',
							14);
						$fg->generateTwoOptions(
							'15. Me agrada más cuando un maestro:',
							'Utiliza muchos esquemas y dibujos en el tablero para explicar el tema.',
							'Toman mucho tiempo en la explicando dialogando acerca del tema.',
							15);
						$fg->generateTwoOptions(
							'16. Cuando estoy analizando un cuento, una novela o texto narrativo, sucede que:',
							'Pienso en los incidentes y trato de acomodarlos para estructurar los temas.',
							'Me doy cuenta de cuáles son los temas cuando termino de leer y luego tengo que regresar y encontrar los incidentes que los demuestran.',
							16);
						$fg->generateTwoOptions(
							'17. Cuando comienzo a resolver un problema dentro de alguna tarea, es más probable que:',
							'Comience a trabajar en su solución inmediatamente y empiece a solucionar cada parte.',
							'Primero trate de entender completamente el problema y luego plantea una solución.',
							17);
						$fg->generateTwoOptions(
							'18. Prefiero las ideas basadas en: ',
							'La certeza, concreto o correcto, estando estructuradas en conocimientos previos, claros y seguros de algo.<br>
							<i> Se que esa idea tiene una base que es correcta o estoy segur@ de que es cierta o no es falsa. </i>',
							'La teoría, que están estructuradas en diferentes pensamientos y conjeturas acerca de algo. <br>
							<i> Se que esta idea está pensada desde alguna manera innovadora, pero podría no ser cierta. </i>',
							18);
						$fg->generateTwoOptions(
							'19. Recuerdo mejor:',
							'Las cosas que veo.',
							'Las cosas que oigo.',
							19);
						$fg->generateTwoOptions(
							'20. Lo más importante para mí de un docente/tutor es que:',
							'Exponga el material en pasos secuenciales y claros.',
							'Me dé un panorama general y relacione el material con otros temas.',
							20);
						$fg->generateTwoOptions(
							'21. Prefiero estudiar de manera:',
							'Grupal (un grupo de estudio).',
							'Individual (apartado de los grupos de estudio).',
							21);
						$fg->generateTwoOptions(
							'22. Prefiero que se me considere:',
							'Cuidados@ en los detalles al realizar mi trabajo.',
							'Creativ@ en la manera en la que realizo mi trabajo.',
							22);
					?>
				</div>
				<div class="tab-pane " id="t3" role="tabpanel" >
					<?php
						$fg->generateTwoOptions(
							'23. Cuando alguien me da direcciones de nuevos lugares, prefiero hacer use de:',
							'Un mapa o diagrama.',
							'Instrucciones escritas.',
							23);
						$fg->generateTwoOptions(
							'24. Aprendo de manera:',
							'Constante, si estudio con empeño consigo lo deseado.',
							'Con pausas y reinicios, me llego a confundir muchas veces pero de un momento a otro lo entiendo.',
							24);
						$fg->generateTwoOptions(
							'25. Ante una nueva tarea, prefiero:',
							'Hacer algo y ver que sucede luego.',
							'Pensar como voy a hacer algo antes de actuar.',
							25);
						$fg->generateTwoOptions(
							'26. Cuando leo por diversión, me agradan más los escritores que: ',
							'Dicen claramente lo que desean dar a entender.',
							'Dicen las cosas en forma creativa e interesantes.',
							26);
						$fg->generateTwoOptions(
							'Cuando veo un esquema, mapa, diagrama, dibujo o bosquejo en clase para la explicación de un tema, es más probable que de él recuerde:',
							'Las imágenes que lo componen.',
							'Lo que el profesor dijo acerca del mismo.',
							27);
						$fg->generateTwoOptions(
							'28. Cuando intento aprender un nuevo tema:', 
							'Me concentro en los detalles y paso por alto la estructura total de la temática.', 
							'Trato de entender el todo antes de concentrarme en los detalles de la temática.', 
							28);
						$fg->generateTwoOptions(
							'29. Recuerdo más fácilmente:', 
							'Las cosas que hago.', 
							'Las cosas en las que he pensado mucho.', 
							29);
						$fg->generateTwoOptions(
							'30. Cuando tengo un trabajo o tarea por hacer, prefiero:',
							'Intentar dominar una manera de hacerlo.',
							'Intentar nuevas formas de hacerlo.',
							30);
						$fg->generateTwoOptions(
							'31. Cuando alguien quiere mostrarte datos, prefieres que:',
							'Haga uso de uso de gráficas, esquemas o dibujos para explicarme los resultados.',
							'Haga uso de texto para resumir y explicarme los resultados.',
							31);
						$fg->generateTwoOptions(
							'32. Cuando escribo un trabajo, es más probable que lo haga (piense o escriba):',
							'Desde el inicio y avance poco a poco en cada una de sus partes.',
							'En distintas partes y luego las ordene para darles sentido.',
							32);
						$fg->generateTwoOptions(
							'33. Cuando tengo que trabajar en un proyecto grupalmente o debo trabajar en equipo, primero quiero:',
							'Realizar una "Lluvia de ideas" donde cada integrante aporta o ayuda con su opinión.',
							'Realizar una "Lluvia de ideas" de manera individual y luego revisarlas y llegar a un acuerdo.',
							33);
					?>
				</div>
				<div class="tab-pane " id="t4" role="tabpanel" >
					<?php
						$fg->generateTwoOptions(
							'34. Considero que es más agradable ser:' , 
							'Una persona sensible.', 
							'Una persona innovadora.', 
							34);
						$fg->generateTwoOptions(
							'35. Cuando conozco gente en una fiesta, es probable que recuerde:',
							'Cómo es su apariencia.',
							'Lo que dicen de sí mismos.',
							35);
						$fg->generateTwoOptions(
							'36. Cuando estoy aprendiendo un tema, prefiero:',
							'Mantenerme concentrado en ese tema, aprendiendo lo más que se pueda de él.',
							'Hacer conexiones entre ese tema y otros temas relacionados.',
							36);
						$fg->generateTwoOptions(
							'37. Me considero un ser:',
							'Abierto con mis ideas y sentimientos.',
							'Reservado con mis ideas y sentimientos.',
							37);
						$fg->generateTwoOptions(
							'38. Prefiero cursos, materias o áreas que dan más importancia a:',
							'Material y contenido concreto (hechos, datos).',
							'Material y contenido abstracto (conceptos, teoría).',
							38);
						$fg->generateTwoOptions(
							'39. Para divertirme prefiero:', 
							'Ver televisión, ver videos o series.', 
							'Leer un libro, textos, historietas, comics u otro tipo de contenido escrito o con imágenes plasmadas.', 
							39);
						$fg->generateTwoOptions(
							'40. Algunos profesores inician sus clases haciendo una lista, bosquejos o esquemas de lo que enseñan, este contenido para ti puede ser:',
							'Algo útiles.',
							'Algo muy útiles.',
							40);
						$fg->generateTwoOptions(
							'41. La idea de hacer un trabajo grupal con una sola calificación para todos:',
							'Me parece justa.',
							'Me parece injusta.',
							41);
						$fg->generateTwoOptions(
							'42. Cuando realizo cálculos grandes, trabajos complejos o largos:',
							'Tiendo a repetir todos mis pasos para revisar cuidadosamente mi trabajo.',
							'Me cansa hacer su revisión y tengo que esforzarme para hacerlo.',
							42);
						$fg->generateTwoOptions(
							'43. Tiendo a recordar lugares en los que he estado:',
							'Con facilidad y con bastante exactitud.',
							'Con dificultad y sin mucho detalle.',
							43);
						$fg->generateTwoOptions(
							'44. Cuando resuelvo problemas en grupo, es más probable que yo:', 
							'Piense en los pasos para la solución de los problemas.', 
							'Piense en las posibles consecuencias o aplicaciones de la solución general para el problema y como aplicarlo en otras ocasiones.', 
							44);
					?>

					<button type="submit" class="btn btn-primary" >Enviar respuestas</a>
				</div>
			</div>

			<br>
			<ul class="nav nav-pills" id="myTab" role="tablist">
				<li class="nav-item">
					<a class="nav-link active"  data-toggle="tab" href="#t1" aria-selected="true" onclick="goTop()">1</a>
				</li>
				<li class="nav-item">
					<a class="nav-link"  data-toggle="tab" href="#t2" aria-selected="false" onclick="goTop()">2</a>
				</li>
				<li class="nav-item">
					<a class="nav-link"  data-toggle="tab" href="#t3" aria-selected="false" onclick="goTop()">3</a>
				</li>
				<li class="nav-item">
					<a class="nav-link"  data-toggle="tab" href="#t4" aria-selected="false" onclick="goTop()">4</a>
				</li>
			</ul>
		
			
			
		</div>

	</form>


</div>

<script>

	function goTop(){
	window.scroll(0, 0);
	}
</script>
<?php }?>


