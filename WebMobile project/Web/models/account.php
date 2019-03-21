<?php
//model nam služi kot objekt, s pomočjo katerega definiramo obliko podatkov, za potrebe obdelave in prikaza
//hkrati pa nam služi kot razred, preko katerega s statičnimi metodami lahko beremo sezname objektov ter posamične objekte iz trajne hrambe (baza) in jih tja tudi dodajamo


class account
{
    //struktura podatkov kot razredne spremenjivke
    //    $id,    $firstname,    $lastname, $username, $email, $password, $naslov, $posta, $telefonska_stevilka, $starost, $spol, $is_admin,
    public $id;
    public $firstname;
    public $lastname;
    public $username;
    public $email;
    public $password;
    public $is_admin;
	public $image;

    //konstrutkor, ki nam ustvari novi primerek razreda
    public function __construct($id, $firstname, $lastname, $username, $email, $password, $is_admin, $file)
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->is_admin = $is_admin;
		$this->image = $file;
    }

    //funkcija, ki vrne polje vseh objektov, ki so trajno shranjeni v bazi
    public static function vsi()
    {
        //prazno polje
        $list = [];
        //dobimo mysqli_connect objekt
        $db = Db::getInstance();
        //izvedemo query
        $result = mysqli_query($db, 'SELECT * FROM account');
        //v zanki ustvarjamo posamične objekte
        while ($row = mysqli_fetch_assoc($result)) {
            //dodajamo jih na konec polja
            $list[] = new account($row['id'], $row['firstname'], $row['lastname'], $row['username'], $row['email'], $row['password'], $row['naslov'], $row['posta'], $row['telefonska_stevilka'], $row['starost'], $row['spol'], $row['is_admin']);
        }
        //vrnemo polje
        return $list;
    }

    //poiščemo spefični account glede na id
    public static function najdi($id)
    {
        //preverimo, da je id v številski obliki
        $id = intval($id);
        //izvedemo poizvedbo
        $db = Db::getInstance();
        $result = mysqli_query($db, "SELECT * FROM account where id=$id");
        $row = mysqli_fetch_assoc($result);
        //ker pričakujemo samo en rezultat, vrnemo en objekt razreda account
        return new account($row['id'], $row['firstname'], $row['lastname'], $row['username'], $row['email'], $row['password'], $row['is_admin'], $row['image']);
    }

    public static function register($firstname, $lastname, $username, $email, $password, $password2, $file){
        $db = Db::getInstance();

        $msg = "";
        for ($i = 0; $i < 1; $i++) {// function that always loops one time, and you can use break inside it

            $size = strlen($firstname);
            if ($size < defaults::min_name_length or $size > defaults::max_name_length) {
                $firstname = "";
                $msg = "Your first name should be between ".defaults::min_name_length." and ".defaults::max_name_length." characters!";
                break;
            }

            $size = strlen($lastname);
            if ($size < defaults::min_name_length or $size > defaults::max_name_length) {
                $lastname = "";
                $msg = "Your last name should be between ".defaults::min_name_length." and ".defaults::max_name_length." characters!";
                break;
            }

            $size = strlen($username);
            if ($size < defaults::min_username_length or $size > defaults::max_username_length) {
                $username = "";
                $msg = "Your username should be between ".defaults::min_username_length." and ".defaults::max_username_length." characters!";
                break;
            }

            $size = strlen($email);
            if ($size < defaults::min_email_length or $size > defaults::max_email_length) {
                $email = "";
                $msg = "Your email should be between ".defaults::min_email_length." and ".defaults::max_email_length." characters!";
                break;
            }

            $size = strlen($password);
            if ($size < defaults::min_password_length or $size > defaults::max_password_length) {
                $password = "";
                $msg = "Your password should be between ".defaults::min_password_length." and ".defaults::max_password_length." characters!";
                break;
            }

            if (!($password == $password2)) {
                $password = "";
                $password2 = "";
                $msg = "Your passwords don't match!";
                break;
            }

            $result = mysqli_query($db, "SELECT * FROM account WHERE username ='$username'");
            $num = mysqli_num_rows($result);
            if ($num != 0) {
                $username = "";
                $msg = "Your username: $username is already taken!";
                break;
            }
            $result = mysqli_query($db, "SELECT * FROM account WHERE email ='$email'");
            $num = mysqli_num_rows($result);
            if ($num != 0) {
                $email = "";
                $msg = "Your email $email is already taken!";
                break;
            }

            $msg ="You have registered succesfully";
            $password = $_POST["password"]; //md5()
            $password = md5($password);
            return self::add_account($firstname, $lastname, $username, $email, $password, 0, $file);
        }

        return $msg;
    }



    //funkcija za shranjevanje novega accounta v bazo
    public static function add_account($firstname, $lastname, $username, $email, $password, $is_admin, $file)
    {
        $db = Db::getInstance();
        //klasični način z lepljenjem
        // mysqli_query($db,"Insert into account (Naslov,Vsebina,DatumObjave) Values (\"$naslov\",\"$vsebina\",now())");
        //primer mysqli poizvedbe s prepared statements
        //namesto lepljenja poizvedbe, damo na mesto, kjer želimo parametre, ki jih posreduje uporabnik placeholderje - znak ?
        //ustvarimo objekt prepared statement, kateremu je potrebno namesto vprašajev, določiti vrednosti, s katerimi se poizvedba izvede

        if ($stmt = mysqli_prepare($db, " insert into account (firstname, lastname, username, email, password, is_admin, image)
                                          Values (              ?,          ?,          ?,      ?,      ?,        ?,        ?)")
        ) {
            //dodamo parametre v takšnem vrsnem redu, kot smo zapisali vprašaje
            //drugi argument je niz, ki mora imeti dolžino enako številu vprašajev (placeholderjev)
            //vsak znak niza predstavlja tip vrednosti, ki bo zamenjala vprašaje
            //uporabimo s za string, i za integer ,d za double in b za blob
            //v našem primeru, bomo v poizvedbo dodajanja poslali dva niza, zato je vrednost argumenta "ss"
            //nato naštejemo vrednosti
            mysqli_stmt_bind_param($stmt, "sssssis", $firstname, $lastname, $username, $email, $password, $is_admin, $file);
            //izvedemo poizvedbo
            mysqli_stmt_execute($stmt);
            //če smo izvedli poizvedbo, kjer pričakujemo rezultate, potem lahko te dobimo na sledeč način:
            //$result=mysqli_stmt_get_result($stmt);
            //v praksi, lahko sedaj s bind_param parametre zamenjamo in spet pokličemo execute
            //tako lahko zelo efekitvno izvajamo npr. vstavljanje večje količine podatkov
            //na koncu vedno zapremo prepared statemenet
            mysqli_stmt_close($stmt);

        } else {
            echo mysqli_error($db);
        }
        //funkcija nam vrne id zadnjega zapisa, ki smo ga vstavili, v kolikor imamo v tabeli AUTO INCREMENT primarni ključ
        $id = mysqli_insert_id($db);
        //vrnemo objekt, ki predstavlja trenunto dodani account
        return account::najdi($id);
    }


    public static function user_login($username, $password)
    {

        $db = Db::getInstance();
        $result = mysqli_query($db, "SELECT * FROM account WHERE username ='$username' AND password='$password'");

        if (mysqli_num_rows($result) > 0) {
            $result = mysqli_fetch_array($result);
            $_SESSION['logged_account'] = new account($result['id'], $result['firstname'], $result['lastname'], $result['username'], $result['email'], $result['password'],$result['is_admin'],$result['image']);
            return $_SESSION['logged_account'];
        } else {
            return 0;
        }
    }

    public static function about_user($id)
    {
        $db = Db::getInstance();

        $result = mysqli_query($db,"SELECT id,firstname,lastname,username,email,password,is_admin,image FROM account WHERE id='$id'");
        $row = mysqli_fetch_assoc($result);
        $user = new account($row['id'], $row['firstname'], $row['lastname'],$row['username'],$row['email'],$row['password'],$row['is_admin'],$row['image']);
        return $user;
    }

    public static function edit($id)
    {
        $status="";
        if(isset($_POST['confirm_button']))
        {
            $db = Db::getInstance();
            $username=$_POST['username'];
            $firstname=$_POST['firstname'];
            $lastname=$_POST['lastname'];
            $email=$_POST['email'];

            if(isset($_POST['password']) && $_POST['password']!="")
            {
                if(isset($_POST['password2']) && $_POST['password2']!="")
                {
                    if($_POST['password'] == $_POST['password2'])
                    {
						$password=$_POST['password'];
						$password=md5($password);
						if (empty($_FILES['Filename']['name']))
						{
							$sql="UPDATE account SET firstname='$firstname',lastname='$lastname',username='$username',email='$email',password='$password' WHERE id='$id'";
							mysqli_query($db,$sql);
							$status="Data changed";
							return $status;
						}
						else
						{
							$ime=sha1(date('Y-m-d H:i:s'));
							$target = "user_image/";
							$target = $target . basename( $_FILES['Filename']['name']);
							$file_type = $_FILES['Filename']['type'];
							$allowed = array("image/jpeg", "image/png");
							$filename = $_FILES["Filename"]["name"];
							$file_basename = substr($filename, 0, strripos($filename, '.'));
							$file_ext = substr($filename, strripos($filename, '.')); 
							$newfilename = $file_basename . $ime . $file_ext;
							if(in_array($file_type, $allowed)) 
							{
								if(move_uploaded_file($_FILES['Filename']['tmp_name'], "user_image/" . $newfilename))
								{
									$sql="UPDATE account SET firstname='$firstname',lastname='$lastname',username='$username',email='$email',password='$password', image='$newfilename' WHERE id='$id'";
									mysqli_query($db,$sql);
									$status="Data changed";
									return $status;
								}
								else
								{
									$status="There was an error, please try again";
									return $status;
								}
							}
							else
							{
								$status="File must be image in .jpeg or .png format";
								return $status;
							}
						}
                    }
                    else
                    {
                        $status="Passwords doesn't match";
                        return $status;
                    }
                }
                else
                {
                    $status="You need to insert both passwords";
                    return $status;
                }
            }
            else
            {
				if (empty($_FILES['Filename']['name']))
				{
					$sql="UPDATE account SET firstname='$firstname',lastname='$lastname',username='$username',email='$email' WHERE id='$id'";
					mysqli_query($db,$sql);
				}
				else
				{
					$ime=sha1(date('Y-m-d H:i:s'));
					$target = "user_image/";
					$target = $target . basename( $_FILES['Filename']['name']);
					$file_type = $_FILES['Filename']['type'];
					$allowed = array("image/jpeg", "image/png");
					$filename = $_FILES["Filename"]["name"];
					$file_basename = substr($filename, 0, strripos($filename, '.'));
					$file_ext = substr($filename, strripos($filename, '.')); 
					$newfilename = $file_basename . $ime . $file_ext;
					if(in_array($file_type, $allowed)) 
					{
						if(move_uploaded_file($_FILES['Filename']['tmp_name'], "user_image/" . $newfilename))
						{
							$sql="UPDATE account SET firstname='$firstname',lastname='$lastname',username='$username',email='$email',image='$newfilename' WHERE id='$id'";
							mysqli_query($db,$sql);
						}
					}
				}
            }
        }
    }

    public static function parse_news()
    {
        if(isset($_SESSION["logged_account"]) && $_SESSION["logged_account"]->is_admin==1)
        {
            $db = Db::getInstance();
            if(isset($_POST['reddit']))
            {
                $string_reddit = file_get_contents("https://www.reddit.com/r/technews/.json");
                $json = json_decode($string_reddit, true);

                $children = $json['data']['children'];
                foreach ($children as $child){
                    $title = $child['data']['title'];
                    $url = $child['data']['url'];
					$link= "<a style='text-decoration:underline;' href=".$url.">".$url."</a>";
					$text = mysqli_real_escape_string($db, $link);
                    $author = $child['data']['author'];
                    $date = date('Y-m-d H:i:s');

                    $sql = "INSERT INTO forum (ime_teme,vsebina,datum_objave,views,tk_uporabnik)
							SELECT * FROM (SELECT '$title','$text','$date','0','$author') AS tmp
							WHERE NOT EXISTS ( SELECT ime_teme FROM forum WHERE ime_teme = '$title') LIMIT 1;";
                    mysqli_query($db,$sql);
                }
            }
            if(isset($_POST['computernews']))
            {
                global $articles;

                $html = new simple_html_dom();
                $html->load_file("http://www.computerworld.com/news/");

                $items = $html->find('div[class=river-well article]');

                foreach($items as $post) {
                    $articles[] = array($post->children(0)->first_child()->href,
                        $post->children(1)->children(1)->children(0)->plaintext);


                }

                foreach($articles as $article)
                {
                    $link_address2 = $article[0];

                    $html1 = new simple_html_dom();
                    $html1->load_file('http://www.computerworld.com/'.$link_address2);
                    $items1 = $html1->find('div[id=drr-container]');
                    $content="";
                    foreach($items1 as $post1) {
                        $content = $content.$post1;
                    }

                    $header = $article[1];
                    $date = date('Y-m-d H:i:s');

                    $text = mysqli_real_escape_string($db, $content);


                    $sql = "INSERT INTO forum (ime_teme,vsebina,datum_objave,views,tk_uporabnik)
							SELECT * FROM (SELECT '$header','$text','$date','0','Admin') AS tmp
							WHERE NOT EXISTS ( SELECT ime_teme FROM forum WHERE ime_teme = '$header') LIMIT 1;";
                    mysqli_query($db,$sql);
                }
            }
        }
        else
            header('Location: index.php');
    }
	
}

class game_account {
    public $id,$username,$file,$number_of_games,$number_of_wins,$number_of_loses,$id_of_current_game,$campaign_level,$elo;
    public function __construct($id,$username, $file, $number_of_games,$number_of_wins,$number_of_loses,$id_of_current_game,$campaign_level,$elo)
    {
        $this->id=$id;
        $this->username=$username;
		$this->image=$file;
        $this->number_of_games=$number_of_games;
        $this->number_of_wins=$number_of_wins;
        $this->number_of_loses=$number_of_loses;
        $this->id_of_current_game=$id_of_current_game;
        $this->campaign_level=$campaign_level;
        $this->elo=$elo;
    }
    public static function find_by_id($id)
    {
        $id = intval($id);
        $db = Db::getInstance();
        $result = mysqli_query($db, "SELECT id,username,image,number_of_games,number_of_wins,number_of_loses,id_of_current_game,campaign_level,elo FROM account where id=$id") or die(mysqli_error($db));
        $row = mysqli_fetch_assoc($result);
        return new game_account($row['id'],$row['username'],$row['image'], $row['number_of_games'], $row['number_of_wins'], $row['number_of_loses'], $row['id_of_current_game'],$row['campaign_level'],$row['elo']);
    }
    public static function find_by_username($username)
    {
        $db = Db::getInstance();
        $result = mysqli_query($db, "SELECT id,username,image,number_of_games,number_of_wins,number_of_loses,id_of_current_game,campaign_level,elo FROM account where username='$username'") or die(mysqli_error($db));
        $row = mysqli_fetch_assoc($result);
        return new game_account($row['id'],$row['username'],$row['image'], $row['number_of_games'], $row['number_of_wins'], $row['number_of_loses'], $row['id_of_current_game'],$row['campaign_level'],$row['elo']);
    }

    public static function vsi_elo_desc()
    {
        //prazno polje
        $list = [];
        //dobimo mysqli_connect objekt
        $db = Db::getInstance();
        //izvedemo query
        $result = mysqli_query($db, 'SELECT * FROM account order by elo DESC ');
        //v zanki ustvarjamo posamične objekte
        while ($row = mysqli_fetch_assoc($result)) {
            //dodajamo jih na konec polja
            $list[] = new game_account($row['id'],$row['username'],$row['image'], $row['number_of_games'], $row['number_of_wins'], $row['number_of_loses'], $row['id_of_current_game'],$row['campaign_level'],$row['elo']);
        }
        //vrnemo polje
        return $list;
    }

}

class ionic_account
{
    public $id;
    public $username;
    public $number_of_games;
    public $number_of_wins;
    public $number_of_loses;
    public $elo;

    //konstrutkor, ki nam ustvari novi primerek razreda
    public function __construct($id, $username, $games, $wins, $loses, $elo)
    {
        $this->id = $id;
        $this->username = $username;
        $this->number_of_games = $games;
        $this->number_of_wins = $wins;
        $this->number_of_loses = $loses;
        $this->elo = $elo;
    }
	
	public static function allUsers()
	{
        $list = [];
        $db = Db::getInstance();
        $result = mysqli_query($db, 'SELECT id,username,number_of_games,number_of_wins,number_of_loses,elo FROM account ORDER BY elo DESC');
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = new ionic_account($row['id'],$row['username'], $row['number_of_games'], $row['number_of_wins'], $row['number_of_loses'], $row['elo']);
        }
        return $list;
	}
	
	public static function exsists($username,$password)
	{
		$db = Db::getInstance();
		$result = mysqli_query($db, "SELECT id,image FROM account WHERE username='$username' AND password='$password'");
		$rowcount=mysqli_num_rows($result);
		$row = mysqli_fetch_assoc($result);
		if($rowcount>0)
			return $row;
		else
			return "False";
	}
	
	public static function user_info($username)
	{
		$db = Db::getInstance();
		$result = mysqli_query($db, "SELECT id,firstname,lastname,username,email,image,number_of_games,number_of_wins,number_of_loses,elo FROM account WHERE username='$username'");
		$row = mysqli_fetch_assoc($result);
		$rowcount=mysqli_num_rows($result);
		if($rowcount==0)
		{
			$arr = array('username' => 'yyxx!!!%%%lll');
			return $arr;
		}
		else
			return $row;
	}
	
	public static function editUser1($id,$firstname,$lastname,$username,$email)
	{
		$db = Db::getInstance();
		$sql="UPDATE account SET firstname='$firstname',lastname='$lastname',username='$username',email='$email' WHERE id='$id'";
		mysqli_query($db,$sql);
		if(mysqli_error($db))
		{
			return mysqli_error($db);
		}
		else
			return ($firstname);
	}
	
	public static function editUser2($id,$firstname,$lastname,$username,$email,$password)
	{
		$db = Db::getInstance();
		$sql="UPDATE account SET firstname='$firstname',lastname='$lastname',username='$username',email='$email',password='$password' WHERE id='$id'";
		mysqli_query($db,$sql);
		if(mysqli_error($db))
		{
			return mysqli_error($db);
		}
		else
			return ($firstname);
	}
	
	public static function updateImage($id,$imageName)
	{
		$db = Db::getInstance();
		$sql="UPDATE account SET image='$imageName' WHERE id='$id'";
		mysqli_query($db,$sql);
	}
	
	public static function registerUser($username,$firstname,$lastname,$email,$password)
	{
		$db = Db::getInstance();
		
		$sql="INSERT INTO account (firstname,lastname,username,email,password) VALUES ('$firstname','$lastname','$username','$email','$password')";
		mysqli_query($db,$sql);
	}
}
