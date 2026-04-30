
<div class="page-header-content fontawesome-i2svg-active fontawesome-i2svg-complete">
    <div class="container">
    <!-- tabs -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">INICIO</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">ESTILOS DE APRENDIZAJE</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">PERFILES DE JUGADORES</a>
            </li>
        </ul>
    <!-- contenido -->
        <div class="tab-content" id="myTabContent">
            <!-- HOME -->
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="row align-items-center mt-2">
                    <div class="col-lg-6 ">
                        <br>
                        <h1 class="page-header-title" >¡BIENVENIDOS!</h1>
                        <p class=" page-header-text" style="text-align: justify;">

                            Esta aplicación tiene como objetivo en primer lugar, aplicar el test del modelo de estilos de aprendizaje propuesto por Felder y Silverman, el cual consta de 4 dimensiones de aprendizaje, cada una compuesta por dos estilos de aprendizaje. Este test permite identificar la preferencia del estudiante hacia un estilo de aprendizaje de cada dimensión; Esta preferencia puede ser fuerte, media o equilibrada.		
                            <br>
                            En segundo lugar, aplicar el test del modelo de “Tipos de usuarios Hexad”, propuesto por Andrzej Marczewski en ambientes de gamificación. El cual consta de una clasificación de 6 tipos de usuarios. Con este test se busca clasificar a cada tipo de usuario y su motivación para jugar y divertirse.
                            <br>
                            Bienvenido(a) y gracias por su aporte
                        </p>
                    </div>

                    <div class="col-lg-6 d-none d-lg-block aos-init " data-aos="fade-up" data-aos-delay="500">
                        <img src="models/img/welcome.png"  height="400" width="533">
                    </div> 
					<!-- <footer class="blockquote-footer">
						<p>
							Por favor diligencie el siguiente formulario. La información recopilada a través de este formulario está enmarcada dentro del desarrollo de una propuesta de investigación doctoral de un estudiante de doctorado en Ingeniería de la Universidad del Valle y será utilizada únicamente con fines académicos.
						</p>
						<p>
						En caso de tener alguna duda o solicitud puntual por favor enviar un correo a: <a href="mailto:yuri.bermudez@correounivalle.edu.co">yuri.bermudez@correounivalle.edu.co </a>  
						</p>
					</footer>  -->
                </div>
            </div>

            <!-- Learn styles -->
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="row align-items-center mt-2">
                    <div class="card-body">
                        <h1>Estilos de aprendizaje</h1>
                        <div class="card-body">

                            <blockquote class="blockquote">
                                <p class="mb-0 ">
								El modelo de Felder-Silverman clasifica preferencias de aprendizaje de los estudiantes en una de las categorías en cada una de las siguientes cuatro dimensiones de estilo de aprendizaje: sensitivo o intuitivo, visual o verbal, activo o reflexivo, secuencial o global. Tal como se muestra a continuación:
                                </p>
                                <!--<footer class="blockquote-footer">
                                    <cite >
                                        Keefe, J. W. (1987). Learning Style Theory and Practice. National Association of Secondary School Principals, 1904 Association Dr., Reston, VA 22091.
                                    </cite>
                                </footer>-->
                            </blockquote>                            
                        </div>

                        <!-- styles -->

                        <div class=" col-xl-12 mb-4">  
                            <div class="card lift h-100">
                                <div class="card-header">
                                    <h5>Dimensión percepción</h5>
                                    <h6>¿Qué tipo de información perciben preferentemente los estudiantes? </h6>
                                </div>
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <dl>
                                        <dt>Sensitivo</dt>
                                        <dd> No les gusta aprender cosas que no vean útiles para el mundo real, son bastante prácticos. Están orientados hacia hechos, siempre desde una perspectiva concreta y dotados de una memoria ágil. Prestan atención a los detalles. </dd>

                                        <dt>Intuitivo</dt>
                                        <dd> Conceptuales por naturaleza. Comprenden rápidamente nuevos conceptos y trabajan bien con abstracciones y formulaciones matemáticas. Les gusta innovar y no les gusta la monotonía, la repetición y la memorización excesiva. </dd>
                                    </dl>  
                                </div>
                            </div>
                        </div>
                       
                        <div class=" col-xl-12 mb-4">  
                            <div class="card lift h-100">
                                <div class="card-header">
                                    <h5>Dimensión entrada </h5>
                                    <h6>¿A través de qué canal sensorial se percibe de forma más eficiente la información?</h6>
                                </div>
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <dl>
                                        <dt>Visual</dt>
                                        <dd>  En la obtención de información prefieren representaciones visuales como mapas conceptuales, diagramas de flujo, diagramas, etcétera.</dd>

                                        <dt>Verbal</dt>
                                        <dd> Prefieren obtener la información a través del uso del lenguaje ya sea en forma escrita o hablada.</dd>
                                    </dl>  
                                </div>
                            </div>
                        </div>

                        <div class=" col-xl-12 mb-4">  
                            <div class="card lift h-100">
                                <div class="card-header">
                                    <h5>Dimensión procesamiento</h5>
                                    <h6>¿Cómo prefiere procesar la información el estudiante?</h6>
                                </div>
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <dl>
                                        <dt>Activo</dt>
                                        <dd>Necesitan hacer algo activo con la información para comprenderla y retenerla mejor. Comprenden mejor la nueva información cuando la discuten y la aplican.  Les gusta trabajar en grupo y relacionarse. </dd>

                                        <dt>Reflexivo</dt>
                                        <dd> Este tipo de alumnos prefiere retener y comprender la nueva información pensando y reflexionando sobre ella de una manera individual. Son sujetos racionales, lógicos y ordenados.</dd>
                                    </dl>  
                                </div>
                            </div>
                        </div>

                        <div class=" col-xl-12 mb-4">  
                            <div class="card lift h-100">
                                <div class="card-header">
                                    <h5>Dimensión comprensión</h5>
                                    <h6>¿Cómo progresa el estudiante en su aprendizaje?</h6>
                                </div>
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <dl>
                                        <dt>Secuencial</dt>
                                        <dd> Son lineales y resuelven las cosas paso a paso mediante pasos lógicos, por eso aprenden en base a pequeños pasos incrementales siempre y cuando estén relacionados. Aprende mejor cuando hay un progreso en la dificultad  y la complejidad de la tarea.</dd>

                                        <dt>Global</dt>
                                        <dd> Pueden entender algo concreto, visualizando la totalidad. Aprenden sin seguir un orden, pero entienden fácilmente el sentido global de un conjunto de información, resuelven problemas en forma novedosa Pueden resolver problemas complejos rápidamente gracias a esta capacidad, pero pueden tener dificultad en explicar paso a paso cómo lo consiguieron.</dd>
                                    </dl>  
                                </div>
                            </div>
                        </div>

                        <!-- <div class=" col-xl-12 mb-4">  
                            <div class="card lift h-100">
                                <div class="card-header">
                                    <h5>¿Cómo prefiere el estudiante procesar la información?</h5>
                                </div>
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <dl>
                                        <dt>Inductivo</dt>
                                        <dd>  Como su propio nombre indican procesan la información por inducción. Entienden mejor si se les presentan hechos y observaciones para luego inducir los principios o generalizaciones.</dd>

                                        <dt>Deductivo</dt>
                                        <dd> Es el caso opuesto. Prefieren deducir por ellos mismos las consecuencias y aplicaciones a partir de los principios o generalizaciones.</dd>
                                    </dl>  
                                </div>
                            </div>
                        </div> -->

                    </div>
                </div>
            </div>


            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <div class="row align-items-center mt-2">
                    <div class="card-body">
                        <h1>PERFILES DE JUGADORES</h1>
                        <h6> <i>Modelo propuesto por Andrzej Marczewski. </i></h6>
                        <div class="card-body">
                            <p class="blockquote text-justify">
								Marczewski propuso un modelo de seis tipos de usuario Hexad que se diferencian por el grado de motivación que pueden tener, ya sea intrínseca (por ejemplo, la autorrealización) o extrínseca (por ejemplo, las recompensas). A continuación se describe cada tipo de jugador indicando su motivación principal para jugar y divertirse: 
                            </p>
                        </div>                       

                       
                        <div class="card-columns">
                             <!-- player -->
                            <div class="card">
                                <img class="card-img-top" src="models/img/Ultimate/reward.jpg" alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title">Jugador (Player):</h5>
                                    <p class="card-text text-muted">
										Motivados por Recompensas extrínsecas
                                       <br>
                                        No les interesa el objetivo o tarea a cumplir sino obtener recompensas por ello. Les agrada mucho conseguir diferentes logros y que se les reconozca estos mismos abiertamente y por medio de diferentes recompensas.

                                    </p>
                                </div>
                            </div>

                            <!-- Socializadores (socialisers): -->
                            <div class="card">
                                <img class="card-img-top" src="models/img/Ultimate/social.jpg" alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title">Socializador  (Socialiser):</h5>
                                    <p class="card-text text-muted">
                                        Motivados por la interacción social
										<br>
										Les gusta interactuar con otros y crear conexiones sociales. Se motivan por el carisma y la capacidad de generar una conexión hacia los demás.

                                    </p>
                                </div>
                            </div>

                             <!-- Filántropos (philanthropists): -->
                            <div class="card">
                                <img class="card-img-top" src="models/img/Ultimate/filantropo.jpg" alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title">Filántropo  (philanthropist):</h5>
                                    <p class="card-text text-muted">
                                        Motivados por el propósito
                                        <br>
                                        Necesitan un propósito y significado en lo que hacen,
										Están dispuestos a dar sin esperar una recompensa por ayudar
										Su motivación principal es ayudar al otro o a una causa. 
										Les gusta ser parte de algo y compartir su conocimiento

                                    </p>
                                </div>
                            </div>

                             <!-- Espíritus Libre (free spirit): -->
                            <div class="card">
                                <img class="card-img-top" src="models/img/Ultimate/explore.jpg" alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title">Espíritus Libre (free spirit):</h5>
                                    <p class="card-text text-muted">
                                        Motivados por la autonomía y autoexpresión.
                                        <br>
                                        Quieren libertad para expresarse y actuar sin control externo. Les gusta crear y explorar dentro de un sistema. Por esta razón les agrada la falta de limitaciones en la creatividad que pueden emplear para llevar a cabo una tarea.

                                    </p>
                                </div>
                            </div>
                             <!-- Disruptores (disruptive): les -->
                            <div class="card">
                                <img class="card-img-top" src="models/img/Ultimate/disruption.jpg" alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title">Disruptores (Disruptor): </h5>
                                    <p class="card-text text-muted">
                                         Motivados por El Cambio
                                        <br>
                                       Tienden a perturbar el sistema ya sea directamente o a través de otros para forzar cambios negativos o positivos. 
										Les gusta probar los límites del sistema y tratar de ir más allá. 
										Pueden mejorar el sistema a  través de la búsqueda de fallos.
										Por lo general son buenos encontrando ineficiencias en las actividades y acciones para completar las tareas sin necesidad de emplear el esfuerzo que se espera para su logro.

                                    </p>
                                </div>
                            </div>
                             <!-- Triunfadores (disruptive): les -->
                             <div class="card">
                                <img class="card-img-top" src="models/img/Ultimate/master.jpg" alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title">Triunfadores (archivers): </h5>
                                    <p class="card-text text-muted">
                                        Motivados por la competencia y la maestría.
                                        <br>
                                        Tratan de progresar dentro del sistema completando tareas Se prueban a sí mismos asumiendo retos difíciles. Buscan el máximo nivel de maestría en lo que hacen. Siempre buscan mejorar en lo que hacen. Son los mejores en alcanzar objetivos que requieren la adquisición de habilidades o alcanzar determinados logros.

                                    </p>
                                </div>
                            </div>


                        </div>
                        

                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
