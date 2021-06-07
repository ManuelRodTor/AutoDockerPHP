<?php
    session_start();
	unset($_SESSION["error"]);
	unset($_SESSION["login"]);

	$dbhost = "192.168.0.22";
	$dbuser = "login_user";
	$dbpass = "login_pass";
	$dbname = "login";

	$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	if (!$conn) 
	{
		die("No hay conexión: ".mysqli_connect_error());
	}

	$nombre = $_POST["txtusuario"];
	$pass = $_POST["txtpassword"];

	$query = mysqli_query($conn,"SELECT * FROM login WHERE usuario = '".$nombre."' and password = '".$pass."'");
	$nr = mysqli_num_rows($query);

	if($nr == 1)
	{
		//header("Location: pagina.html")
		echo "Bienvenido:" .$nombre;
		$_SESSION["login"] = $nombre;
	}
	else if ($nr == 0) 
	{
		//header("Location: login.html");
		//echo "No ingreso"; 
		$_SESSION["error"] = $nombre;

	}
	if (isset($_SESSION["error"])) {
		header("Location: index.php");
	}

	if (isset($_SESSION["login"])) {
		header("Location: index.php");
	}
?>