<?php ob_start()?>

<?php
if (isset($_SESSION['notice']))
{ 
	echo "<br/>";
	echo "<div class=\"succes\">";
	echo $_SESSION['notice'];
	echo "</div>" ;
	echo "<br/>";
	unset($_SESSION['notice']);
}
else if (isset($_SESSION['notice_error']))
{
	echo "<br/>";
	echo "<div class=\"error\">";
	echo $_SESSION['notice_error'];
	echo "</div>" ;
	echo "<br/>";
	unset($_SESSION['notice_error']);
} 
?>

<div class="lol">

<h1>USTVARITE TURNIR</h1>
Na strani lahko ustvarite turnirje, vpišite kraj in uro turnira, dodajte klube po svoji želji.</br><br/>
<img src="images/p3.JPG" class="slike" rel="zoom">
<img src="images/p4.JPG" class="slike" rel="zoom">
<br/><br/>
<h1>OGLEJTE SI TURNIRJE</h1>
Turnirje si lahko ogledate v pregledni tabeli, jih urejate, po želji izbrišete ter si jih natisnete.</br><br/>
<img src="images/p1.JPG" class="slike" rel="zoom">
<img src="images/p2.JPG" class="slike" rel="zoom">

   <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.js"></script>
	<script type="text/javascript" src="zoom/js/easing.js"></script>
   <script type="text/javascript" src="zoom/js/smoothzoom.min.js"></script>
    <script type="text/javascript">
         $(window).load( function() {
         $('img').smoothZoom({
            // Options go here
                });
         });
    </script>

</div>

<?php

$title = "Prva stran";
$content=ob_get_clean();

require "layout.html.php";


 ?>
