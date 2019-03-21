


<div class="menu_item">
    <h2>create your board</h2><p> if you created your custom board you can play it here</p>

    <table>
        <?php if($account['is_admin']==1): ?>
            <td><input type="text" name="board_name" value="<?php echo $_SESSION['username']?>_board"/></td>
        <?php else:?>
            <td><?php echo $_SESSION['username']?>_board (you may own only 1 board unless you are admin)</td>
            <input type="hidden" name="board_name" value="<?php echo $_SESSION['username']?>_board"/>
        <?php endif;?>
        <tr>
            <td>
                <input class="send_btn" type="submit" name="your_custom"  value="play" alt="play" title="play" />
            </td>
        </tr>
    </table>
</div>


