<?php ob_start()?>

<h1>Pozabljeno geslo</h1>
Vnesite e-mail, da vam pošljemo povezavo za spremembo gesla.<br/><br/> 
<form method='post' name="form" action="#">
	<input type="email" class="register" style="width:35%;" name="email" placeholder="E-mail"><br/><br/>
	
	<input type="submit" name="gumb" value="Potrdi" id="submit" /> 
	<br/><br/>
</form>
<?php echo pozabljenoGeslo(); ?>

<?php

$title = "Pozabljeno geslo";
$content=ob_get_clean();

require "layout.html.php";


?>

 



 

