<?php ob_start()?>

<?php echo odjava(); ?>

<?php

$title="Logout";
$content=ob_get_clean();
require "layout.html.php";

?>
