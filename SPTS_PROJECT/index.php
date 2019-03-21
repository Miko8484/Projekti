<?php

session_start();
require_once 'model.php';

samodejna_prijava();

$naslovi = array (1=>"registracija",2=>"prijava",3=>"odjava",4=>"potrditev",5=>"resendEmail",6=>"kontakt",7=>"ustvariTurnir",8=>"pozabljenoGeslo",9=>"spremeniGeslo",
				 10=>"tekme",11=>"turniri",12=>"brisi",13=>"uredi",14=>"izidi");

if (isset($_GET["stran"]))
{
    for ($i = 0; $i < 1; $i++) 
    {
		$naslov=$_GET['stran'];
		
        if ($naslov==$naslovi[1])
		{
			include "template/registracija.html.php";
		}
		else if ($naslov==$naslovi[2])
		{
			if(!isset($_SESSION['uporabnik']))
			{
				include "template/prijava.html.php";
			}
			else
			{
				include "template/index.html.php";
			}
		}
		else if ($naslov==$naslovi[3])
		{
			if(isset($_SESSION['uporabnik']))
			{
				include "template/odjava.html.php";
			}
			else
			{
				include "template/index.html.php";
			}
		}
		else if ($naslov==$naslovi[4])
		{
			include "template/potrditev.html.php";
		}
		else if ($naslov==$naslovi[5])
		{
			include "template/resendEmail.html.php";
		}
		else if ($naslov==$naslovi[6])
		{
			include "template/kontakt.html.php";
		}
		else if($naslov==$naslovi[7])
		{
			if(isset($_SESSION['uporabnik']))
			{
				include "template/ustvariTurnir.html.php";
			}
			else
			{
				include "template/index.html.php";
			}
		}
		else if($naslov==$naslovi[8])
		{
			include "template/pozabljenoGeslo.html.php";
		}
		else if($naslov==$naslovi[9])
		{
			include "template/spremeniGeslo.html.php";
		}
		else if($naslov==$naslovi[10])
		{
			if(isset($_SESSION['uporabnik']))
			{
				include "template/tekme.html.php";
			}
			else
			{
				include "template/index.html.php";
			}
		}
		else if($naslov==$naslovi[11])
		{
			if(isset($_SESSION['uporabnik']))
			{
				include "template/turniri.html.php";
			}
			else
			{
				include "template/index.html.php";
			}
		}
		else if($naslov==$naslovi[12])
		{
			if(isset($_SESSION['uporabnik']))
			{
				include "template/brisi.html.php";
			}
			else
			{
				include "template/index.html.php";
			}
		}
		else if($naslov==$naslovi[13])
		{
			if(isset($_SESSION['uporabnik']))
			{
				include "template/uredi.html.php";
			}
			else
			{
				include "template/index.html.php";
			}
		}
		else if($naslov==$naslovi[14])
		{
			if(isset($_SESSION['uporabnik']))
			{
				include "template/izidi.html.php";
			}
			else
			{
				include "template/index.html.php";
			}
		}
		else
		{
			include "template/error.html.php";
		}
    }
}

else
{
	include "template/index.html.php";
}
 ?>
