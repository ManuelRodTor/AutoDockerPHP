<?php
	$file='/var/www/uploaded_files/datos.csv';
    $actual = file_get_contents($file);
    $actual_a=rtrim($actual);
	$actual_b=explode("\n",$actual_a);
	foreach($actual_b as $linea){
		if ($linea !== ""){
			?><li type="circle"><?php echo $linea ?></li><?php
		}
	}
?>