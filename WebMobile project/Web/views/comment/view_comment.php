<?php
?>
<p>Komentar</p>

<div class="panel panel-default">
    <div class="panel-heading">

        <a href="?controller=comment&action=view_comment&id=<?php echo $comment->id ?>"> <?php echo $comment->id ?> </a>
        <?php if ($comment->user_id == -1) {
            echo $comment->user_mail;
        } else {
            echo account::najdi($comment->user_id)->username;
        }
        ?>


        <span class="label label-primary"> <?php echo $comment->date_created ?></span></div>
    <div class="panel-body">            <?php echo $comment->content ?></div>

</div>

<?php if(isset($_SESSION["logged_account"]) and $_SESSION["logged_account"]->id == $comment->user_id) :?>
    <a href="?controller=comment&action=delete_comment&id=<?php echo $comment->id ?>"> Delete comment </a>

<?php endif; ?>

