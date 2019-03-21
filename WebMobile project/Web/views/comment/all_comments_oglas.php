<?php
?>
<p>Seznam vseh komentarjev</p>


<a href="?controller=comment&action=add_comment_page&oglas_id=<?php echo $oglas_id ?>" class="btn btn-primary">add <span
            class="glyphicon glyphicon-plus"></span></a>


<?php foreach ($comments as $comment) { ?>

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

<?php } ?>



