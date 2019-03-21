
            <?php
            $root=$_SERVER['DOCUMENT_ROOT'];

            require_once ("$root/connection.php");
            require_once("$root/views/game/pattern/pattern_ai_names.php");
            ?>

<div class="form-group">
    <label for="pattern_ai">pattern ai:</label>
    <select name="pattern_ai">
        <?php for ($i = 0; $i < $arr_size; $i++): ?>
            <option value="<?php echo $arr[$i]; ?>"><?php echo $arr[$i]; ?></option>
        <?php endfor; ?>
    </select>
</div>
<div class="form-group">
    <label for="brute_force_deepness">difficulty:</label>
    <select name="brute_force_deepness">
        <?php for ($i = 0; $i < $arr2_size; $i++): ?>
            <option value="<?php echo $arr2[$i]; ?>"><?php echo $arr2[$i]; ?></option>
        <?php endfor; ?>
    </select>
</div>
<div class="form-group">
    <label for="minimal_priority">minimal priority:</label>
    <select name="minimal_priority">
        <?php for ($i = 0; $i < $arr2_size; $i++): ?>
            <option value="<?php echo $arr2[$i]; ?>"><?php echo $arr2[$i]; ?></option>
        <?php endfor; ?>
    </select>
</div>
<button class="btn btn-default" type="submit" name="custom"  value="brute_force_and_pattern_ai">play vs AI</button>