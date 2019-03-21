
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: *");
//dodamo razred za povezavo z bazo
// Allow from any origin

require_once('connection.php');
date_default_timezone_set('Europe/Ljubljana');
//podobna funkcija kot v routes, s tem da dodatno pošiljamo v funkcije kontrolerja dva argumenta
//prvi je reuqest, ki predstavlja polje ukazov v zahtevi
//drugi je input ki predstavlja objekt, ki je bil apiju posredovan v json obliki
//noben parameter ni obvezen
function call1($controller, $action,$request="",$input="") {
    require_once('controllers/' . $controller . '_controller.php');
	require_once('models/' . $controller . '.php');
    $o=$controller . "_controller";
	$controller=new $o;
	$controller->{ $action }($request,$input);
	
  }

 //prebermo metodo zahteve 
$method = $_SERVER['REQUEST_METHOD'];
//iz zahteve v obliki api.php/a/b/c/d/.. naredimo polje
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));

//na api se lahko pri zahtevi pošljejo tudi surovi podatki, te se prebere iz toka podatkov php://input
//api pričakuje podatke v json obliki
//rezultat json_decode je objekt, če damo na konec argument true, dobimo asociativno polje
$data = file_get_contents('php://input');
$input=json_decode($data);
parse_str($data,$input1);


// delete metode
/*if (isset($input['_method']) && $input['_method']=="DELETE") {
	$method = $input['_method'];
}*/

///controler je prvi argument v ukazu -> api.php/controller/x/y/z
$controller=$request[0];

//metoda zahteve nam določa katero akcijo bomo izvedli
//tukaj imamo ekvivalent strani routes.php, ki je precej poenostavljen
//v bolj zapletenih apijih je potrebno dodati malo več logike, ki kombinira vrsto metode ter podakte poslane v ukazu (pri nas $request), ter ustrezno kliče kontrolerje
switch ($method) {
		case 'GET':
		call1($controller,"prikaziAPI",$request,$input);
		break;
		case 'PUT':
		call1($controller,"posodobiAPI",$request,$input);
		break;
		case 'POST':
		call1($controller,"dodajAPI",$request,$input);
		break;
		case 'DELETE':
		call1($controller,"odstraniAPI",$request,$input);
		break;
	}
//primer ne vsebuje implementiranih vseh akcij, temveč samo prikaziAPI in dodajAPI




?>