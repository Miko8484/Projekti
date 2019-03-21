<?php
if(isset($root))
include("$root/defaults.php");

class account_controller
{


    public function register()
    {

        $db = Db::getInstance();

        $firstname = mysqli_escape_string($db,$_POST['firstname']);
        $lastname = mysqli_escape_string($db,$_POST['lastname']);
        $username = mysqli_escape_string($db,$_POST['username']);
        $email = mysqli_escape_string($db,$_POST['email']);
        $password = mysqli_escape_string($db,$_POST['password']);
        $password2 = mysqli_escape_string($db,$_POST['password2']);
		
		if (empty($_FILES['Filename']['name']))
		{
			$file = "user-default.png";
			$msg = account::register($firstname, $lastname, $username, $email, $password, $password2, $file);
			if(is_object($msg)){
				$account=$msg;
				$msg = "Registered successfully";
			}
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
					$msg = account::register($firstname, $lastname, $username, $email, $password, $password2, $newfilename);
					if(is_object($msg)){
						$account=$msg;
						$msg = "Registered successfully";
					}
				}
			}
		}

            
        require_once('views/strani/message.php');
    }

    public function login()
    {
        $msg = "";
        $username = $_POST["username"];
        $password = $_POST["password"]; //md5()
        $password = md5($password);
        if ($username == '' || $password == '') {
            $msg = "You must enter all fields";
        } else {


            $bool = account::user_login($username, $password);
            if ($bool) {
				echo "<script>
						document.getElementById('left_info').innerHTML = '<span class=\"glyphicon glyphicon-user\"></span> Profile';
						document.getElementById('right_info').innerHTML = '<span class=\"glyphicon glyphicon-log-out\"></span> Logout';
					 </script>";
                $msg = "Login in successful";
                $firstname = $_SESSION["logged_account"]->firstname;
                $lastname = $_SESSION["logged_account"]->lastname;
                require_once('views/strani/home.php');
            } else {

                $msg = "Username and password do not match";
                require_once('views/account/login.php');
            }


            //naložimo pogled, ki potrjuje uspešnost dodajanja
        }
    }

    public function logout()
    {
		echo "<script>
				document.getElementById('left_info').innerHTML = '<span class=\"glyphicon glyphicon-user\"></span> Sign up';
				document.getElementById('right_info').innerHTML = '<span class=\"glyphicon glyphicon-log-in\"></span> Login';
			 </script>";
        session_destroy();
		require_once('views/strani//home.php');
    }

    public static function edit()
    {
		if(isset($_SESSION["logged_account"]))
		{
			$user=account::about_user($_SESSION["logged_account"]->id);
			$msg = account::edit($_SESSION["logged_account"]->id);
			require_once('views/account/edit_page.php');
		}
		else
			header('Location: index.php');
    }
	
	public function admin_settings()
	{
		if(isset($_SESSION["logged_account"]) && $_SESSION["logged_account"]->is_admin==1)
		{
			require_once('views/account/admin_page.php');
		}
		else
			header('Location: index.php');
	}
	
	public function parse_news()
	{
		include('simple_html_dom.php');
		account::parse_news();
		require_once('views/account/admin_page.php');
	}
	
	public function leaderboards()
	{
	    $accounts = game_account::vsi_elo_desc();
		require_once('views/account/leaderboards.php');
	}

    public function view_account()
    {
        $account = account::najdi($_GET["id"]);
        $game_account = game_account::find_by_id($_GET["id"]);
        require_once('views/account/view_game_account.php');
    }

    public function game_account_card()
    {
        $game_account = game_account::find_by_id($_SESSION["logged_account"]->id);
        require_once('views/account/game_account_card.php');
    }
	
	public function prikaziAPI($request,$input) {
		  if (!isset($request[1]))
		  {
			$users=ionic_account::allUsers();
			require_once('views/forum/JSON_list_users.php');
		  }
		  if(isset($request[1]) && $request[1] =="login_autho")
		  {
			  if(isset($request[2]) && isset($request[3]))
			  {
				  $exsists=ionic_account::exsists($request[2],$request[3]);
				  $arr = array('id' => $exsists);
			  }
			  else
				  $arr = array('id' => 'False');
			  
			  require_once('views/account/JSON_user_exsists.php');
		  }
		  else if(isset($request[1]))
		  {
			  $user_info=ionic_account::user_info($request[1]);
			  require_once('views/account/JSON_user_info.php');
		  }
		  
    }
	
	public function posodobiAPI($request,$input){
		if (!isset($request[1]))
	    {
			if(!isset($input->password))
				$user=ionic_account::editUser1($input->id,$input->firstname,$input->lastname,$input->username,$input->email);
			else
				$user=ionic_account::editUser2($input->id,$input->firstname,$input->lastname,$input->username,$input->email,$input->password);
			//require_once('views/account/JSON_user_edit.php');
	    }
	}
	
	public function dodajAPI($request,$input){
		if(!isset($request[1]))
		{
			$user=ionic_account::registerUser($input->username,$input->firstname,$input->lastname,$input->email,$input->password);
		}
	}


}

?>