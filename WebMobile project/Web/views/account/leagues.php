<?php
//0 = pogoj, 1 = naziv

$league="";

function get_liga($elo){
    $leagues[0][0]=0;
    $leagues[0][1]="bronze";

    $leagues[1][0]=1500;
    $leagues[1][1]="silver";

    $leagues[2][0]=2000;
    $leagues[2][1]="gold";


    $st_lig=3;



    for($i=$st_lig-1;$i>=0;$i--){
        if($elo>=$leagues[$i][0]){
            return  $leagues[$i][1];
        }
    }
    return 0;
}

?>