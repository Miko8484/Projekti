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
    $game = $_SESSION["game"];
    $player_1=$game->player_1;
    $player_2=$game->player_2;
    $board =$game->board;
    $database = new database("","");
    $x = $_GET["x"];
    $y = $_GET["y"];
    for ($once = 0; $once < 1; $once++) {
        $result = array(0, 0, 0);
        $game_name = $game->name;
        $goblin_tower = database::get_GTdb();
        $goblin_tower_games = database::get_GT_games_db();
        //0->  if (1) was played, 0 not played < errors
        //1-> player_2 play
        //2-> winner? // 1 for p1 2 for p2
        create_pattern_ai_function($goblin_tower, $player_2->pattern_ai);
        $player_1_race = $player_1->race;
        $tower_index = 1;
        if ($game->on_turn == $player_1->id) {//check if allowed to place
            if ($board->check_requirement($x, $y, $player_1) == false) {
                $result[0] = -1;//illegal move
                break;
            } else {
                $result[0] = 1;
                $game->on_turn = $player_2->id;
                $game->place($x, $y, $tower_index, $player_1, $player_2);
            }
        } else {
            $result[0] = -2;//not on turn
            break;
        }
        if (empty($player_2->options_priority_desc)) {
            $result[2] = 1;
            $winner = $player_1->name;
            game_GT::end_game_ranked($game,$game->player_1,$game->player_2);
        } else if ($game->on_turn == $player_2->id) {
            $learn_mode_on = false;
            $total_possible_moves_both_players = $board->size[0] * $board->size[1];
            $brute_force_deepness = $player_2->brute_force_deepness;
            $time_start = microtime(true);//time
            $result1 = to_the_future($brute_force_deepness, 0, $board, $player_2, $player_1, $total_possible_moves_both_players, $learn_mode_on);
            $time_end = microtime(true);//time
            $exec = ($time_end - $time_start);//time
            $x = $result1[0];
            $y = $result1[1];
            $game->place($x, $y, board_GT::tent, $player_2, $player_1);
            $game->on_turn = $player_1->id;
            $result[1] = $result1;
        } else {
            $result[0] = -4;//not on turn
            break;
        }
        if (empty($player_1->options_priority_desc)) {
            $result[2] = 2;
            $winner = $player_2->name;
            // fix funkcija pri goblin_db more to biti
            game_GT::end_game_ranked($game,$game->player_2,$game->player_1);
        }
    }
    echo json_encode($result);
}


?>