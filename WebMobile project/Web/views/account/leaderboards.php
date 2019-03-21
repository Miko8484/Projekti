<script>
document.getElementById("main_content_header").innerHTML="Leaderboards";
</script>

<div class="text-center">

<?php

require_once ("leagues.php")
?>
<div class="table-responsive">
<table class="table " style="width:99%">
    <thead id="table-head">
      <tr>
        <th style="padding-left:11.5%">Username</th>
        <th style="padding-left:8.5%">ELO</th>
		<th style="padding-left:7.5%">League</th>
        <th style="padding-left:2.5%">Games</th>
        <th style="padding-left:3%">Wins</th>
		<th style="padding-left:2.5%">Loses</th>
      </tr>
    </thead>
    <tbody id="tbody">
	<?php foreach ($accounts as $account) {?>
        <tr class="table-row" >
            <td style="width:30%;"><a style="color:black" href="?controller=account&action=view_account&id=<?php echo $account->id ?>"> <?php echo $account->username ?></a></td>
            <td style="width:20%"> <?php echo $account->elo ?></td>
            <td style="width:20%"> <?php echo get_liga($account->elo) ?></td>
			<td style="width:10%"> <?php echo $account->number_of_games ?></td>
            <td style="width:10%"> <?php echo $account->number_of_wins ?></td>
            <td style="width:10%"> <?php echo $account->number_of_loses ?></td>
        </tr>
    <?php } ?>
</tbody>
</table>
</div>
<br/>
<p>0="bronze";</p>

<p>1500="silver";</p>

<p>2000="gold";</p>
</div>

