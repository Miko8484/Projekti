<?php ob_start()?>
<h1>Ustvari turnir</h1>

<?php
if (isset($_SESSION['notice']))
{ 
	echo "<div class=\"succes\">";
	echo $_SESSION['notice'];
	echo "</div>" ;
	echo "<br/>";
	unset($_SESSION['notice']);
}
else if (isset($_SESSION['notice_error']))
{
	echo "<div class=\"error\">";
	echo $_SESSION['notice_error'];
	echo "</div>" ;
	echo "<br/>";
	unset($_SESSION['notice_error']);
} 
?>

<h2>1. Ime turnira</h2>
<form method='post' name="form" action="#">
	 <input type="text" name="imeTurnira" class="register" style="width:35%" placeholder="Ime turnira"  required />

<h2>2. Izberi datum in čas ter kraj turnira</h2>
<input class="register" name="datumcas" style="width:35%" id="datetimepicker" type="text" placeholder="Datum in čas" ><br/>
<input class="register" name="kraj" style="width:35%" type="text" placeholder="Kraj" >
<script>
	jQuery('#datetimepicker').datetimepicker({
	 lang:'en',
	 i18n:{
	  en:{
	   months:[
		'Januar','Februar','Marc','April',
		'Maj','Juni','Juli','Avgust',
		'September','Oktober','November','December',
	   ],
	   dayOfWeek:[
		"Pon", "Tor", "Sre", "Čet", 
		"Pet", "Sob", "Ned",
	   ]
	  }
	 },
	 format:'d-m-Y H:i'
	});
	
	
	</script>

<h2>3. Izberi klube</h2>
<?php echo addKlub(); ?>

<div class="klubi">
	<?php echo izpisKlubov(); ?>
		<div class="klubi_footer">
			<input type="submit" name="gumb0" value="Izberi klube" id="submit" />
			<input type="submit" name="gumb1" onclick="return confirm('Ste prepričani, da želite izbrisati izbrane klube ?')" value="Izbriši klube" id="submit" /> 
		</div>
	</form>
</div>

<?php

$title = "Ustvari turnir";
$content=ob_get_clean();
?>

<?php ob_start()?>
<div class="odzadje"></div>

<div class="addwrapper">
	<div class="popup">
		<div class="exit"> <div class="exit2">x</div> </div>
		<h1>Dodaj klub</h1>
		<?php echo addForm(); ?>
	</div>
</div>


<?php
$content2=ob_get_clean();
require "layout.html.php";


?>

 
