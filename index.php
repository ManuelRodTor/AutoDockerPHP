<?php
    session_start();
	$file='/var/www/datos.csv';
	$actual = file_get_contents($file);
	$contador=1;
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Wordpress Projetc</title>
        <link rel="shortcut icon" href="ficheros/wp.png" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <style>
                .bottomleft {
                position: absolute;
                bottom: 8px;
                left: 16px;
                font-size: 18px;
                }
                .bottomright {
                position: absolute;
                bottom: 8px;
                right: 16px;
                font-size: 18px;
                }
        </style>
            <script>
            $(document).ready(function(){
                $("#metricas").load("metricas.php");
                setInterval(function() {
                    $("#metricas").load("metricas.php");
                }, 20000);
            });
        </script>
        <script>
            $(document).ready(function(){
                $("#texto").load("texto_precargado.php");
                setInterval(function() {
                    $("#texto").load("texto_precargado.php");
                }, 1000);
            });
        </script>
            <script>
            $(document).ready(function(){
                $("#texto_actual").load("texto.php");
                setInterval(function() {
                $("#texto_actual").load("texto.php");
                }, 100);
            });
        </script>
    </head>
    <body>
        <div class="jumbotron text-center">
            <h3>Wordpress Project</h3>
        </div>
        <div class="container">
            <div class="row"> 
            <!-- Barra de navegación -->
                <div class="col-sm-2">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Listado Dockers</a>
                        <a class="nav-link" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Subir CSV</a>
                        <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Acerca del proyecto</a>
                        <a class="nav-link" href="http://85.136.119.23/script.php">Levantar Wordpress</a>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                        <div id="metricas"></div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
<!-- Código subida CSV // Posibilidad 1

                    <form method="POST" action="upload.php" enctype="multipart/form-data">
                        <div>
                        <span>Subir archivo CSV:</span>
                        <input type="file" name="uploadedFile" />
                        </div>
                    
                        <input type="submit" name="uploadBtn" value="Subir" />
                    </form>

     FIN CSV -->

<!-- Código de subida CSV // Posibilidad 2 -->
                        <h4>Contenido del fichero precargado:</h4>
                        <hr>
                        <!-- TEXTO PRECARGADO -->
                        <div id='texto' class='container p-3 my-3 border'></div>
                        <script>
                            function accion()
                            {
                                $.ajax({
                                    type:'POST', //aqui puede ser igual get
                                    url: 'move_data.php',//aqui va tu direccion donde esta tu funcion php
                                    success:function(data){
                                        //lo que devuelve tu archivo mifuncion.php
                                },
                                error:function(data){
                                    //lo que devuelve si falla tu archivo mifuncion.php
                                }
                                });
                            }
                        </script>

                        <input type="submit" name="" value="Cargar texto" id="boton1" onclick = "accion();" class="btn btn-secondary" >


                        <hr>
                        <form enctype="multipart/form-data" action="upload.php" method="POST">
                            <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
                            <input name="fichero_usuario" type="file" />
                            <input type="submit" value="Enviar fichero" />
                        </form>


    <!-- FIN -->
                        <hr>
                            <h4>Contenido del fichero precargado:</h4>
                        <hr>
                        <!-- TEXTO Actual -->
                        <div id='texto_actual' class='container p-3 my-3 border' background-color: lightblue></div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                        <p>Wordpress Project intenta dar entornos de desarrollo o de prueba para uso escolar o laboral.</p>
                        <p>Mediante <b>"Levantar Wordpress"</b> podremos tener multitud de containers con Docker levantado en cuestión de minutos.</p>
                        <p>En la sección <b>"Listado Dockers"</b> podremos ver los dockers creados, ademas de algunos datos:</p>
                        <ul>
                            <li>Nombre</li>
                            <li>Puertos</li>
                            <li>Enlace</li>
                            <li>Estado</li>
                        </ul>
                        <p>En la sección <b>"Subir CSV"</b> el usuario podrá cargar un fichero.csv desde su ordenador, pudiendo obviar la sección de introducción de datos.</p>
                        <p><b>"Levantar Wordpress"</b> nos redirigirá a la página desde la que podremos introducir datos al CSV o hacer uso de los cargados en la sección <b>"Subir CSV"</b>.</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- DIVISOR BAJO -->
        <?php
            if (isset($_SESSION['message']) && $_SESSION['message']){
                if($_SESSION['message'] == 'SI'){
                    ?>
                    <div class="bottomright">
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        Fichero precargado correctamente
                    </div>
                </div>
                <?php
                }
                elseif($_SESSION['message'] == 'NO'){
                    ?>
                    <div class="bottomright">
                    <div class="alert alert-warning alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        El fichero no se ha podido subir correctamente.
                    </div>
                </div>
                <?php
                }
                unset($_SESSION['message']);
            }

        ?>


    </body>
</html>