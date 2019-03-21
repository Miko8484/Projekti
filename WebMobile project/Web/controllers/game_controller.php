<?php
class board2{
    public $board = array();
}
class cell{
    public $owner;
}

class game_controller
{

    public static function custom_board()
    {
        require_once('views/game/custom_board.php');
    }

    public function game_page()
    {
        if (isset($_SESSION["logged_account"])) {
            $game_account = game_account::find_by_id($_SESSION["logged_account"]->id);
            require_once('views/game/game.php');
        } else {
            require_once('views/strani/login_first.php');
        }
    }

    public function create_player_vs_player_game($x_size,$y_size,$player_1_name,$player_2_name,$player_1_color,$player_2_color,$player_1_race,$player_2_race)
    {
        $player_1_minimal_priority = 1;
        $player_2_minimal_priority = 1;

        $player_1_brute_force_deepness = 0;
        $player_2_brute_force_deepness = 0;
        $player_1_pattern_ai = "function_return_1";
        $player_2_pattern_ai = "function_return_1";



        return $this->create_game(
            "player_vs_player",$x_size,$y_size,
            $player_1_name,$player_1_brute_force_deepness,$player_1_minimal_priority,$player_1_pattern_ai,$player_1_color,$player_1_race,
            $player_2_name,$player_2_brute_force_deepness,$player_2_minimal_priority,$player_2_pattern_ai,$player_2_color,$player_2_race
        );
    }

    public function create_player_vs_future_game($x_size,$y_size,$player_1_name,$player_1_color,$player_2_color,$player_1_race,$player_2_race,$minimal_priority,$brute_force_deepness,$pattern_ai)
    {
        $player_2_name = "goblins";

        $player_1_minimal_priority = $minimal_priority;
        $player_2_minimal_priority = $minimal_priority;

        $player_1_brute_force_deepness = $brute_force_deepness;
        $player_2_brute_force_deepness = $brute_force_deepness;
        $player_1_pattern_ai = $pattern_ai;
        $player_2_pattern_ai = $pattern_ai;

        $game = $this->create_game(
            "player_vs_future",$x_size,$y_size,
            $player_1_name,$player_1_brute_force_deepness,$player_1_minimal_priority,$player_1_pattern_ai,$player_1_color,$player_1_race,
            $player_2_name,$player_2_brute_force_deepness,$player_2_minimal_priority,$player_2_pattern_ai,$player_2_color,$player_2_race
        );

        return $game;
    }

    public function create_game(
        $game_mod,$x_size,$y_size,
        $player_1_name,$player_1_brute_force_deepness,$player_1_minimal_priority,$player_1_pattern_ai,$player_1_color,$player_1_race,
        $player_2_name,$player_2_brute_force_deepness,$player_2_minimal_priority,$player_2_pattern_ai,$player_2_color,$player_2_race
    ){
        $game_name = $player_1_name . "_vs_" . $player_2_name . "_somenumber";

        $player_0 = new player_GT(0, "player_0", null, null, null, null, null, null, null, null, null);
        $player_1 = new player_GT(1, $player_1_name, array(), array(), $player_1_brute_force_deepness, $player_1_minimal_priority, $player_1_pattern_ai, 2, $player_1_color, $player_1_race);
        $player_2 = new player_GT(2, $player_2_name, array(), array(), $player_2_brute_force_deepness, $player_2_minimal_priority, $player_2_pattern_ai, 1, $player_2_color, $player_2_race);


        create_pattern_ai_function(database::get_GTdb(), $player_1_pattern_ai);
        create_pattern_ai_function(database::get_GTdb(), $player_2_pattern_ai);



        //create board
        $size = array($x_size, $y_size);
        $board = new board_GT($game_name, $size, null);
        //update options
        $player_1->get_options($board);
        $player_2->get_options($board);

        $on_turn_id = 1;
        $not_on_turn_id = 2;

        $game = new game_GT(null, $game_name, $player_1, $player_2, $board, $game_mod, $on_turn_id, 0, null);


        $game->create_custom_game_db();


        return $game;

    }

    public function create_new_game()
    {
        $defender = "?";

        // create_players
        $_SESSION["database"] = new database("", "");

        $player_1_name = $_SESSION["logged_account"]->username;

        $player_1_color = strip_tags($_POST['color']);

        $player_1_race = "goblin";// pri chinese wall je isto
        $player_2_race = "goblin";

        $x_size = strip_tags($_POST['x_size']);
        $y_size = strip_tags($_POST['y_size']);

        $number_of_obstacles = strip_tags($_POST['number_of_obstacles']);

        if ($player_1_color == "red") {
            $player_2_color = "blue";
        } else {
            $player_2_color = "red";
        }

        if ($_POST['custom'] == 'player_vs_player') {

            $player_2_name = $_POST['player_2_name'];
            $game =$this->create_player_vs_player_game($x_size,$y_size,$player_1_name,$player_2_name,$player_1_color,$player_2_color,$player_1_race,$player_2_race);

        }else if($_POST['custom'] == 'brute_force_and_pattern_ai'){

            $minimal_priority = $_POST['minimal_priority'];
            $brute_force_deepness = $_POST['brute_force_deepness'];
            $pattern_ai = $_POST['pattern_ai'];

            $game = $this->create_player_vs_future_game($x_size,$y_size,$player_1_name,$player_1_color,$player_2_color,$player_1_race,$player_2_race,$minimal_priority,$brute_force_deepness,$pattern_ai);


        }

        $game_mod = "";
        if ($_POST['custom'] == 'goblins') {
            $game_mod = "player_vs_goblins";
        } else if ($_POST['custom'] == 'brute_force_and_pattern_ai') {
            $game_mod = "player_vs_future";
        } else if ($_POST['custom'] == 'player_vs_player') {
            $game_mod = "player_vs_player";
        }


        $_SESSION["lock"] = 1;// game is not ready toe play
        $_SESSION["game_id"] = $game->id;

        $_SESSION["game"] = $game;

        $_SESSION["board"] = $_SESSION["game"]->board;
        $_SESSION["player_1"] = $_SESSION["game"]->player_1;

        $_SESSION["player_2"] = $_SESSION["game"]->player_2;

        $_SESSION["lock"] = 0;// game is ready to play


        $game_account = game_account::find_by_id($_SESSION["logged_account"]->id);
        require_once("views/game/game.php");

    }



    public function play()
    {
    }

    public function end_game()
    {
        if ($_SESSION["logged_account"]->is_admin == true) {
            $id = $_POST["game_id"];
            game_GT::end_game_by_id($id);

        }
        $game_account = game_account::find_by_id($_SESSION["logged_account"]->id);
        require_once("views/game/game.php");
    }

    public function prikaziAPI($request, $input){
        //če uporabnik v klicu apija ni podal idja commenta -> api.php/commenti/x, vrnemo napako
        //opcijsko, bi tukaj lahko vrnili tudi seznam vseh commentov
        //kar bi pomenilo, da klic metode GET na api.php/commenti vrne vse commente
        if (!(isset($request[1]))){
            return -1;
        }
        if (is_numeric($request[1])){
            $game_id=($request[1]);
        }else{
            $account = game_account::find_by_username($request[1]);
            $game_id=$account->id_of_current_game;
        }
        if($game_id == null or $game_id==0){
            echo -1;
            return 0;
        }
        $game = game_GT::database_construct($game_id);


        //$arr=$game->board;
        $arr=new board2();
	if($game!=null){

        for($i=0;$i<$game->board->size[0];$i++){
        for($j=0;$j<$game->board->size[1];$j++) {
            $arr->board[$j][$i] = new cell();
            $arr->board[$j][$i]->owner = $game->board->board[$j][$i][0];
        }
        }
	}else{
		$arr->board = null;
	}
        echo json_encode($arr);
        return 1;
    }

    public function dodajAPI($request, $input)
    {
        if (is_numeric($request[1])){
            $game_id=($request[1]);
        }else{
            $account = game_account::find_by_username($request[1]);
            $game_id=$account->id_of_current_game;

        }
        $result = array(0, 0, 0);
        if($game_id == null or $game_id==0){
            echo -1;
            return 0;
        }
        $game = game_GT::database_construct($game_id);


        $player_1=$game->player_1;
        $player_2=$game->player_2;
        $board =$game->board;


        $root=$_SERVER['DOCUMENT_ROOT'];
        require_once ("$root/models/game.php" );
        require_once ("$root/connection.php");
            $database = new database("","");
            $x = $input->arr->x;
            $y = $input->arr->y;
            for ($once = 0; $once < 1; $once++) {

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

    public function posodobiAPI($request, $input)
    {
        if (!(isset($request[1]))){
            echo "napaka11";
            require_once('views/strani/napaka.php');
            return 0;
        }

        $date_created = date("Y-m-d H:i:s");
        comment::edit($request[1],$input->oglas_id,
            $input->user_id,$input->user_mail,
            $input->content, $date_created);
        //naložimo pogled, ki apiju vrne glavo za preusmeritev na novo dodani komentar
        require_once('views/strani/success.php');
        return 1;
    }


    public function odstraniAPI($request, $input)
    {
        if (!(isset($request[1]))){
            require_once('views/strani/napaka.php');
            return 0;
        }

        $comment=comment::najdi($request[1]);

        if (!isset($_SESSION["logged_account"])){
            echo "not logged1";
            return 0;
        }else if($comment->user_id!=$_SESSION['logged_account']->id){
            echo "not logged2 ";
            return 0;
        }
        comment::delete_comment($request[1]);
        require_once('views/comment/json.php');
        require_once('views/strani/success.php');

        return 1;
    }
}

?>
