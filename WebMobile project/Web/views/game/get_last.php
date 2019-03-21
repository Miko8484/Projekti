<?php
$root=$_SERVER['DOCUMENT_ROOT'];
require_once ("$root/models/game.php" );
require_once ("$root/connection.php");
if (isset($_GET["game_id"]) ) {
    $game_id = $_GET["game_id"];
    $database = new database("","");
    $goblin_tower=$database->goblin_tower;

    $result = mysqli_query($goblin_tower, "SELECT last_play FROM ongoing_games where id='$game_id' limit 1") or die("mysql error here 1 1 3 44");
    $result = mysqli_fetch_assoc($result);

    echo ($result["last_play"]);
}

?>
