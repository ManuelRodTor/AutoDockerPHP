<?php
	$file='/var/www/datos.csv';
	$actual = file_get_contents($file);
	$contador=1;
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Wordpress Projetc</title>
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
                }, 1000);
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
                        <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Inicio</a>
                        <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Listado Dockers</a>
                        <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Messages</a>
                        <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</a>
                    </div>
                </div>
                <div class="col-sm-8">
                        <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                            <p>Prueba de texto</p>
                        </div>
                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                            <div id="metricas"></div>
                        </div>
                        <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">ccc</div>
                        <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab"></div>
                </div>
            </div>
        </div>
        
    </body>
</html>