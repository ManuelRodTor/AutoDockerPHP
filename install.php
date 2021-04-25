<?php
	$file='datos.csv';
	$actual = file_get_contents($file);
	include '/var/www/ficheros/pluggable.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Generador de Wordpress</title>
		<style>
			.topleft {
			  position: absolute;
			  top: 100px;
			  left: 16px;
			  font-size: 18px;
			}
			.topright {
			  position: absolute;
			  top: 100px;
			  right: 500px;
			  font-size: 18px;
			}
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
	</head>
	<body>
		<link rel="stylesheet" href="bootstrap.min.css" />
		<h1>&nbsp;Generador de Wordpress</h1>
		<hr>

	</body>
</html>

<?php
	//Generación de puertos libres
/*
			//Método conexión 1
	$connection = ssh2_connect('172.17.0.1', 22);
	ssh2_auth_password($connection, 'pirate', 'hypriot');
	$stream = ssh2_exec($connection, 'sudo docker ps -a');
	echo $stream;
*/
			//Método conexión 2
	set_include_path('/var/www/phpseclib1.0.11');
	include("Net/SSH2.php");
	//PASS
	$key ="hypriot";
	//String
	$ssh = new Net_SSH2('172.17.0.1', 22);   // Domain or IP
	$ssh->login('pirate', $key);
		
	$contenido=$ssh->exec("docker ps -a | sed 's/ \{1,\}/ /g'");
	$fichero_container="/var/www/ficheros/containers";
	shell_exec("> ".$fichero_container);

	$containers = fopen($fichero_container, "w+");
	file_put_contents($fichero_container, $contenido);
	
	fclose($containers);

	$containers_a=file_get_contents($fichero_container);
	$containers_b=rtrim($containers_a);
	$containers_c=explode("\n",$containers_b);

	$fichero_puertos="/var/www/ficheros/ports_used";
	shell_exec("> ".$fichero_puertos);
	//Para obtener los nombres de todos los containers
	foreach($containers_c as $clave => $linea_docker){
		if (!$clave == "0"){
			$linea_docker = str_replace(' ', ';', $linea_docker);
			$linea_docker=strrev ( $linea_docker );
			$linea_docker_explode=explode(";",$linea_docker);
			foreach($linea_docker_explode as $clave_2 => $nombre_cont_port){
			if($clave_2 == "0"){
				//Devuelve una línea por cada uno de los containers activos
				$nombre_cont_port=strrev ( $nombre_cont_port );		

				//Devuelve los puertos en uso
				$puertos_uso=$ssh->exec("docker port ". $nombre_cont_port);
				$actual = file_get_contents($fichero_puertos);
				$actual .= $ssh->exec("docker port ". $nombre_cont_port);
				file_put_contents($fichero_puertos, $actual);
				}
			}
		}
	}

	//Generamos listado con los puertos libres en el array $all_ports

	$all_ports=range(8080, 8201);
	$actual = file_get_contents($fichero_puertos);
	$array_fichero_puertos=explode("\n",$actual);

	foreach($array_fichero_puertos as $linea_puertos){
		$array_linea_puertos=explode(":",$linea_puertos);
		foreach($array_linea_puertos as $index => $puertos){
			if($index == "1"){
				foreach (array_keys($all_ports, $puertos) as $key) {
					unset($all_ports[$key]);
				}
			}
		}
	}

	//Preparación datos Docker
	sort($all_ports);
	$file='/var/www/datos.csv';
	$actual = file_get_contents($file);
	$actual_a=rtrim($actual);
	$actual_b=explode("\n",$actual_a);
	foreach($actual_b as $indexport => $linea){

		//Datos básicos introducidos por el usuario
		$dato=explode(";",$linea);
		$nombreDB=$dato[0];
		$nombreWP=$dato[1];
		$username=$dato[2];
		$pass=$dato[3];
		$mail=$dato[4];
		
		//Generación de datos restantes
		$port_wp= $all_ports[$indexport];
		$scheme="auth";
		$hash= wp_hash( $pass, $scheme );
		echo $hash;
	}

?>
