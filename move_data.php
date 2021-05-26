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

		shell_exec("cp /var/www/uploaded_files/datos.csv /var/www/datos.csv ");
?>