<?php
session_start();
$message = ''; 

$dir_subida = '/var/www/uploaded_files/';
$fichero_subido = $dir_subida . basename($_FILES['fichero_usuario']['name']);

echo '<pre>';
if (move_uploaded_file($_FILES['fichero_usuario']['tmp_name'], $fichero_subido)) {
    $message = 'SI';
} else {
    $message = 'NO';
}

// print_r($_FILES);

$_SESSION['message'] = $message;
if (isset($_SESSION['message'])){
    header("Location: index.php");
}
?>