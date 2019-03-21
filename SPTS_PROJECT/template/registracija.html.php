<?php ob_start()?>

<h1>Registracija</h1>

<div style="float:left;width:40%;">
Že imate račun? Prijavite se <a href="index.php?stran=prijava">tukaj</a>
</div>

<div style="float:left;border-left:2px solid #D8D8D8;padding-left:50px;width:50%;">
	<?php echo registracija();?>
	<form method='post' name="form" action="#">
	  
	  <input type="text" name="upoime" class="register" placeholder="Uporabniško ime"  required /><br/><br/>
	  <input type="email" name="email" class="register" placeholder="E-mail" required ><br/><br/>
	  <input type="password" name="geslo" class="register" placeholder="Geslo" required /><br/><br/>
	  <input type="password" name="geslo2" class="register" placeholder="Ponovite geslo" required /><br/><br/>
	  
	  <script type="text/javascript">
			 var RecaptchaOptions = {
				theme : 'blackglass',
				custom_translations : {
							instructions_visual : "Vnesite znake z slike",
					},
			 };
		 </script>
		<?php
			  require_once('recaptchalib.php');
			  $publickey = "6LeQ1O8SAAAAAEHCMjaDF-oV5QruMEg9EQr-0IIc";
			  echo recaptcha_get_html($publickey);
		?>
		<br/>
	  <input type="submit" name="gumb" value="Ustvari račun" id="submit" /> 
	  
	</form>
	
	
</div>

<?php


$title = "Registracija";
$content=ob_get_clean();

require "layout.html.php";


?>

 

