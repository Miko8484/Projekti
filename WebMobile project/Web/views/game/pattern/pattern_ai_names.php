<?php

$goblin_tower=database::get_GTdb();
$result = mysqli_query ($goblin_tower,"SELECT pattern_ai FROM pattern_ai  ORDER BY id asc");

$i=0;
$arr = array();
while($row = mysqli_fetch_assoc($result)){
    $arr[$i++]=$row['pattern_ai'];
}
$arr_size =count($arr);


$arr2 = array(
    0,
    1,
    2,
    3,
    4,
    5,
    6,
    7,
    8,
    9,
    100000
);

$arr2_size= count($arr2);


?>