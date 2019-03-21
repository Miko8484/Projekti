
<form action="?controller=game&action=custom_board" method="post">
    <table>
        <tr>
            <td colspan="2">
                <div align="center">
                    <strong>board options ( needs submit )</strong>
                </div>
            </td>
        </tr>
        <tr>

            <?php

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $board_name=$_POST['board_name'];
                $x_size=$_POST['x_size'];
                $y_size=$_POST['y_size'];
            }
            else{
                $board_name=$_SESSION['logged_account']->username."_board";
                $x_size=8;
                $y_size=8;
            }

            ?>
            <td><div align="left"><strong>board_name:</strong></div></td>
            <?php if($_SESSION['logged_account']->is_admin==1): ?>
                <td><input type="text" name="board_name" value="<?php echo $board_name?>"/></td>
            <?php else:?>
                <td><?php echo $_SESSION['logged_account']->username?>_board (you may own only 1 board unless you are admin)</td>
                <input type="hidden" name="board_name" value="<?php echo $board_name?>"/>
            <?php endif;?>

        </tr>
        <tr>
            <td><div align="left"><strong>x_size:</strong></div></td>
            <td><input type="text" name="x_size" value="<?php echo $x_size ?>"/></td>
        </tr>
        <tr>
            <td><div align="left"><strong>y_size:</strong></div></td>
            <td><input type="text" name="y_size" value="<?php echo $y_size ?>"/></td>
        </tr>
        <tr>
            <td>
                <input class="send_btn" type="submit" value="Go" alt="Go" title="Go" />
            </td>
        </tr>
        konec
    </table>
</form>
