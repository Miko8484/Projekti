<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/connection.php");

if(isset($pattern_ai)){

$goblin_tower=mysqli_connect('localhost',$db_user,$db_pass,"goblin_tower") or die("Couldn't connect to database [".mysqli_errno($server)."]");

if(1==2){// defining function to avoid phpstorm errors
    function pattern_ai($board, $x_size, $y_size, $newx, $newy, $owners, $friendly, $enemy){}
}

$parameters="\$board, \$x_size, \$y_size, \$newx, \$newy, \$owners, \$friendly, \$enemy";

$pattern_ai_function = mysqli_query($goblin_tower, "SELECT * FROM pattern_ai WHERE pattern_ai='$pattern_ai'");
$pattern_ai_function = mysqli_fetch_assoc($pattern_ai_function);
$pattern_ai_function=$pattern_ai_function['function'];
$pattern_ai_function = "function pattern_ai($parameters){ $pattern_ai_function }";

eval($pattern_ai_function);



}

?>