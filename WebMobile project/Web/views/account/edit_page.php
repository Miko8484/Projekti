<script>
$('#theForm').submit(function() {
  var txt = $('#firstname');
  alert(txt.val());
  txt.val("updated " + txt.val());
});

document.getElementById("main_content_header").innerHTML="Account settings";
</script>


<form name="reg" action="?controller=account&action=edit" id="theForm" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <p style="color:#EB7260"><strong><big><?php echo $msg; ?></big></strong></p>
        <strong>Username:</strong>
        <input class="form-control" id="username" type="text" name="username" value="<?php if(isset($_POST['username'])){echo $_POST['username'];} 
																						   else{ echo $user->username;}?>" required />
		
        <strong>First name:</strong>
        <input class="form-control" id="firstname" type="text" name="firstname" value="<?php if(isset($_POST['firstname'])){echo $_POST['firstname'];} 
																						   else{ echo $user->firstname;}?>" required />
        <strong>Last name:</strong>
        <input class=" form-control" type="text" name="lastname" value="<?php if(isset($_POST['lastname'])){echo $_POST['lastname'];} 
																						   else{ echo $user->lastname;}?>" required />
        <strong>Email:</strong>
        <input class="form-control" type="text" name="email" value="<?php if(isset($_POST['email'])){echo $_POST['email'];} 
																						   else{ echo $user->email;}?>" required />
		<strong>New password (optional):</strong>
		<input class="form-control" type="password" name="password"/>
        <strong>Confirm password (optional):</strong>
        <input class="form-control" type="password" name="password2"/>
		<strong>New profile image(optional):</strong>
		<input id="files" type="file" name="Filename" /> <br/>
		<input type="submit" name="confirm_button" class="btn btn-info" value="Edit profile">
    </div>
</form>

<?php 
	if(isset($_SESSION["logged_account"]) && $_SESSION["logged_account"]->is_admin==1){
?>
	<a href='?controller=account&action=admin_settings'>Admin settings</a>
<?php } ?>