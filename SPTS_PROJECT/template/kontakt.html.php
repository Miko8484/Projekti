<?php ob_start()?>
<h1>Kontakt</h1>

Pošljite vprašanja, mnenja...<br/><br/>
<form method='post' name="form" action="#">

  <input type="text" class="kontakt" name="ime" placeholder="Ime"/><br/><br/>
   
  <input type="email" class="kontakt" name="email"  placeholder="Email" /><br/><br/>
   
  <input type="text" class="kontakt" name="zadeva" placeholder="Zadeva" /><br/><br/>
	
  <textarea name="editor1" id="editor1" rows="10" cols="80" placeholder="Sporočilo"></textarea>

	<script>
	// Replace the <textarea id="editor1"> with a CKEditor
	// instance, using default configuration.
	CKEDITOR.replace( 'editor1', {
		uiColor : '#9AB8F3'
	});
	</script>

<br/>
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
	<input type="submit" name="gumb" value="Pošlji" id="submit" /> 
</form>

<?php echo contact(); ?>

<?php

$title = "Kontakt";
$content=ob_get_clean();

require "layout.html.php";


?>

 
