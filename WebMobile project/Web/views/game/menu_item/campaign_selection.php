<?php

//$goblin_tower=mysqli_connect('localhost',$db_user,$db_pass,"goblin_tower") or die("Couldn't connect to database [".mysqli_errno($sql)."]");
$level=$game_account['campaign_level'];
$campaign1=mysqli_query($goblin_tower,"SELECT * FROM campaign WHERE level='$level'");
$campaign=mysqli_fetch_assoc($campaign1);


?>

<?php if(isset($campaign)): ?>
    <div class="game_item" >
        <h2>Campaign level </h2>
        <h3>level <?php echo $game_account['campaign_level'] ?></h3>
        <h3><?php echo $campaign['name']." ".$campaign['race']?></h3>

        <div class="game_item_image">
            <img src="includes/race_avatar/goblin.png" />
        </div>

        <div class="game_item_part">

            <p><?php echo $campaign['description'] ?></p>
            <input type="submit" value="battle!" name="campaign" >

            <br>
        </div>
    </div>
    <hr>

    <?php else : ?>
<div class="game_item" >
        You won the campaign congratulations!
    <img src="includes/blue-user-icon.png" />
    <img src="includes/blue-user-icon.png" />
    <img src="includes/blue-user-icon.png" />
    <img src="includes/blue-user-icon.png" />
    <img src="includes/blue-user-icon.png" />
    <img src="includes/blue-user-icon.png" />
    <img src="includes/blue-user-icon.png" />
    <hr>
</div>
<?php endif;?>



