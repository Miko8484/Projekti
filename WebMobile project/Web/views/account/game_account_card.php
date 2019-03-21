<?php
require_once ("leagues.php");
?>

<div class="well">
		<img width="100%" height="100%" alt="User Pic" src="user_image/<?php echo $game_account->image ?>" class="image-circle img-responsive">
        <h3 class="card-subtitle mb-2 text-muted"><?php echo $game_account->username ?></h3>
        <p class="card-text">
			Games: <?php echo $game_account->number_of_games ?> <br/>
            Wins: <?php echo $game_account->number_of_wins ?><br/>
            Loses: <?php echo $game_account->number_of_loses ?>
        </p>
        <p class="text-warning">ELO: <?php echo $game_account->elo ?></p>
        <a  class="text-success">League:<?php echo get_liga($game_account->elo) ?></a>
</div>