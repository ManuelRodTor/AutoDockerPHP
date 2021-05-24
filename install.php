<?php
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
  </head>
  <body>
  	<div class="jumbotron text-center">
  		<h1>Wordpress Project</h1>
		<hr>
		<h5>Listado De Wordpress levantados</h5>
	</div>
	<div class="container-fluid"> 
		<div class="row">
			<?php
		//	echo "<h5>Listado De Wordpress levantados</h5>";
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
				$ssh = new Net_SSH2('172.17.0.1', 22);
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
					$url= $all_ports[$indexport];

					//Crear hash propio [[ DESARROLLO en Cli funcioan en web no]]


					//Hash temporal de contraseña.
					$hash="OERdEA9ZeE50cuz3j164kJQ3LUPto1";


					//Creación de la DB
					$mySQL = new mysqli('192.168.0.22:3306', 'root',  'RootPassXx-');

					$mySQL->query("CREATE DATABASE ".$nombreDB);
					$mySQL->query("GRANT ALL PRIVILEGES ON ".$nombreDB.".* TO 'wordpress'"); 
				
					mysqli_close($mySQL);

					//Creación del entorno Docker
					$ssh->exec("docker volume create ".$nombreWP);
					$ssh->exec("cp -r /var/lib/docker/volumes/wp1/_data /var/lib/docker/volumes/".$nombreWP."/_data");
					$ssh->exec("docker run -itd --restart always --name ".$nombreWP." -p ".$url.":80 -v ".$nombreWP.":/var/www/html -e WORDPRESS_DB_HOST=192.168.0.22:3306 -e WORDPRESS_DB_USER=wordpress -e WORDPRESS_DB_PASSWORD=MySQLPassPrueba -e WORDPRESS_DB_NAME=".$nombreDB." wordpress");	
					
					//	Transformación de la plantilla
					$ssh->exec("cat /home/pirate/template.sql | sed 's/datos_db/'".$nombreDB."'/g' | sed 's/URL_PAG/'".$url."'/g' | sed 's/blogname_data/'".$nombreWP."'/g' | sed 's/admin_email@wp.es/'".$mail."'/g' | sed 's/HashContrasena/'".$hash."'/g' | sed 's/user_login_data/'".$username."'/g' | sed 's/first_name_data/'".$username."'/g' > /home/pirate/sql_content.sql");
					$ssh->exec("mysql -u root -h 192.168.0.22 -pRootPassXx- < /home/pirate/sql_content.sql");
					
					//Generación de datos restantes
					$url= $all_ports[$indexport];
				//	$mySQL->close();
				//	mysqli_close($mySQL);

					// Generamos el Hash [[ ERROR a nivel Web // Buen funcionameinto PHP cli]]
			//		set_include_path('/var/www/phppass');
			//		include_once("PasswordHash.php");

			//		$t_hasher = new PasswordHash(8, FALSE);
			//		$hash = $t_hasher->HashPassword($pass);

					//	Insertamos el HASH

					$mySQL_DB = new mysqli("192.168.0.22:3306", "root",  "RootPassXx-", "$nombreDB");
					$mySQL_DB->query("UPDATE (wp_users) SET user_pass = MD5('$pass') WHERE ID = 1;");
				//	mysqli_close($mySQL_DB);
				//	$mySQL_DB->close();

					//	Registro de datos generados:

				$fichero_regis="/home/registro_containers_generados";
				/*
				$current = file_get_contents($fichero_regis);
				$current .= $nombreDB.";".$nombreWP.";".$username.";".$pass.";".$mail.";".$url."\n";
				file_put_contents($fichero_container, $current);
				*/
				?>
					<div class="col-sm-4">
				<?php
				echo "<p><b>&nbsp;&nbsp;&nbsp;&nbsp;Docker nº".$contador."</b></p>";
				echo "<p>&nbsp;&nbsp;&nbsp;&nbsp;Nombre del Container: ".$nombreDB."</p>";
				echo "<p>&nbsp;&nbsp;&nbsp;&nbsp;Nombre de la Base de Datos: ".$nombreWP."</p>";
				echo "<p>&nbsp;&nbsp;&nbsp;&nbsp;Nombre del usuario: ".$username."</p>";
				echo "<p>&nbsp;&nbsp;&nbsp;&nbsp;Contraseña del usuario: ".$pass."</p>";
				echo "<p>&nbsp;&nbsp;&nbsp;&nbsp;Correo del usuario: ".$mail."</p>";
				echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://85.136.119.23:'.$url.'">Enlace al sitio Web</a>';
				echo "<br>";
				echo "<br>";
				$contador++;
				?>
					</div>
				<?php
				}
			$fichero=file_get_contents($fichero_regis);
			$fichero_a=rtrim($fichero);
			$fichero_b=explode("\n",$fichero_a);
			foreach($fichero_b as $index_fichero => $linea_fichero){
				$linea_fichero_a=rtrim($linea_fichero);
				$linea_fichero_b=explode(";",$linea_fichero_a);
			}

			?>
		</div>
	</div>
	<div id="bottomleft">
		<a class="btn btn-info" href="http://85.136.119.23/index.php">Index</a>
	</div>
</body>
</html>
