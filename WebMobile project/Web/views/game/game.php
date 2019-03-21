<?php if(!empty($_SESSION['logged_account'])): ?>

    <?php
    $dbb =db::getInstance();

    $include="";
    if($game_account->id_of_current_game==0 and !isset($game_start)){
        $include="level_selection.php";
    }
    else{
 //       require_once ($_SERVER['DOCUMENT_ROOT']."/connection.php");
   //     require_once ($_SERVER['DOCUMENT_ROOT']."/models/game.php");

        $game_id = $game_account->id_of_current_game;
        $include="game_main.php";
    }
    if($include)
        include $include;
    ?>

<?php else : ?>

    <strong id="log_in"><p>log in to view game</p></strong>

<?php endif;?>






