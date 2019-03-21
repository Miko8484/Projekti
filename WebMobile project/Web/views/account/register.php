<form name="reg" action="?controller=account&action=register" method="post">
    <div class="form-group">
        <div align="center">
            <h2><strong>Register here</strong></h2>
        </div>
        <p><strong style="color:red"><?php echo $msg; ?></strong></p>
        <strong>Username:</strong>
        <input class="form-control" type="text" name="username" value="<?php echo $username ?>"/>
        <strong>First name:</strong>
        <input class="form-control" type="text" name="firstname" value="<?php echo $firstname ?>"/>
        <strong>Last name:</strong>
        <input class=" form-control" type="text" name="lastname" value="<?php echo $lastname ?>"/>
        <strong>Email:</strong>
        <input class="form-control" type="text" name="email" value="<?php echo $email ?>"/>
        <strong>Password:</strong>
        <input class="form-control" type="password" name="password" value="<?php echo $password ?>"/>
        <strong>Confirm password:</strong>
        <input class="form-control" type="password" name="password2" value="<?php echo $password2 ?>"/>
		<br/>
        <input class="send_btn" type="submit" value="Submit" alt="Submit" title="Submit" />
    </div>
</form>
