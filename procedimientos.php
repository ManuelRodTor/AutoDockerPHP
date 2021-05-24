<?php
//Configuración Global

	//Método conexión 2
        set_include_path('/var/www/phpseclib1.0.11');
        include("Net/SSH2.php");

        //Contraseña
        $key ="hypriot";

        //Config
        $ssh = new Net_SSH2('172.17.0.1', 22);
        $ssh->login('pirate', $key);

        $salida = array_slice($_POST, 0);

    foreach($salida as $linea){
        echo "<p>".$linea."</p>";
        $ssh->exec("docker stop ".$linea." ");
        $ssh->exec("docker rm ".$linea." ");	
        $ssh->exec("docker volume rm ".$linea." ");	
    }
    header("Location: http://85.136.119.23/index.php");
    exit();
?>
