<?php

//zelo enostaven kontroler, za statični prikaz vsebin
class strani_controller
{

    //akcija home, ki ne potrebuje pravega modela, ampak samo nastavi fiksne vrednosti spremenljivk, ki jih view prikaže
    public function home()
    {
        require_once('views/strani/home.php');
    }

    public function register()
    {
        $msg = "";
        $firstname = "";
        $lastname = "";
        $username = "";
        $email = "";
        $password = "";
        $password2 = "";
        require_once('views/account/register.php');
    }

    public function login()
    {
        $msg = "";
        require_once('views/account/login.php');
    }

    public function about()
    {
        require_once('views/strani/about.php');
    }
	public function contact()
	{
		contact::send();
		require_once('views/strani/contact.php');
	}

    //akcija napaka, ki naloži view z obvestilom o napaki
    public function napaka()
    {
        require_once('views/strani/napaka.php');
    }
	
	public function forum_edit()
	{
		require_once('models/forum.php');
		$id=$_GET['id'];
		$topic=forum::about($id);
		require_once('views/forum/edit_topic.php');
	}
}

?>