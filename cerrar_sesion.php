<?php
    session_start();
	unset($_SESSION["login"]);
	if (isset($_SESSION["login"])) {
		header("Location: index.php");
		header("Refresh:0");
	}
?>