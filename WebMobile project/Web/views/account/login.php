<?php
if(isset($_SESSION['logged_account'])) {die("already logined");}
?>

<?php

?>
<div align="center">
	<h2><strong>Login here</strong></h2>
</div>
<p style="color:red"><strong><?php echo $msg; ?></strong></p><br/>
		
<form class="form-horizontal" name="login" action="?controller=account&action=login" method="post" style="margin-left:30%">
  <div class="form-group">
    <label class="control-label col-sm-2" for="email">Username:</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" name="username" id="text" placeholder="Enter username">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="pwd">Password:</label>
    <div class="col-sm-4"> 
      <input type="password" class="form-control" name="password" id="pwd" placeholder="Enter password">
    </div>
  </div>
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Submit</button>
    </div>
  </div>
</form>