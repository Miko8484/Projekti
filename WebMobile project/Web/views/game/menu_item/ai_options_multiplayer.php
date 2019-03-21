<input id="pattern_ai" name="pattern_ai" type="hidden" value="function_return_1">
<input id="brute_force_deepness" name="brute_force_deepness" type="hidden" value="0">
<input id="minimal_priority" name="minimal_priority" type="hidden" value="1">


<?php
$result = mysqli_query(db::getInstance(),"SELECT id,username FROM account");


?>

<div class="form-group">
    <label for="player_2_name">choose a player:</label>
    <select id="player_2_name" name="player_2_name">
        <?php while ($row =  mysqli_fetch_assoc($result)): ?>

            <?php if ($row["id"]!=$_SESSION["logged_account"]->id): ?>
                <option value="<?php echo $row["username"]; ?>"><?php echo $row["username"]; ?></option>
            <?php endif; ?>
        <?php endwhile; ?>
    </select>
</div>


<br>

<button class="btn btn-default" type="submit" name="custom" value="player_vs_player">play vs player</button>


