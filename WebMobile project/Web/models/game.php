<?php
require_once("account.php");
require_once("history.php");


class game
{
    public $id, $name;

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}

class game_GT extends game
{
    public $game_mod, $on_turn, $turn, $winner_id;
    public $board;
    public $player_1;
    public $player_2;

    public function __construct($id, $name, player_GT $player_1, player_GT $player_2, board_GT $board, $game_mod, $on_turn, $turn, $winner_id)
    {
        parent::__construct($id, $name);
        $this->player_1 = $player_1;
        $this->player_2 = $player_2;
        $this->board = $board;
        $this->game_mod = $game_mod;
        $this->on_turn = $on_turn;
        $this->turn = $turn;
        $this->winner_id = $winner_id;
    }


    //draw
    function draw_board()
    {
        $players = array();
        $players[0] = $this->player_1;
        $players[1] = $this->player_1;
        $players[2] = $this->player_2;

        echo "<table class='board_table'>";
        for ($i = $this->board->size[1] - 1; $i >= 0; $i--) {
            echo "<tr class='row' id='row_" . $i . "'>";
            echo "<td class='coordinates_cell' id='indentifier_y_" . $i . "' > $i </td>";
            for ($j = 0; $j < $this->board->size[0]; $j++) {
                $classes = $this->draw_condition($this->board->board[$j][$i],
                    $players[$this->board->board[$j][$i][0]]);

                ////onclick="play(<?php echo $row['x'] . "," . $row['y']; );">
                //if($cell[0]==0){$classes.="\" onclick=\"play($)";}
                echo "<td class='cell" . $classes . "' id='cell_" . $j . "_" . $i . "' onclick=\"play($j,$i);\">" . "</td>";
            }
            echo "</tr>";
        }


        echo "<td  class='coordinates_cell' id='empty_cell' >  </td>";
        for ($i = 0; $i < $this->board->size[0]; $i++) {
            echo "<td class='coordinates_cell' id='indentifier_x_" . $i . "' > $i </td>";
        }

        echo "</table>";
        echo "</br>";
    }

    function draw_board_2()
    {
        $players = array();
        $players[0] = $this->player_1;
        $players[1] = $this->player_1;
        $players[2] = $this->player_2;


        $stringo = "<table class='board_table'>";
        for ($i = $this->board->size[1] - 1; $i >= 0; $i--) {
            $stringo .= "<tr class='row' id='row_" . $i . "'>";
            $stringo .="<td class='coordinates_cell' id='indentifier_y_" . $i . "' > $i </td>";
            for ($j = 0; $j < $this->board->size[0]; $j++) {
                $classes = $this->draw_condition($this->board->board[$j][$i],
                    $players[$this->board->board[$j][$i][0]]);

                ////onclick="play(<?php echo $row['x'] . "," . $row['y']; );">
                //if($cell[0]==0){$classes.="\" onclick=\"play($)";}
                $stringo .= "<td class='cell" . $classes . "' id='cell_" . $j . "_" . $i . "' onclick=\"play($j,$i);\">" . "</td>";
            }
            $stringo .= "</tr>";
        }


        $stringo .= "<td  class='coordinates_cell' id='empty_cell' >  </td>";
        for ($i = 0; $i < $this->board->size[0]; $i++) {
            $stringo .= "<td class='coordinates_cell' id='indentifier_x_" . $i . "' > $i </td>";
        }

        $stringo .= "</table>";
        $stringo .= "</br>";
        return $stringo;
    }

    function draw_condition($cell, player_GT $owner)
    {

        $classes = "";

        if ($cell[0] != 0) {
            $classes .= " " . $owner->color;
        } else {
            $classes .= " " . "neutral";
        }

        return $classes;

    }

    //

    public static function database_construct($id)
    {
        $game1 = mysqli_query(database::get_GTdb(), "SELECT * FROM ongoing_games where id='$id' limit 1") or die ("error no db");
        $game1 = mysqli_fetch_assoc($game1);

        $board = board_GT::database_construct($game1["board_id"]);
        $player_1 = player_GT::database_construct($game1["player_1_id"], $board);
        $player_2 = player_GT::database_construct($game1["player_2_id"], $board);

        $game = new game_GT($game1["id"], $game1["name"], $player_1, $player_2, $board, $game1["game_mod"], $game1["on_turn"], $game1["turn"], $game1["winner_id"]);


        return $game;
    }

    function create_custom_game_db()
    {
        $this->board->create_empty_board();
        $this->board->basic_start($this->player_1, $this->player_2);
        $this->create_game_db();
        $p1_name = $this->player_1->name;
        $p2_name = $this->player_2->name;
        $db = db::getInstance();
        mysqli_query($db, "UPDATE account SET id_of_current_game='$this->id' WHERE username='$p1_name'") or die (mysqli_error($db));

        mysqli_query($db, "UPDATE account SET id_of_current_game='$this->id' WHERE username='$p2_name'") or die (mysqli_error($db));
    }

    function create_game_db()
    {

        $goblin_tower = database::get_GTdb();

        $this->board->insert_to_db();
        $p1_db_id=$this->player_1->insert_to_db();
        $p2_db_id=$this->player_2->insert_to_db();


        //fix je dobro minimal priority pa deepness napravleno kak se prenaša glede na oba playera?


        mysqli_query($goblin_tower, "DELETE FROM ongoing_games WHERE name='$this->name'");

        $x_size = $this->board->size[0];
        $y_size = $this->board->size[1];
        $board_id = $this->board->id;
        mysqli_query($goblin_tower, "INSERT INTO ongoing_games (player_1_id,player_2_id,board_id,x_size,y_size,name,game_mod,on_turn,turn,winner_id)
                                                        VALUES ('$p1_db_id','$p2_db_id','$board_id','$x_size','$y_size','$this->name','$this->game_mod','1','0','noone')")
        or die(mysqli_error_bluecat(pathinfo(__FILE__, PATHINFO_FILENAME), __LINE__, $goblin_tower));

        $this->id = mysqli_insert_id($goblin_tower);
        $this->update_ai();
    }

    function update_ai()
    {

        $this->player_1->get_options($this->board);

        $this->player_2->get_options($this->board);

        $this->player_1->options_desc($this->board->size);

        $this->player_2->options_desc($this->board->size);

        //fix tu si nekda dodavo še prioritete, toga več ne delaš kak boš rešo?
        //insert_mysql_priorities($game, $player_1->options_priority_desc, $this->goblin_tower_user_ai);
        //insert_mysql_priorities($game, $player_2->options_priority_desc, $this->goblin_tower_ai);
    }

    function place($x, $y, $tower_name, player_GT $on_turn, player_GT $not_on_turn)
    {// tu še enkrat prioritije dela pa bi lejko vbistvi že od funkcije kera je računala vnesla

        global $database;
        if(!isset($database)){//err tou bi moglo MUS biti global
            $database = new database("","");
        }


        $database->update($this, $x, $y, $on_turn->id, $tower_name);
        $database->next_player($this, $not_on_turn);

        if ($on_turn->options[$x][$y] > 0) {
            for ($i = 0; $i < count($on_turn->options_priority_desc); $i++) {//find if enemy had priority there; if it did remove it
                if ($on_turn->options_priority_desc[$i][0] == $x and $on_turn->options_priority_desc[$i][1] == $y) {
                    array_splice($on_turn->options_priority_desc, $i, 1);//remove from vector index $id_from_vector_played
                }
            }
        }
        if ($not_on_turn->options[$x][$y] > 0) {
            for ($i = 0; $i < count($not_on_turn->options_priority_desc); $i++) {//find if enemy had priority there; if it did remove it
                if ($not_on_turn->options_priority_desc[$i][0] == $x and $not_on_turn->options_priority_desc[$i][1] == $y) {
                    array_splice($not_on_turn->options_priority_desc, $i, 1);//remove from vector index $id_from_vector_played
                }
            }
        }
        $on_turn->options[$x][$y] = 0;
        $not_on_turn->options[$x][$y] = 0;
        $this->board->board[$x][$y][0] = $on_turn->id;
        $this->board->board[$x][$y][1] = board_GT::tent; //trenutno samo tent

        $this->board->find_ai_options_around_possition($x, $y, $on_turn);
        $this->board->find_ai_options_around_possition($x, $y, $not_on_turn);

        //fix tu si nekda dodavo še prioritete, toga več ne delaš kak boš rešo?
        //insert_mysql_priorities($game, $player_1->options_priority_desc, $this->goblin_tower_user_ai);
        //insert_mysql_priorities($game, $player_2->options_priority_desc, $this->goblin_tower_ai);
    }

    function check_end()
    {

        $board = clone $this->board;
        $player_1 = clone $this->player_1;
        $player_2 = clone $this->player_2;

        $player_1->pattern_ai = "check_end";
        $player_2->pattern_ai = "check_end";

        //board_GT $board, player_GT $on_turn, player_GT $not_on_turn,$total_possible_moves_both_players
        $result = check_end_basic_ai($board, $player_1, $player_2, $this->board->size[0] * $this->board->size[1]);
        // fix player win points by number_of_places - $p1_pieces -$p2_pieces;

        return array($result, 3);// is end, point difference
    }

    public static function end_game(game_GT $game)
    {
        $goblin_tower = database::get_GTdb();
        $goblin_tower_games = database::get_GT_games_db();
        $db = db::getInstance();

        $p1 = $game->player_1->name;
        $p2 = $game->player_2->name;

        mysqli_query($db, "UPDATE account SET id_of_current_game='0' WHERE username='$p1'") or die (mysqli_error_bluecat(pathinfo(__FILE__, PATHINFO_FILENAME), __LINE__, $db));
        mysqli_query($db, "UPDATE account SET id_of_current_game='0' WHERE username='$p2'") or die (mysqli_error_bluecat(pathinfo(__FILE__, PATHINFO_FILENAME), __LINE__, $db));


        mysqli_query($goblin_tower, "DELETE FROM player WHERE name='$p1'")or die (mysqli_error($goblin_tower) . "err here 335");
        mysqli_query($goblin_tower, "DELETE FROM player WHERE name='$p2'")or die (mysqli_error($goblin_tower) . "err here 336");

        $id = $game->id;
        mysqli_query($goblin_tower, "DELETE FROM ongoing_games WHERE id='$id'")or die (mysqli_error($goblin_tower) . "err here 337");
        $name = $game->name;
        mysqli_query($goblin_tower_games, "DROP TABLE IF EXISTS $name")or die (mysqli_error($goblin_tower) . "err here 3388");


    }

    public static function end_game_ranked($game, player_GT $winner, player_GT $loser)
    {

        $goblin_tower = database::get_GTdb();
        $goblin_tower_games = database::get_GT_games_db();
        $db = db::getInstance();


        $winner_account = game_account::find_by_username($winner->name);
        $loser_account = game_account::find_by_username($loser->name);

        mysqli_query($goblin_tower, "UPDATE ongoing_games SET winner_id='$winner_account->id'  WHERE id='$game->id'") or die (mysqli_error($goblin_tower) . "err here 33");


        $elo_gain_w = round(($loser_account->elo / $winner_account->elo) * 50);
        $elo_loss_l = $elo_gain_w;

        $date = date('Y-m-d H:i:s');
        history::add_history($game->id,$winner_account->id,$loser_account->id,$date,$elo_gain_w);

        $id = $winner_account->id;


        mysqli_query($db, "
        UPDATE account
        SET number_of_games=number_of_games+1, number_of_wins=number_of_wins+1,elo=elo+'$elo_gain_w' 
        WHERE id='$id'") or die (mysqli_error($db) . "err here");//

        mysqli_query($db, "
        UPDATE account
        SET number_of_games=number_of_games+1, number_of_loses=number_of_loses+1,elo=elo-'$elo_loss_l'
        WHERE id='$loser_account->id'") or die (mysqli_error($db) . "err here 2");//


        game_GT::end_game($game);


    }


    public static function end_game_by_id($id)
    {

        $game = game_GT::database_construct($id);
        self::end_game($game);

    }

}


$options = array
(
    array(0, 1),
    array(0, -1),
    array(1, 1),
    array(1, 0),
    array(1, -1),
    array(-1, 1),
    array(-1, 0),
    array(-1, -1)
);


class player_GT extends player
{

    public $options, $options_priority_desc, $brute_force_deepness, $minimal_priority, $pattern_ai, $enemy, $color, $race;


    //races
    const neutral = 0;
    const goblin = 1;
    const human = 2;


    public function __construct($id, $name, $options, $options_priority_desc, $brute_force_deepness, $minimal_priority, $pattern_ai, $enemy, $color, $race)
    {
        parent::__construct($id, $name);
        $this->options = $options;
        $this->options_priority_desc = $options_priority_desc;
        $this->brute_force_deepness = $brute_force_deepness;
        $this->minimal_priority = $minimal_priority;
        $this->pattern_ai = $pattern_ai;
        $this->enemy = $enemy;
        $this->color = $color;
        $this->race = $race;
    }

    function print_player()
    {
        echo "$this->name, brute_force_deepness $this->brute_force_deepness,
         minimal_priority: $this->minimal_priority, minimal_priority:$this->pattern_ai , enemy: $this->enemy,
         color:$this->color , race: $this->race";

        echo "options";
        echo "<pre>";
        print_r($this->options);
        echo "</pre>";

        echo "options priority desc";
        echo "<pre>";
        print_r($this->options_priority_desc);
        echo "</pre>";

    }//fix to bi lejko podedovana klasa bila od player, mogoče bole dinamično tudi napisana, ka bi se samo napravilo

    public function insert_to_db()
    {
        $db = database::get_GTdb();
        $result = mysqli_query($db, "Insert into player (game_id,name, brute_force_deepness ,minimal_priority, pattern_ai, enemy, color, race) 
values ('$this->id','$this->name','$this->brute_force_deepness', '$this->minimal_priority', '$this->pattern_ai', '$this->enemy', '$this->color', '$this->race')") or die(mysqli_error_bluecat(pathinfo(__FILE__, PATHINFO_FILENAME), __LINE__, $db));
        return mysqli_insert_id($db);
    }

    public static function database_construct($id, board_GT $board)
    {
        $player_db = mysqli_query(database::get_GTdb(), "SELECT * FROM player where id='$id' limit 1") or die("mysql error here 1 1 3");
        $player_db = mysqli_fetch_assoc($player_db);

        $player = new player_GT($player_db["game_id"], $player_db["name"], array(), array(), $player_db["brute_force_deepness"], $player_db["minimal_priority"], $player_db["pattern_ai"], $player_db["enemy"], $player_db["color"], $player_db["race"]);

        create_pattern_ai_function(database::get_GTdb(), $player->pattern_ai);


        $player->get_options($board);
        $player->options_desc($board->size);

        return $player;
    }

    public static function database_properties_construct($id)
    {
        $player_db = mysqli_query(database::get_GTdb(), "SELECT * FROM player where id='$id' limit 1");
        $player_db = mysqli_fetch_assoc($player_db);

        $player = new player_GT($player_db["id"], $player_db["name"], null, null, $player_db["brute_force_deepness"], $player_db["minimal_priority"], $player_db["pattern_ai"], $player_db["enemy"], $player_db["color"], $player_db["race"]);

        return $player;
    }

    /*
        function get_options_mysql($game, board_GT $board, $goblin_tower_ai_or_user_ai)// zakoj je global x pa y size
        {
            global $goblin_tower;

            $ongoing_games = mysqli_query($goblin_tower, "SELECT * FROM ongoing_games where game_name='$game'");
            $ongoing_games = mysqli_fetch_assoc($ongoing_games);

            $this->options = array();
            for ($i = 0; $i < $board->size[1]; $i++) {
                for ($j = 0; $j < $board->size[0]; $j++) {
                    $this->options[$j][$i] = 0;
                }
            }

            $ai_result1 = mysqli_query($goblin_tower_ai_or_user_ai, "SELECT * FROM $game");
            while ($ai_result = mysqli_fetch_assoc($ai_result1)) {
                $priority = $ai_result['priority'];
                $x = $ai_result['x'];
                $y = $ai_result['y'];
                $this->options[$x][$y] = $priority;
            }
        }
    */
    function get_options(board_GT $board)
    {
        $this->options_priority_desc = array();
        $this->options = array();
        for ($i = 0; $i < $board->size[1]; $i++) {
            for ($j = 0; $j < $board->size[0]; $j++) {
                $this->options[$j][$i] = 0;
            }
        }
        for ($i = 0; $i < $board->size[1]; $i++) {
            for ($j = 0; $j < $board->size[0]; $j++) {
                if ($board->board[$j][$i][0] == $this->id) {
                    $board->find_ai_options_around_possition($j, $i, $this);
                }
            }
        }
    }

    function options_desc($sizexy)
    {// tu maš bubble sort kar je weak... samo seeno pač to samo enkrat delaš nej pa večkrat. ovači pa lejko popravip v kaj bokšoga
        // tu delaš 2krat tej prioritete to je dobro? lejko bi meu samo array eden v keron maš x,y,pa priority shranjeni pa nej po kordinataj zloženo
        global $my_time4;//time
        $time_start = microtime(true);//time
        $this->options_priority_desc = array();
        $size = 0;
        for ($i = 0; $i < $sizexy[1]; $i++) {
            for ($j = 0; $j < $sizexy[0]; $j++) {
                if ($this->options[$j][$i] != 0) {
                    $this->options_priority_desc[$size][0] = $j;
                    $this->options_priority_desc[$size][1] = $i;
                    $this->options_priority_desc[$size][2] = $this->options[$j][$i];
                    $size++;
                }
            }
        }
        $this->options_desc_by_value($size);
        $time_end = microtime(true);//time
        $my_time4 += ($time_end - $time_start);//time
    }

    function options_desc_by_value($size)
    {
        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size - 1 - $i; $j++) {
                if ($this->options_priority_desc[$j][2] < $this->options_priority_desc[$j + 1][2]) {
                    $this->swap($this->options_priority_desc[$j][0], $this->options_priority_desc[$j + 1][0]);
                    $this->swap($this->options_priority_desc[$j][1], $this->options_priority_desc[$j + 1][1]);
                    $this->swap($this->options_priority_desc[$j][2], $this->options_priority_desc[$j + 1][2]);
                }
            }
        }
    }


    public function play(board_GT $board, $x, $y, $piece)
    {
        return $board->place($x, $y, $piece, $this);
    }

    function swap(&$x, &$y)
    {
        $tmp = $x;
        $x = $y;
        $y = $tmp;
    }

}


$options = array
(
    array(0, 1),
    array(0, -1),
    array(1, 1),
    array(1, 0),
    array(1, -1),
    array(-1, 1),
    array(-1, 0),
    array(-1, -1)
);

class board_GT extends board
{
    public $name;
    public $id;

    const board_name = "Goblin Tower";// imej igrice
// constante pri board array
    const owner = 0;
    const piece = 1;

    const field = 0;
    const stone_tower = 1;
    const tent = 2;

    const neutral = 0;


    public function __construct($name, $size, $board)
    {
        parent::__construct($size);
        $this->name = $name;
        $this->board = $board;
    }

    public static function database_construct($id)
    {
        $db = database::get_GTdb();
        $board_class = mysqli_query($db, "SELECT * FROM board where id='$id'") or die (mysqli_error_bluecat(pathinfo(__FILE__, PATHINFO_FILENAME), __LINE__, $db));
        $board_class = mysqli_fetch_assoc($board_class);

        if($board_class==null){
            return null;
        }


        $name = $board_class["game_name"];

        $db = database::get_GT_games_db();
        $board = array();
        $board_result = mysqli_query($db, " SELECT * FROM $name ")
        or die (mysqli_error_bluecat(pathinfo(__FILE__, PATHINFO_FILENAME), __LINE__, $db) . " id=$id name=$name");
        while ($row = mysqli_fetch_assoc($board_result)) {
            $x = $row['x'];
            $y = $row['y'];
            $owner = $row['owner'];
            $tower_name = $row['tower_name'];
            $board[$x][$y][board_GT::owner] = (int)$owner;
            $board[$x][$y][board_GT::piece] = $tower_name;
        }

        $size = array($board_class["x_size"], $board_class["y_size"]);
        return new board_GT($name, $size, $board);


    }

    //create


    public function insert_to_db()
    {
        $db = database::get_GTdb();
        $x_size = $this->size[1];
        $y_size = $this->size[1];
        $result = mysqli_query($db, "Insert into board (game_name,x_size, y_size) values ('$this->name','$x_size', '$y_size')") or die(mysqli_error($db) . " error insert 111");
        $this->id = mysqli_insert_id($db);

        $this->insert_board();
    }

    public function insert_board()
    {
        $goblin_tower_games = database::get_GT_games_db();
        mysqli_query($goblin_tower_games, "DROP TABLE IF EXISTS $this->name") or die(mysqli_error_bluecat(pathinfo(__FILE__, PATHINFO_FILENAME), __LINE__, $goblin_tower_games));
        mysqli_query($goblin_tower_games, "CREATE TABLE $this->name (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        x INT(30),
        y INT(30),
        owner VARCHAR(45),
        tower_name VARCHAR(45)
        );") or die(mysqli_error_bluecat(pathinfo(__FILE__, PATHINFO_FILENAME), __LINE__, $goblin_tower_games));


        $stmt = mysqli_prepare($goblin_tower_games, "Insert into $this->name (x,y,owner,tower_name) Values (?,?,?,?)") or die ("error stmt");
        for ($i = 0; $i < $this->size[1]; $i++) {
            for ($j = 0; $j < $this->size[0]; $j++) {

                mysqli_stmt_bind_param($stmt, "iiis", $j, $i, $this->board[$j][$i][board_GT::owner], $this->board[$j][$i][board_GT::piece]) or die ("error stmt2");
                mysqli_stmt_execute($stmt);

            }
        }
    }

    function create_empty_board()
    {
        $this->board = array();
        for ($i = 0; $i < $this->size[1]; $i++) {
            for ($j = 0; $j < $this->size[0]; $j++) {
                $this->board[$j][$i][0] = 0;
                $this->board[$j][$i][1] = board_GT::field;
            }
        }
    }

    function basic_start(player_GT $on_turn, player_GT $not_on_turn)
    {
        $x = 0;
        $y = 0;
        $this->board[$x][$y][0] = $on_turn->id;
        $this->board[$x][$y][1] = "basic";

        $x = $this->size[0] - 1;
        $y = $this->size[1] - 1;
        $this->board[$x][$y][0] = $not_on_turn->id;
        $this->board[$x][$y][1] = "basic";


        if ($this->size[0] % 2 == 1) {// if map is odd, count as if player 2 started for advantage
            $x = $this->size[0] - 2;
            $y = $this->size[1] - 2;
            $this->board[$x][$y][0] = $not_on_turn->id;
            $this->board[$x][$y][1] = "basic";
        }
        $players = array();
        $players[0] = $on_turn;
        $players[1] = $not_on_turn;
        $this->set_basic_towers($players);
    }

    function set_basic_towers($players)
    {
        global $goblin_tower;

        if (1 == 2) {//this sets db towers
            foreach ($players as $player) {
                $basic_tower = mysqli_query($goblin_tower, "SELECT array_index FROM piece WHERE race='$player->race' and tier='1'") or die(mysqli_error_bluecat($goblin_tower));
                $basic_tower = mysqli_fetch_assoc($basic_tower);
                $basic_tower = $basic_tower['array_index'];
                if ($basic_tower == 0) {
                    $basic_tower = "0";
                }
                for ($i = 0; $i < $this->size[1]; $i++) {
                    for ($j = 0; $j < $this->size[0]; $j++) {
                        if ($this->board[$j][$i][self::owner] == $player->id and $this->board[$j][$i][self::piece] == "basic") {
                            $this->board[$j][$i][self::piece] = $basic_tower;
                        }
                    }
                }
            }
        } else {
            foreach ($players as $player) {
                $basic_tower = "wall";
                for ($i = 0; $i < $this->size[1]; $i++) {
                    for ($j = 0; $j < $this->size[0]; $j++) {
                        if ($this->board[$j][$i][self::owner] == $player->id and $this->board[$j][$i][self::piece] == "basic") {
                            $this->board[$j][$i][self::piece] = $basic_tower;
                        }
                    }
                }
            }
        }

    }

    //options

    function check_requirement($x, $y, player_GT $on_turn)
    {
        $options = array
        (
            array(0, 1),
            array(0, -1),
            array(1, 1),
            array(1, 0),
            array(1, -1),
            array(-1, 1),
            array(-1, 0),
            array(-1, -1)
        );
        if ($this->board[$x][$y][0] == board_GT::neutral)
            for ($i = 0; $i < 8; $i++) {
                $x1 = $x + $options[$i][0];
                $y1 = $y + $options[$i][1];
                if (isset($this->board[$x1][$y1][0])) {
                    if ($this->board[$x1][$y1][0] == $on_turn->id) return 1;
                }
            }
        return 0;
    }

    function find_nearby_fields($x, $y)
    {
        $options = array
        (
            array(0, 1),
            array(0, -1),
            array(1, 1),
            array(1, 0),
            array(1, -1),
            array(-1, 1),
            array(-1, 0),
            array(-1, -1)
        );
        global $my_time5;//time
        $time_start = microtime(true);//time
        $owner_count_1x1 = array(0, 0, 0);
        for ($i = 0; $i < 8; $i++) {
            $priority_x = $x + $options[$i][0];
            $priority_y = $y + $options[$i][1];
            if (isset($this->board[$priority_x][$priority_y][0])) {
                $owner = $this->board[$priority_x][$priority_y][0];
                $owner_count_1x1[$owner]++;
            } else {
                $owner_count_1x1[0]++;
            }
        }
        $time_end = microtime(true);//time
        $my_time5 += ($time_end - $time_start);//time
        return $owner_count_1x1;
    }

    function find_ai_options_around_possition($x, $y, player_GT $player)
    {
        $options = array
        (
            array(0, 1),
            array(0, -1),
            array(1, 1),
            array(1, 0),
            array(1, -1),
            array(-1, 1),
            array(-1, 0),
            array(-1, -1)
        );
        global $my_time3, $my_time5;//time

        //err here dont call database
        global $goblin_tower;
        create_pattern_ai_function($goblin_tower, $player->pattern_ai);//fix to je dobro ka skoz zoven :(?
        $time_start = microtime(true);//time

        for ($i = 0; $i < 8; $i++) {
            $newx = $x + $options[$i][0];
            $newy = $y + $options[$i][1];
            if (isset($this->board[$newx][$newy][0])) {
                if ($this->board[$newx][$newy][board_GT::owner] == 0) {
                    if ($this->board[$x][$y] == $player->id or $this->check_requirement($newx, $newy, $player)) {

                        $pattern_ai_string = $player->pattern_ai;
                        $priority = $pattern_ai_string($this, $newx, $newy, $player);

                        if ($player->options[$newx][$newy] != 0) { // if he found that option remove it
                            for ($j = 0; $j < count($player->options_priority_desc); $j++) {
                                if ($player->options_priority_desc[$j][0] == $newx and $player->options_priority_desc[$j][1] == $newy) {
                                    array_splice($player->options_priority_desc, $j, 1);//remove from vector index $id_from_vector_played
                                }
                            }
                        }
                        $player->options[$newx][$newy] = $priority;
                        $index = -1;
                        $arr = array($newx, $newy, $priority);
                        for ($j = 0; $j < count($player->options_priority_desc); $j++) { // find insertion index (same or smaller priorities)
                            if ($player->options_priority_desc[$j][2] <= $priority) {
                                $index = $j;
                                break;
                            }
                        }
                        if ($index == -1) {
                            array_push($player->options_priority_desc, $arr);
                        } else {
                            array_splice($player->options_priority_desc, $index, 0, 1);// insert into index $j val
                            $player->options_priority_desc[$index] = $arr;
                        }
                    }
                }
            }
        }
        $time_end = microtime(true);//time
        $my_time3 += ($time_end - $time_start);//time
    }

    //place

    function place_local_sort_priority($x, $y, $type, player_GT $on_turn, player_GT $not_on_turn)
    {
        global $my_time2;//time
        $time_start = microtime(true);//time
        //possible shortcut is to create id from last played and remove it witouth searching ( it caused me errors so i removed that option)
        if ($on_turn->options[$x][$y] > 0) {
            for ($i = 0; $i < count($on_turn->options_priority_desc); $i++) {//find if enemy had priority there; if it did remove it
                if ($on_turn->options_priority_desc[$i][0] == $x and $on_turn->options_priority_desc[$i][1] == $y) {
                    array_splice($on_turn->options_priority_desc, $i, 1);//remove from vector index $id_from_vector_played
                }
            }
        }
        if ($not_on_turn->options[$x][$y] > 0) {
            for ($i = 0; $i < count($not_on_turn->options_priority_desc); $i++) {//find if enemy had priority there; if it did remove it
                if ($not_on_turn->options_priority_desc[$i][0] == $x and $not_on_turn->options_priority_desc[$i][1] == $y) {
                    array_splice($not_on_turn->options_priority_desc, $i, 1);//remove from vector index $id_from_vector_played
                }
            }
        }
        $on_turn->options[$x][$y] = 0;
        $not_on_turn->options[$x][$y] = 0;
        $this->board[$x][$y][0] = $on_turn->id;
        $this->board[$x][$y][1] = $type; //trenutno samo tent
        $this->find_ai_options_around_possition($x, $y, $on_turn);
        $this->find_ai_options_around_possition($x, $y, $not_on_turn);
        $time_end = microtime(true);//time
        $my_time2 += ($time_end - $time_start);//time
    }

    function place_local(board $board, $x, $y, player_GT $on_turn, player_GT $not_on_turn)
    {
        global $my_time2;//time
        $time_start = microtime(true);//time
        $board->board[$x][$y][0] = $on_turn->id;
        $board->board[$x][$y][1] = self::tent; //trenutno samo tent
        $this->find_ai_options_around_possition($x, $y, $on_turn);
        $this->find_ai_options_around_possition($x, $y, $not_on_turn);
        $time_end = microtime(true);//time
        $my_time2 += ($time_end - $time_start);//time
    }


}

class database
{

    public static $options;

    private $db_user;
    private $db_pass;
    public $goblin_tower;
    public $goblin_tower_games;


    public function __construct($usr, $pass)
    {
        $this->db_user = "clan1";
        $this->db_pass = "projekt123";
        $this->goblin_tower = mysqli_connect('164.8.230.118', $this->db_user, $this->db_pass, "game") or die("Couldn't connect to database [" . mysqli_errno($this->goblin_tower) . "]");
        $this->goblin_tower_games = mysqli_connect('164.8.230.118', $this->db_user, $this->db_pass, "games") or die("Couldn't connect to database [" . mysqli_errno($this->goblin_tower_games) . "]");

    }

    public static function get_GTdb()
    {
        $db_user = "clan1";
        $db_pass = "projekt123";
        $goblin_tower = mysqli_connect('164.8.230.118', $db_user, $db_pass, "game") or die("Couldn't connect to database [" . mysqli_errno($goblin_tower) . "]");
        return $goblin_tower;
    }

    public static function get_GT_games_db()
    {
        $db_user = "clan1";
        $db_pass = "projekt123";
        $goblin_tower_games = mysqli_connect('164.8.230.118', $db_user, $db_pass, "games") or die("Couldn't connect to database [" . mysqli_errno($goblin_tower_games) . "]");
        return $goblin_tower_games;
    }


    function update(game $game, $x, $y, $owner, $tower_name)
    {
        mysqli_query($this->goblin_tower_games, "UPDATE $game->name SET owner='$owner',tower_name='$tower_name' WHERE x='$x' AND y='$y'") or die (mysqli_error_bluecat(pathinfo(__FILE__, PATHINFO_FILENAME), __LINE__, $this->goblin_tower_games));
    }

    function next_player(game_GT $game, player_GT $not_on_turn)
    {
        mysqli_query($this->goblin_tower, "UPDATE ongoing_games SET on_turn='$not_on_turn->id' WHERE name='$game->name'") or die (mysqli_error($this->goblin_tower));
    }

    function check_on_turn($game)
    {
        $id = mysqli_query($this->goblin_tower, "SELECT on_turn FROM ongoing_games where name='$game->name'") or die (mysqli_error($this->goblin_tower));
        $id = mysqli_fetch_assoc($id);
        $id = $id['on_turn'];

        return $id;
    }

    function random_obstacles($game, $number_of_obstacles)
    {
        global $goblin_tower_games;
        for ($i = 0; $i < $number_of_obstacles; $i++) {//to napravi obstacle na random lokacijaj, mogoče fali to ka bi nej delalo stolpe tan ge lejko ai ali tij zidaš,oz nekak ka te nemre zadelati
            $result = mysqli_query($goblin_tower_games, "SELECT * FROM $game WHERE owner=0");
            $size = mysqli_num_rows($result);
            $random = rand(0, $size);

            $result1 = mysqli_query($goblin_tower_games, "SELECT x,y FROM $game WHERE owner='0' LIMIT  1 OFFSET $random");
            $result = mysqli_fetch_assoc($result1);
            $x = $result['x'];
            $y = $result['y'];

            $this->update($game, $x, $y, 0, 'mountain_1');
        }
    }



    /// sometin
    /*function create_specific_game($game, player_GT $player_1, player_GT $player_2, $board_name, $game_mod, $brute_force_deepness)
    {
        mysqli_query($this->goblin_tower_games, "CREATE TABLE goblin_tower_games.$game SELECT * FROM goblin_tower_boards.$board_name");
        $board = new board_GT(null, null, null, $this->goblin_tower);

        $players = array();
        $players[0] = $player_1;
        $players[1] = $player_2;
        $board->set_basic_towers($players);
        $this->create_game($player_1, $player_2, $board, $game, $game_mod, $brute_force_deepness);//bruteforce deepness je malo čudno

    }
*/
}


class player
{
    public static $static_id = 0;
    public $id, $name;

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

}

class board
{
    public $board;
    public $size;//x,y

    //static $static_id;
    public function __construct($size1)
    {
        $this->size = $size1;
    }


}

class player_a extends player
{
    public static $static_id;
    public $options;
    public $player_piece;


    public function __construct($name1)
    {
        parent::__construct($name1);
    }

    public function manual_construct($a1, $b1)
    {
        //$this->a=$a1
    }

    public function database_construct(game $game)
    {
        $table = mysqli_query($game->database, "SELECT * FROM game where name='$game'");
        $table = mysqli_fetch_assoc($table);
        $this->id = $table["player_$this->id"];
        //create_pattern_ai_function($game->database, $this->pattern);
        $this->options = array();
    }

    public function get_options(board_a $board)
    {
        $this->options = array();
        for ($i = 0; $i < $board->size[1]; $i++) {
            for ($j = 0; $j < $board->size[0]; $j++) {
                if (1 == 1)// checks conditions
                    $this->options[$j][$i] = 0;
            }
        }
    }

    public function options_desc($sizexy, $options)
    {
        $options_desc = array();
        $size = 0;
        for ($i = 0; $i < $sizexy[1]; $i++) {
            for ($j = 0; $j < $sizexy[0]; $j++) {
                if ($options[$j][$i] > 0) {
                    $options_desc[$size][0] = $j;
                    $options_desc[$size][1] = $i;
                    $options_desc[$size][2] = $options_desc[$j][$i];
                    $size++;
                }
            }
        }
        return $options_desc;
    }

    public function play(board_a $board, $x, $y)
    {
        return $board->place($x, $y, $this->player_piece, $this);
    }

    public function play_random(board_a $board)
    {
        $rand_x = rand(0, $board->size[0] - 1);
        $rand_y = rand(0, $board->size[1] - 1);


        // neje čista random zato ka prvo naprej ide pa te nazaj
        for ($i = $rand_x; $i < $board->size[1]; $i++) {
            for ($j = $rand_y; $j < $board->size[0]; $j++) {
                if ($board->place($i, $j, $this->player_piece, $this)) {
                    $location = array($i, $j);
                    return $location;
                }
            }
        }
        for ($i = $rand_x; $i >= 0; $i--) {
            for ($j = $rand_y; $j >= 0; $j--) {
                if ($board->place($i, $j, $this->player_piece, $this)) {
                    $location = array($i, $j);
                    return $location;
                }
            }
        }
        return 0;
    }
}

class board_a extends board
{

    const board_name = "board_a";// imej igrice

    // constante primeri konstant,
    //3d array slot values
    const owner = 0;
    const piece = 1;

    //piece types
    const field = 0;
    const stone_tower = 1;
    const tent = 2;


    //const
    public function __construct($size)
    {
        parent::__construct($size);
    }

    public function manual_construct($a1, $b1)
    {
        //$this->a=$a1
    }

    public function database_construct(game $game)
    {
        $table = mysqli_query($game->database, "SELECT * FROM game where name='$game->name'");
        $table = mysqli_fetch_assoc($table);
        $this->a = $table["a"];
    }

    function create_empty_board()
    {
        $this->board = array();
        for ($i = 0; $i < $this->size[1]; $i++) {
            for ($j = 0; $j < $this->size[0]; $j++) {
                $this->board[$j][$i][0] = 0;
                $this->board[$j][$i][1] = self::field;
            }
        }
    }


    //draw
    function draw_condition($cell)
    {
        $classes = "";


        if ($cell[self::owner] == 0) {
            $classes .= " neutral";
        } else if ($cell[self::owner] == 1) {
            $classes .= " red";
        } else if ($cell[self::owner] == 2) {
            $classes .= " blue";
        }

        if ($cell[self::piece] == 1) {
            $classes .= " stone_tower";
        } else if ($cell[self::piece] == 2) {
            $classes .= " tent";
        }

        return $classes;

    }

    //gameplay

    function place($x, $y, $type, player_a $on_turn)// plays on coordinates, if requirements else return false
    {
        if ($this->check_requirement($x, $y, $on_turn, $type)) {
            $this->place_local($x, $y, $type, $on_turn);
            return true;
        } else return false;
    }

    function place_local($x, $y, $type, player_a $on_turn)// plays on coordinates, no requirements.
    {
        $this->board[$x][$y][0] = $on_turn->id;
        $this->board[$x][$y][1] = $type;
    }


    function check_requirement($x, $y, player_a $on_turn, $type)
    {
        if ($this->board[$x][$y][self::owner] == "neutral") {
            return true;
        } else return false;
    }

    function check_victory($x, $y, player_a $on_turn)// ne deluvle za večje boarde
    {
        #check if previous move caused a win on vertical line
        if ($this->board[0][$y][self::owner] == $on_turn->id and
            $this->board[1][$y][self::owner] == $on_turn->id and
            $this->board[2][$y][self::owner] == $on_turn->id
        ) {
            return True;
        }
        #check if previous move caused a win on horizontal line
        if ($this->board[$x][0][self::owner] == $on_turn->id and
            $this->board[$x][1][self::owner] == $on_turn->id and
            $this->board[$x][2][self::owner] == $on_turn->id
        ) {
            return True;
        }
        #check if previous move was on the main diagonal and caused a win
        if ($x == $y and
            $this->board[0][0][self::owner] == $on_turn->id and
            $this->board[1][1][self::owner] == $on_turn->id and
            $this->board [2][2][self::owner] == $on_turn->id
        ) {
            return True;
        }
        #check if previous move was on the secondary diagonal and caused a win
        if ($x + $y == 2 and
            $this->board[0][2][self::owner] == $on_turn->id and
            $this->board[1][1][self::owner] == $on_turn->id and
            $this->board[2][0][self::owner] == $on_turn->id
        ) {
            return True;
        }
        return False;
    }

    function get_hevristics(player_a $p1, player_a $p2)
    {
        $skupno = 0;
        if ($p1 == $p2) $skupno += 1;
        return $skupno;
    }


}

function create_function_bluecat($name, $parameters, $code)
{// doesnt check for existing
    $function = "function $name ($parameters){ $code }";
    eval($function);
}

function create_pattern_ai_function($database, $pattern_ai)
{
    if (function_exists($pattern_ai) == 0) {
        $parameters = "board_GT \$board, \$x, \$y, player_GT \$player";
        $pattern_ai_function = mysqli_query($database, "SELECT * FROM pattern_ai WHERE pattern_ai='$pattern_ai'") or die (mysqli_error($database) . "$pattern_ai");
        $pattern_ai_function = mysqli_fetch_assoc($pattern_ai_function);
        $pattern_ai_function = $pattern_ai_function['function'];
        create_function_bluecat($pattern_ai, $parameters, $pattern_ai_function);
    }

}

function insert_mysql_priorities(game $game, $ai_options, $goblin_tower_ai_options)
{
    global $goblin_tower;
    $ongoing_games1 = mysqli_query($goblin_tower, "SELECT * FROM ongoing_games where game_name='$game->name'");
    $ongoing_games = mysqli_fetch_assoc($ongoing_games1);
    $x_size = $ongoing_games['x_size'];
    $y_size = $ongoing_games['y_size'];
    for ($i = 0; $i < $y_size; $i++) {
        for ($j = 0; $j < $x_size; $j++) {
            mysqli_query($goblin_tower_ai_options, "DELETE FROM $game->name WHERE x='$j' and y='$i'");
            if (isset($ai_options[$j][$i]))
                if ($ai_options[$j][$i] > 0) {//if priority >0 then insert into table
                    $priority = $ai_options[$j][$i];
                    mysqli_query($goblin_tower_ai_options, "INSERT INTO $game->name (x,y,priority) VALUES ('$j','$i','$priority')");
                }
        }
    }
}

function to_the_future($future_deepness, $current_deepness,
                       board_GT $board, player_GT $on_turn, player_GT $not_on_turn,
                       $total_possible_moves_both_players, $learn_mode_enabled
)
{
    $placed_piece = "tent";// test still not fixed
    $counting_deepness = 2;
    if (empty($on_turn->options_priority_desc)) {
        return 0;
    }
    //startup
    $times_lost = 0;
    $x_y_lost = array();
    $result = 0;
    $result_not_on_turn = 0;
    for ($i = 0; $i < count($on_turn->options_priority_desc); $i++) {// trying first moves by all possible options with priority descending
        $result = 0;

        if ($on_turn->options_priority_desc[$i][2] < $on_turn->minimal_priority) {// breaks if next priority is smaller than minimal priority(not worthy to check), there fore it didnt find a win
            $result_not_on_turn = 1;
            break;
        }
        $on_turn_tmp = clone $on_turn;
        $not_on_turn_tmp = clone $not_on_turn;
        $board_tmp = clone $board;
        $x = $on_turn->options_priority_desc[$i][0];
        $y = $on_turn->options_priority_desc[$i][1];


        $board_tmp->place_local_sort_priority($x, $y, $placed_piece, $on_turn_tmp, $not_on_turn_tmp);//play given option also changes board, on_turn, not_on_tun,options and descending optionsZ
        if ($future_deepness > $current_deepness) {
            $result_not_on_turn = to_the_future($future_deepness, $current_deepness + 1,
                $board_tmp, $not_on_turn_tmp, $on_turn_tmp,
                $total_possible_moves_both_players, $learn_mode_enabled);
        } else {//fix basic ai
            $result_not_on_turn = basic_ai($board_tmp, $not_on_turn_tmp, $on_turn_tmp, $total_possible_moves_both_players);// play next steps with priorites withouth checking future
        }
        if ($result_not_on_turn == 0) {//checks if he won, if he did he plays that option and breaks
            if ($current_deepness == 0) {
                $result = array();
                $result[0] = $x;
                $result[1] = $y;
            } else {
                $result = 1;
            }
            if ($learn_mode_enabled == true) {// if he lost on best priority and won on another? hevristics can be improved!
                if ($times_lost > 0) {//najnovejse je ucinkovito?


                    add_test_move($board->board, $board->size[0], $board->size[1],
                        $x, $y,
                        $current_deepness, $times_lost, $x_y_lost, $on_turn->id, $on_turn->pattern_ai);

                }
            }
            break;
        }// else lose on this option
        if ($learn_mode_enabled == true) {//tmp
            if ($current_deepness <= $counting_deepness) {
                $x_y_lost[$i] = $on_turn->options_priority_desc[$i][0] . "," . $on_turn->options_priority_desc[$i][1];
                $times_lost++;
            }
        }
    }//checks if he lost, then he plays his best priority... maybe should play where he found most wins
    if ($result_not_on_turn != 0) {//lose
        if ($current_deepness == 0) {
            $result = array();
            $result[0] = $on_turn->options_priority_desc[0][0];
            $result[1] = $on_turn->options_priority_desc[0][1];
        } else {
            $result = 0;
        }
    }
    return $result;
}

function check_end(board_GT $board, $x, $y, player_GT $player)
{
    $owner_count_1x1 = $board->find_nearby_fields($x, $y);
    $enemy_field = $player->enemy;

    $neutral_fields = $owner_count_1x1[0];
    $enemy_fields = $owner_count_1x1[$enemy_field];
    $priority = 1;
    if ($enemy_fields >= 1)
        $priority = 402;

    return $priority;
}

function check_end_basic_ai(board_GT $board, player_GT $on_turn, player_GT $not_on_turn, $total_possible_moves_both_players)// if 1 then win, if 0 then lose, if -1 not game end
{
    $placed_piece = "tent";
    $win = 1;

    for ($j = 0; $j < $total_possible_moves_both_players; $j++) {//$result==0){ //$j+1 should  be a blocker

        if (!empty($on_turn->options_priority_desc)) {
            if ($on_turn->options_priority_desc[0][2] == 402) {
                $win = -1;
                break;
            }
            $result_on_turn = $on_turn->options_priority_desc[0];//if result = 0 return result 1 break
        } else {
            $win = 0;
            break;
        }
        $board->place_local_sort_priority($result_on_turn[0], $result_on_turn[1], $placed_piece, $on_turn, $not_on_turn);


        if (!empty($not_on_turn->options_priority_desc)) {
            if ($not_on_turn->options_priority_desc[0][2] == 402) {
                $win = -1;
                break;
            }
            $result_not_on_turn = $not_on_turn->options_priority_desc[0];//if result = 0 return result 0 break
        } else {
            $win = 1;
            break;
        }
        $board->place_local_sort_priority($result_not_on_turn[0], $result_not_on_turn[1], $placed_piece, $not_on_turn, $on_turn);


        if ($j >= $total_possible_moves_both_players) {// total possible moves bi se mogo spreminjati sikdar samo se nej kda ide notri tou neje tak pomembno bole za debbuganje samo echo naj ostane kda de fixano prvo
            echo $total_possible_moves_both_players . "err";
            $result_on_turn = "error";
            break;
        }
    }
    return $win;
}

function basic_ai(board_GT $board, player_GT $on_turn, player_GT $not_on_turn, $total_possible_moves_both_players)
{
    global $my_time1, $my_time2, $my_time3, $my_time4, $my_time5, $my_time6;//time
    $placed_piece = "tent";
    $time_start = microtime(true);//time
    $win = 1;

    for ($j = 0; $j < $total_possible_moves_both_players; $j++) {//$result==0){ //$j+1 should  be a blocker
        if (!empty($on_turn->options_priority_desc))
            $result_on_turn = $on_turn->options_priority_desc[0];//if result = 0 return result 1 break
        else {
            $win = 0;
            break;
        }
        $board->place_local_sort_priority($result_on_turn[0], $result_on_turn[1], $placed_piece, $on_turn, $not_on_turn);

        if (!empty($not_on_turn->options_priority_desc))
            $result_not_on_turn = $not_on_turn->options_priority_desc[0];//if result = 0 return result 0 break
        else {
            break;
        }
        $board->place_local_sort_priority($result_not_on_turn[0], $result_not_on_turn[1], $placed_piece, $not_on_turn, $on_turn);

        if ($j >= $total_possible_moves_both_players) {// total possible moves bi se mogo spreminjati sikdar samo se nej kda ide notri tou neje tak pomembno bole za debbuganje samo echo naj ostane kda de fixano prvo
            echo $total_possible_moves_both_players . "err";
            $result_on_turn = "error";
            break;
        }
    }
    $time_end = microtime(true);//time
    $my_time1 += ($time_end - $time_start);//time
    return $win;
}



if(!isset($database)){
    $database = new database("","");
}