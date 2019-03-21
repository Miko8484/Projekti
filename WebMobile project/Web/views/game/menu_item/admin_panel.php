





<?php if( $_SESSION['logged_account']->is_admin==true and isset ($game_id)): ?>

<div class="menu_item">
    <h2>admin panel</h2><p> <br>- this is only avaible for admins.</p>


        <form action="?controller=game&action=end_game" method="post">

                <input type="hidden" name="game_id" value="<?php echo $game_id ?>"/>
                End game <input class="send_btn" type="submit" value="Go" alt="Go" title="Go" />
            </table>
        </form>


</div>
<?php endif;?>

