<?php
/*
opis learna
nekaj->
trenutno velja:
če je boarda soda te glejda samo player 1 če je boarda liha glejda player 2
program se nafči samo deepness <=2
trentno ne velja:
(x*y /2) deepnees poteze
program se nafči samo prvo potezo nej pa kere zmes zmaga
remove $x_size $ysize board if exists
mogoče je error pri idijaj ka nejso sikdar povrsti mogli bi pa biti
dodaj še en id
*/
require_once("connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/goblin_tower/AI/ai_functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/goblin_tower/AI/bruteforce/brute_force_ai.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/goblin_tower/games_and_boards/create_game_functions.php");
$board_name = "";
if(!isset($_GET["x_size"]) or !isset($_GET["y_size"])){
    echo "x_size and y not set";
    exit();
}
$x_size=$_GET["x_size"];
$y_size=$_GET["y_size"];

$game = game_Db::getInstance();
$end_id = mysqli_query($game, "SELECT end_id FROM memory_moves_stats ORDER BY id DESC LIMIT 0, 1");
$end_id = mysqli_fetch_assoc($end_id);
$board_counter_start = $end_id['end_id'];
if ($board_counter_start == null) {
    $board_counter_start = 0;
}
$board_counter = $board_counter_start;
$test_move_counter = 0;
echo "board counter start" . $board_counter_start . "<br>";
$my_time1 = 0;
$my_time2 = 0;
$my_time3 = 0;
$my_time4 = 0;
$my_time5 = 0;
$my_time6 = 0;
$my_time_special = 0;
$add_test_move = 0;
$time_start = microtime(true);//time
create_boards($x_size, $y_size);
$time_end = microtime(true);//time
$exec = ($time_end - $time_start);//time
echo 'Total Execution Time:' . $exec . '<br>';
echo '$my_time1 (basic ai): ' . $my_time1 . '<br>';
echo '$my_time2 (place_local_sort_priority): ' . $my_time2 . '<br>';
echo '$my_time3 (find_ai_options_around_possition): ' . $my_time3 . '<br>';
echo '$my_time4 (sort priority to middle): ' . $my_time4 . '<br>';
echo '$my_time5 (find_nearby_fields): ' . $my_time5 . '<br>';
echo '$my_time_create_boards (create_boards): ' . ($exec - $my_time_special) . '<br>'; //zanemarljivo
echo '$my_time_insert_boards (add_test_move): ' . ($add_test_move) . '<br>';
echo '<br>';
function create_boards($x_size, $y_size)
{
    $game = game_Db::getInstance();
    $player_1_name="player_1";
    $player_2_name="player_2";
    $player_1_race = 'x';
    $player_2_race = 'x';
    $player_1_color = "red";
    $player_2_color = "blue";
    $player_1_minimal_priority = 1;
    $player_2_minimal_priority = 1;
    //   __construct()
    $player_0 = new player_GT(0, "player_0", null, null, null, null, null, null, "grey", "neutral");
    $pattern_ai = "function_return_1";//fix
    create_pattern_ai_function(database::get_GTdb(), $pattern_ai);//fix
    $player_1_pattern_ai = $pattern_ai;
    $player_2_pattern_ai = $pattern_ai;
    $future_deepness=100000;
    $player_1 = new player_GT (1, $player_1_name, null, null, $future_deepness, $player_1_minimal_priority, $player_1_pattern_ai, 2, $player_1_color, $player_1_race);
    $player_2 = new player_GT (2, $player_2_name, null, null, $future_deepness, $player_2_minimal_priority, $player_2_pattern_ai, 1, $player_2_color, $player_2_race);
    $board = new board_GT("gamex", array($x_size, $y_size), $game);
    $board->create_empty_board();
//create board
//update options
    $player_1->get_options($board);
    $player_2->get_options($board);
    $player_1->options_desc($board->size);
    $player_2->options_desc($board->size);
//additional options
    $result = 0;
    $on_turn = 2; //player 2
    $not_on_turn = 1;// player 1
    $tower_name = "tent";
    $learn_mode_on = true;
    $total_possible_moves_both_players = ($x_size * $y_size) / 2;
    $counter = 0;
    for ($i = 0; $i < round($y_size / 2); $i++) {
        for ($j = 0 + $i; $j < round($x_size / 2); $j++) {// prvo postavi vse možne start pozicije, šparavno!
            $board_tmp_1 = clone $board;
            $board_tmp_1->board[$j][$i][0] = $on_turn;
            $board_tmp_1->board[$j][$i][1] = $tower_name;
            for ($k = 0; $k < $y_size; $k++) {
                for ($l = 0; $l < $x_size; $l++) {
                    if ($i != $k or $j != $l) {// if not on player 1s position
                        $board_tmp_2 = clone $board_tmp_1;
                        $board_tmp_2->board[$l][$k][0] = $not_on_turn;
                        $board_tmp_2->board[$l][$k][1] = $tower_name;
                        $player_1->get_options($board_tmp_2);// ze nekse optionse ma od prlej taka to bi lejko skrajšo
                        $player_2->get_options($board_tmp_2);
                        $player_1->options_desc($board->size);
                        $player_2->options_desc($board->size);
                        $result = to_the_future($future_deepness, 0, $board_tmp_2, $player_2, $player_1, $total_possible_moves_both_players, $learn_mode_on);
                        $counter++;
                    }
                }
            }
        }
    }
    echo "added $counter test moves";
    /*
    echo "added " . $test_move_counter . " moves" . '<br>';
    $end_id = $board_counter_start + $test_move_counter;
    mysqli_query($game, "INSERT INTO memory_moves_stats (x_size,y_size,start_id,end_id,total_moves,pattern_ai) VALUES ('$x_size','$y_size','$board_counter_start','$end_id','$test_move_counter','$pattern_ai')");
*/
}
function add_test_move($board, $x_size, $y_size,
                       $x, $y,
                       $turn, $times_lost, $x_y_lost,$on_turn,$pattern_ai)
{
    global $add_test_move;//time
    $time_start = microtime(true);//time
    global $game_future_ai_moves, $game, $board_counter, $test_move_counter;
    $board_name = "board_" . $board_counter++;
    $serialized_board = json_encode($board);
    $serialized_x_y_lost = json_encode($x_y_lost);
    mysqli_query($game, "INSERT INTO memory_moves (game_name,x_size,y_size,board,x,y,times_lost,x_y_lost,on_turn,turn,pattern_ai) VALUES ('$board_name','$x_size','$y_size','$serialized_board','$x','$y','$times_lost','$serialized_x_y_lost','$on_turn','$turn','$pattern_ai')");
    $test_move_counter++;
    $time_end = microtime(true);//time
    $add_test_move += ($time_end - $time_start);//time
}
?>
