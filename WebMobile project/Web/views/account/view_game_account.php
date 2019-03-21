<script>
document.getElementById("main_content_header").innerHTML="Account information";
</script>

<div class="container">
      <div class="row">
        <div class="col-md-8 toppad" >
          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title"><?php echo $game_account->username ?></h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-3 col-lg-3 " align="center"> 
					<img width="100%" height="100%" alt="User Pic" src="user_image/<?php echo $game_account->image ?>" class="img-responsive"> 
				</div>
                <div class=" col-md-9 col-lg-9 "> 
                  <table class="table table-user-information">
                    <tbody id="profil_card" style="background-color:white">
					  <tr>
                        <td>Firstname:</td>
                        <td><?php echo $account->firstname ?></td>
                      </tr>
					  <tr>
                        <td>Lastname:</td>
                        <td><?php echo $account->lastname ?></td>
                      </tr>
					  <tr>
                        <td>Email:</td>
                        <td><a href="mailto:<?php echo $account->email ?>"><?php echo $account->email ?></a></td>
                      </tr>
                      <tr>
                        <td>ELO:</td>
                        <td><?php echo $game_account->elo ?></td>
                      </tr>
                      <tr>
                        <td>Games:</td>
                        <td><?php echo $game_account->number_of_games ?></td>
                      </tr>
                      <tr>
                        <td>Wins:</td>
                        <td><?php echo $game_account->number_of_wins ?></td>
                      </tr>
                      <tr>
                        <td>Loses:</td>
                        <td><?php echo $game_account->number_of_loses ?></td>
                      </tr>
                      <tr>
                        <td>Campaign_level:</td>
                        <td><?php echo $game_account->campaign_level ?></td>
                      </tr>
                      <tr>
                          <td></td>
                          <td><a href="?controller=history&action=player_history<?php echo "&id=".$game_account->id;?>">view match_history </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>