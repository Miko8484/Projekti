<?php

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

        if (!empty($on_turn->options_priority_desc)){
            if ($on_turn->options_priority_desc[0][2] == 402) {
                $win = -1;
                break;
            }
            $result_on_turn = $on_turn->options_priority_desc[0];//if result = 0 return result 1 break
        }else {
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
