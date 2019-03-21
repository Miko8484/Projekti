<?php ob_start()?>

<?php 
	if(!isset($_SESSION['uporabnik'])) 	
	{ 
		echo resendEmail(); 
	}
?>

<?php

$title = "Ponovno pošiljanje emaila";
$content=ob_get_clean();

require "layout.html.php";


?>

 
