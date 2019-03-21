<?php



require_once($_SERVER['DOCUMENT_ROOT'] . "/connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/models/game.php");

function find_nearby_fields($x, $y, board_GT $board)
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
        if (isset($board->board[$priority_x][$priority_y][0])) {
            $owner = $board->board[$priority_x][$priority_y][0];
            $owner_count_1x1[$owner]++;
        } else {
            $owner_count_1x1[0]++;
        }
    }
    $time_end = microtime(true);//time
    $my_time5 += ($time_end - $time_start);//time
    return $owner_count_1x1;
}


//tmp
$owners[0] = 1;//friendly
$owners[1] = 2;//enemy
$owners[2] = 0;//neutral
$owners[3] = 3;//obstacle
static $nm_of_stats = 4;
echo "stat 1 = friendly";
echo "stat 2 = enemy";
echo "stat 3 = neutral";
echo "stat 4 = obstacle <br>";

$goblin_tower = database::get_GTdb();

$memory_moves_stats = mysqli_query($goblin_tower, "SELECT * FROM memory_moves_stats ORDER BY id DESC LIMIT 0, 1");
$memory_moves_stats = mysqli_fetch_assoc($memory_moves_stats);
$start_id = 1;//fix prlej je blo tak$memory_moves_stats['start_id'];
$end_id = 100;//fix prlej je blo tak $memory_moves_stats['end_id'];
$x_size = 3;//$memory_moves_stats['x_size'];
$y_size = 3;//$memory_moves_stats['y_size'];


if ($start_id == null) {
    echo "error 1";
}

$difference_count_greater = array();
$difference_count_smaller = array();
for ($j = 0; $j < $nm_of_stats; $j++) {
    $difference_count_greater[$j] = 0;
    $difference_count_smaller[$j] = 0;
}

$avg_win_counter = array();
for ($j = 0; $j < $nm_of_stats; $j++) {
    $avg_win_counter[$j] = 0;
}

$win_possitions = array();
$win_possitions_counter = 0;


$lose_possitions = array();
$lose_possitions_counter = 0;


for ($i = $start_id; $i < $end_id; $i++) {

    $game_name = "board_" . $i;
    $move = mysqli_query($goblin_tower, "SELECT * FROM memory_moves WHERE x_size='$x_size' and y_size='$y_size' and game_name='$game_name'");
    $move = mysqli_fetch_assoc($move);

    $on_turn = $move['on_turn'];

    if ($on_turn == 1) $not_on_turn = 2;
    else $not_on_turn = 1;

    $times_lost = $move['times_lost'];
    $x_y_lost = json_decode($move['x_y_lost']);
    $board = new board_GT("asd",array(3,3),json_decode($move['board']));
    $x = $move['x'];
    $y = $move['y'];

    $win_statistics = find_nearby_fields($x, $y, $board);

    // get all 1x1 around for win
    $win_statistics_encoded = json_encode($win_statistics);
    if (!isset($$win_statistics_encoded)) {
        $$win_statistics_encoded = 1;
        $win_possitions[$win_possitions_counter++] = $win_statistics_encoded;
    } else {
        $$win_statistics_encoded++;
    }

    $lose_statistics_arr = array();
    for ($j = 0; $j < $times_lost; $j++) {
        $lose_statistics_arr[$j] = find_nearby_fields($x_y_lost[$j][0], $x_y_lost[$j][1], $board);

        // get all 1x1 around for loss
        $lose_statistics_encoded = json_encode($lose_statistics_arr[$j]);
        if (!isset($$lose_statistics_encoded)) {
            $$lose_statistics_encoded = 1;
            $lose_possitions[$lose_possitions_counter++] = $lose_statistics_encoded;
        } else {
            $$lose_statistics_encoded++;
        }


    }

    if ($times_lost > 0) {
        check_stats($win_statistics, $lose_statistics_arr, $times_lost);
    }
}


for ($j = 0; $j < 2; $j++) {
    echo $difference_count_smaller[$j] . ", ";
    echo $difference_count_greater[$j] . ", avgwin";

    if(($difference_count_greater[$j]+$difference_count_smaller[$j])>0){
    echo($avg_win_counter[$j] / ($difference_count_greater[$j] + $difference_count_smaller[$j]));
    }
    echo "<br>";
}
$difference_count_smaller1 = 1;
$difference_count_greater2 = 10;
echo $difference_count_smaller1 . ", ";
echo $difference_count_greater2 . ", avgwin";




function check_stats($win_statistics, $lose_statistics_arr, $times_lost)
{
    global $nm_of_stats, $owners;
    global $difference_count_smaller, $difference_count_greater, $avg_win_counter;


    for ($i = 0; $i < $times_lost; $i++) {
        for ($j = 0; $j < $nm_of_stats; $j++) {
            if (isset($win_statistics[$j]) and isset($lose_statistics_arr[$j]) and$win_statistics[$j] > $lose_statistics_arr[$i][$j]) {
                $difference_count_greater[$j]++;
                $avg_win_counter[$j] += $win_statistics[$j];
            } else if (isset($win_statistics[$j]) and isset($lose_statistics_arr[$j]) and $win_statistics[$j] < $lose_statistics_arr[$i][$j]) {
                $difference_count_smaller[$j]++;
                $avg_win_counter[$j] -= $win_statistics[$j];
            }
        }
    }


}

?>
