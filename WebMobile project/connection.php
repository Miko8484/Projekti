<?php
date_default_timezone_set('Europe/Ljubljana');
require_once ("models/account.php");
require_once ("defaults.php");

require_once('routes.php');

session_start();
$server_addr="http://".$_SERVER["SERVER_NAME"];
$root=$_SERVER['DOCUMENT_ROOT'];

class Db {
    //razredna spremenljivka, ki nam predstavlja objekt, potreben za povezavo na bazo
    private static $instance = NULL;


    //razredna funkcija, s katero bomo pridobili ta objekt
    public static function getInstance() {
        //če še objekt ni inicializiran, ga nastavimo
        if (!isset(self::$instance)) {
            //glede na vašo podatkovno bazo, morate tukaj ustrezno spremeniti podatke za prijavo
            self::$instance = mysqli_connect("164.8.230.118", "clan1", "projekt123", "projekt");
        }
        //vrnemo objekt, da lahko nato izvajamo povpraševanja na bazo
        return self::$instance;
    }
}
function mysqli_error_bluecat($pathinfo,$line,$server){
    $string = "ERROR in <b>".$pathinfo."</b> on line <b>".$line." </b>";
    if(isset($server)) $string=$string.mysqli_error($server);
    return $string;
}

if (!empty($_SESSION['login'])){
    $username=$_SESSION['username'];
    $account=mysqli_query($server,"SELECT * FROM account WHERE username='$username'");
    $account=mysqli_fetch_assoc($account);

}
?>