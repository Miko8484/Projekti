<?php
date_default_timezone_set('Europe/Ljubljana');
function open_database_connection()
{
	$link=new mysqli("localhost","mitjac","BUmaQ29U","mitjac");
	$link->query("SET NAMES 'utf8'");
	return $link;
}

function close_database_connection($link)
{
	mysqli_close($link);
}

function error($status)
{
	echo "<div class=\"error\">";
	echo "<b>".$status."</b>";
	echo "<br/>";
	echo "</div>";
	echo "<br/>";
}

function success($status)
{
	echo "<div class=\"succes\">";
	echo "<b>".$status."</b>"; 
	echo "</div>";
	echo "<br/>";
}

function registracija()
{
	$status="";
	if(isset($_POST['gumb']))
	{
		if ((trim($_POST["upoime"])=="") || ($_POST["geslo"]=="") || ($_POST["geslo2"]=="") || ($_POST["email"]=="") )
		{
			$status="Niste izpolnili vseh polj.";
			echo error($status);
			return false;
		}
		else
		{
			$username=strip_tags($_POST['upoime']);
			$geslo=strip_tags($_POST['geslo']);
			$email=strip_tags($_POST['email']);
			
			$link=open_database_connection();
			
			$sql="SELECT id FROM uporabniki WHERE username='$username' ";
			$poizvedba=mysqli_query($link,$sql);
			$stevilopoizvedbe=mysqli_num_rows($poizvedba);
			if($stevilopoizvedbe==0)
			{
				$sql="SELECT id FROM uporabniki WHERE email='$email'";
				$poizvedba2=mysqli_query($link,$sql);
				$stevilopoizvedbe2=mysqli_num_rows($poizvedba2);
				if($stevilopoizvedbe2==0)
				{
					$dolzina=mb_strlen($geslo, 'UTF-8');
					
					if($dolzina<8)
					{
						$status="Geslo je prekratko.";
						echo error($status);
						return false;
					}
					
					if(($_POST["geslo"]) != ($_POST["geslo2"]))	
					{	
						$status="Gesli se ne ujemata.";
						echo error($status);
						return false;	
					}
					
					require_once('recaptchalib.php');
					  $privatekey = "6LeQ1O8SAAAAAHk-8_ZzP8zl5VsLmACgJrpY_U5s";
					  $resp = recaptcha_check_answer ($privatekey,
													$_SERVER["REMOTE_ADDR"],
													$_POST["recaptcha_challenge_field"],
													$_POST["recaptcha_response_field"]);

					  if (!$resp->is_valid) 
					  {
						$status="Napačni znaki." ;
						echo error($status);
						return false;
					  } 
					  else 
					  {
							$sifriranje=sha1($geslo);
							date_default_timezone_set('Europe/London');
							$date = date('Y-m-d H:i:s');
							$hash = md5( rand(0,1000) );
							
							$sql="INSERT INTO uporabniki (username,password,email,registerDate,hash) VALUES ('$username','$sifriranje','$email','$date','$hash')";
							mysqli_query($link,$sql);

							$subject="Aktivacija računa";
							$message = '
Hvala za registracijo!
Račun je bil ustvarjen z naslednjimi podatki:
															 
------------------------
Username: '.$username.'
Password: '.$geslo.'
------------------------
															 
Za aktivacijo računa, kliknite na spodnjo povezavo:
http://193.2.128.148/~mitjac/project2/index.php?stran=potrditev&email='.$email.'&hash='.$hash.'
															 
							'; 
							$to = "$email";
							$headers = "From: webmaster@example.com" . "\r\n" .
							"Reply-To: " .$email . "\r\n" .
							'X-Mailer: PHP/' . phpversion();
							mail($to,$subject,$message,$headers);
							echo "<br/>";
							$status="Registracija uspešna,preveriti e-mail naslov za aktivacijo računa. <br/>
							Če niste prejeli email-a kliknite <a href=index.php?stran=resendEmail&email='$email'&hash='$hash'>tukaj</a> za ponovno pošiljanje emaila. ";
							echo success($status);
							close_database_connection($link);
					  }
				  }
				  else
				  {
					  $status="Email je že v uporabi.";
					  echo error($status);
					  return false;
				  }
			}
			else
			{
				$status="Uporabnik že obstaja.";
				echo error($status);
				return false;
			}
		}
	}
}

function potrditev()
{
	$status="";
	$link=open_database_connection();
	if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash']))
	{
		$email = strip_tags($_GET['email']);
		$hash = strip_tags($_GET['hash']);
		$sql="SELECT id FROM uporabniki WHERE email='$email' AND hash='$hash' ";
		$poizvedba=mysqli_query($link,$sql);
		$stevilopoizvedbe=mysqli_num_rows($poizvedba);
		
		if($stevilopoizvedbe==1)
		{
			$sql="UPDATE uporabniki SET validation='1' WHERE email='$email' AND hash='$hash'";
			mysqli_query($link,$sql);
			$status="Račun je aktiviran, sedaj se lahko prijavite.";
			session_start();
			$_SESSION['notice'] = $status;
			header('Location: index.php');
		}
		else
		{
			$status="Napačna povezava ali pa je račun že bil aktiviran.";
			session_start();
			$_SESSION['notice_error'] = $status;
			header('Location: index.php');
		}
	}
	else
	{
		$status="Napačna povezava.";
		echo error($status);
		session_start();
		$_SESSION['notice_error'] = $status;
		header('Location: index.php');
	}
	close_database_connection($link);
}

function resendEmail()
{
	$email = strip_tags($_GET['email']);
	$hash = strip_tags($_GET['hash']);
	
	$hashtrue=substr($hash, 1);
	$hashpravi=substr_replace($hashtrue ,"",-1);
	
	$emailtrue=substr($email, 1);
	$emailpravi=substr_replace($emailtrue ,"",-1);
	$link=open_database_connection();
					$query = "SELECT * FROM uporabniki WHERE email='$emailpravi' "; 
					$result = mysqli_query($link,$query);
					$row = mysqli_fetch_array($result);
					$username=$row['username'];
					$geslo=$row['password'];
	close_database_connection($link);
	
	$subject="Aktivacija računa";
							$message = '
Hvala za registracijo!
Račun je bil ustvarjen z naslednjimi podatki:
															 
------------------------
Username: '.$username.'
------------------------
															 
Za aktivacijo računa, kliknite na spodnjo povezavo:
http://193.2.128.148/~mitjac/project2/index.php?stran=potrditev&email='.$emailpravi.'&hash='.$hashpravi.'
															 
							'; 
							$to = "$emailpravi";
							$headers = "From: webmaster@example.com" . "\r\n" .
							"Reply-To: " .$email . "\r\n" .
							'X-Mailer: PHP/' . phpversion();
							mail($to,$subject,$message,$headers);
							
header('Location: index.php');
}

function prijava()
{
	$status="";
	
	if(isset($_POST['gumb']))
	{
		$username=strip_tags($_POST['upoime']);
		$geslo=strip_tags($_POST['geslo']);
		$sifriranje=sha1($geslo);

		if((trim($_POST['upoime'])=="") || ($_POST['geslo']==""))
		{
			$status="Niste izpolnili vse polj.";
			echo error($status);
			return false;
		}
		else
		{
			$link=open_database_connection();
			
			$sql="SELECT id FROM uporabniki WHERE username='$username' AND password='$sifriranje'";
			$poizvedba=mysqli_query($link,$sql);
			$stevilopoizvedbe=mysqli_num_rows($poizvedba);
			
			if($stevilopoizvedbe==0)
			{
				$status="Napačno uporabniško ime ali geslo.";
				echo error($status);
				return false;
			}
			else
			{	
				$sql="SELECT id FROM uporabniki WHERE username='$username' AND validation='1'";
				$poizvedba2=mysqli_query($link,$sql);
				$stevilopoizvedbe2=mysqli_num_rows($poizvedba2);
				
				if($stevilopoizvedbe2==0)
				{
					$status="Račun še ni bil aktiviran,preverite email za aktivacijo.";
					echo error($status);
					return false;
				}
				else
				{
					$_SESSION['uporabnik']=$username;
					if(isset($_POST['zapomni']))
					{
						setcookie("piskot", $username, time()+3600);
					}
					header('Location: index.php');
				}
			}
			close_database_connection($link);
		}	
	}
}

function odjava()
{
 session_destroy();
 setcookie("piskot", $_SESSION['uporabnik'], time()-1);
 header('Location: index.php');	
}

function samodejna_prijava()
{
	if(isset($_COOKIE['piskot']))
	{
		$_SESSION['uporabnik']=$_COOKIE['piskot'];
	}
}

function pozabljenoGeslo()
{
	$status="";
	$link=open_database_connection();
	
	if(isset($_POST['gumb']))
	{
		$email=strip_tags($_POST['email']);
		
		if($_POST["email"]=="")
		{
			$status="Niste izpolnili polja.";	
			return error($status);
			return false;
		}
		else
		{
			$sql="SELECT id FROM uporabniki WHERE email='$email' ";
			$poizvedba=mysqli_query($link,$sql);
			$stevilopoizvedbe=mysqli_num_rows($poizvedba);
			
			
			if($stevilopoizvedbe==0)
			{
				$status="Email ne obstaja";	
				return error($status);
				return false;
			}
			else
			{
				$query = "SELECT hash FROM uporabniki WHERE email='$email'"; 
				$result = mysqli_query($link,$query);
				$row = mysqli_fetch_array($result);
				$hash=$row['hash'];
				
					$subject="Pozabljeno geslo";
							$message = '
Za spremembo geslo, kliknite na spodnjo povezavo:
http://193.2.128.148/~mitjac/project2/index.php?stran=spremeniGeslo&email='.$email.'&hash='.$hash.'
															 
							'; 
							$to = "$email";
							$headers = "From: webmaster@example.com" . "\r\n" .
							"Reply-To: " .$email . "\r\n" .
							'X-Mailer: PHP/' . phpversion();
							mail($to,$subject,$message,$headers);
				
				$status="Povezava je bila poslana.";
				echo success($status);
				return false;
			}
		}
	}
	close_database_connection($link);
}

function spremembaGesla()
{
	$status="";
	$link=open_database_connection();
	if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash']))
	{
		$email = strip_tags($_GET['email']);
		$hash = strip_tags($_GET['hash']);
		$sql="SELECT id FROM uporabniki WHERE email='$email' AND hash='$hash' ";
		$poizvedba=mysqli_query($link,$sql);
		$stevilopoizvedbe=mysqli_num_rows($poizvedba);
		
		if($stevilopoizvedbe==1)
		{
			if(isset($_POST['gumb']))
			{
				$geslo=strip_tags($_POST['geslo1']);
				$dolzina=mb_strlen($geslo, 'UTF-8');
					
				if($dolzina<8)
				{
					$status="Geslo je prekratko.";
					echo error($status);
					return false;
				}
					
				if(($_POST["geslo1"]) != ($_POST["geslo2"]))	
				{	
					$status="Gesli se ne ujemata.";
					echo error($status);
					return false;	
				}
				else
				{
					$geslo=sha1($_POST['geslo1']);
					$sql="UPDATE uporabniki SET password='$geslo' WHERE email='$email' AND hash='$hash'";
					mysqli_query($link,$sql);
					$status="Geslo je bilo spremenjeno.";
					echo success($status);
					return false;
				}
			}
		}
		else
		{
			$status="Napačna povezava.";
			echo error($status);
			return false;
		}
	}
	else
	{
		$status="Napačna povezava.";
		echo error($status);
		return false;
	}
	close_database_connection($link);
}

function contact()
{
	$status="";
	
	if(isset($_POST['gumb']))
	{
		$ime=strip_tags($_POST['ime']);
		$email=strip_tags($_POST['email']);
		$subject=strip_tags($_POST['zadeva']);
		$txt=strip_tags($_POST['editor1']);
		
		if($_POST["email"]=="" || trim($_POST['ime'])=="" || trim($_POST['zadeva'])=="" || trim($_POST['editor1'])=="")
		{
			$status="Niste izpolnili vseh polj.";	
			echo error($status);
			return false;
		}
		
		require_once('recaptchalib.php');
		$privatekey = "6LeQ1O8SAAAAAHk-8_ZzP8zl5VsLmACgJrpY_U5s";
		$resp = recaptcha_check_answer ($privatekey,
		$_SERVER["REMOTE_ADDR"],
		$_POST["recaptcha_challenge_field"],
		$_POST["recaptcha_response_field"]);

		if (!$resp->is_valid) 
		{
			echo "<br/>";
			$status="Napačni znaki" ;
			echo error($status);
			return false;
		} 
		else
		{	
										$message = '
'.$txt.'

OD: '.$email.'
'; 
			
			$to = "mitja.celec12@gmail.com";
			$headers = "From: ".$ime."  <".$email.">" . "\r\n" .
			"Reply-To: " .$email . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
			mail($to,$subject,$message,$headers);
			echo "<br/";
			$status="Sporočilo uspešno poslano.";
			echo success($status);
			return false;
		}
	}
}

function izpisKlubov()
{
	$array = array();
	$stevec=0;
	$link=open_database_connection();
	
	$username=$_SESSION['uporabnik'];
	$query = "SELECT id FROM uporabniki WHERE username='$username'"; 
	$result = mysqli_query($link,$query);
	$row = mysqli_fetch_array($result);
	$IdUporabnika=$row['id'];
	
	$query = "SELECT * FROM klubi WHERE idUporabnika='$IdUporabnika' ORDER BY imeKluba ASC"; 
	$result = mysqli_query($link,$query);
	
	while ($row = mysqli_fetch_array($result))
	{
		echo "<div class=\"klub\">".$row['imeKluba'];
		echo "<br/><br/>";
		$path=$row['imeLogo'];
		if($path=="")
			echo "<img src='../uploads/noimage.png' />";
		else
			echo "<img src='../uploads/$path' />";
		echo "<br/>";
		echo "<input type=\"checkbox\" name=\"check_list[]\" value='{$row['imeKluba']}'>";
		echo "<br/><br/>";
		echo "</div>";
		
		$array[] = $row['imeKluba'];
	}
	echo "<a id=\"add\" onclick=\"showLoginForm()\">";
	echo "<div onclick=\"showLoginForm()\" class=\"dodaj\"><br/><br/><br/><img src='icons/plus.png'/><br/></div>";
	echo "</a>";
	
	/*****  IZBRIS KLUBA *****/
	if(isset($_POST['gumb1']))
	{
		if(!empty($_POST['check_list']))
		{
			foreach($_POST['check_list'] as $klub)
			{
				for($i=0;$i<count($array);$i++)
				{
					if ($klub == $array[$i])
					{
						$key=$array[$i];
						$query = "DELETE FROM klubi WHERE imeKluba='$key'"; 
						$result = mysqli_query($link,$query);
					}
				}
		    }
	    }
	  header('Location: index.php?stran=ustvariTurnir');
	}
	/*****  IZBRIS KLUBA *****/
	
	/*****  USTVARJANJE TURNIRJA  *****/
	else if(isset($_POST['gumb0']))
	{
		if(trim($_POST['imeTurnira'])=="")
		{
			$status="Niste vnseli imena za turnir.";
			$_SESSION['notice_error'] = $status;
			header("refresh: 0;");
			return false;
		}
		
		$imeTurnira=strip_tags($_POST['imeTurnira']);
		
		$sql="SELECT id FROM turniri WHERE imeTurnira='$imeTurnira' ";
		$poizvedba=mysqli_query($link,$sql);
		$stevilopoizvedbe=mysqli_num_rows($poizvedba);
		
		if($stevilopoizvedbe==1)
		{
			$status="Turnir z tem imenom že obstaja.";
			$_SESSION['notice_error'] = $status;
			header("refresh: 0;");
			return false;
		}
		if(!empty($_POST['check_list']))
		{
			foreach($_POST['check_list'] as $klub)
			{
				$stevec++;
		    }
	    }
		
		if($stevec<3)
		{
			$status="Za turnir so potrebni vsaj 3 klubi.";
			$_SESSION['notice_error'] = $status;
			header("refresh: 0;");
			return false;
		}
		
		$cur_time=date("Y-m-d H:i:s");
		$duration='+10 minutes';
		$date=date('Y-m-d H:i:s', strtotime($duration, strtotime($cur_time)));
		
		if(trim($_POST['datumcas'])!="")
		{
			$originalDate =strip_tags($_POST['datumcas']);
			$newDate = date("Y-m-d H:i:00", strtotime($originalDate));
			
			if($originalDate<$date)
			{
				$status="Napačen datum oz. čas.";
				$_SESSION['notice_error'] = $status;
				header("refresh: 0;");
				return false;
			}
		}
		else
		{
			$newDate=date("0000-00-01 00:00:00");
		}
		
		$kraj=$_POST['kraj'];
		$sql="INSERT INTO turniri (imeTurnira,date,idUporabnika,datumCas,kraj) VALUES ('$imeTurnira','$date','$IdUporabnika','$newDate','$kraj')";
		mysqli_query($link,$sql);
		
		if(!empty($_POST['check_list']))
		{
			foreach($_POST['check_list'] as $klub)
			{
				for($i=0;$i<count($array);$i++)
				{
					if ($klub == $array[$i])
					{
						$query = "SELECT id FROM turniri WHERE imeTurnira='$imeTurnira'"; 
						$result = mysqli_query($link,$query);
						$row = mysqli_fetch_array($result);
						$IdTurnira=$row['id'];
						
						$query = "SELECT id FROM klubi WHERE imeKluba='$array[$i]'"; 
						$result = mysqli_query($link,$query);
						$row = mysqli_fetch_array($result);
						$IdKluba=$row['id'];
						
						$sql="INSERT INTO turnir_klub (id_klub,id_turnir) VALUES ('$IdKluba','$IdTurnira')";
						mysqli_query($link,$sql);
						header('Location: index.php?stran=tekme&id='.$IdTurnira.'');
					}
				}
		    }
	    }
	}
	/*****  USTVARJANJE TURNIRJA  *****/
	close_database_connection($link);	
}

function addForm()
{
	echo "<div class=\"addForm\">";
		
		echo "<form enctype=\"multipart/form-data\" method='post' name=\"form\" action=\"#\">";
			
			echo "<input type=\"text\" name=\"klub\" class=\"register\" placeholder=\"Ime kluba\"  required />"."<br/><br/>"; 
			echo "<input name=\"uploadedfile\" type=\"file\" value=\"Slika logo-ja\" />"."<br/><br/>";
			echo "<input type=\"submit\" name=\"gumb2\" value=\"Ustvari klub\" id=\"submit\" />"."<br/><br/>";
			
		echo "</form>";
	
	echo "</div>";
}

function addKlub()
{
	$status="";
	if(isset($_POST['gumb2']))
	{
		$link=open_database_connection();
		$ime=sha1(date('Y-m-d H:i:s'));
		
		if (trim($_POST["klub"])=="")
		{
			$status="Niste vnesli ime kluba.";
			echo error($status);
			return false;
		}
		$username=$_SESSION['uporabnik'];
		$query = "SELECT id FROM uporabniki WHERE username='$username'"; 
		$result = mysqli_query($link,$query);
		$row = mysqli_fetch_array($result);
		$IdUporabnika=$row['id'];
		
		$imeKluba=$_POST["klub"];
		$sql="SELECT id FROM klubi WHERE imeKluba='$imeKluba' AND IdUporabnika='$IdUporabnika'  ";
		$poizvedba=mysqli_query($link,$sql);
		$stevilopoizvedbe=mysqli_num_rows($poizvedba);
		if($stevilopoizvedbe==1)
		{
			$status="Klub z tem imenom že obstaja.";
			echo error($status);
			return false;
		}
		
		if ($_FILES['uploadedfile']['size'] == 0)
		{
			$username=$_SESSION['uporabnik'];
			$query = "SELECT id FROM uporabniki WHERE username='$username'"; 
			$result = mysqli_query($link,$query);
			$row = mysqli_fetch_array($result);
			$IdUporabnika=$row['id'];
			
			$imeKluba=strip_tags($_POST['klub']);
			$sql="INSERT INTO klubi (ImeKluba,idUporabnika) VALUES ('$imeKluba','$IdUporabnika')";
			mysqli_query($link,$sql);
			header('Location: index.php?stran=ustvariTurnir');
		}
		
		else
		{
			$velikost=$_FILES['uploadedfile']['size'];
			if($velikost < 1024000)
			{		
				$file_type = $_FILES['uploadedfile']['type']; //returns the mimetype

				$allowed = array("image/jpeg", "image/png");
				
				$filename = $_FILES["uploadedfile"]["name"];
				$file_basename = substr($filename, 0, strripos($filename, '.')); // get file extention
				$file_ext = substr($filename, strripos($filename, '.')); // get file name
				$newfilename = $file_basename . $ime . $file_ext;
				
				if(in_array($file_type, $allowed)) 
				{
					if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], "../uploads/" . $newfilename)) {
						header('Location: index.php?stran=ustvariTurnir');	
						$status="Datoteka ".  basename( $_FILES['uploadedfile']['name']). 
						" je bila naložena";
						echo success($status);
						
						$imeKluba=strip_tags($_POST['klub']);
						$sql="INSERT INTO klubi (ImeKluba,ImeLogo,idUporabnika) VALUES ('$imeKluba','$newfilename','$IdUporabnika')";
						mysqli_query($link,$sql);
						
					} else{
						$status="Prišlo je do napake, poskusite ponovno";
						echo error($status);
						return false;
					}
				}
				else
				{
					$status="Nepravilen format datoteke";
					echo error($status);
					return false;
				}
			}
			else
			{
				$status="Datoteka je prevelika";
				echo error($status);
				return false;
			}
		}
		close_database_connection($link);
	}
}

function tekme()
{
	$teams = array();
	$klubi = array();
	$stevila=array();
	$zaporedje=array();
	$zaporedje_klubov=array();
	$idji=array();
	$true_idji=array();
	$status="";
	$stevec=0;
	$stevec2=0;
	$odigrane=0;
	$link=open_database_connection();
	$id=$_GET['id'];
	
	$query1 = "SELECT id_klub  FROM turnir_klub WHERE id_turnir='$id'"; 
	$result1 = mysqli_query($link,$query1);
	$IdKlub = array();
	while ($row1 = mysqli_fetch_array($result1))
	{
		$query2 = "SELECT imeKluba FROM klubi WHERE id='{$row1['id_klub']}'"; 
		$result2 = mysqli_query($link,$query2);
			
		while ($row2 = mysqli_fetch_array($result2))
		{
			$teams[] = $row2['imeKluba'];
		}
	}
	
	for($i = 0; $i < count($teams)-1; $i++) {
		for($j = $i + 1; $j < count($teams); $j++) {
			$games2[] = $teams[$i];
			$games2[] = $teams[$j];
		}
	}
	
	$query = "SELECT sprememba,imeTurnira FROM turniri WHERE id='$id'"; 
	$result = mysqli_query($link,$query);
	$row = mysqli_fetch_array($result);
	$sprememba=$row['sprememba'];
	$imeTurnira=$row['imeTurnira'];
	
	if($sprememba==1)
	{
		echo "<table class=\"tekme\">";
			echo "<tr><th colspan=\"2\">$imeTurnira</th></tr>";
			
			$query = "SELECT id_klub,dani_goli,prejeti_goli,ura,tekme FROM tekme WHERE id_turnir='$id' ORDER BY tekme ASC"; 
				$result = mysqli_query($link,$query);
				while($row = mysqli_fetch_array($result))
				{
					$query2 = "SELECT imeKluba FROM klubi WHERE id='{$row['id_klub']}'"; 
					$result2 = mysqli_query($link,$query2);
					$row2 = mysqli_fetch_array($result2);
					
					$ura[]=strtotime($row['ura']);
					$klub[]=$row2['imeKluba'];
					$dani[]=$row['dani_goli'];
					$prejeti[]=$row['prejeti_goli'];
					$tekme=$row['tekme'];
				}
				$tekme=$tekme*2;
				$k=0;
				for($i=0;$i<$tekme;$i++)
				{
				$ura[$i]=date("H:i",$ura[$i]);
					echo "<td width='50px'>"."<input name='ura{$k}' value='{$ura[$i]}'  type='text' placeholder='Ura' >"."</td>"."<td>";
					echo "<div class='levi'>".$klub[$i].' '."<input type=\"text\" id='rezultati' value='{$dani[$i]}' name='dani{$k}'/>".'</div>'." - "."<div class='desni'>"."<input type=\"text\" value='{$prejeti[$i]}' id='rezultati' name='prejeti{$k}' />".' '.$klub[$i+1].'</div>';
					echo "</td>";
					echo "</tr>";
					$i++;
					$k=$k+1;
				}
			
		echo "</table>";
		
		if(isset($_POST['gumb0']))
		{
			$query = "SELECT id FROM tekme WHERE id_turnir='$id'"; 
			$result = mysqli_query($link,$query);
			$vrstice=mysqli_num_rows($result);
			$vrstice=$vrstice/2;
			for($i=0; $i<$vrstice;$i++)
			{

				$rezultati[$i][0]=$_POST['dani'.$i];
				$rezultati[$i][1]=$_POST['prejeti'.$i];
				$ura[$i]=$_POST['ura'.$i];
				
				$dani=$rezultati[$i][0];
				$prejeti=$rezultati[$i][1];
				
			}
			
			$query = "SELECT id FROM tekme WHERE id_turnir='$id'"; 
			$result = mysqli_query($link,$query);
			while($row = mysqli_fetch_array($result))
			{
				$idji[]=$row['id'];
			}
			
			$z=count($idji);
			$y=($z/2)-1;
			for($i=0; $i<$z;$i++)
			{
				$x=$i+1;
				$id1=$idji[$i];
				$id2=$idji[$x];
						
				$dani=$rezultati[$y][0];
				$prejeti=$rezultati[$y][1];
				
				$cas=$ura[$y];
				
				$stevec2=$i+1;
				
				if($dani>$prejeti)
				{
						$sql="UPDATE tekme SET odigrane='odigrane'+1,ura='$cas',dani_goli='$prejeti',prejeti_goli='$dani',porazi='1',tocke='0' WHERE id='$id1'";
						mysqli_query($link,$sql);
						
						$sql="UPDATE tekme SET odigrane='odigrane'+1,ura='$cas',dani_goli='$dani',prejeti_goli='$prejeti',zmage='1',tocke='3' WHERE id='$id2'";
						mysqli_query($link,$sql);
				}
				else if($dani<$prejeti)
				{
						$sql="UPDATE tekme SET odigrane='odigrane'+1,ura='$cas',dani_goli='$prejeti',prejeti_goli='$dani',zmage='1',tocke='3' WHERE id='$id1'";
						mysqli_query($link,$sql);
						
						$sql="UPDATE tekme SET odigrane='odigrane'+1,ura='$cas',dani_goli='$dani',prejeti_goli='$prejeti',porazi='1',tocke='0' WHERE id='$id2'";
						mysqli_query($link,$sql);
				}
				
				else if($dani==$prejeti)
				{
						$sql="UPDATE tekme SET odigrane='odigrane'+1,ura='$cas',dani_goli='$dani',prejeti_goli='$prejeti',neodloceno='1',tocke='1' WHERE id='$id1'";
						mysqli_query($link,$sql);
						
						$sql="UPDATE tekme SET odigrane='odigrane'+1,ura='$cas',dani_goli='$prejeti',prejeti_goli='$dani',neodloceno='1',tocke='1' WHERE id='$id2'";
						mysqli_query($link,$sql);
				}
				$i++;
				$y--;
			}
				$sql="SELECT id FROM tocke WHERE id_turnir='$id' ";
				$poizvedba=mysqli_query($link,$sql);
				$stevilopoizvedbe=mysqli_num_rows($poizvedba);
			
				$query = "SELECT DISTINCT id_klub FROM tekme WHERE id_turnir='$id'"; 
				$result = mysqli_query($link,$query);
				while ($row = mysqli_fetch_array($result))
				{
					$query2 = "SELECT sum(odigrane),sum(zmage),sum(porazi),sum(neodloceno),sum(dani_goli),sum(prejeti_goli),sum(tocke) FROM tekme WHERE id_turnir='$id' and id_klub='{$row['id_klub']}'"; 
					$result2 = mysqli_query($link,$query2);
					$row2 = mysqli_fetch_array($result2);
					
					if($stevilopoizvedbe!=0)
					{
						$sql="UPDATE tocke SET tocke='{$row2['sum(tocke)']}',odigrane='{$row2['sum(odigrane)']}',zmage='{$row2['sum(zmage)']}',porazi='{$row2['sum(porazi)']}',neodloceno='{$row2['sum(neodloceno)']}',dani_goli='{$row2['sum(dani_goli)']}',prejeti_goli='{$row2['sum(prejeti_goli)']}' WHERE id_klub='{$row['id_klub']}'";
						mysqli_query($link,$sql);
					}
					else
					{
						$sql="INSERT INTO tocke (id_turnir,id_klub,tocke,odigrane,zmage,porazi,neodloceno,dani_goli,prejeti_goli) VALUES ('$id','{$row['id_klub']}','{$row2['sum(tocke)']}','{$row2['sum(odigrane)']}','{$row2['sum(zmage)']}','{$row2['sum(porazi)']}','{$row2['sum(neodloceno)']}','{$row2['sum(dani_goli)']}','{$row2['sum(prejeti_goli)']}')";
						mysqli_query($link,$sql);
					}
				}	
			header('Location: index.php?stran=izidi&id='.$id);
		}
		return false;
	}
	
	$query = "SELECT id FROM tekme WHERE id_turnir='$id'"; 
	$result = mysqli_query($link,$query);
	$rowcount=mysqli_num_rows($result);
	
	$preverjanje=count($games2);
	if($rowcount!=$preverjanje)
	{
			
			$games = array();
			$stevec=0;

			class game {
				public $home_team;
				public $visitors;
				
				function __construct($h,$v) {
					$this->home_team = $h;
					$this->vistors = $v;
				}
				
				function show_pairing($i) {
					echo "<td width='50px'><input name='ura{$i}' type='text' placeholder='Ura' ></td>"."<td>";
					echo "<div class='levi'>".$this->home_team.' '."<input type=\"text\" name='dani{$i}' id=\"rezultati\" />".'</div>'." - "."<div class='desni'> <input type=\"text\" name='prejeti{$i}' id=\"rezultati\" /> ".$this->vistors.'</div>';
					echo "</td>";
				}
			}

			$x=0;
			$y=0;
			for($i = 0; $i < count($teams)-1; $i++) {
				for($j = $i + 1; $j < count($teams); $j++) {
					$games[] = new game($teams[$i],$teams[$j]);
					
					$klubi[$x][$y]=$teams[$i];
					$klub=$teams[$i];
					$y++;
					$klubi[$x][$y]=$teams[$j];
					$klub2=$teams[$j];
					$x++;
					$y=0;
					
				}
			}
			
			for($i = 0; $i < count($games); $i++) 
			{
				$stevila[] = $i;	
			} 

			$query = "SELECT imeTurnira FROM turniri WHERE id='$id'"; 
			$result = mysqli_query($link,$query);
			$row = mysqli_fetch_array($result);
			$imeTurnira=$row['imeTurnira'];
			
		echo "<div class=\"pare\">";

			echo "<table class=\"tekme\">";
			echo "<tr>
					<th colspan=\"2\">
						$imeTurnira
						<button class=\"print-link no-print\">
							<img src=\"icons/fileprint.png\" alt=\"printing\">
						</button>
					</th></tr>";
				
				for($i = 0; $i < count($games); $i++) {
					echo "<tr>";
					$rand= array_rand($stevila);
					$games[$rand]->show_pairing($i);
					$zaporedje[]=$rand;
					unset($stevila[$rand]);
					echo "</tr>";
				} 
				
			echo "</table>";
			
			
		echo "</div>";


		for($a=0;$a<count($zaporedje);$a++)
		{
			$zaporedje_klubov[$a][0]=$klubi[$zaporedje[$a]][0];
			$klub=$zaporedje_klubov[$a][0];
			$zaporedje_klubov[$a][1]=$klubi[$zaporedje[$a]][1];
			$klub2=$zaporedje_klubov[$a][1];
			
			$stevec=$a+1;
			
				$query = "SELECT id FROM klubi WHERE imeKluba='$klub'"; 
				$result = mysqli_query($link,$query);
				while($row = mysqli_fetch_array($result))
				{
					$idkluba1=$row['id'];
					$sql="INSERT INTO tekme (id_turnir,id_klub,tekme) VALUES ('$id','$idkluba1','$stevec')";
					mysqli_query($link,$sql);
				}
						
				/*Drugi klub*/
				$query = "SELECT id FROM klubi WHERE imeKluba='$klub2'"; 
				$result = mysqli_query($link,$query);
				while($row = mysqli_fetch_array($result))
				{
					$idkluba2=$row['id'];
					$sql="INSERT INTO tekme (id_turnir,id_klub,tekme) VALUES ('$id','$idkluba2','$stevec')";
					mysqli_query($link,$sql);
				}
		 
		}
		$sql="UPDATE turniri SET sprememba='1' WHERE id='$id'";
		mysqli_query($link,$sql);
	}				
	if(isset($_POST['gumb0']))
	{
		$query = "SELECT id FROM tekme WHERE id_turnir='$id'"; 
		$result = mysqli_query($link,$query);
		$vrstice=mysqli_num_rows($result);
		$vrstice=$vrstice/2;
		for($i=0; $i<$vrstice;$i++)
		{

			$rezultati[$i][0]=$_POST['dani'.$i];
			$rezultati[$i][1]=$_POST['prejeti'.$i];
			$ura[$i]=$_POST['ura'.$i];
			
			$dani=$rezultati[$i][0];
			$prejeti=$rezultati[$i][1];
			
		}
		
		$query = "SELECT id FROM tekme WHERE id_turnir='$id'"; 
		$result = mysqli_query($link,$query);
		while($row = mysqli_fetch_array($result))
		{
			$idji[]=$row['id'];
		}
		
		$z=count($idji);
		$y=($z/2)-1;
		for($i=0; $i<$z;$i++)
		{
			$x=$i+1;
			$id1=$idji[$i];
			$id2=$idji[$x];
					
			$dani=$rezultati[$y][0];
			$prejeti=$rezultati[$y][1];
			
			$cas=$ura[$y];
			
			$stevec2=$i+1;
			
			if($dani>$prejeti)
			{
					$sql="UPDATE tekme SET odigrane='odigrane'+1,ura='$cas',dani_goli='$prejeti',prejeti_goli='$dani',porazi='1',tocke='0' WHERE id='$id1'";
					mysqli_query($link,$sql);
					
					$sql="UPDATE tekme SET odigrane='odigrane'+1,ura='$cas',dani_goli='$dani',prejeti_goli='$prejeti',zmage='1',tocke='3' WHERE id='$id2'";
					mysqli_query($link,$sql);
			}
			else if($dani<$prejeti)
			{
					$sql="UPDATE tekme SET odigrane='odigrane'+1,ura='$cas',dani_goli='$prejeti',prejeti_goli='$dani',zmage='1',tocke='3' WHERE id='$id1'";
					mysqli_query($link,$sql);
					
					$sql="UPDATE tekme SET odigrane='odigrane'+1,ura='$cas',dani_goli='$dani',prejeti_goli='$prejeti',porazi='1',tocke='0' WHERE id='$id2'";
					mysqli_query($link,$sql);
			}
			
			else if($dani==$prejeti)
			{
					$sql="UPDATE tekme SET odigrane='odigrane'+1,ura='$cas',dani_goli='$dani',prejeti_goli='$prejeti',neodloceno='1',tocke='1' WHERE id='$id1'";
					mysqli_query($link,$sql);
					
					$sql="UPDATE tekme SET odigrane='odigrane'+1,ura='$cas',dani_goli='$prejeti',prejeti_goli='$dani',neodloceno='1',tocke='1' WHERE id='$id2'";
					mysqli_query($link,$sql);
			}
			$i++;
			$y--;
		}
			$query = "SELECT DISTINCT id_klub FROM tekme WHERE id_turnir='$id'"; 
			$result = mysqli_query($link,$query);
			while ($row = mysqli_fetch_array($result))
			{
				$query2 = "SELECT sum(odigrane),sum(zmage),sum(porazi),sum(neodloceno),sum(dani_goli),sum(prejeti_goli),sum(tocke) FROM tekme WHERE id_turnir='$id' and id_klub='{$row['id_klub']}'"; 
				$result2 = mysqli_query($link,$query2);
				$row2 = mysqli_fetch_array($result2);
				
				$sql="INSERT INTO tocke (id_turnir,id_klub,tocke,odigrane,zmage,porazi,neodloceno,dani_goli,prejeti_goli) VALUES ('$id','{$row['id_klub']}','{$row2['sum(tocke)']}','{$row2['sum(odigrane)']}','{$row2['sum(zmage)']}','{$row2['sum(porazi)']}','{$row2['sum(neodloceno)']}','{$row2['sum(dani_goli)']}','{$row2['sum(prejeti_goli)']}')";
				mysqli_query($link,$sql);
			}	
		header('Location: index.php?stran=izidi&id='.$id);
	}
	
	
return $status;
close_database_connection($link);
}

function mojiNasloviTurnirov()
{
	$link=open_database_connection();
	
	$username=$_SESSION['uporabnik'];
	$query = "SELECT id FROM uporabniki WHERE username='$username'"; 
	$result = mysqli_query($link,$query);
	$row = mysqli_fetch_array($result);
	$IdUporabnika=$row['id'];
		
		$query = "SELECT * FROM turniri WHERE idUporabnika='$IdUporabnika' ORDER BY datumCas ASC"; 
		$result = mysqli_query($link,$query);	
		$naslovi = array();
		while ($row = mysqli_fetch_array($result))
			{
				array_push($naslovi, $row);
			}
	
	close_database_connection($link);
	return $naslovi;
}

function vsiNasloviTurnirov()
{
	$link=open_database_connection();
		
		$query = "SELECT * FROM turniri ORDER BY datumCas ASC"; 
		$result = mysqli_query($link,$query);	
		$naslovi = array();
		while ($row = mysqli_fetch_array($result))
			{
				array_push($naslovi, $row);
			}
	
	close_database_connection($link);
	return $naslovi;
}

function brisiTurnir($id)
{
	$status="";
	$link=open_database_connection();
	
	$query = "DELETE FROM turniri WHERE id='$id'"; 
	$result = mysqli_query($link,$query);
	
	$status="Turnir je bil izbrisan.";
	session_start();
	$_SESSION['notice'] = $status;
	
	header('Location: index.php?stran=turniri');
	
	close_database_connection($link);
}

function urediTurnir($id,$orignal_ime)
{
	$idklub=array();
	$array=array();
	$stevec=0;
	$status="";
	$link=open_database_connection();
	$username=$_SESSION['uporabnik'];
	$query = "SELECT id FROM uporabniki WHERE username='$username'"; 
	$result = mysqli_query($link,$query);
	$row = mysqli_fetch_array($result);
	$IdUporabnika=$row['id'];
	
	/******* CHECKBOXE ********/
	$query2 = "SELECT * FROM turnir_klub where id_turnir='$id' "; 
	$result2 = mysqli_query($link,$query2);
	while ($row2 = mysqli_fetch_array($result2))
	{
		$idklub[]=$row2['id_klub'];
	}
	
	$query = "SELECT * FROM klubi WHERE idUporabnika='$IdUporabnika' ORDER BY imeKluba ASC"; 
	$result = mysqli_query($link,$query);
	
	while ($row = mysqli_fetch_array($result))
	{
		echo "<div class=\"klub\">".$row['imeKluba'];
		echo "<br/><br/>";
		$path=$row['imeLogo'];
		if($path=="")
			echo "<img src='../uploads/noimage.png' />";
		else
			echo "<img src='../uploads/$path' />";
		echo "<br/>";
		
		if(in_array($row['id'],$idklub))
		{
			echo "<input type=\"checkbox\" name=\"check_list[]\" value='{$row['imeKluba']}' checked >";
		}
		else
		{
			echo "<input type=\"checkbox\" name=\"check_list[]\" value='{$row['imeKluba']}'>";
		}
		echo "<br/><br/>";
		echo "</div>";
		
		$array[] = $row['imeKluba'];
	}
	echo "<a id=\"add\" onclick=\"showLoginForm()\">";
	echo "<div onclick=\"showLoginForm()\" class=\"dodaj\"><br/><br/><br/><img src='icons/plus.png'/><br/></div>";
	echo "</a>";
	
	
	/******* CHECKBOX-E ********/
	
	/*****  IZBRIS KLUBA *****/
	if(isset($_POST['gumb1']))
	{
		if(!empty($_POST['check_list']))
		{
			foreach($_POST['check_list'] as $klub)
			{
				for($i=0;$i<count($array);$i++)
				{
					if ($klub == $array[$i])
					{
						$key=$array[$i];
						$query = "DELETE FROM klubi WHERE imeKluba='$key'"; 
						$result = mysqli_query($link,$query);
						
						$status="Klub je bil izbrisan.";
					    session_start();
						$_SESSION['notice'] = $status;
					}
				}
		    }
	    }
	}
	/*****  IZBRIS KLUBA *****/

	/*****  UREJANJE TURNIRJA  *****/
	if(isset($_POST['gumb0']))
	{
		if(trim($_POST['imeTurnira'])=="")
		{
			$status="Niste vnseli imena za turnir.";
			$_SESSION['notice_error'] = $status;
			header("refresh: 0;");
			return false;
		}
		
		$imeTurnira=strip_tags($_POST['imeTurnira']);
		
		$sql="SELECT id FROM turniri WHERE imeTurnira='$imeTurnira' ";
		$poizvedba=mysqli_query($link,$sql);
		$stevilopoizvedbe=mysqli_num_rows($poizvedba);
		
		if($stevilopoizvedbe==1 && $imeTurnira!=$orignal_ime)
		{
			$status="Turnir z tem imenom že obstaja.";
			$_SESSION['notice_error'] = $status;
			header("refresh: 0;");
			return false;
		}
		if(!empty($_POST['check_list']))
		{
			foreach($_POST['check_list'] as $klub)
			{
				$stevec++;
		    }
	    }
		
		if($stevec<3)
		{
			$status="Za turnir so potrebni vsaj 3 klubi.";
			$_SESSION['notice_error'] = $status;
			header("refresh: 0;");
			return false;
		}
		
		if(trim($_POST['datumcas'])!="")
		{
			$originalDate =strip_tags($_POST['datumcas']);
			$newDate = date("Y-m-d H:i:00", strtotime($originalDate));
			
			if($originalDate<$date)
			{
				$status="Napačen datum oz. čas.";
				$_SESSION['notice_error'] = $status;
				header("refresh: 0;");
				return false;
			}
		}
		else
		{
			$newDate=date("0000-00-01 00:00:00");
		}
		
		$kraj=strip_tags($_POST['kraj']);
		$sql="UPDATE turniri SET imeTurnira='$imeTurnira',idUporabnika='$IdUporabnika',datumCas='$newDate',kraj='$kraj' WHERE id='$id'";
		mysqli_query($link,$sql);
		
		$sql="DELETE FROM turnir_klub WHERE id_turnir='$id'";
		mysqli_query($link,$sql);

		if(!empty($_POST['check_list']))
		{
			foreach($_POST['check_list'] as $klub)
			{
				for($i=0;$i<count($array);$i++)
				{
					if ($klub == $array[$i])
					{
						$query = "SELECT id FROM turniri WHERE imeTurnira='$imeTurnira'"; 
						$result = mysqli_query($link,$query);
						$row = mysqli_fetch_array($result);
						$IdTurnira=$row['id'];
						$query = "SELECT id FROM klubi WHERE imeKluba='$array[$i]'"; 
						$result = mysqli_query($link,$query);
						$row = mysqli_fetch_array($result);
						$IdKluba=$row['id'];
						
						$sql="INSERT INTO turnir_klub (id_klub,id_turnir) VALUES ('$IdKluba','$IdTurnira')";
						mysqli_query($link,$sql);
						header('Location: index.php?stran=turniri');
						
						$status="Turnir je bil spremenjen.";
						session_start();
						$_SESSION['notice'] = $status;
					}
				}
		    }
	    }
	}
	/*****  UREJANJE TURNIRJA  *****/
	close_database_connection($link);
}

class prejsnjaVsebina {  
    public $vsebina = array();
    
    public function loadme($id) {
		$link=open_database_connection();
		$query = "SELECT imeTurnira,datumCas,kraj FROM turniri WHERE id='$id'"; 
		$result = mysqli_query($link,$query);
		$row = mysqli_fetch_array($result);
		
        $this->vsebina['ime'] =  $row['imeTurnira'];
        $this->vsebina['datum_cas'] = $row['datumCas'];
        $this->vsebina['kraj'] = $row['kraj'];
        return $this->vsebina;
        close_database_connection($link);
    }
}

function izidi($id)
{
	$j=0;
	$ura=array();
	$klub=array();
	$dani=array();
	$prejeti=array();
	$link=open_database_connection();
	
	echo "<div class='toper'><button class=\"print-link no-print\"><img src=\"icons/fileprint.png\" alt=\"printing\"></button></div>";
	
	echo "<table class=\"izidi\">";
	echo "<tr>
			<th>Mesto</th>
			<th>Klub</th>
			<th>Tekme</th>
			<th>Zmage</th>
			<th>Porazi</th>
			<th>Neodloceno</th>
			<th>Goli</th>
			<th>Tocke</th>
		</tr>";
		
	$query = "SELECT imeTurnira FROM turniri WHERE id='$id'"; 
	$result = mysqli_query($link,$query);
	$row = mysqli_fetch_array($result);
	$imeTurnira=$row['imeTurnira'];	
		
	$query = "SELECT id_klub,odigrane,zmage,porazi,neodloceno,dani_goli,prejeti_goli,tocke FROM tocke WHERE id_turnir='$id' ORDER BY tocke DESC"; 
	$result = mysqli_query($link,$query);
	while ($row = mysqli_fetch_array($result))
	{
		$query2 = "SELECT imeKluba,imeLogo FROM klubi WHERE id='{$row['id_klub']}'"; 
		$result2 = mysqli_query($link,$query2);
		$row2 = mysqli_fetch_array($result2);
		$imekluba=$row2['imeKluba'];
		$path=$row2['imeLogo'];
		
		$j=$j+1;
			echo "<tr>".
				"<td>". $j ."</td>". //mesto
				"<td>"; 
				if($path=="")
					echo "<img class=\"small\" src='../uploads/noimage.png' />";
				else
					echo "<img class=\"small\"src='../uploads/$path' />";
				echo $imekluba ."</td>". //klub
				"<td>". $row['odigrane']."</td>". //tekme
				"<td>". $row['zmage']."</td>". //zmage
				"<td>". $row['porazi']."</td>". //porazi
				"<td>". $row['neodloceno']."</td>". //neodloceno
				"<td>". $row['dani_goli'].":".$row['prejeti_goli']."</td>". //goli
				"<td>". $row['tocke']."</td>". //tocke
			"</tr>";
	} 
		
	echo "</table>";
	echo "<br/><br/>";
	
	echo "<table class=\"tekme\">";
			echo "<tr><th colspan=\"2\">$imeTurnira</th></tr>";
			
			$query = "SELECT sprememba,imeTurnira FROM turniri WHERE id='$id'"; 
			$result = mysqli_query($link,$query);
			$row = mysqli_fetch_array($result);
			$sprememba=$row['sprememba'];
			
			if($sprememba==1)
			{
				$query = "SELECT id_klub,dani_goli,prejeti_goli,ura,tekme FROM tekme WHERE id_turnir='$id' ORDER BY tekme ASC"; 
				$result = mysqli_query($link,$query);
				while($row = mysqli_fetch_array($result))
				{
					$query2 = "SELECT imeKluba FROM klubi WHERE id='{$row['id_klub']}'"; 
					$result2 = mysqli_query($link,$query2);
					$row2 = mysqli_fetch_array($result2);
					
					$ura[]=strtotime($row['ura']);
					$klub[]=$row2['imeKluba'];
					$dani[]=$row['dani_goli'];
					$prejeti[]=$row['prejeti_goli'];
					$tekme=$row['tekme'];
				}
				$tekme=$tekme*2;
				for($i=0;$i<$tekme;$i++)
				{
				$x=$i+1;
					echo "<td width='50px'>".date("H:i",$ura[$i])."</td>"."<td>";
					echo "<div class='levi'>"."<span class='premikl'>".$klub[$i]."</span>".' '.$dani[$i].'</div>'." - "."<div class='desni'>".$prejeti[$i].' '."<span class='premikd'>".$klub[$x]."</span>".'</div>';
					echo "</td>";
					echo "</tr>";
				$i++;
				}
			}
	echo "</table>";
	
	close_database_connection($link);
}

function naslovturnira($id)
{
	$link=open_database_connection();
	
	$query = "SELECT imeTurnira FROM turniri WHERE id='$id'"; 
	$result = mysqli_query($link,$query);
	$row = mysqli_fetch_array($result);
	$naslov=$row['imeTurnira'];
	close_database_connection($link);
	echo $naslov;
}
?>

