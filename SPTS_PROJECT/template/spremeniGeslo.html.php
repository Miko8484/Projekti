<?php ob_start()?>

<h1>Sprememba gesla</h1>

<form method='post' name="form" action="#">
	<input type="password" class="register" style="width:35%;" name="geslo1" class="text" placeholder="Novo geslo" required /> <br/><br/>
	<input type="password" class="register" style="width:35%;" name="geslo2" class="text" placeholder="Ponovi geslo" required /> <br/><br/>
	
	<input type="submit" name="gumb" value="Potrdi" id="submit" /> 
	<br/><br/>
</form>
<?php echo spremembaGesla(); ?>

<?php


$title = "Sprememba gesla";
$content=ob_get_clean();

require "layout.html.php";


?>

 

