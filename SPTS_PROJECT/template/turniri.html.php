<?php ob_start()?>

<h1>Turnirji</h1>

<?php
if (isset($_SESSION['notice']))
{ 
	echo "<div class=\"succes\" style=\"width:auto;\">";
	echo $_SESSION['notice'];
	echo "</div>" ;
	echo "<br/>";
	unset($_SESSION['notice']);
}
?>

<ol class="turniri">
	<li>
		<p class="glava">Moji turnirji</p>
		<div class="vsebina">
			<ol class="imenaturnirov">
				<?php
					$naslovi=mojiNasloviTurnirov();
					foreach($naslovi as $x)
					{
						$originalDate =$x[4];
						$newDate = date("d-m-Y H:i", strtotime($originalDate));
						
						echo "<li class=\"imeturnira\"><span class=\"span1\">".$x[1]." "."<span class=\"span2\">";
						if($x[4]!="0000-00-01 00:00:00"){
						echo "Dne:".$newDate." ";}
						if($x[5]!=""){
						echo "Kraj:".$x[5];} 
						
						echo"</span></span>
							<a class='icon' onclick=\"return confirm('Ste prepričani da želite izbrisati turnir ?')\" href='index.php?stran=brisi&id=". $x[0] ."' name=\"brisi\"><img src=\"icons/delete.png\" alt=\"delete\"></a>
							<a class='icon' href='index.php?stran=tekme&id=". $x[0] ."'><img src=\"icons/ball.png\" alt=\"change\"></a>
							<a class='icon' href='index.php?stran=uredi&id=". $x[0] ."'><img src=\"icons/edit.png\" alt=\"change\"></a>
							<a class='icon' href='index.php?stran=izidi&id=". $x[0] ."'><img src=\"icons/see.png\" alt=\"see\"></a>
							</li>";
					}
				?>
			</ol>
		</div>

	</li>
	<br/>
	<li>
		<p class="glava">Vsi turnirji</p>
		<div class="vsebina">
			<ol class="imenaturnirov">
				<?php
					$naslovi2=vsiNasloviTurnirov();
					foreach($naslovi2 as $y)
					{
						$originalDate =$y[4];
						$newDate = date("d-m-Y H:i", strtotime($originalDate));
						
						echo "<li class=\"imeturnira\"><span class=\"span1\">".$y[1]." "."<span class=\"span2\">";
						if($y[4]!="0000-00-01 00:00:00"){
						echo "Dne:".$newDate;}
						if($y[5]!=""){
						echo " Kraj:".$y[5];} 
						echo "</span></span>
							<a class='icon' href='index.php?stran=izidi&id=". $y[0] ."'><img src=\"icons/see.png\" alt=\"see\"></a>
							</li>";
					}
				?>
			</ol>
		</div>
	</li>
</ol>
<?php


$title = "Turnirji";
$content=ob_get_clean();

require "layout.html.php";


?>

 



