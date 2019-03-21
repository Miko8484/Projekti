<?php ob_start()?>
<h1>Uredi turnir</h1>

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

<?php 
	$id=$_GET['id'];
	$obj_super = new prejsnjaVsebina();
	$value = $obj_super->loadme($id);
	$orignal_ime=$value['ime'];
?> 

<h2>1. Ime turnira</h2>
<form method='post' name="form" action="#">
	 <input type="text" name="imeTurnira" class="register" style="width:35%" placeholder="Ime turnira" value="<?php echo $value['ime']; ?>"  required />

<h2>2. Izberi datum in čas ter kraj turnira</h2>
<input class="register" name="datumcas" style="width:35%" id="datetimepicker" type="text" placeholder="Datum in čas" value="<?php if($value['datum_cas']!="0000-00-01 00:00:00"){ echo $value['datum_cas'];} ?>"><br/>
<input class="register" name="kraj" style="width:35%" type="text" placeholder="Kraj" value="<?php echo $value['kraj']; ?>" >
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
	<?php echo urediTurnir($id,$orignal_ime); ?>
		<div class="klubi_footer">
			<input type="submit" name="gumb0" value="Spremeni" id="submit" />
			<input type="submit" name="gumb1" onclick="return confirm('Ste prepričani, da želite izbrisati izbrane klube ?')" value="Izbriši klube" id="submit" /> 
		</div>
	</form>
</div>

<div class="odzadje"></div>

<!--
<div class="addwrapper">
	<div class="popup">
		<div class="exit"> <div class="exit2">x</div> </div>
		<h1>Dodaj klub</h1>
		<?php //echo addForm(); ?>
	</div>
</div>
-->
<div class="popup">
	<div class="exit"> <div class="exit2">x</div> </div>
		<h1>Dodaj klub</h1>
		<?php echo addForm(); ?>
</div>
<?php

$title = "Uredi turnir";
$content=ob_get_clean();

require "layout.html.php";

?>


 
