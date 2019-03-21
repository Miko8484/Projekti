<?php

$logged_player = $_SESSION["logged_player"];
$other_player = $_SESSION["other_player"];
?>
<script type="text/javascript">


    var enemy = "<?php echo $other_player->id?>";
    var player_1_basic_tower = "<?php echo $player_1_basic_tower; ?>";
    var player_2_basic_tower = "<?php echo $player_2_basic_tower; ?>";
    var player_1_color = "<?php echo $logged_player->color; ?>";
    var player_2_color = "<?php echo $other_player->color; ?>";

    function executeQuery() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {

                if (this.responseText) {
                    result = JSON.parse(this.responseText);
                    if (result[4] == enemy) {
                        cell = document.getElementById("cell_" + result[3]);
                        $(cell).addClass(player_2_color);
                        $(cell).addClass(player_2_basic_tower);
                        $(cell).prop('onclick', null).off('click');// ene igre majo ka lejko onemogočiš
                    }
                }
            }
        };
        xhttp.open("GET", '<?php echo "http://" . $_SERVER["SERVER_NAME"] ?>/views/game/get_last.php?game_id=<?php echo $game_id ?>', true);
        xhttp.send();
        setTimeout(executeQuery, 2000); // you could choose not to continue on failure...
    }

    $(document).ready(function () {
        // run the first time; all subsequent calls will take care of themselves
        setTimeout(executeQuery, 5000);
    });
    function play(x, y) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                result = JSON.parse(this.responseText);
                cell = document.getElementById("cell_" + x + "_" + y);
                if (result[0] == 1) {// if move is legal
                    property = document.getElementById("turn");
                    $(property).html(turn++);
                    $(cell).addClass(player_1_color);
                    $(cell).addClass(player_1_basic_tower);
                    $(cell).prop('onclick', null).off('click');// ene igre majo ka lejko onemogočiš

                    if (result[2] == 1) {// check if win
                        alert("you win");
                        for (i = 0; i < 10; i++) { // onemogoči za vse
                            for (j = 0; j < 10; j++) { // onemogoči za vse
                                cell = document.getElementById("cell_" + i + "_" + j);
                                $(cell).prop('onclick', null).off('click');
                            }
                        }
                    } else {
                        cell = document.getElementById("cell_" + result[1][0] + "_" + result[1][1]);
                        $(cell).addClass(player_2_color);
                        $(cell).addClass(player_2_basic_tower);
                        $(cell).prop('onclick', null).off('click');// ene igre majo ka lejko onemogočiš
                        if (result[2] == 2) {// you lose
                            alert("you lose");
                            for (i = 0; i < 10; i++) { // onemogoči za vse
                                for (j = 0; j < 10; j++) { // onemogoči za vse
                                    cell = document.getElementById("cell_" + i + "_" + j);
                                    $(cell).prop('onclick', null).off('click');
                                }
                            }
                        }
                    }
                } else {
                    //$(cell).addClass("cell empty");
                }
            }
        };
        xhttp.open("GET", "<?php echo "http://" . $_SERVER["SERVER_NAME"] ?>/views/game/play_multiplayer.php?x=" + x + "&y=" + y, true);
        xhttp.send();
    }


</script>

