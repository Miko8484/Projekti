<script>
    document.getElementById("main_content_header").innerHTML="History";
</script>
<div class="text-center">
<div class="table-responsive">
<table class="table " style="width:99%">
    <thead id="table-head">
      <tr>
        <th style="padding-left:11.5%">Winner</th>
        <th style="padding-left:11.5%">Loser</th>
		<th style="padding-left:10%">ELO</th>
        <th style="padding-left:9%">Date</th>
      </tr>
    </thead>
    <tbody id="tbody">
        <tr class="table-row" >
            <?php foreach ( $history_arr as  $history) {?>
                <?php
                $winner = game_account::find_by_id($history->winner_id);
                $loser = game_account::find_by_id($history->loser_id);

                if($winner->id == $_SESSION["logged_account"]->id){
                    $elo_gain=$history->elo_gain;
                }else{
                    $elo_gain=-($history->elo_gain);
                }

                ?>
                <tr class="table-row" >
                    <td style="width:25%;"><a style="color:black" href="?controller=account&action=view_account&id=<?php echo  $history->winner_id ?>"> <?php echo $winner->username."($winner->elo)"  ?></a></td>
                    <td style="width:25%;"><a style="color:black" href="?controller=account&action=view_account&id=<?php echo  $history->loser_id ?>"> <?php echo $loser->username."($loser->elo)"  ?></a></td>
                    <td style="width:20%"> <?php echo  $elo_gain ?></td>
                    <td style="width:20%"> <?php echo  $history->date ?></td>
                </tr>
            <?php } ?>
        </tr>
</tbody>
</table>
</div>
   
</div>

