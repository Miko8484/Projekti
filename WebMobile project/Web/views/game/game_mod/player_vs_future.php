

<script type="text/javascript">
    function play(x, y) {
        var enemy="<?php echo $player_2_game_id?>";
        var player_1_basic_tower="<?php echo $player_1_basic_tower; ?>";
        var player_2_basic_tower="<?php echo $player_2_basic_tower; ?>";
        var player_1_color="<?php echo $_SESSION["player_1"]->color; ?>";
        var player_2_color="<?php echo $_SESSION["player_2"]->color; ?>";

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
        xhttp.open("GET", "<?php echo "http://".$_SERVER["SERVER_NAME"] ?>/views/game/play.php?x=" + x + "&y=" + y, true);
        xhttp.send();
    }
</script>