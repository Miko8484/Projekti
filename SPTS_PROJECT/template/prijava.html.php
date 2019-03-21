<?php ob_start()?>

<h1>Prijava</h1>

<div style="float:left;width:40%;">
Še nimate računa? Registrirajte se  <a href="index.php?stran=registracija">tukaj</a>
</div>

<div style="float:left;border-left:2px solid #D8D8D8;padding-left:50px;width:50%;">
	<?php echo prijava();?>
	<form method='post' name="form" action="#">
	  
	  <input type="text" name="upoime" class="register" placeholder="Uporabniško ime"  required /><br/><br/>
	  <input type="password" name="geslo" class="register" placeholder="Geslo" required /><br/><br/>
	  <input type="checkbox" name="zapomni" value="zapomni">Zapomni si me <br/><br/>
	  <a class="linki" href="index.php?stran=pozabljenoGeslo" name="pozabljenoGeslo">Pozabil geslo</a><br/><br/>
	  
	  <input type="submit" name="gumb" value="Prijavi se" id="submit" /> 
	  
	</form>
	
	
</div>

<?php

$title = "Prijava";
$content=ob_get_clean();

require "layout.html.php";


?>

 

