<?php
    $cuenta = 0;
//Configuración Global

	//Método conexión 2
        set_include_path('/var/www/phpseclib1.0.11');
        include("Net/SSH2.php");

        //Contraseña
        $key ="hypriot";

        //Config
        $ssh = new Net_SSH2('172.17.0.1', 22);
        $ssh->login('pirate', $key);

    //Texto
        $input_text="/var/www/ficheros/metric_input";

        shell_exec("> ".$input_text);

    //Ejecución
        $contenido=$ssh->exec("docker ps -a | sed 's/ \{1,\}/ /g'");
        file_put_contents($input_text, $contenido);


    //Seteamos la cabecera
    ?>
    <head>
        <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    </head>
    <form action="/procedimientos.php" method="post">
    <!-- Modelo 2 Script -->
    <script type="text/javascript">
        $('#select-all').click(function(event) {   
        if(this.checked) {
            // Iterate each checkbox
            $(':checkbox').each(function() {
                this.checked = true;                        
            });
        } else {
            $(':checkbox').each(function() {
                this.checked = false;                       
            });
        }
        });
    </script>
    <!-- FIN Modelo 2 Script -->
        <table class="table">
        <thead>
        <tr>
            <th><input type="checkbox" name="select-all" id="select-all" /></th>
            <th>Nombre</th>
            <th>Redirección de puertos</th>
            <th>Enlace</th>
            <th>Estado</th>
        </tr>
        </thead>
        <tbody>
        <?php
            $actual = file_get_contents($input_text);
            $actual_b=explode("\n",$actual);
            foreach($actual_b as $clave => $actual_linea){
                if (!$clave == "0"){
                    ?>
                    <tr>
                    <?php
                    $actual_linea = str_replace(' ', ';', $actual_linea);
                    $actual_linea=strrev ( $actual_linea );
                    $linea_docker_explode=explode(";",$actual_linea);
                    foreach($linea_docker_explode as $clave_2 => $nombre_cont_port){
                        if($clave_2 == "0"){
                            if(!$nombre_cont_port == ""){
                            //Devuelve una línea por cada uno de los containers activos
                            $nombre_cont_port=strrev ( $nombre_cont_port );	
                            $puertos_uso=$ssh->exec("docker port ".$nombre_cont_port);
                            $URL_web="";
                            if($nombre_cont_port == "wpsql" || $nombre_cont_port == "php" || $nombre_cont_port == "sqldb" ){
                                ?>
                                    <td> </td>
                                <?php
                            }
                            else{
                                $cuenta += 1;
                                ?>
                                    <td><input type="checkbox" id="select_container" name="select_container.<?php echo $cuenta?>" value="<?php echo $nombre_cont_port ?>"></td>
                                <?php
                            }
                            ?>
                                <td><?php echo $nombre_cont_port ?></td>
                                <td><?php echo $puertos_uso ?></td>
                            <?php
                            }
                        }
                    }
                    $actual_linea=strrev ( $actual_linea );
                    $campos_docker_ps=explode(";",$actual_linea);
                    foreach($campos_docker_ps as $clave_3 => $linea_campos_docker_ps){

                        if($clave_3 == 6){
                            if($linea_campos_docker_ps == "Up"){
                                $valor_estado='<span class="badge badge-success">Levantado</span>';
                                $check_status=0;
                                ?>
                                <td><?php echo $valor_estado ?></td>
                                <?php
                            }
                            elseif($linea_campos_docker_ps == "Exited"){
                                $valor_estado='<span class="badge badge-danger">Apagado</span>';
                                $check_status=0;
                                ?>
                                <td><?php echo $valor_estado ?></td>
                                <?php
                            }
                            else{
                                $check_status=1;
                            }

                        }
                        if($clave_3 == 7){
                            if ( $check_status=1){
                                if($linea_campos_docker_ps == "Up"){
                                    $valor_estado='<span class="badge badge-success">Levantado</span>';
                                    ?>
                                    <td><?php echo $valor_estado ?></td>
                                    <?php
                                }
                                elseif($linea_campos_docker_ps == "Exited"){
                                    $valor_estado='<span class="badge badge-danger">Apagado</span>';
                                    ?>
                                    <td><?php echo $valor_estado ?></td>
                                    <?php
                                }

                            }
                        }
                        if($clave_3 == 1){
                            if($linea_campos_docker_ps == "wordpress"){
                                $url_port=explode(":",$puertos_uso);
                                ?>
                                <td>"<a href="http://85.136.104.237:<?php echo $url_port[1] ?>">Enlace</a>"</td>
                                <?php
                            }
                            else{
                                ?>
                                <td>Sin Web</td>
                                <?php
                            }
                        }
                    }
                }
            }
        ?>
        </tbody>
        <br>


        <!-- Script 1 check all/none  [[ No funciona con el cambio en name]] -->
        <!--
        </script>
            <button type="button" class="btn btn-default" onclick="checkAll()">Select All</button>
            <button type="button" class="btn btn-default" onclick="uncheckAll()">Clear</button>
                <script type="text/javascript">
                // Select all check boxes : Setting the checked property to true in checkAll() function
                function checkAll(){
                    var items = document.getElementsByName('select_container');
                    for (var i = 0; i < items.length; i++) {
                        if (items[i].type == 'checkbox')
                            items[i].checked = true;
                    }
                }
            // Clear all check boxes : Setting the checked property to false in uncheckAll() function
                function uncheckAll(){
                    var items = document.getElementsByName('select_container');
                    for (var i = 0; i < items.length; i++) {
                        if (items[i].type == 'checkbox')
                            items[i].checked = false;
                    }
                }
        </script>
    -->


    <button type="submit" class="btn btn-secondary" >Borrar Wordpress</button>
    </form>