<?php
session_start();

class asd{
    public $asd =0;
}

$_SESSION["arr1"]=new asd();
$_SESSION["arr1"]->asd=3;

$new_arr=$_SESSION["arr1"];

$new_arr->asd=2;

echo $_SESSION["arr1"]->asd;


?>