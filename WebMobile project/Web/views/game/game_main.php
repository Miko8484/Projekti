<div class="text-center">
<?php

if (!isset($_SESSION["game_id"]) or (isset($_SESSION["game_id"]) and $_SESSION["game_id"] != $game_id) OR $_SESSION["game"]->game_mod =="player_vs_player") {// tu si dodao zadnji or ker je pri controlleri buggano ovači, lejko fixaš

    $_SESSION["lock"]=1;// game is not ready toe play

    $_SESSION["game_id"]=$game_id;

    $_SESSION["player_0"] = new player_GT(0, "player_0", 0 ,0, 0, 0, 0, 0, "grey", "neutral");
    $_SESSION["game"] = game_GT::database_construct($game_id);

    $_SESSION["board"] = $_SESSION["game"]->board;
    $_SESSION["player_1"] = $_SESSION["game"]->player_1;
    $_SESSION["player_2"] = $_SESSION["game"]->player_2;

    $_SESSION["lock"]=0;// game is ready to play
    echo "db cons";
}

$player_1=$_SESSION["game"]->player_1;
$player_2=$_SESSION["game"]->player_2;


if($player_1->name==$_SESSION["logged_account"]->username){
    $var_name1= "player_1";
    $var_name2= "player_2";
}else{
    $var_name1= "player_2";
    $var_name2= "player_1";
}
$_SESSION["logged_player"]=$$var_name1;
$_SESSION["other_player"]=$$var_name2;

echo $_SESSION["logged_player"]->name ." as ".$_SESSION["logged_player"]->color." vs ".$_SESSION["other_player"]->name." as ".$_SESSION["other_player"]->color;
echo $_SESSION["logged_player"]->id. " ". $_SESSION["other_player"]->id;


$player_1_race = $_SESSION["player_1"]->race;
$player_2_race = $_SESSION["player_2"]->race;
$basic_tower="wall";

$player_2_basic_tower="wall";
$player_1_basic_tower="wall";


$player_2_game_id = $_SESSION["player_2"]->id;
$turn = $_SESSION["game"]->turn;
$on_turn = $_SESSION["game"]->on_turn;
$game_name = $_SESSION["game"]->name;
$game_mod = $_SESSION["game"]->game_mod;
$game_id = $_SESSION["game"]->id;

?>
<?php
include "game_mod/" . $game_mod . ".php";
?>

<p><?php echo " game mod: " . $game_mod; ?></p>
<p>on turn: <span id="on_turn"><?php echo $on_turn; ?></span> turn:<span id="turn"><?php echo $turn; ?></span>
</p><!-- fix što je navrsti, število potez, turn keri je -->

<?php
$_SESSION["game"]->draw_board();
echo $_SESSION["game"]->check_end()[0];

?>

<?php include("menu_item/admin_panel.php"); ?>

</div>







































