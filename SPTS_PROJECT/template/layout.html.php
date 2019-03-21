<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="slog.css"/>
  <title><?php echo $title; ?></title>
  <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/themes/smoothness/jquery-ui.css" />
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" ></script> 
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.min.js"></script>
  <script src="jquery-1.8.2.js" type="text/javascript"></script>
  
  <script src="ckeditor/ckeditor.js"></script>
  
  <link rel="stylesheet" href="zoom/css/smoothzoom.css">
  
  <link rel="stylesheet" type="text/css" href="datetime/jquery.datetimepicker.css" />
  <script src="datetime/jquery.js"></script>
  <script src="datetime/jquery.datetimepicker.js"></script>

  <script>
	$(document).ready(function(){
		$( "#add" ).click(function() {
			alert("abc");
			$( ".addwrapper" ).show();
			$( ".odzadje" ).show();
			$( ".popup" ).show();
			$('div.container').addClass('blur');
			$('div.container2').addClass('blur');
			$('html, body').css({
				'overflow': 'hidden',
				'height': '100%'
			})
		});
		$( ".exit2" ).click(function() {
			$( ".addwrapper" ).hide();
			$( ".odzadje" ).hide();
			$( ".popup" ).hide();
			$('div.container').removeClass('blur');
			$('div.container2').removeClass('blur');
			$('html, body').css({
				'overflow': 'auto',
				'height': 'auto'
			})
		});
		
		$("li:gt(0) .vsebina").hide();
		$(".vsebina:hidden").prev().addClass("pokazi");   //postavitev znaka
		$(".vsebina:visible").prev().addClass("skrij");	  //postavitev znaka
		
		$( "p.glava" ).click(function() {
			 if ($(this).next('.vsebina').is(':visible')) {
				 $(this).next('.vsebina').hide();
				 $(this).removeClass('skrij').addClass('pokazi');
			 }
			 else{
				$(this).next('.vsebina').show(); 					 
				$(this).removeClass('pokazi').addClass('skrij');     //sprememba znaka
			}
		});
	});
  </script>
</head>
<body>
<?php
	if(isset($_GET['stran']) and $_GET['stran']=='ustvariTurnir')
		echo $content2;
?>	

<div class="container">
	<div class="header">
		<div class="header_levo">
		<?php
		if(isset($_SESSION['uporabnik']))
		{ 
			echo "<span class=\"welcome_msg\">" . "Pozdravljen, " . $_SESSION['uporabnik'] . "</span>"; 
		} 
		?>
		</div>
		<div class="header_desno">
		<a class="menu_link" href="index.php">DOMOV</a>
		<span class="sep"> | </span>
		<?php if(!isset($_SESSION['uporabnik'])) { ?> <a class="menu_link" href="index.php?stran=prijava">PRIJAVA</a>
		<span class="sep"> | </span><?php } ?>
		<?php if(!isset($_SESSION['uporabnik'])) { ?> <a class="menu_link" href="index.php?stran=registracija">REGISTRACIJA</a>
		<span class="sep"> | </span><?php } ?>
		<?php if(isset($_SESSION['uporabnik'])) { ?> <a class="menu_link" href="index.php?stran=turniri">TURNIRJI</a>
		<span class="sep"> | </span><?php } ?>
		<?php if(isset($_SESSION['uporabnik'])) { ?> <a class="menu_link" href="index.php?stran=ustvariTurnir">USTVARI TURNIR</a>
		<span class="sep"> | </span><?php } ?>
		<?php if(isset($_SESSION['uporabnik'])) { ?> <a class="menu_link" href="index.php?stran=odjava">ODJAVA</a>
		<span class="sep"> | </span><?php } ?>
		<a class="menu_link" href="index.php?stran=kontakt">KONTAKT</a>
		</div>
	</div>
</div>

<div class="container2">
	<div class="main">
		<?php
			echo $content;
		?>
	</div>
</div>

</body>
</html>
