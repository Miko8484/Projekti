<?php ob_start()?>

<h1>Brisanje turnirov</h1>

<?php

$id=$_GET['id'];

echo brisiTurnir($id);

?>

<?php

$title = "Brisanje";
$content=ob_get_clean();

require "layout.html.php";


?>

 

