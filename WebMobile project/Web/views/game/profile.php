<?php
$game_account=game_account::find_by_id($_SESSION["logged_account"]->id);

?>

<table>
    <tr>
        <td><strong>Username : </strong></td>
        <td><?php echo $game_account->username; ?></td>
    </tr>
    <tr>
        <td><strong>Number of games : </strong></td>
        <td><?php echo $game_account->number_of_games; ?></td>
    </tr>
    <tr>
        <td><strong>Wins : </strong></td>
        <td><?php echo $game_account->number_of_wins; ?></td>
    </tr>
    <tr>
        <td><strong>Loses : </strong></td>
        <td><?php echo $game_account->number_of_loses; ?></td>
    </tr>
    <tr>
        <td><strong>Campaign level : </strong></td>
        <td><?php echo $game_account->campaign_level; ?></td>
    </tr>
</table>
<hr>