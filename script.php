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
	<script src="https://code.jquery.com/jquery-latest.js"></script>
	<script>
		$(document).ready(function(){
			$("#div_refresh").load("texto.php");
			setInterval(function() {
				$("#div_refresh").load("texto.php");
			}, 1000);
		});
	</script>
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
</div>
  </body>
</html>
<?php
	$file='datos.csv';
	$actual = file_get_contents($file);
if ( $_POST["borrar_csv"] == "borrar_csv"){
	shell_exec (' > datos.csv');
}
if ( $_POST["no_mas"] == "no_datos" ) {
	?>
<div class="container-fluid"> 
	<div class="row">
		<!-- IZQUIERDA-SUP -->
		<div class="col-sm-6" >
		<h5>¿Comenzamos con la instalación?</h5>
		<a href="/install.php" class="btn btn-success">SI </a>
		<a href="/script.php"   class="btn btn-danger">NO</a>
		</div>
		<br>

		<!-- DERECHA-SUP -->
		<div class="col-sm-4" >
			<h5><b>Datos en el CSV:</b></h5>
			<div id="div_refresh"></div>
		</div>
	</div>
</div>
	<?php
}
else{
?>
	<div class="container-fluid"> 
	<div class="row">
	<!-- IZQUIERDA-SUP -->
		<div class="col-sm-6">
		<h5><b>Introduzca los datos de cada Wordpress:</b></h5> 
		<br>
		<form action="/script.php" method="post">

			<div class="form-group">
				<label for="database">Nombre de la DB:</label>
				<input type="text" class="form-control" id="database" name="database">
			</div>
			<div class="form-group">
			<label for="container">Nombre del Wordpress:</label>
			<input type="text" class="form-control" id="container" name="container">
			</div>

			<div class="form-group">
			<label for="user">Nombre de usuario:</label>
			<input type="text" class="form-control" id="user" name="user">
			</div>

			<div class="form-group">
			<label for="pass">Contraseña:</label>
			<input type="password" class="form-control" id="pass" name="pass">
			</div>

			<div class="form-group">
			<label for="mail">Correo:</label>
			<input type="text" class="form-control" id="mail" name="mail">
			</div>

			<input type="hidden" value="hay_datos" id="check" name="check">

			<input type="submit" value="Añadir Wordpress" class="btn btn-info">

		</form>
		<br>
			<div class="btn-group btn-group-lg">

				<form action="/script.php" method="post">
					<input type="hidden" value="no_datos" id="no_mas" name="no_mas">
					<button class="btn btn-info btn-primary">Finalizar introducci&oacute;n</button>
				</form>
				<br>
				<form action="/script.php" method="post">
					<input type="hidden" value="borrar_csv" id="borrar_csv" name="borrar_csv">
					<button class="btn btn-info btn-primary">Borrar datos CSV</button>
				</form>
			</div>
			<a class="btn btn-info" href="http://85.136.104.237/index.php">Index</a>
		</div>
		<br>
		<br>
		<!-- DERECHA-SUP -->
		<div class="col-sm-4" >
			<h5><b>Datos en el CSV:</b></h5>
			<div id="div_refresh"></div>
		</div>
	</div>
</div>

<?php
	if ($_POST["check"] == "hay_datos"){
		$actual .= $_POST["database"].";".$_POST["container"].";".$_POST["user"].";".$_POST["pass"].";".$_POST["mail"]."\n";
		if ($_POST["database"] == "" || $_POST["container"] == "" || $_POST["user"] == "" || $_POST["mail"] == "") {
		?>
		<!-- DIVISOR BAJO -->
			<div class="bottomright">
				<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>¡Cuidado!</strong> Ha dejado un campo sin introducir.
				</div>
			</div>
		<?php
		}
		else{
		     file_put_contents($file, $actual);
		}
	}
}
echo "<br>";
?>
