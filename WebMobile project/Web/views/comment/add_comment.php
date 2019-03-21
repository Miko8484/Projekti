<?php
if (isset($_SESSION['logged_account'])) {
    $logged=1;
}else{
    $logged=0;
}

?>
<p>add comment</p>

<form action="?controller=comment&action=add_comment" method="post">
    <div class="form-group">
        <label for="content">content</label><input type="text" class="form-control" name="content" value=""/>
<?php if($logged!=1): ?>
        <label for="user_mail">email</label><input type="text" class="form-control" name="user_mail" value=""/>
<?php endif;?>
        <input type="hidden"  name="oglas_id" value="<?php echo $_GET["oglas_id"] ?>"/>

        <input class="btn btn-primary" type="submit" name="add" value="add"/>
    </div>
</form>