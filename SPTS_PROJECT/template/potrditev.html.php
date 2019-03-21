<?php ob_start()?>

<?php 
	if(!isset($_SESSION['uporabnik'])) 	
	{ 
		echo potrditev(); 
	}
?>

<?php

$title = "Aktivacija";
$content=ob_get_clean();

require "layout.html.php";


?>

 
