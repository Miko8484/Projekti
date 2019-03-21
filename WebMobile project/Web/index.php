<?php
	//dodamo statični razred za povezavo z podatkovno bazo

  //preberemo uporabnikov namen
  //naslov zahteve mora biti v obliki index.php?controller=xxx&action=xxx
  //v nasprotnem primeru javimo napako
  if (isset($_GET['controller']) && isset($_GET['action'])) {
    $controller = $_GET['controller'];
    $action     = $_GET['action'];
  } else {
    $controller = 'strani';
    $action     = 'home';
  }
require_once('connection.php');

  //naložimo obliko naše spletne strani
  require_once('views/layout.php');
?>