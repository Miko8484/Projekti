<?php if (!empty($_SESSION['logged_account'])): ?>
    <?php include("form_custom_board.php") ?>

    <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>

        <div class="game_item">
            <table class="float_left board_table">
                <script type="text/javascript">
                    x_size =<?php echo strip_tags($_POST['x_size']);?>;
                    y_size =<?php echo strip_tags($_POST['y_size']);?>;
                    board_name =<?php echo json_encode(strip_tags($_POST['board_name']));?>;
                    <?php include("generate_board.js") ?>
                    <?php include("draw_board.js") ?>
                </script>
            </table>
        </div>
        <?php include("selections/color_selection.php") ?>
        <?php include("selections/tower_and_color_script.php") ?>

        <!-- play memory or future -->
        <div>
            select on turn ->
            <label >
                player_1(red)<input type="radio" name="player" value="1" id="not_on_turn">
                player_2(blue)<input type="radio" name="player" value="2" id="on_turn" checked="checked">
            </label>
            <br>
            <input id="play_memory" type="submit"  value="play_memory">
        </div>

    <?php endif; ?>
<?php else : ?>


    <strong id="log_in"><p>log in to test boards</p></strong>

<?php endif; ?>