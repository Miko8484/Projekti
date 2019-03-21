<?php
$my_time1=0;
$my_time2=0;
$my_time3=0;
$my_time4=0;
$my_time5=0;
$my_time6=0;
$my_time7=0;
$root=$_SERVER['DOCUMENT_ROOT'];
require_once ("$root/models/game.php" );
require_once ("$root/connection.php");
if (isset($_GET["x"]) and isset($_GET["y"])) {
    $x = $_GET["x"];
    $y = $_GET["y"];
    $database = new database("","");
    $goblin_tower=$database->goblin_tower;

    //fix tou se znovak refresha pri multiplayer ker neveš ka je špilo
    $_SESSION["player_0"] = new player_GT(0, "player_0", 0 ,0, 0, 0, 0, 0, "grey", "neutral");
    $_SESSION["game"] = game_GT::database_construct($_SESSION["game"]->id);
    $_SESSION["board"] = $_SESSION["game"]->board;
    $_SESSION["player_1"] = $_SESSION["game"]->player_1;
    $_SESSION["player_2"] = $_SESSION["game"]->player_2;

    for ($once = 0; $once < 1; $once++) {
        $result = array(0, 0, 0);
        //0->  if (1) was played, 0 not played < errors
        //1-> player_2 play
        //2-> winner? // 1 for p1 2 for p2
        //3 x,y

        $logged_player=$_SESSION["logged_player"];
        $other_player=$_SESSION["other_player"];

        $game_name = $_SESSION["game"]->name;
        $game = $_SESSION['game'];
     //   echo "test ".$logged_player->id." ".$database->check_on_turn($game)." <br>";
        if ($logged_player->id != $database->check_on_turn($game)) {//check if allowed to place
            $result[0] = -2;//not on turn
            break;
        }
        if ($_SESSION['board']->check_requirement($x, $y, $logged_player) == false) {

            $result[0] = -1;//illegal move
            break;
        }
        $tower_index = 1;
        $result[0] = 1;
        $_SESSION["game"]->on_turn = $other_player->id;
        $_SESSION["game"]->place($x, $y, $tower_index, $logged_player, $other_player);

        if (empty($_SESSION["player_2"]->options_priority_desc)) {
            $result[2] = $logged_player->id;
            $winner = $logged_player->name;
            game_GT::end_game_ranked($_SESSION["game"],$logged_player,$other_player);
        }
        $result[3]="$x"."_$y";
        $result[4]=$logged_player->id;
        $mysql_result = json_encode($result);
        mysqli_query($goblin_tower, "UPDATE ongoing_games SET last_play='$mysql_result' WHERE name='$game_name'") or die (mysqli_error($goblin_tower));
    }
    echo json_encode($result);
}
?>
