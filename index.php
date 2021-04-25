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
    <div class="container">
      <br>
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
	<!-- IZQUIERDA-SUP -->

	<div class="topleft">
	  <p>¿Comenzamos con la instalación?</p>
	  <a href="/install.php" class="btn btn-success">SI</a>
	  <a href="/script.php" class="btn btn-success">NO</a>
	</div>

	<!-- DERECHA-SUP -->
	<div class="topright">
	  <p><b>Datos en el CSV:</b></p>
	  <?php
		$actual_a=rtrim($actual);
		$actual_b=explode("\n",$actual_a);
		foreach($actual_b as $linea){
			if ($linea !== ""){
			?><li type="circle"><?php echo $linea ?></li><?php
			}
		}
	  ?>
	</div>
	<?php
}
else{
?>
<!-- IZQUIERDA-SUP -->

	<div class="topleft">
	  <p><b>Introduzca los datos de cada Wordpress:</b></p>
	  <br>
	  <form action="/script.php" method="post">

	    &nbsp;&nbsp;&nbsp;&nbsp
	    <label for="database">Nombre de la DB:</label>
	    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp
	    <input type="text" id="database" name="database"><br><br>

	    &nbsp;&nbsp;&nbsp;&nbsp
	    <label for="container">Nombre del Wordpress:</label>
	    <input type="text" id="container" name="container"><br><br>

	    &nbsp;&nbsp;&nbsp;&nbsp
	    <label for="user">Nombre de usuario:</label>
	    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	    <input type="text" id="user" name="user"><br><br>

	    &nbsp;&nbsp;&nbsp;&nbsp
	    <label for="pass">Contraseña:</label>
	    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	    <input type="text" id="pass" name="pass"><br><br>

	    &nbsp;&nbsp;&nbsp;&nbsp
	    <label for="mail">Correo:</label>
	    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	    <input type="text" id="mail" name="mail"><br><br>

	    <input type="hidden" value="hay_datos" id="check" name="check">

	    <input type="submit" value="Añadir Wordpress" class="btn btn-success">

	  </form>
	  <br>
	  <form action="/script.php" method="post">
	     <input type="hidden" value="no_datos" id="no_mas" name="no_mas">
	     <button class="btn btn-success">Finalizar introducci&oacute;n</button>
	     <input type="hidden" value="borrar_csv" id="borrar_csv" name="borrar_csv">
	     <button class="btn btn-success">Borrar datos CSV</button>
	  </form>
	</div>

	<!-- DERECHA-SUP -->
	<div class="topright">
	  <p><b>Datos en el CSV:</b></p>
	  <?php
		$actual_a=rtrim($actual);
		$actual_b=explode("\n",$actual_a);
		foreach($actual_b as $linea){
			if ($linea !== ""){
			?><li type="circle"><?php echo $linea ?></li><?php
			}
		}
	  ?>
	</div>


<?php
	if ($_POST["check"] == "hay_datos"){
		$actual .= $_POST["database"].";".$_POST["container"].";".$_POST["user"].";".$_POST["pass"].";".$_POST["mail"]."\n";
		if ($_POST["database"] == "" || $_POST["container"] == "" || $_POST["user"] == "" || $_POST["mail"] == "") {
		?>
		<!-- DIVISOR BAJO -->
			<div class="bottomleft">
			   <p style="border-left: 6px solid red;background-color:lightgrey;">Error, faltan campos</p>
			</div>
		<?php
		}
		else{
		     file_put_contents($file, $actual);
		}
	}
}
?>
