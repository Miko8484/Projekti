<?php

function function_return_1(board_GT $board, $x, $y, player_GT $player)
{
    return 1;
}

function prvi_ai(board_GT $board, $x, $y, player_GT $player)
{
    $owner_count_1x1 = $board->find_nearby_fields($x, $y);
    $neutral_field = 0;
    $friendly_field = $player->id;
    $enemy_field = $player->enemy;

    $neutral_fields = $owner_count_1x1[0];
    $friendly_fields = $owner_count_1x1[$friendly_field];
    $enemy_fields = $owner_count_1x1[$enemy_field];
    $priority = 1;
    if ($enemy_fields >= 1)
        $priority += 33 / 19;
    if ($neutral_field >= 5)
        $priority += 1;

    return $priority;
}

function drugi_ai(board_GT $board, $x, $y, player_GT $player)
{

    $owner_count_1x1 = $board->find_nearby_fields($x, $y);


    $neutral_field = 0;
    $friendly_field = $player->id;
    $enemy_field = $player->enemy;

    $neutral_fields = $owner_count_1x1[0];
    $friendly_fields = $owner_count_1x1[$player->id];
    $enemy_fields = $owner_count_1x1[$player->enemy];

    $arr_size[0] = 3;
    $arr_size[1] = 3;
    $priority = 1;

    if ($enemy_fields >= 1)
        $priority += 33 / 19;
    if ($neutral_field >= 5)
        $priority += 1;
    /*
        if($obstacle_fields<1)
            $priority+=1;
    */

    return $priority;
}


function goblins_level_1($board, $x_size, $y_size, $x, $y, $friendly, $enemy)
{//prvo more začnoti z velkimi prioritetami ka te ne glejda vseh
    //initialisation


    $owner_count_1x1 = find_nearby_fields($x, $y, $board, $friendly, $enemy);


    $friendly_field = $friendly;
    $enemy_field = $enemy;
    $neutral_field = $owners[2];
    $obstacle_field = $owners[3];

    $friendly_fields = $owner_count_1x1[0];
    $enemy_fields = $owner_count_1x1[1];
    $neutral_fields = $owner_count_1x1[2];
    $obstacle_fields = $owner_count_1x1[3];

    $arr_size[0] = 3;
    $arr_size[1] = 3;
    $priority = 0;
    //  if($friendly==2)echo $friendly_field." asd";
    $priority = 1;
    if ($enemy_fields == 1) {// boti falijo robni primeri, pa fali jemi izracunava bokše poteze, torej brani tisto ge ma vec polj praznih vse skup, ali pa napadne tisto ge je vec polj vse skup( ne vijde boarde na velko) LEJKO VIJDI NA VELKO TAK KA ŠE ENGA AI-JA IMAGINARNOGA NOT BACIŠ PA SE ZNJIN IDE PA TAN GE ZMAGA TAN ZMAGUAUAUUAUA HAHAAHAHAHHA
        $priority = 2;
        if ($neutral_fields > 0) {
            $priority = 3;
            if ($neutral_fields > 1) {
                /*
                $arr = array();
                $arr[0][1] = $friendly_field;
                if (check_all($board, $x_size, $y_size, $x, $y, $arr, $arr_size)) {// ce je enemy polek spila vodoravno
                    $priority = 5;
                    return $priority;
                }
                */

                $arr = array();
                $arr[1][2] = $friendly_field;
                $arr[2][1] = $friendly_field;
                $arr[2][2] = $enemy_field;
                if (check_all($board, $x_size, $y_size, $x, $y, $arr, $arr_size)) {//brani križ kraž
                    $priority = 7;
                    return $priority;
                }

            }
        }
    }
    if ($enemy_fields >= 2) {
        $priority = 2;
        if ($neutral_fields > 0) {// vercja prioriteta ci so se neutral fielde
            $priority = 4;
            if ($neutral_fields > 1) {
                /*
                $arr = array();
                $arr[0][1] = $friendly_field;
                if (check_all($board, $x_size, $y_size, $x, $y, $arr, $arr_size)){// ce je enemy polek spila vodoravno
                    $priority = 6;
                    return $priority;
                }
                */
            }
            if ($neutral_fields > 2 and $friendly_fields == 1) {
                $arr = array();
                $arr[1][2] = $enemy_field;
                $arr[2][1] = $enemy_field;
                $arr[2][2] = $friendly_field;
                if (check_all($board, $x_size, $y_size, $x, $y, $arr, $arr_size)) {//napadne križ kražđ
                    $priority = 8;
                    return $priority;
                }
            }
        }
    }
    return $priority;
}


?>