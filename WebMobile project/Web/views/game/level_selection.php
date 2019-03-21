<?php
//include("profile.php");
?>

<?php


$arr1 = array(
    5,
    6,
    7,
    8,
    9,
    10
);

$arr2 = array(0,
    1, 2, 3, 4,
    5,
    6,
    7,
    8,
    9,
    10
);


?>

<div id="level_selection" class="text-center ">
    <form name="reg" action="?controller=game&action=create_new_game" method="post">
        <div class="item_part_half">
            <h2>create new game</h2>
            <p> create xy sized board with basic start</p>

            <div class="form-group">
                <label for="x_size">X size:</label>
                <select name="x_size">
                    <?php for ($i = 0; $i < count($arr1); $i++): ?>
                        <option value="<?php echo $arr1[$i]; ?>" <?php
                        if ($arr1[$i] == 6) {
                            echo "selected='selected'";
                        }
                        ?>><?php echo $arr1[$i]; ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="y_size"> Y size:</label>

                <select name="y_size">
                    <?php for ($i = 0; $i < count($arr1); $i++): ?>
                        <option value="<?php echo $arr1[$i]; ?>" <?php if ($arr1[$i] == 6) {
                            echo "selected='selected'";
                        } ?>><?php echo $arr1[$i]; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="number_of_obstacles">Number of obstacles</label>
                <select name="number_of_obstacles">
                    <?php for ($i = 0; $i < count($arr1); $i++): ?>
                        <option value="<?php echo $arr1[$i]; ?>"><?php echo $arr1[$i]; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>

        <div class="item_part_half">
            <h3>Select your color:</h3>
            <div>
                <div>
                    <label class="nav_img">
                        <input type="radio" name="color" value="red" checked="checked">
                        <div class="cell owner1"></div>
                    </label>
                    <label style="margin-left:50px" class="nav_img">
                        <!--<span>blue</span> -->
                        <input type="radio" name="color" value="blue">
                        <div class="cell owner2"></div>
                    </label>
                </div>
            </div>
        </div>
        <br>


        <div class="game_item">
            <?php

            include("level_selection_singleplayer.php");
            include("level_selection_multiplayer.php");

            ?>
        </div>
        <?php
        include("menu_item/admin_panel.php");
        ?>
    </form>
</div>
    